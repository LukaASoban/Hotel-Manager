<!DOCTYPE html>
<html>
	<head>
		<title>Management Panel</title>
	</head>
	<body>
		<h2> Welcome <?php session_start(); echo $_SESSION['username'] . ",";?></h2>
		<br>
		<a href="new_reservation.php"> View Reservation report </a>
		<br>
		<a href="update_reservation.php"> View Popular room category report </a>
		<br>
		<a href="cancel_reservation.php"> View revenue report </a>
	</body>
</html>