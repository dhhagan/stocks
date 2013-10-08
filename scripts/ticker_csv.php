<?php
	/*
		This file return an array containing the information
		from the csv containing all company information
	*/

function stock_array {
	$row = 1;
	$stock_array = array();
			
	if (($handle = fopen("stock_info.csv", "r")) !== FALSE) {
		 while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			$num = count($data);
			if ($row != 1) {
				$row++;
				for ($c=0; $c < $num; $c++) {
					$stock_array["{$data[0]}"] = "{$data[1]}";
				}
			}
			else {
				$row++;
				}
		  }
		  fclose($handle);
		}
	else {
		echo "Could not open file";
		}
}
	
?>