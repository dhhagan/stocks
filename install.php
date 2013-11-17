<?php

require_once 'scripts/config.php';

echo "Installing ystockquote for php... <br />";

// Connect to the database
$con = mysql_connect(HOST,USERNAME,PASSWORD)
			or die("<p>Error connecting to database: " . mysql_error() . "</p>");

$qry = sprintf("CREATE DATABASE IF NOT EXISTS `%s`", mysql_real_escape_string(DATABASE));

mysql_query($qry) or die("ERROR: Could not create database" . mysql_error());
mysql_select_db(DATABASE) or die("ERROR: Could not use {DATABASE}");

// Install TKR_TBL
$qry_tickerInfo = sprintf("CREATE TABLE IF NOT EXISTS %s (
							ID int NOT NULL AUTO_INCREMENT,
							ticker varchar(10) NOT NULL,
							name varchar(75) NOT NULL,
							exchange varchar(50) NOT NULL,
							PRIMARY KEY (ID)
							) ENGINE=INNODB", 
							mysql_real_escape_string(TKR_TBL));

mysql_query($qry_tickerInfo) or die("ERROR: Could not create table " . TKR_TBL . ": " . mysql_error());

// Install the daily_data table
$qry_stockData = sprintf("CREATE TABLE IF NOT EXISTS %s (
							ID INT NOT NULL AUTO_INCREMENT,
							tkr_id INT NOT NULL,
							tckr varchar(10),
							price decimal(13,3),
							open decimal(13,3),
							close decimal(13,3),
							high decimal(13,3),
							low decimal(13,3),
							ask decimal(13,3),
							bid decimal(13,3),
							daily_change decimal(13,3),
							change_from_50dayAvg decimal(13,3),
							change_from_200dayAvg decimal(13,3),
							change_from_year_high decimal(13,3),
							change_from_year_low decimal(13,3),
							dividend_date date,
							annual_dividend_yield decimal(10,2),
							annual_dividend_yieldPercent varchar(8),
							trade_date date,
							last_trade_date date,
							last_trade_size int,
							avg_daily_vol int,
							fifty_day_avg decimal(13,3),
							twohundred_day_avg decimal(13,3),
							exchange varchar(20),
							symbol varchar(10),
							ticker_trend varchar(10),
							currency varchar(8),
							holdings_gain varchar(20),
							holdings_val varchar(20),
							revenue INT,
							shares_owned INT UNSIGNED,
							one_year_tgt decimal(13,3),
							year_high decimal(13,3),
							year_low decimal(13,3),
							volume INT UNSIGNED,
							market_cap INT UNSIGNED,
							book_val_per_share decimal(13,3),
							diluted_eps decimal(10,3),
							ebitda INT,
							eps_est_current_yr decimal(8,3),
							eps_est_quarter decimal(8,3),
							eps_est_next_yr decimal(8,3),
							peg_ratio decimal(8,2),
							pe_ratio decimal(8,2),
							short_ratio decimal(8,2),
							book_price decimal(8,2),
							price_eps_est_current decimal(8,2),
							price_eps_est_next decimal(8,2),
							PRIMARY KEY (ID),
							FOREIGN KEY (tkr_id) REFERENCES %s(ID)
							ON UPDATE CASCADE
							ON DELETE CASCADE
							) ENGINE=INNODB", 
							mysql_real_escape_string(DATA_TBL), 
							mysql_real_escape_string(TKR_TBL));

mysql_query($qry_stockData) or die("ERROR: Could not create table " . DATA_TBL . ": ". mysql_error());
				
// Create the HISTORICAL_TBL table
$qry_historical = sprintf("CREATE TABLE IF NOT EXISTS %s (
							id int NOT NULL AUTO_INCREMENT,
							tkr_id int NOT NULL,
							entry_date date,
							open decimal(10,2),
							high decimal(10,2),
							low decimal(10,2),
							close decimal(10,2),
							volume INT UNSIGNED,
							adj_close decimal(10,2),
							PRIMARY KEY (id),
							FOREIGN KEY (tkr_id) REFERENCES %s(ID)
							ON UPDATE CASCADE
							ON DELETE CASCADE
					)", mysql_real_escape_string(HISTORICAL_TBL),
						mysql_real_escape_string(TKR_TBL));				
				
mysql_query($qry_historical) or die("ERROR: Could not create table " . HISTORICAL_TBL . ": " . mysql_error());
				

// Build the ticker table
$qry_tbl = sprintf("LOAD DATA LOCAL INFILE '%s' INTO TABLE %s
						FIELDS TERMINATED BY ','
						LINES TERMINATED BY '\r\n'
						IGNORE 1 LINES
						(Ticker,Name,Exchange)
						", mysql_real_escape_string("scripts/" . STOCK_CSV), mysql_real_escape_string(TKR_TBL));
						
mysql_query($qry_tbl) or die("Invalid query: " . mysql_error() . "\n");
			
echo "<br />ystockquote for php has installed successfully";

mysql_close($con);
?>