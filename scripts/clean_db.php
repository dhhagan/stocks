<?php

require_once ('ystockquote_db.php');

$con = connect();

$tmp = fopen("tmp_ticks.csv",'w');
$header =array("Ticker","Name","Exchange");
fputcsv($tmp, $header);

$line = 0;
$newLines = 0;
$stock_array = array();
				
if ($handle = fopen(STOCK_CSV, "r")) {
	while ($data = fgetcsv($handle, 1000, ",")) {
			$new_tick = array();
			if ($line != 0) {
				// Check to see if the ticker is already in the database
				$qry = sprintf("SELECT COUNT(*) FROM %s WHERE ticker='%s'", mysql_real_escape_string(TKR_TBL), mysql_real_escape_string($data[0]));
				$res = mysql_query($qry);
				if (!$res){
					continue;
					}
				// If num == 0, add to the file so it can be inserted into the db
				$num = mysql_fetch_row($res);
				if ($num[0] == 0){
					$new_tick['ticker'] = $data[0];
					$new_tick['name'] = $data[1];
					$new_tick['exchange'] = $data[2];
					
					fputcsv($tmp, $new_tick);
					$newLines++;
					}
					
				array_push($stock_array, $new_tick);
				}
			$line++;
			 }
	fclose($handle);
			}
else {
	echo "Could not open file";
	}
fclose($tmp);


echo "Added {$newLines} lines";

?>