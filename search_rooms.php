<?php
	/**
	 * Version : 0.1
	 * Author: Battulga Myagmarjav
	 */

	/* get connect to MYSQL server */
	require 'connect.php';

	/* instances for search engine */
	$location = $start_date = $end_date = "";
	$query = "SELECT DISTINCT *
			  FROM room as r
			  WHERE r.Location = '"
	$result = "";
	$error_msg = "";

	/* start session */
	session_start();

	if (isset($_POST['search'])) {
		$location = $_POST['location'];	//location
		/* If start date is empty then by default is current date */
		if (empty($_POST['start_date'])) {
			$start_date = date("y-m-d");
		} else {
			$start_date = $_POST['start_date'];
		}
		/* If end date is empty then by default is a day after current date */
		if (empty($_POST['end_date'])) {
			$end_date = date("y-m-") . (date("d") + 1);
		} else {
			$end_date = $_POST['end_date'];
		}
		/* all the sessions start here , for future reference */
		$_SESSION['location'] = $location;
		$_SESSION['start_date'] = $start_date;
		$_SESSION['end_date'] = $end_date;
		/* query to check whether there is unavailable */
		$query += $location . "' && (r.Room_number, r.Location, r.Cost, 
				r.Category, r.Capacity, r.Cost_extra_bed) NOT IN
    			(SELECT *
     		 	FROM (SELECT rhr.Room_number, rhr.Location
           		FROM reservation as r
           		INNER JOIN reservation_has_room as rhr
           		ON r.ReservationID = rhr.ReservationID
           		&& r.Is_cancelled = 0 && r.End_date >= '"
        $query += $start_date . "' && rhr.Location = '"
        		. $location . "') as A NATURAL JOIN room)"

		$result = mysql_query($query);
		if (empty($result)) {
			$error_msg = "All rooms are booked within your input time!";
		} else {
			$_SESSION['available_rooms'] = $result;
			header("Location: new_reservation.php");
		}
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Search Rooms</title>
	</head>
	<body>
		<?php echo "Location";?>
		<form method="post">
			<select name="location">
				<option value="Atlanta">Atlanta</option>
				<option value="Charlotte">Charlotte</option>
				<option value="Savannah">Savannah</option>
				<option value="Orlando">Orlando</option>
				<option value="Miami">Miami</option>
			</select>
			<br>
			<?php echo "Start Date";?>
			<input type="text" name="start_date" min="<?php echo date("y-m-d");?>" required>
			<?php echo "End Date"?>
			<input type="date" name="end_date" required>
			<br>
			<input type="submit" name="search">
		</form>
	</body>
</html>