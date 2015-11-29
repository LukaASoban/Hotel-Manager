<?php
	/**
	 * Version : 0.1
	 * Author: Luka Antolic-Soban
	 */

	/* get connect to MYSQL server */
	require 'connect.php';

	/*Session is starting*/
	session_start();

	/* instances for search engine */
	//$_SESSION["query"] = "";
	//$_SESSION["resultCur"] = "";
	$_SESSION["table_current_dates"] = "";

	$_SESSION["savedReservation"] = "value=''";

	$_SESSION["newSearchForm"] = "";

	$table = "";


	/*Function for reservation ID*/
	function checkReservationID($reservationID) {
		$_SESSION["savedReservation"] = "value = '$reservationID'";
		$_SESSION["query"] = "SELECT Username, Start_date, End_date FROM reservation WHERE ReservationID = '$reservationID' && Is_cancelled = '0'";


		$_SESSION["resultCur"] = mysql_query($_SESSION["query"]);


		//if the ReservationID is not an active one TELL THE USER!
		if(mysql_num_rows($_SESSION["resultCur"]) == 0) {
			$message = "Sorry! There is no active reservation under this ID. It may have been canceled.";
			echo "<script type='text/javascript'>alert('$message');</script>";
			$_SESSION["resultCur"] = 0;
			return;
		}


		$_SESSION["table_current_dates"] .= "<table border='1' style = 'width:100%'>";
		$_SESSION["table_current_dates"] .= "<tr><th>Current Start Date</th><th>Current End Date</th></tr>";
		while($row = mysql_fetch_assoc($_SESSION["resultCur"])) {


			//if the user is not the one under RID restart the page!
			if(strcmp($row['Username'], $_SESSION["username"]) != 0) {
				$message = "You may NOT view reservations that are not under your name.";
				echo "<script type='text/javascript'>alert('$message');</script>";
				$_SESSION["resultCur"] = 0;
				$_SESSION["table_current_dates"] = "";
				return;
			}


			$phpdate1 = strtotime($row['Start_date']);
			$mysqldate1 = date( 'm/d/Y', $phpdate1);

			/////////////////////////////////////////////
			$datex = date('Y-m-d', strtotime($row['Start_date']));
			$currDate = date('Y-m-d');

			//diff between the current date and the start date
			$diff3 = date_diff(date_create($currDate), date_create($datex));
			$diff3 = $diff3->format("%a");

			if($diff3 <= 3) {
				$message = "You cannot update reservations 3 days before the Start Date of the reservation. You may only cancel.";
				echo "<script type='text/javascript'>alert('$message');</script>";
				$_SESSION["resultCur"] = 0;
				$_SESSION["table_current_dates"] = "";
				return;
			}


			$phpdate2 = strtotime($row['End_date']);
			$mysqldate2 = date( 'm/d/Y', $phpdate2);			

			$_SESSION["table_current_dates"].= "<tr><td>" . $mysqldate1 . "</td><td>" . $mysqldate2 . "</td></tr>";

		}
		$_SESSION["table_current_dates"] .= "</table>";	
	}



	/* User clicks the return button to go back to panel page */
	if(isset($_POST['goBack'])) {
		//go back to menu since it worked
		header("Location: customer_panel.php");
	}




	/*The button press check for Searching reservation IDs*/
	if (isset($_POST['search'])) {
		$reservationID = $_POST['reservationID'];
		$_SESSION["reservationID"] = $reservationID; //reservation ID
		
		checkReservationID($reservationID);

		if($_SESSION["resultCur"]) {
			$_SESSION["newSearchForm"] = "<form method='post'>
			New Start Date:<input type='text' name='newStartDate' value='mm/dd/yyyy' />
			New End Date:<input type='text' name='newEndDate' value='mm/dd/yyyy' />
			<input type='submit' name='newSearch' value='Search Availability' />
			</form>";
		}
	}





	if(isset($_POST['newSearch'])) {
		$newStartDate = $_POST['newStartDate'];
		$newEndDate = $_POST['newEndDate'];
		$rid = $_SESSION["reservationID"];

		// $_SESSION["newStartDate"] = $newStartDate;
		// $_SESSION["newEndDate"] = $newEndDate;

		//converted the post dates into sql readable dates
		$date1 = date('Y-m-d', strtotime($newStartDate));
		$date2 = date('Y-m-d', strtotime($newEndDate));
		$currDate = date('Y-m-d');

		$_SESSION["newStartDate"] = $date1;
		$_SESSION["newEndDate"] = $date2;

		//number of days in between the dates
		$diff = date_diff(date_create($date1), date_create($date2));
		$diff = $diff->format("%a");


		if((strtotime($date1) >= strtotime(date('Y-m-d'))) && (strtotime($date2) > strtotime($date1))) {
			//populates the top of the page again
			checkReservationID($_SESSION["reservationID"]);

			//this is the new form for search
			$_SESSION["newSearchForm"] = "<form method='post'>
				New Start Date:<input type='text' name='newStartDate' value='$newStartDate' />
				New End Date:<input type='text' name='newEndDate' value='$newEndDate' />
				<input type='submit' name='newSearch' value='Search Availability' />
			</form>";

			//query to see if there is a valid update reservation
			$query_for_valid = "SELECT Room_number, Location FROM reservation NATURAL JOIN reservation_has_room 
			WHERE (Start_date BETWEEN '$date1' AND '$date2') 
			&& (Room_number, Location) IN 
			(SELECT Room_number,Location FROM reservation NATURAL JOIN reservation_has_room GROUP BY Room_number,Location HAVING COUNT(*) > 1 ) && NOT(ReservationID = '$rid') &&  NOT(Is_cancelled = '1')";


			$check_valid_update = mysql_query($query_for_valid);


			//if the query result is an empty string then we know someone already reserved it
			if($row = mysql_fetch_assoc($check_valid_update)) {
				$message = "Sorry!, someone already reserved this Date. Please cancel if you want to reserve a room for this time period.";
				echo "<script type='text/javascript'>alert('$message');</script>";
			} else {

				//query the DB for the user's current reservation
				$query_current = "SELECT Room_number, Category, Capacity, Cost, Cost_extra_bed, Include_extra_bed
								FROM reservation_has_room NATURAL JOIN room
								WHERE ReservationID = '$rid'";

				// get the current result for the RID
				$currentRes = mysql_query($query_current);

				//initialize totalcost
				$totalcost = 0;

				//make the table for the current RID
				$table .= "<p><big>Rooms are available. Please confrim details below before submitting.</big></p>";
				$table .= "<table border='1' style = 'width:100%'>";
				$table .= "<tr><th>Room Number</th><th>Room Category</th><th>Total Persons Allowed</th><th>Cost per Day</th><th>Cost of Extra Bed per Day</th><th>Extra Bed?</th></tr>";
				while($row = mysql_fetch_assoc($currentRes)) {

					//extrabed cost?
					$extra = 0;

					if($row['Include_extra_bed'] == '1') { $row['Include_extra_bed'] = '&#10004;'; $extra = $row['Cost_extra_bed']; }
					else { $row['Include_extra_bed'] = '&#x2718;';}

					$table.= "<tr><td>" . $row['Room_number'] . "</td><td>" . $row['Category'] . "</td><td>" . $row['Capacity'] . "</td><td>"
							. $row['Cost'] . "</td><td>"
							. $row['Cost_extra_bed'] . "</td><td>"
							. $row['Include_extra_bed'] . "</td></tr>";

					//new total cost to send to the update query
					$totalcost = $totalcost + (($row['Cost'] + $extra) * $diff);


				}
				$table .= "</table>";
				$table .= "<br></br>";
				$table .= "<form method='post'>
						Total Cost Updated:<input type='text' name='totalCost' value='$$totalcost' readonly />
						<input type='submit' name='updateReservation' value='Update Reservation' />
						</form>";


				$_SESSION["newCost"] = $totalcost;




			}

		} else {

			$message = "Reservation is in the past or the End Date is before the Start Date. Please try a valid date!";
			echo "<script type='text/javascript'>alert('$message');</script>";

			//populates the top of the page again
			checkReservationID($_SESSION["reservationID"]);
			$_SESSION["newSearchForm"] = "<form method='post'>
			New Start Date:<input type='text' name='newStartDate' value='mm/dd/yyyy' />
			New End Date:<input type='text' name='newEndDate' value='mm/dd/yyyy' />
			<input type='submit' name='newSearch' value='Search Availability' />
			</form>";
		}



	}

	/* Here we will take in the new data the person has inputed and then send the
		update query to the DB */
	if(isset($_POST['updateReservation'])) {
		$user_rid = $_SESSION["reservationID"];
		$user_name = $_SESSION['username'];
		$user_nsd = $_SESSION["newStartDate"];
		$user_ned = $_SESSION["newEndDate"];
		$totalcost = $_SESSION["newCost"];



		$query_update = "UPDATE reservation SET Start_date = '$user_nsd', End_date = '$user_ned', Total_cost = '$totalcost' WHERE `ReservationID` = '$user_rid'";

		$finalsubmit = mysql_query($query_update);

		if(!$finalsubmit) {
			$message = "An ERROR has occured when trying to update the DB. Please try again.";
				echo "<script type='text/javascript'>alert('$message');</script>";
				$_SESSION["resultCur"] = 0;
				$_SESSION["table_current_dates"] = "";
		}
		//go back to menu since it worked
		header("Location: customer_panel.php");
	}



?>

<!DOCTYPE html>
<html>
	<head>
		<title>Update Reservation</title>
	</head>
	<body>
		Reservation ID
		<form method="post">
			<input type='text' name='reservationID' <?php echo $_SESSION["savedReservation"]; ?> />
			<input type='submit' name='search' value="Search" />
		</form>
		<br></br>
		<div id="table1"><?php echo $_SESSION["table_current_dates"]; ?></div><br></br>
		<div id="table2"><?php echo $_SESSION["newSearchForm"]; ?></div><br></br>
		<div id="table3"><?php echo $table; ?></div><br></br>
		<form method="post">
			<input type='submit' name='goBack' value="Back to Customer Panel" />
		</form>
	</body>
</html>