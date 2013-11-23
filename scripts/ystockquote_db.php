<?php
	//ystockquote_db.php
	
	require_once('config.php');
	
	function connect() {
		$con = mysql_connect(HOST,USERNAME,PASSWORD)
			or die("<p>Error connecting to database: " . mysql_error() . "</p>");

		mysql_select_db(DATABASE, $con)
			or die("<p>Error selecting the database: " .mysql_error() . "</p>");
		
		return $con;
		}
	
	function install() {
		/*
			This function installs the database and neccesary tables
		*/
		
		$con = mysql_connect(HOST,USERNAME,PASSWORD)
			or die("<p>Error connecting to database: " . mysql_error() . "</p>");
		$db = new new_db;    
		$db->createTables();
		
		}
		
	function stock_array() {
		/*
			stock_array() opens the generates a 2D array of information from the stock_info.csv 
			file including ticker, name, and exchange for each row in the file. This information 
			is then used to enter into the table stock_info.
		*/
		$row = 0;
		$stock_array = array();
				
		if ($handle = fopen(STOCK_CSV, "r")) {
			 while ($data = fgetcsv($handle, 1000, ",")) {
				$new_tick = array();
				if ($row != 0) {
					$new_tick['ticker'] = $data[0];
					$new_tick['name'] = $data[1];
					$new_tick['exchange'] = $data[2];
					
					array_push($stock_array, $new_tick);
					}
				$row++;
			  }
			  fclose($handle);
			}
		else {
			echo "Could not open file";
			}
		return $stock_array;
	}	
	
	function build_ticker_table() {
		/*
			build_ticker_table() reads in the csv file containing a list of three fields for each company listed on
				an exchange. The file should be defined in the configuration file
		*/
		
		$con = connect();
		
		$qry = sprintf("LOAD DATA LOCAL INFILE '%s' INTO TABLE %s
						FIELDS TERMINATED BY ','
						LINES TERMINATED BY '\r\n'
						IGNORE 1 LINES
						(Ticker,Name,Exchange)
						", mysql_real_escape_string(STOCK_CSV), mysql_real_escape_string(TKR_TBL));
						
		mysql_query($qry) or die("Invalid query: " . mysql_error() . "\n");

		mysql_close($con);
		}

	
	class tick_info {
		/* tick_info allows the user to enter and delete entries from the database
			containing the ticker information for all companies
			
			Instructions:
				$FB = new tick_info("FB","Facebook","NASDAQQ");
				echo $FB->add_entry();
				
				Possibly change table name to be based on config file
		*/

		function __construct($tick,$name,$exchange) {
			$this->tick = $tick;
			$this->name = $name;
			$this->exchange = $exchange;
			}
		function add_entry() {
			$qry = sprintf("INSERT INTO %s (ticker, name, exchange) VALUES ('%s', '%s', '%s')",mysql_real_escape_string(TKR_TBL),mysql_real_escape_string($this->tick),mysql_real_escape_string($this->name),mysql_real_escape_string($this->exchange));
			mysql_query($qry) or die("ERROR: " . mysql_error());

			}
		function del_entry() {
			$qry = sprintf("DELETE FROM %s WHERE ticker={$this->tick} and exchange={$this->exchange}", mysql_real_escape_string(TKR_TBL));
			mysql_query($qry) or die("ERROR: " . mysql_error());
			}
		}
			
	
	class historical {
		/*
			The object of this class is to be able to easily add and delete historical information
				for any security
		*/
		
		function __construct($tkr, $entry_date, $open, $high, $low, $close, $vol, $adj_close) {
			$this->tkr = $tkr;
			$this->entry_date = $entry_date;
			$this->open = $open;
			$this->high = $high;
			$this->low = $low;
			$this->close = $close;
			$this->vol = $vol;
			$this->adj_close = $adj_close;
			
			$tkr_qry = sprintf("SELECT * FROM %s WHERE ticker='%s'", TKR_TBL, $this->tkr);
			if (!mysql_ping()) {
				$con = connect();
				}
			$result = mysql_query($tkr_qry);
			if (!$result) {
				die("ERROR: Could not get ticker ID: " . mysql_error());
				}
			else {
				$data = mysql_fetch_row($result);
				$this->tkr_id = $data[0];
				}
			}
			
		function add_day() {
			// Convert the current timestamp from Yahoo into valid SQL format [yyyy-mm-dd]
			$date = new DateTime($this->entry_date);
			
			$qry = sprintf("INSERT INTO %s (tkr_id, entry_date, open, high, low, close, volume, adj_close) 
					VALUES (%d,'%s',%8.2f,%8.2f,%8.2f,%8.2f,%d,%8.2f)",
					mysql_real_escape_string(HISTORICAL_TBL), $this->tkr_id, mysql_real_escape_string($date->format('Y-m-d')),
					$this->open, $this->high, $this->low, $this->close, $this->vol, $this->adj_close);
			mysql_query($qry) or die(mysql_error());
			}
			
		function del_day() {
			echo "Added line";
			}
			
		}
		
		
	class new_db {
		/*
			This class creates all neccesary tables in your database 
				1) ticker_info : contains ticker, name, exchange
				2) stock_data : contains daily information including:
									price
									open
									close
									high
									low
									ask
									bid
									change
									change from 50 day average
									change from 20 day average
									change from the years high
									change from the years low
									dividend pay date
									annual dividend yield
									annual dividend yield in percent
									trade date
									last trade date
									last trade size
									average daily volume
									50 day moving average
									200 day moving average
									exchange
									symbol
									ticker trend
									currency
									holdings gain
									holdings value
									revenue
									shares owned
									one year target
									52 week high
									52 week low
									volume
									market cap
									book value per share
									diluted eps
									ebitda
									Earnings per share estimate: current year
									Earnings per share estimate: current quarter
									Earnings per share estimate: next year
									Price/earnings+growth ratio
									price/earnings ratio
									short ratio
									book price
									Price EPS Estimate: Current year
									Price EPS Estimate: Next Year
				3) To use this class to install your own database:
					$db = new new_db       
					$db->createTables();  // This will build a db if it doesn't already exist, and then add the necessary tables
			*/
		
		function __construct() {
			$this->db_name = DATABASE;
			//$this->table1 = TKR_TBL; 
			//$this->table2 = DATA_TBL;
			
			}
	
		
		function createTables() {
			mysql_query("CREATE DATABASE IF NOT EXISTS `$this->db_name`;") or die("ERROR: Could not create database" . mysql_error());
			mysql_select_db(DATABASE) or die("ERROR: Could not use $this->db_name.");
				
				$qry_tickerInfo = sprintf("CREATE TABLE IF NOT EXISTS %s (
							ID int NOT NULL AUTO_INCREMENT,
							ticker varchar(10) NOT NULL,
							name varchar(75) NOT NULL,
							exchange varchar(50) NOT NULL,
							PRIMARY KEY (ID)
							) ENGINE=INNODB", 
							mysql_real_escape_string(TKR_TBL));
					
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
						
				mysql_query($qry_tickerInfo) or die("ERROR: Could not create table " . TKR_TBL . ": " . mysql_error());
				mysql_query($qry_stockData) or die("ERROR: Could not create table " . DATA_TBL . ": ". mysql_error());
				mysql_query($qry_historical) or die("ERROR: Could not create table " . HISTORICAL_TBL . ": " . mysql_error());
				
				}
		}
		
	
	
	/*
	install();
	build_ticker_table();
	echo "Testing! <br />";
	$test = new historical('', '', '', '', '', '', '');
	$test->add_day();
	mysql_close($con);
	*/
?>