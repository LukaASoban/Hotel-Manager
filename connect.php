<?php
	$db_hostname = 'localhost';
	$db_database = 'csv_db';
	$db_username = 'root';
	$db_password = 'ilios123';
	$db_server = mysql_connect($db_hostname, $db_username, $db_password);
	if (!$db_server) {
		die('Could not connect to server: ' . mysql_error());
	}
	mysql_select_db($db_database)
		or die("Could not select database: " . mysql_error());
	//echo 'Connected successfully';

	function close_mysql() {
		mysql_close();
		return "Closed successfully!";
	}
?>