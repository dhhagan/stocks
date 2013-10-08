<?php

require_once('config.php');

$con = mysql_connect(HOST,USERNAME,PASSWORD)
	or die("<p>Error connecting to database: " . mysql_error() . "</p>");

mysql_select_db(DATABASE, $con)
	or die("<p>Error selecting the database: " .mysql_error() . "</p>");

?>