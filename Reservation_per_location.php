<html>
	<head>
		<title>Reservation Report </title>
	</head>

	<body>

		<?php
		/**
		* Version: 1.0
		* Author: Thanh Tran
		* Report of the total reservation in each location in August and Septemper
		**/
			include ('connect.php');

			$query = "SELECT Reservation_has_room.Location, COUNT(*) AS Num FROM Reservation_has_room NATURAL JOIN Reservation WHERE month(Reservation.End_date) = '08' GROUP BY Reservation_has_room.Location";
			$result = mysql_query($query);
			echo "<table border = '5'>";
			echo "<tr> <th COLSPAN='3'> Reservation Report</th> </tr>";
			echo "<tr><th> Month</th><th>Location</th><th>Num</th></tr>";
			echo "<th ROWSPAN='6'>August</th>";
			while($row = mysql_fetch_assoc(($result))) {
				echo "<tr>";
				echo "<td>". $row['Location'] . "</tb>";
				echo "<td>". $row['Num'] . "</tb>";
				echo "</tr>";
			}
			//echo "</table>";


			$query = "SELECT Reservation_has_room.Location, COUNT(*) AS Num FROM Reservation_has_room NATURAL JOIN Reservation WHERE month(Reservation.End_date) = '09' GROUP BY Reservation_has_room.Location";
			$result = mysql_query($query);
			//echo "December";
			//echo "<table border = '5'><tr><th>Location</th><th>Num</th></tr>";
			echo "<tr><th ROWSPAN='6'>September</th>";
			while($row = mysql_fetch_assoc($result)) {
				echo "<tr>";
				echo "<td>". $row['Location'] . "</tb>";
				echo "<td>". $row['Num'] . "</tb>";
				echo "</tr>";
			}
			echo "</table>";

			mysql_close($db_server);
		?>
		<form action="management_panel.php">
			<input type="submit" value="Back">
		</form>
	</body>
</html>