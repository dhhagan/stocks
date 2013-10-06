<?php
	//ystockquote_db.php
	//SQL Statements:  Enter into DB
	
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
		*/
		require_once('use_stocks.php');
		
		function __construct() {
			
			}
		
		}
	/*
	Example for how to use tick_info:
		$FB = new tick_info("FB","Facebook","NASDAQQ");
		echo $FB->add_entry();
	*/
?>