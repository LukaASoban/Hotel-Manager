<?php
	/**
	 * Version : 0.1
	 * Author: Battulga Myagmarjav
	 */

	/* get connect to MYSQL server */
	require 'connect.php';

	/* start session */
	session_start();

	/* instances for search engine */
	$location = $start_date = $end_date = "";
	$query = "SELECT DISTINCT *
			  FROM room as r
			  WHERE r.Location = '";
	$result = "";
	$error_date = $error_empty = $error_msg = "";
	$legal = true;

	if (isset($_POST['search'])) {
		$location = $_POST['location'];	//location
		/* check empty if yes send the mesasage */
		if (empty($_POST['start_date']) || empty($_POST['end_date'])) {
			$error_empty = "Your dates cannot be in empty!";
			$legal = false;
		}
		/* If start date is not empty and not in the past else send warning*/
		if (!empty($_POST['start_date']) && $_POST['start_date'] < date("y-m-d")) {
			$error_date = "The dates cannot be in the past!";
			$legal = false;
		} else {
			$start_date = $_POST['start_date'];
		}
		/* If end date is not empty and not in the past else send warning */
		if (!empty($_POST['end_date']) && $_POST['end_date'] < date("y-m-d")) {
			$error_date = "The dates cannot be in the past!";
			$legal = false;
		} else {
			$end_date = $_POST['end_date'];
		}
		if ($legal) {
			/* declerae the rest of varaiables */
			$start_date = $_POST["start_date"];
			$end_date = $_POST["end_date"];
			/* all the sessions start here , for future reference */
			$_SESSION['location'] = $location;
			$_SESSION['start_date'] = $start_date;
			$_SESSION['end_date'] = $end_date;
			/* query to check whether there is available */
			$query .= $location . "' && (r.Room_number, r.Location, r.Cost, 
					r.Category, r.Capacity, r.Cost_extra_bed) NOT IN
	    			(SELECT *
	     		 	FROM (SELECT rhr.Room_number, rhr.Location
	           		FROM reservation as r
	           		INNER JOIN reservation_has_room as rhr
	           		ON r.ReservationID = rhr.ReservationID
	           		&& r.Is_cancelled = 0 && r.End_date >= '";
	        $query .= $start_date . "' && rhr.Location = '"
	        		. $location . "') as A NATURAL JOIN room)";
			/* result of my query */
			$result = mysql_query($query);
			/* need my query result in the future */
			if (empty($result)) {
				$error_msg = "All rooms are booked within your input time!";
			} else {
				$table = array(array());
				for ($r = 0; $r < mysql_num_rows($result); $r++) {
					$row = mysql_fetch_assoc($result);
					$table[$r]['Room_number'] = $row['Room_number'];
					$table[$r]['Cost'] = $row['Cost'];
					$table[$r]['Category'] = $row['Category'];
					$table[$r]['Capacity'] = $row['Capacity'];
					$table[$r]['Cost_extra_bed'] = $row['Cost_extra_bed'];
				}
				$_SESSION['available_rooms'] = $table;
				header("Location: new_reservation.php");
			}
		}
	}
?>

<?php echo "<!DOCTYPE html>"; ?>
<html>
	<head>
		<title>Search Rooms</title>
	</head>
	<body>
		<?php echo $error_empty . $error_date . $error_msg;?>
		<form method="post">
			<select name="location">
				<option value="atlanta">Atlanta</option>
				<option value="charlotte">Charlotte</option>
				<option value="savannah">Savannah</option>
				<option value="orlando">Orlando</option>
				<option value="miami">Miami</option>
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