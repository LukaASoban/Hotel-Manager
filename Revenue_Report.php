<html>
	<head>
		<title>Resenue</title>
	</head>

	<body>

		<?php
		/**
		* Version: 1.0
		* Author: Thanh Tran
		* Total revenue report in each location in Agust and Septemper
		**/
			include ('connect.php');

			$query = "SELECT R.Location, sum(R.Total_cost) AS Cost
FROM
(
SELECT Reservation_has_room.Room_number, Reservation_has_room.Location, Reservation.Total_cost
FROM Reservation_has_room
NATURAL JOIN Reservation
WHERE MONTH(Reservation.End_date) = '08'
)R
GROUP BY R.Location";
			$result = mysql_query($query);
			echo "<table border = '5'>";
			echo "<tr> <th COLSPAN='3'> Revenue Report</th> </tr>";
			echo "<tr><th> Month</th><th>Location</th><th>Cost</th></tr>";
			echo "<th ROWSPAN=".(mysql_num_rows($result) + 1 ).">August</th>";
			while($row = mysql_fetch_assoc(($result))) {
				echo "<tr>";
				echo "<td>". $row['Location'] . "</tb>";
				echo "<td>". $row['Cost'] . "</tb>";

				echo "</tr>";
			}

			$query = "SELECT R.Location, sum(R.Total_cost) AS Cost
FROM
(
SELECT Reservation_has_room.Room_number, Reservation_has_room.Location, Reservation.Total_cost
FROM Reservation_has_room
NATURAL JOIN Reservation
WHERE MONTH(Reservation.End_date) = '09'
)R
GROUP BY R.Location";
			$result = mysql_query($query);
			echo "<th ROWSPAN=".(mysql_num_rows($result) + 1).">September</th>";
			while($row = mysql_fetch_assoc(($result))) {
				echo "<tr>";
				echo "<td>". $row['Location'] . "</tb>";
				echo "<td>". $row['Cost'] . "</tb>";
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