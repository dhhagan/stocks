<?php

require_once('ystockquote_db.php');

			
$con = connect();
$tick = 'AIG.W';
$exchange = 'NYSE';

$qry = sprintf("DELETE FROM %s WHERE ticker='{$tick}' and exchange='{$exchange}'", mysql_real_escape_string(TKR_TBL));

$res = mysql_query($qry);
if (!res){
	die("ERROR: " . mysql_error());
	}

echo "made it";
?>