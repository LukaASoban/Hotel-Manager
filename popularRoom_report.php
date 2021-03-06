<html>
	<head>
		<title>Popular Room </title>
	</head>

	<body>

		<?php
		/**
		* Version: 1.0
		* Author: Thanh Tran
		* Report of popular room category in each location in August
		**/
			include ('connect.php');

			$query = "SELECT RR.Location,RR.Category, MAX( RR.Num ) AS Num 
FROM 
(
	SELECT R.Room_number, R.Location, Room.Category, COUNT( * ) AS Num
	FROM 
	(
		SELECT Reservation_has_room.Room_number, Reservation_has_room.Location
		FROM Reservation_has_room
		NATURAL JOIN Reservation
		WHERE MONTH(Reservation.End_date) = '08'
	)R
	LEFT JOIN Room ON R.Room_number = Room.Room_number
	AND R.Location = Room.Location
	GROUP BY Location, Category
)RR
GROUP BY Location";
			$result = mysql_query($query);
			echo "<table border = '5'>";
			echo "<tr> <th COLSPAN='4'> Popular Room</th> </tr>";
			echo "<tr><th> Month</th><th>Location</th><th>Category</th><th>Num</th></tr>";
			echo "<th ROWSPAN='6'>August</th>";
			while($row = mysql_fetch_assoc(($result))) {
				echo "<tr>";
				echo "<td>". $row['Location'] . "</tb>";
				echo "<td>". $row['Category'] . "</tb>";
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