
<?php
	$db_hostname = 'localhost';
	$db_database = 'cs4400_Group_71';
	$db_username = 'cs4400_Group_71';
	$db_password = 'rORGOo8Z';

	$db_server = mysql_connect($db_hostname, $db_username, $db_password);
	if (!$db_server) {
		die('Could not connect to server: ' . mysql_error());
	}
	mysql_select_db($db_database)
		or die("Could not select database: " . mysql_error());
	echo 'Connected successfully';
	mysql_close($db_server);
?>
