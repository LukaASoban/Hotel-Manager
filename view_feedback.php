<?php
	/**
	 * Version : 0.1
	 * Author: Luka Antolic-Soban
	 */

	/* get connect to MYSQL server */
	require 'connect.php';

	/* instances for search engine */
	$location = "";
	$rating ="";
	$comment = "";
	$query = "";
	$result = "";
	$error_msg = "";


	if (isset($_POST['Check'])) {
		$location = $_POST['location'];	//location
		$user = $_SESSION['username']; //user

		$query = "SELECT Rating, Comment FROM review WHERE Location = '$location'";

		$result=mysql_query($query);

		if (empty($result)) {
			$error_msg = "An ERROR has occured with your submisson";
			echo "<script type='text/javascript'>alert('$error_msg');</script>";
		} else {
			
		}
	}

	if (isset($_POST['back'])) {
		header("Location: customer_panel.php");
	}


	
?>
<!DOCTYPE html>
<html>
	<head>
		<title>View Review</title>
	</head>
	<body>
		<p><big>View Review</big></p>
		Location:
		<form method="post">
			<select name="location">
				<option value="Atlanta">Atlanta</option>
				<option value="Charlotte">Charlotte</option>
				<option value="Savannah">Savannah</option>
				<option value="Orlando">Orlando</option>
				<option value="Miami">Miami</option>
			</select><br />
			<input type='submit' name='Check' value='Check Reviews' /><br />
			<input type='submit' name='back' value='Go Back' />
		</form>
	</body>
</html>