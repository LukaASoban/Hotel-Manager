<!DOCTYPE html>
<html>
	<head>
		<title>Customer Panel</title>
	</head>
	<body>
		<h2> Welcome <?php session_start(); echo $_SESSION['username'] . ",";?></h2>
		<br>
		<a href="search_rooms.php"> Make new reservation </a>
		<br>
		<a href="update_reservation.php"> Update your reservation </a>
		<br>
		<a href="cancel_reservation.php"> Cancel Reservation </a>
		<br>
		<a href="provide_feedback.php"> Provide feedback</a>
		<br>
		<a href="view_feedback.php"> View feedback </a>
	</body>
</html>