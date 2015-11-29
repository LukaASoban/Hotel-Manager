<!DOCTYPE html>
<html>
	<head>
		<title>Management Panel</title>
	</head>
	<body>
		<h2> Welcome <?php session_start(); echo $_SESSION['username'] . ",";?></h2>
		<br>
		<a href="Reservation_per_location.php"> View Reservation report </a>
		<br>
		<a href="popularRoom_report.php"> View Popular room category report </a>
		<br>
		<a href="Revenue_Report.php"> View revenue report </a>
	</body>
</html>