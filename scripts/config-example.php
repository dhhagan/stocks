<?php
/*
	To set up your installalation of OSS stocks in php, you must define these parameters for
	your system.
		1) HOST: Your host name (localhost, 127.0.0.1, etc)
		2) USERNAME: Your username for your database
		3) PASSWORD: Your password for your database
		4) DATABASE: This should be the name you wish your stock database to have (ex. stocks)
		5) TKR_TBL: This is the name of the table that holds the ticker information
		6) DATA_TBL: This is the name of the table that holds all information
		7) STOCK_CSV: This is the file path for a csv containing ticker information
		8) HISTORICAL_TBL: This is the table name for the historical stock data
*/

define("HOST","Host Name");
define("USERNAME","username");
define("PASSWORD","****");
define("DATABASE","db_name");

define("TKR_TBL","ticker_info");
define("DATA_TBL","stock_data");
define("STOCK_CSV","stock_info.csv");
define("HISTORICAL_TBL","historical_data");
?>