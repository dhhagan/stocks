<?php

/*
	Written: David H Hagan, November 16, 2013
	Overview: This script gathers and inserts several pieces of information key to analyzing
				securities. A log file is generated that describes outcome for the run.
				
	Updates: 
		Nov 16, 2013	D Hagan		First test run for 25 tickers=>successful

*/


require_once('ystockquote.php');
require_once('ystockquote_db.php');

set_time_limit(0);
ignore_user_abort(1);

/*
	For each company:
	1) Check database and grab the last 'trade_date' to make sure duplicates are not entered
	2) Request all data for the ticker 
	3) If the trade_date is not the same as the last trade_date entry, enter into db
*/

// Get today's date
$now = new DateTime();
$now->format('Y-m-d');

// Get the start time for purpose of measuring script efficiency
$log_file = "log_dailydata_" . $now->format('Ymd') . ".txt";
$log = fopen("logs/" . $log_file, 'w');

$start = microtime(true);

// Check connection to  and reconnect if ping fails
if (!mysql_ping()) {
	$con = connect();
	}
	
# Write the header to the log file
fwrite($log, "Log file for Daily Securities data: " . $now->format('Y/m/d') . "\r\r\n");
fwrite('\r\n');

// List of companies pulled from ticker_info table
$companies = array();
$company_query = sprintf("SELECT ticker FROM %s LIMIT 10", TKR_TBL);
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
	
	
	$qry = sprintf("SELECT last_trade_date FROM %s WHERE tkr_id=%d ORDER BY last_trade_date DESC LIMIT 1", mysql_real_escape_string(DATA_TBL), $id);
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
	
	// At this point, we have the id and the last date in the db for the specific security
	/*
		1) Grab data using get_all from ystockquote
	*/
	
	$stock = new ystockquote($tick);
	$data = $stock->get_all();
	
	// Check each item in data array to make sure it is good; otherwise set to blank
	if (!empty($data)) {
		
		$data_entry = array();
		// Individually check each entry to make sure it is valid => tkr_id = $id
		// 1) Price
		if ($data['price'] !== ('N/A' || 'NA' || '-')) {
			$data_entry['price'] = (float)$data['price'];
			}
		else {
			$data_entry['price'] = floatval('');
			}
			
		// 2) open	
		if ($data['open'] !== ('N/A' || 'NA' || '-')) {	
			$data_entry['open'] = (float)$data['open'];
			}
		else {
			$data_entry['open'] = floatval('');
			}
			
		// 3) previous_close	
		if ($data['previous_close'] !== ('N/A' || 'NA' || '-')) {	
			$data_entry['close'] = (float)$data['previous_close'];
			}
		else {
			$data_entry['close'] = floatval('');
			}
			
		// 4) high	
		if ($data['high'] !== ('N/A' || 'NA' || '-')) {	
			$data_entry['high'] = (float)$data['high'];
			}
		else {
			$data_entry['high'] = floatval('');
			}
			
		// 5) low	
		if ($data['low'] !== ('N/A' || 'NA' || '-')) {	
			$data_entry['low'] = (float)$data['low'];
			}
		else {
			$data_entry['low'] = floatval('');
			}
			
		// 6) change
		if ($data['change'] !== ('N/A' || 'NA' || '-')) {	
			$data_entry['change'] = (float)$data['change'];
			}
		else {
			$data_entry['change'] = floatval('');
			}
			
		// 7) change from 50 day avg
		if ($data['change_from_50day'] !== ('N/A' || 'NA' || '-')) {	
			$data_entry['change_from_50dayAvg'] = (float)$data['change_from_50day'];
			}
		else {
			$data_entry['change_from_50dayAvg'] = floatval('');
			}
			
		// 8) change from 200 day avg
		if ($data['change_from_200day'] !== ('N/A' || 'NA' || '-')) {	
			$data_entry['change_from_200dayAvg'] = (float)$data['change_from_200day'];
			}
		else {
			$data_entry['change_from_200dayAvg'] = floatval('');
			}	
			
		// 9) change from year high
		if ($data['change_from_year_high'] !== ('N/A' || 'NA' || '-')) {	
			$data_entry['change_from_year_high'] = (float)$data['change_from_year_high'];
			}
		else {
			$data_entry['change_from_year_high'] = floatval('');
			}
			
		// 10) change from year low
		if ($data['change_from_year_low'] !== ('N/A' || 'NA' || '-')) {	
			$data_entry['change_from_year_low'] = (float)$data['change_from_year_low'];
			}
		else {
			$data_entry['change_from_year_low'] = floatval('');
			}		
			
		// 11) dividend pay date
		if ($data['dividend_pay_date'] !== ('N/A' || 'NA' || '-')) {	
			$div_date = new DateTime($data['dividend_pay_date']);
			$data_entry['dividend_date'] = $div_date->format('M-d-Y');
			}
		else {
			$data_entry['dividend_date'] = '';
			}
		
		// 12) Annual Dividend Yield
		if ($data['annual_div_yield'] !== ('N/A' || 'NA' || '-')) {	
			$data_entry['annual_dividend_yield'] = (float)$data['annual_div_yield'];
			}
		else {
			$data_entry['annual_dividend_yield'] = floatval('');
			}	
			
		// 13) Annual Dividend Yield Percent
		if ($data['annual_div_yield_percent'] !== ('N/A' || 'NA' || '-')) {	
			$data_entry['annual_dividend_yieldPercent'] = (float)$data['annual_div_yield_percent'];
			}
		else {
			$data_entry['annual_dividend_yieldPercent'] = floatval('');
			}
			
		// 14) last_trade_date
		if ($data['last_trade_date'] !== ('N/A' || 'NA' || '-')) {	
			$div_date = new DateTime($data['last_trade_date']);
			$data_entry['last_trade_date'] = $div_date->format('Y-m-d');
			}
		else {
			$data_entry['last_trade_date'] = '';
			}
			
		// 15) Average Daily Volume
		if ($data['avg_daily_volume'] !== ('N/A' || 'NA' || '-')) {	
			$data_entry['avg_daily_vol'] = (float)$data['avg_daily_volume'];
			}
		else {
			$data_entry['avg_daily_vol'] = floatval('');
			}
			
		// 16) 50 day moving average
		if ($data['50_day_moving'] !== ('N/A' || 'NA' || '-')) {	
			$data_entry['fifty_day_avg'] = (float)$data['50_day_moving'];
			}
		else {
			$data_entry['fifty_day_avg'] = floatval('');
			}
			
		// 17) 200 day moving average
		if ($data['200day_moving_avg'] !== ('N/A' || 'NA' || '-')) {	
			$data_entry['twohundred_day_avg'] = (float)$data['200day_moving_avg'];
			}
		else {
			$data_entry['twohundred_day_avg'] = floatval('');
			}
			
		// 18) 50 day moving average
		if ($data['50_day_moving'] !== ('N/A' || 'NA' || '-')) {	
			$data_entry['fifty_day_avg'] = (float)$data['50_day_moving'];
			}
		else {
			$data_entry['fifty_day_avg'] = floatval('');
			}

		// 19) Exchange
		if ($data['exchange'] !== ('N/A' || 'NA' || '-')) {	
			$data_entry['exchange'] = $data['exchange'];
			}
		else {
			$data_entry['exchange'] = '';
			}
			
		// 20) Ticker
		if ($data['symbol'] !== ('N/A' || 'NA' || '-')) {	
			$data_entry['symbol'] = $data['symbol'];
			}
		else {
			$data_entry['symbol'] = '';
			}	
		
		// 21) Currency
		if ($data['currency'] !== ('N/A' || 'NA' || '-')) {	
			$data_entry['currency'] = $data['currency'];
			}
		else {
			$data_entry['currency'] = '';
			}	
			
		// 22) Revenue
		if ($data['revenue'] !== ('N/A' || 'NA' || '-')) {
			$numeric = preg_split("/(?<=[0-9])(?=[A-Z]+)/i", $data['revenue']);
			if ($numeric[1] == 'B') {
				$data_entry['revenue'] = (float)$numeric[0] * pow(10,9);
				}
			elseif ($numeric[1] == 'M') {	
				$data_entry['revenue'] = (float)$numeric[0] * pow(10,6);
				}
			else {
				$data_entry['revenue'] = (float)$numeric[0];
				}
			}
		else {
			$data_entry['revenue'] = intval('');
			}	
			
		// 23) one_year_target
		if ($data['one_year_target'] !== ('N/A' || 'NA' || '-')) {	
			$data_entry['one_year_tgt'] = (float)$data['one_year_target'];
			}
		else {
			$data_entry['one_year_tgt'] = floatval('');
			}
			
		// 24) 52 Week High
		if ($data['52_week_high'] !== ('N/A' || 'NA' || '-')) {	
			$data_entry['year_high'] = (float)$data['52_week_high'];
			}
		else {
			$data_entry['year_high'] = floatval('');
			}
			
		// 25) 52 Week Low
		if ($data['52_week_low'] !== ('N/A' || 'NA' || '-')) {	
			$data_entry['year_low'] = (float)$data['52_week_low'];
			}
		else {
			$data_entry['year_low'] = floatval('');
			}
			
		// 26) Volume
		if ($data['volume'] !== ('N/A' || 'NA' || '-')) {	
			$data_entry['volume'] = (int)$data['volume'];
			}
		else {
			$data_entry['volume'] = (int)('');
			}
			
		// 27) Market Cap
		if ($data['market_cap'] !== ('N/A' || 'NA' || '-')) {	
			$numeric = preg_split("/(?<=[0-9])(?=[A-Z]+)/i", $data['market_cap']);
			if ($numeric[1] == 'B') {
				$data_entry['market_cap'] = (int)$numeric[0] * pow(10,9);
				}
			elseif ($numeric[1] == 'M') {	
				$data_entry['market_cap'] = (int)$numeric[0] * pow(10,6);
				}
			else {
				$data_entry['market_cap'] = (int)$numeric[0];
				}
			}
		else {
			$data_entry['market_cap'] = (int)('');
			}	
			
		// 28) Book Value Per Share
		if ($data['book_val_per_share'] !== ('N/A' || 'NA' || '-')) {	
			$data_entry['book_val_per_share'] = (float)$data['book_val_per_share'];
			}
		else {
			$data_entry['book_val_per_share'] = (float)('');
			}

		// 28) Diluted Earnings Per Share
		if ($data['diluted_eps'] !== ('N/A' || 'NA' || '-')) {	
			$data_entry['diluted_eps'] = (float)$data['diluted_eps'];
			}
		else {
			$data_entry['diluted_eps'] = (float)('');
			}

		// 29) ebitda
		if ($data['ebitda'] !== ('N/A' || 'NA' || '-')) {	
			$numeric = preg_split("/(?<=[0-9])(?=[A-Z]+)/i", $data['ebitda']);
			if ($numeric[1] == 'B') {
				$data_entry['ebitda'] = (int)$numeric[0] * pow(10,9);
				}
			elseif ($numeric[1] == 'M') {	
				$data_entry['ebitda'] = (int)$numeric[0] * pow(10,6);
				}
			else {
				$data_entry['ebitda'] = (int)$numeric[0];
				}
			}
		else {
			$data_entry['ebitda'] = (int)('');
			}	

		// 30) Earnings Per Share Estimate: Current Year
		if ($data['eps_est_current_year'] !== ('N/A' || 'NA' || '-')) {	
			$data_entry['eps_est_current_yr'] = (float)$data['eps_est_current_year'];
			}
		else {
			$data_entry['eps_est_current_yr'] = (float)('');
			}
		
		// 31) Earnings Per Share Estimate: Current Quarter
		if ($data['eps_est_quart'] !== ('N/A' || 'NA' || '-')) {	
			$data_entry['eps_est_quart'] = (float)$data['eps_est_quart'];
			}
		else {
			$data_entry['eps_est_quart'] = (float)('');
			}
			
			
		// 32) Earnings Per Share Estimate: Next Year
		if ($data['eps_est_next_year'] !== ('N/A' || 'NA' || '-')) {	
			$data_entry['eps_est_next_yr'] = (float)$data['eps_est_next_year'];
			}
		else {
			$data_entry['eps_est_next_yr'] = (float)('');
			}
			
		// 33) PEG Ratio
		if ($data['PEG_ratio'] !== ('N/A' || 'NA' || '-')) {	
			$data_entry['peg_ratio'] = (float)$data['PEG_ratio'];
			}
		else {
			$data_entry['peg_ratio'] = (float)('');
			}
		
		// 34) PE Ratio
		if ($data['PE_ratio'] !== ('N/A' || 'NA' || '-')) {	
			$data_entry['pe_ratio'] = (float)$data['PE_ratio'];
			}
		else {
			$data_entry['pe_ratio'] = (float)('');
			}
			
		// 35) Short Ratio
		if ($data['short_ratio'] !== ('N/A' || 'NA' || '-')) {	
			$data_entry['short_ratio'] = (float)$data['short_ratio'];
			}
		else {
			$data_entry['short_ratio'] = (float)('');
			}
		
		// 36) Book Price
		if ($data['book_price'] !== ('N/A' || 'NA' || '-')) {	
			$data_entry['book_price'] = (float)$data['book_price'];
			}
		else {
			$data_entry['book_price'] = (float)('');
			}
		
		// 37) Price Earnings Per Share Estimate: Current Year
		if ($data['Price_EPS_est_curr_year'] !== ('N/A' || 'NA' || '-')) {	
			$data_entry['price_eps_est_current'] = (float)$data['Price_EPS_est_curr_year'];
			}
		else {
			$data_entry['price_eps_est_current'] = (float)('');
			}
			
		// 38) Price Earnings Per Share Estimate: Next Year
		if ($data['Price_EPS_est_next_year'] !== ('N/A' || 'NA' || '-')) {	
			$data_entry['price_eps_est_next'] = (float)$data['Price_EPS_est_next_year'];
			}
		else {
			$data_entry['price_eps_est_next'] = (float)('');
			}	


		// Enter data into database
	$entry_qry = sprintf("INSERT INTO %s (
		tkr_id, tckr, price, open, close,
		high, low, daily_change, 
		change_from_50dayAvg,
		change_from_200dayAvg,
		change_from_year_high,
		change_from_year_low,
		dividend_date,
		annual_dividend_yield,
		annual_dividend_yieldPercent,
		last_trade_date, avg_daily_vol,
		fifty_day_avg, twohundred_day_avg,
		exchange, symbol, currency,
		revenue, one_year_tgt, year_high,
		year_low, volume, market_cap, 
		book_val_per_share, diluted_eps,
		ebitda, eps_est_current_yr,
		eps_est_quarter, eps_est_next_yr,
		peg_ratio, pe_ratio, short_ratio,
		book_price, price_eps_est_current,
		price_eps_est_next) 
		VALUES (
			'%d', '%s', '%13.3f', '%13.3f', '%13.3f',
			'%13.3f', '%13.3f', '%13.3f',
			'%13.3f',
			'%13.3f',
			'%13.3f',
			'%13.3f',
			'%s',
			'%10.2f',
			'%5.2f',
			'%s','%d',
			'%13.3f', '%13.3f',
			'%s', '%s', '%s', 
			'%d', '%13.3f', '%13.3f',
			'%13.3f', '%u', '%u', 
			'%13.3f', '%10.3f',
			'%d', '%8.3f',
			'%8.3f', '%8.3f',
			'%8.2f', '%8.2f', '%8.2f',
			'%8.2f', '%8.2f', 
			'%8.2f'
			)", mysql_real_escape_string(DATA_TBL),
			$id, $tick, $data_entry['price'], $data_entry['open'], $data_entry['close'],
			$data_entry['high'], $data_entry['low'], $data_entry['daily_change'],
			$data_entry['change_from_50dayAvg'],
			$data_entry['change_from_200dayAvg'],
			$data_entry['change_from_year_high'],
			$data_entry['change_from_year_low'],
			$data_entry['dividend_date'],
			$data_entry['annual_dividend_yield'],
			$data_entry['annual_dividend_yieldPercent'],
			$data_entry['last_trade_date'], $data_entry['avg_daily_vol'],
			$data_entry['fifty_day_avg'], $data_entry['twohundred_day_avg'],
			$data_entry['exchange'], $data_entry['symbol'], $data_entry['currency'],
			$data_entry['revenue'], $data_entry['one_year_tgt'], $data_entry['year_high'],
			$data_entry['year_low'], $data_entry['volume'], $data_entry['market_cap'],
			$data_entry['book_val_per_share'], $data_entry['diluted_eps'],
			$data_entry['ebitda'], $data_entry['eps_est_current_yr'],
			$data_entry['eps_est_quarter'], $data_entry['eps_est_next_yr'],
			$data_entry['peg_ratio'], $data_entry['pe_ratio'], $data_entry['short_ratio'],
			$data_entry['book_price'], $data_entry['price_eps_est_current'],
			$data_entry['price_eps_est_next']
			);
	
		// Enter into db IF last_date is not the same as the current last date
		if ($last_date->format('Y-m-d') !== $data_entry['last_trade_date']) {
			mysql_query($entry_qry) or die("ERROR entering daily data for {$tick}: " . mysql_error());
			$totalRows++;
			}
		else {
			fwrite($log, "Double entry for {$tick} \r\n");
			}
			
		$numCompanies++;
		
	}
	
	else {
		fwrite($log, "ERROR with {$tick}. No data available \r\n");
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