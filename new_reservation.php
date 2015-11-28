<?php
	/**
	 * Version : 0.1
	 * Author: Battulga Myagmarjav
	 */

	/* get connected */
	require 'connect.php';
	/* three tables needs to be updated */
	$error_msg = "";
	$query1 = $query2 = "";

	/* session with available room tables */
	session_start();
	$table = $_SESSION['available_rooms'];
	$username = $_SESSION['username'];
	
	/* selecting all cards for reference */
	$query = "SELECT Card_number
			  FROM payment_information
			  WHERE Username = '" . $username . "';";
	$cards = mysql_query($query);

	/* query for new reservation to inserted */
	$query_reserve = "INSERT INTO reservation
					  VALUES(";

	/* qeury for booking rooms */
	$query_room = "INSERT INTO reservation_has_room
				   VALUES(";

	/* need this query for generating ID */
	$query_count = "SELECT ReservationID
					FROM reservation;";
	$result = mysql_query($query_count);
	/* lets generate ID */
	$reservationID = 10000;
	if (!empty($result)) {
		$reservationID = mysql_num_rows($result) + 10001;
	}
	$_SESSION['reservationID'] = $reservationID;

	/* this 2D array is for available rooms table*/
	$selected_table = array(array());
	
	$total = 0; // total cost

	/* number of days to stay at hotel */
	$date1 = date_create($_SESSION['start_date']);
	$date2 = date_create($_SESSION['end_date']);
	$interval = date_diff($date1, $date2);
	$days = $interval->format('%a');

	/* if desired rooms are selected it will be added into array*/
	if (isset($_POST['check_details'])) {
		for ($i = 0; $i < sizeof($table); $i++) {
			if (isset($_POST[$i]) && $_POST[$i] == 'on') {
				array_push($selected_table, $table[$i]);
				$total += $table[$i]['Cost'];
			}
		}
		$total *= $days;
		$_SESSION['selected_table'] = $selected_table;
	}
	/* keeps the updated total when submit fails */
	$check_array = array(array());
	if (isset($_POST['submit'])) {
		$total = $_POST['total'];
	}
	/**
	 * if desired rooms are selected or/and extra bed is selected
	 * plus user has at least one card and selected right options
	 * then he/she may submit
	 */
	if (isset($_POST['submit']) && !empty($cards) && $_POST['card'] != 'none') {
		$selected_table = $_SESSION['selected_table'];
		$query_reserve .= $reservationID . ", '"
						. $_SESSION['start_date'] . "', '"
						. $_SESSION['end_date'] . "', "
						. $total . ", 0, '"
						. $_SESSION['username'] . "', "
						. $_POST['card'] . ");";
		mysql_query($query_reserve);
		for ($r = 1; $r < sizeof($selected_table); $r++) {
			$query_room1 = $query_room . $reservationID . ", "
							. $selected_table[$r]['Room_number'] . ", '"
							. $_SESSION['location'] . "', ";
			$attr = "t" . $r;
			if (isset($_POST[$attr])) {
				$query_room1 .= "1);";
			} else {
				$query_room1 .= "0);";
			}
			echo $query_room1;
			mysql_query($query_room1);
		}
		header("Location: confirmation.php");
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Make a Reservation</title>
		<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	</head>
	<body style="text-align: center;">
		<h1>Make a Reservation</h1>
		<?php echo $error_msg;?>
		<form id="form1" method="post" onsubmit="return collapse(this);">
			<table id="avail_table" style="width: 50%; text-align: center; margin: 0px auto;">
			<?php
				/* while going through all available rooms, make the table*/
				echo "<tr>";
					echo "<th>Room Number</th>";
					echo "<th>Room Category</th>";
					echo "<th>#persons allowed</th>";
					echo "<th>Cost per day</th>";
					echo "<th>Cost extra bed per day</th>";
					echo "<th>Select Room</th>";
				echo "</tr>";
				for ($row = 0; $row < sizeof($table); $row++) {
					echo "<tr>";
						echo "<td>" . $table[$row]['Room_number'] . "</td>";
						echo "<td>" . $table[$row]['Category'] . "</td>";
						echo "<td>" . $table[$row]['Capacity'] . "</td>";
						echo "<td>" . $table[$row]['Cost'] . "</td>";
						echo "<td>" . $table[$row]['Cost_extra_bed'] . "</td>";
						echo "<td> <input type=\"checkbox\" name = \"" . $row . "\" id=\"" . $row . "\"> </td>";
					echo "</tr>";
				}
			?>
			</table>
			<input type="submit" name="check_details" value="Check Details">
		</form>
		<!-- reservation must be made after click submit -->
		<div id="box">
			<form id="form2" method="post" onsubmit="return validateCard(this);">
				<table id="rev_table" style="width: 50%; text-align: center; margin: 0px auto;">
				<?php
					/* while going through all available rooms, make the table*/
					$i = 0;
					for ($r = 1; $r < sizeof($selected_table); $r++) {
						if ($i == 0) {
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
							echo "<td>" . $selected_table[$r]['Room_number'] . "</td>";
							echo "<td>" . $selected_table[$r]['Category'] . "</td>";
							echo "<td>" . $selected_table[$r]['Capacity'] . "</td>";
							echo "<td>" . $selected_table[$r]['Cost'] . "</td>";
							echo "<td>" . $selected_table[$r]['Cost_extra_bed'] . "</td>";
							echo "<td> <input type=\"checkbox\" id=\""
									. $selected_table[$r]['Room_number']
									. "\" value=\"" . $days
									. "\" onclick=\"update()\" name=\"t" . $r . "\"> </td>";

						echo "</tr>";
					}
				?>
				</table>
				<!-- This is where all information starts before submission -->
				Start Date
				<input type="text" value=<?php echo $_SESSION['start_date']?> readonly>
				End Date
				<input type="text" value=<?php echo $_SESSION['end_date']?> readonly>
				<br>
				Total Cost
				<input type="text" name="total" id="total" value=<?php echo $total?> readonly="readonly" />
				<br>
				Use Card
				<select id="card" name="card">
					<option value="none">none</option>
					<?php
					if (!empty($cards)) {
						while ($row = mysql_fetch_assoc($cards)) {
							$card = $row['Card_number'];
							echo "<option value=\"" . $card . "\">" . substr($card, 0, 4) . "</option>";
						}
					}
					?>
				</select>
				<a href="payment_information.php">Add Card</a>
				<br>
				<input type="submit" name="submit" value="Submit">
			</form>
		</div>
		<!-- adds the total according to user check -->
		<script>
			var table = document.getElementById('rev_table');
			/* this extra row knows which one is checked */
			var flipped = ['0'];
			for (var n = 0; n < table.rows.length; n++) {
				flipped[n] = '0';
			}
			/* this function updates the total cost if user checks extra bed*/
			function update() {
				var total = document.getElementById('total').value;
				/* go through the table get what you need add */
				for (var i = 1; i < table.rows.length; i++) {
					var arr = table.rows.item(i).cells;	// one row at a time
					var unique_id = arr.item(0).innerHTML; // room number 
					var num = arr.item(4).innerHTML; // cost per day
					var days = document.getElementById(unique_id).value;
					if (document.getElementById(unique_id).checked && flipped[i] == '0') {
						flipped[i] = '1';
						var new_total = parseInt(total) + (parseInt(num) * days);
						document.getElementById('total').value = new_total;
					} 
					if (!document.getElementById(unique_id).checked && flipped[i] == '1') {
						flipped[i] = '0';
						var new_total = parseInt(total) - (parseInt(num) * days);
						document.getElementById('total').value = new_total;
					}
				}
			}
			/* make sure validate he/she has at least a card */
			function validateCard() {
    			var card = document.getElementById('card').value;
    			if (card == 'none') {
   					alert("Select or add your card information!");
    				return false;
    			}
    			return true;
			}
        </script>
	</body>
</html>