<?php
	/**
	 * Version : 0.1
	 * Author: Battulga Myagmarjav
	 */
	
	/* get connected */
	require 'connect.php';

	session_start();
	/* this query will give customer all his/her reservations */
	$query = "SELECT *
			  FROM reservation 
			  WHERE Is_cancelled = false AND Username = '" . $_SESSION['username'] . "';";
	$result = mysql_query($query);

	/* these queries will accomplish what cancel reservation needs */
	$query_cancel = "SELECT * 
					 FROM (SELECT *
      					   FROM reservation_has_room
      					   NATURAL JOIN reservation
      					   WHERE reservation.Is_cancelled = false) as A
					 NATURAL JOIN room
					 WHERE A.ReservationID = ";
	$result_cancel = "";

	$query_delete = "DELETE FROM reservation_has_room
					 WHERE ReservationID = ";
	$query_update = "UPDATE reservation
					 SET Is_cancelled = true
					 WHERE ReservationID = ";
	$reservation = "";

	/* instances for dates */
	$start_date = $end_date = $days = "";
	$cancel_date = date("m/d/Y");
	$total_cost = 0;
	$refund = 0;

	/* after selection, table will appear */
	if (isset($_POST['reservation'])) {
		while ($row = mysql_fetch_assoc($result)) {
			if ($row['ReservationID'] == $_POST['reservation']) {
				$start_date = date("m/d/Y", strtotime($row['Start_date']));
				$end_date = date("m/d/Y", strtotime($row['End_date']));
				$_SESSION['cancel_ID'] = $_POST['reservation'];
				$query_cancel .= $_POST['reservation'];
				$result_cancel = mysql_query($query_cancel);
				break;
			}
		}
	}

	/* cancel if user submits */
	if (isset($_POST['cancel'])) {
		$query_update .= $_SESSION['cancel_ID'] . ";";
		mysql_query($query_update);
		$query_delete .= $_SESSION['cancel_ID'] . ";";
		mysql_query($query_delete);
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Cancel Your Reservation</title>
	</head>
	<body style="text-align: center">
		<h1>Cancel Reservation</h1>
		<form method="post">
			<select id="reservation" name="reservation" onchange="this.form.submit()">
				<option value="none">none</option>
				<?php
					if (!empty($result)) {
						while ($row = mysql_fetch_assoc($result)) {
							$reservationID = $row['ReservationID'];
							echo "<option value=\"" . $reservationID . "\">" . $reservationID . "</option>";
						}
					}
				?>
			</select>
			
			<br>
			Start Date <input type="text" value="<?php echo $start_date;?>" readonly>
			End Date <input type="text" value="<?php echo $end_date;?>" readonly>

			<table id="table" style="width: 50%; text-align: center; margin: 0px auto;">
			<?php
				/* makes your room */
				$i = 0;
				while (!empty($result_cancel) && $row = mysql_fetch_assoc($result_cancel)) {
					/* only one time */
					if ($i == 0) {
						/* how much will be refunded*/
						$total_cost = $row['Total_cost'];
						$date1 = date_create($start_date);
						$date2 = date_create($cancel_date);
						$interval = date_diff($date1, $date2);
						$days = $interval->format('%a');
						if ($days > 1 && $days < 4) {
							$refund = $total_cost * 0.8;
						}
						if ($days > 3) {
							$refund = $total_cost;
						}
						/* table header */
						echo "<tr>";
							echo "<th>Room Number</th>";
							echo "<th>Room Category</th>";
							echo "<th>#persons allowed</th>";
							echo "<th>Cost per day</th>";
							echo "<th>Cost extra bed per day</th>";
							echo "<th>Select Extra Bed</th>";
						echo "</tr>";
						$i++;
					}
					echo "<tr>";
						echo "<td>" . $row['Room_number'] . "</td>";
						echo "<td>" . $row['Category'] . "</td>";
						echo "<td>" . $row['Capacity'] . "</td>";
						echo "<td>" . $row['Cost'] . "</td>";
						echo "<td>" . $row['Cost_extra_bed'] . "</td>";
						echo "<td> <input type=\"checkbox\"";
						if ($row['Include_extra_bed'] == 1) {
							echo "checked disabled";
						}
						echo "> </td>";
					echo "</tr>";
				}
			?>
			</table>
			<br>
			Total Cost of Reservation <input type="text" value="<?php echo $total_cost;?>" readonly>
			<br>
			Date of Cancellation <input type="text" value="<?php echo $cancel_date;?>" readonly>
			<br>
			Amount to be refunded <input type="text" value="<?php echo $refund;?>" readonly>
			<br>
			<input type="submit" name="cancel" value="Cancel">
		<form>
	</body>
</html>