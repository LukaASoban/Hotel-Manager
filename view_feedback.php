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
	$table = "";
	$items = array();

	$review_location = "";


	if (isset($_POST['Check'])) {
		$location = $_POST['location'];	//location

		$query = "SELECT Rating, Comment FROM review WHERE Location = '$location'";

		$result=mysql_query($query);

		$table .= "<table border='1' style = 'width:100%'>";
		$table .= "<tr><th>Rating</th><th>Comment</th></tr>";
		while($row = mysql_fetch_assoc($result)) {
			$table.= "<tr><td>" . $row['Rating'] . "</td><td>" . $row['Comment'] . "</td></tr>";

		}
		$table .= "</table>";

		$review_location .= "Reviews of " . $location . " location:";
		

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
			</select>
			<input type='submit' name='Check' value='Check Reviews' />
			<br></br>
			<input type='submit' name='back' value='Go Back' />
		</form>
		<br></br>
		<p><big><?php echo $review_location; ?> </big></p>
		<div id="mydiv"><?php echo $table; ?></div> 
	</body>
</html>