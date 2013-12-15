<?php

require_once ('ystockquote_db.php');

// Connect to the database
$con = connect();

$qry = sprintf("CREATE TEMPORARY TABLE tempTickers (
				ticker varchar(10),
				name varchar(50),
				exchange varchar(25)
				) ENGINE=MEMORY");
mysql_query($qry) or die("Could not do: " . mysql_error());

$qry2 = sprintf("INSERT INTO tempTickers (ticker, name, exchange) SELECT DISTINCT ticker, name, exchange FROM ticker_info");

mysql_query($qry2) or die("Could not do: " . mysql_error());

$qry3 = sprintf("DELETE FROM ticker_info");
mysql_query($qry3) or die("Could not do: " . mysql_error());

$qry4 = sprintf("INSERT INTO ticker_info(ticker, name, exchange) SELECT ticker, name, exchange FROM tempTickers");

mysql_query($qry4) or die("Could not do: " . mysql_error());

echo 'done';
?>