<?php

/*
	Written: David H Hagan, November 14, 2013
	Overview: This script inserts historical data from the yahoo stocks csv api into a database configured
				by the install.php script. A log file is generated that describes outcome for the run.
				
	Updates: 
		Nov 14, 2013	D Hagan		First test run for 25 tickers=>successful

*/


require_once('ystockquote.php');
require_once('ystockquote_db.php');

set_time_limit(0);
ignore_user_abort(1);

/*
	For each company:
	1) Check historical_data database to see when the last entry date for the ticker is
	2) Request historical data for the ticker from the last date++ until current date
	3) Create temp csv file to dump good data into for each ticker
		3a) For each row, if good, add to temp csv using fputcsv
		3b) Enter temp csv into db using load data infile

*/
// Get today's date
$now = new DateTime();
$now->format('Y-m-d');

// Get the start time for purpose of measuring script efficiency
$log_file = "log_historical_" . $now->format('Ymd') . ".txt";
$log = fopen("logs/" . $log_file, 'w');

$start = microtime(true);

// Check connection to  and reconnect if ping fails
if (!mysql_ping()) {
	$con = connect();
	}
	
# Write the header to the log file
fwrite($log, "Log file for Historical Stock data: " . $now->format('Y/m/d') . "\r\r\n");
fwrite('\r\n');

// List of companies=> Eventually will be pulled from table ticker_info
$companies = array();
$company_query = sprintf("SELECT ticker FROM %s LIMIT 300", TKR_TBL);
$company_result = mysql_query($company_query);
if (!$company_result) {
	die("Could not get company information from {TKR_TBL}: " . mysql_error());
	}

while ($line = mysql_fetch_row($company_result)) {
	array_push($companies, $line[0]);
	}


$totalRows = 0;
$numCompanies = 0;
$emptyTickers = array();



/*
	For each company:
		1) Grab tkr_id for the ticker from table
			a) If the result is empty, the ticker is not yet in the database (throw alert)
		2) Find the last entry date in the db for each ticker
			a) If no entries are present for the ticker, set the last date to Jan 1, 1800
		3) 
*/
foreach ($companies as $tick){
	$tkr_qry = sprintf("SELECT ID FROM %s WHERE ticker='%s'", TKR_TBL, $tick);
	$result = mysql_query($tkr_qry);
	if (!$result) {
		die("ERROR: Could not get ticker ID: " . mysql_error());
		}
	else {
		$id = mysql_fetch_row($result);
		if (empty($id)){
			array_push($emptyTickers,$tick);
			continue;
			}
		else {
			$id = $id[0];	
			}
		}

	$qry = sprintf("SELECT entry_date FROM %s WHERE tkr_id=%d ORDER BY entry_date DESC LIMIT 1", mysql_real_escape_string(HISTORICAL_TBL), $id);
	$result = mysql_query($qry); 
	if (!$result) {
		die("Could not get last entry: " . mysql_error());
		}
	else {
		$last_date = mysql_fetch_row($result);
		if (empty($last_date)){
			$last_date = new DateTime('1800-01-01');
			}
		else {
			$last_date = new DateTime($last_date[0]);
			}
		$last_date->format('Y-m-d');
		}

	if ($last_date < $now) {
		$new = new ystockquote($tick);
		$rowNo = 0;
		$data = $new->get_historical_prices($last_date->format('m-d-Y'),$now->format('m-d-Y'),'d');
		
		if (empty($data)) {	
			fwrite($log, "Could not open csv data file for {$tick} \r");
			echo "<br />Data is empty for {$tick}";
			continue;
			}

		$tmp = 'tmp.csv';
		$file = fopen($tmp, 'w');
		foreach ($data as $row) {
			$newDate = new DateTime($row['Date']);
			$date_qry = sprintf("SELECT id FROM %s WHERE tkr_id=%d AND entry_date='%s'", 
						mysql_real_escape_string(HISTORICAL_TBL), $id, mysql_real_escape_string($newDate->format('Y-m-d')));
			$res = mysql_query($date_qry);
			if (!$res) {
				die("Error with Date Query: " . mysql_error());
				}
			$res = mysql_fetch_row($res);
			if (empty($res)) {
				array_unshift($row, $id);
				fputcsv($file, $row);
				$rowNo++;
				}
			}
			
			fclose($file);
			
			$ins_qry = sprintf("LOAD DATA LOCAL INFILE '%s' INTO TABLE %s
						FIELDS TERMINATED BY ','
						LINES TERMINATED BY '\n'
						(tkr_id, entry_date, open, high, low, close, volume, adj_close)
						", mysql_real_escape_string($tmp), mysql_real_escape_string(HISTORICAL_TBL));

			mysql_query($ins_qry) or die("Invalid query: " . mysql_error() . "\n");
			
		$totalRows += $rowNo;
		$numCompanies++;

		}

	}

# If there were any incorrect tickers, print them to log file
if (count($emptyTickers) > 0) {
	fwrite($log, "\r\nThe following companies are not yet set up: \r");
	foreach ($emptyTickers as $company) {
		fwrite($log, "\t" . $company . "\r" );
		}
	}
	
	
$end = microtime(true);

fwrite($log, "\n");
fwrite($log, "\r\nAdded " . $totalRows . " total rows for " . $numCompanies . " tickers. \r");
$time = $end - $start;
if ($time >= 60) {
	if ($time >= 3600){
		$time = $time / 3600;
		fwrite($log, sprintf("\r\nTime to run script: %5.2f hrs", $time));
		}
	else {
		$time = $time / 60;
		fwrite($log, sprintf("\r\nTime to run script: %5.2f min", $time));
		}
	}
else {
	fwrite($log, sprintf("\r\nTime to run script: %5.2f seconds", $time));
	}


fclose($log);

?>