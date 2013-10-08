<?php
	//ystockquote_db.php
	//SQL Statements:  Enter into DB
	
	require_once('use_stocks.php');
	
	class tick_info {
		/* tick_info allows the user to enter and delete entries from the database
			containing the ticker information for all companies
		*/
		public function __construct($tick,$name,$exchange) {
			$this->tick = $tick;
			$this->name = $name;
			$this->exchange = $exchange;
			}
		public function add_entry() {
			return "INSERT INTO ticker_info (ticker, name, exchange) VALUES ('{$this->tick}','{$this->name}','{$this->exchange}')";
			}
		public function del_entry() {
			return "DELETE FROM ticker_info WHERE tick={$this->tick}";
			}
		}
		
		
	class daily_entry {
		/*
			daily_entry allows the user to enter and delete entries from the 
			database containing each daily entry for each company
		*/
		public function __construct() {
			
			}
			
		public function add_day() {	
			return "";
			}
			
		public function del_day() {
			return "";
			}
			
		}
		
	class new_db {
		/*
			This class creates all neccesary tables in your database 
				1) ticker_info : contains ticker, name, exchange
				2) stock_data : contains daily information including:
									price
									change
									volume
									market_cap
									book_val
									EPS
									50day_moving_avg
									200day_moving_avg
									PE
									PEG
									price_book
									
		*/
		
		function __construct() {
			$this->table1 = 'ticker_info';
			$this->table2 = 'stock_data';
			}
		
		$qry_tickerInfo = "CREATE TABLE IF NOT EXISTS `$this->table1` (
							id INT NOT NULL AUTO_INCREMENT,
							ticker varchar(10),
							name varchar(50),
							exchange varchar(20),
							PRIMARY_KEY (id)
							) ENGINE=INNODB;";
							
		$qry_stockData = "CREATE TABLE IF NOT EXISTS `$this->table2` (
							id INT NOT NULL AUTO_INCREMENT,
							tkr_id INT NOT NULL,
							tckr varchar(10),
							,
							PRIMARY KEY (id),
							FOREIGN_KEY (tkr_id) REFERENCES $this->table1(id)
							ON UPDATE CASCADE
							ON DELETE CASCADE
							) ENGINE=INNODB;";
		
		}
	/*
	Example for how to use tick_info:
		$FB = new tick_info("FB","Facebook","NASDAQQ");
		echo $FB->add_entry();
	*/
?>