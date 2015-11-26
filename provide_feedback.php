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


	if (isset($_POST['Submit'])) {
		$location = $_POST['location'];	//location
		$rating = $_POST['rating'];	//rating
		$comment = $_POST['comment']; //comment
		$user = $_SESSION['username'];

		$query .= "INSERT INTO `test`.`review` (`Comment`, `Rating`, `Location`, `Username`) VALUES ('$comment', '$rating', '$location', '$user');";

		$result=mysql_query($query);

		if (empty($result)) {
			$error_msg = "An ERROR has occured with your submisson";
			echo "<script type='text/javascript'>alert('$error_msg');</script>";
		} else {
			header("Location: customer_panel.php");
		}
	}


	
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Provide Feedback</title>
	</head>
	<body>
		Location:
		<form method="post">
			<select name="location">
				<option value="Atlanta">Atlanta</option>
				<option value="Charlotte">Charlotte</option>
				<option value="Savannah">Savannah</option>
				<option value="Orlando">Orlando</option>
				<option value="Miami">Miami</option>
			</select><br />
		Rating:
			<select name="rating">
				<option value="Excellent">Excellent</option>
				<option value="Good">Good</option>
				<option value="Bad">Bad</option>
				<option value="Very Bad">Very Bad</option>
				<option value="Neutral">Neutral</option>
			</select>
			<br />
		Comment: <input type='text' name='comment' id='comment' />
		<input type='submit' name='Submit' />
		</form>

	</body>
</html>


