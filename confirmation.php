<?php
	session_start();
	$reservationID = $_SESSION['reservationID'];
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Confirmation Screen</title>
	</head>
	<body style="text-align: center;">
		<h1>Confirmation Screen</h1>
		<label for="id">Your Reservation ID</label>
		<input type="text" id="id" value="<?php echo $reservationID;?>" readonly>
		<p>Please save this reservation id for all further communication.</p>
	</body>
</html>