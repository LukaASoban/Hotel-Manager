<?php
	/**
	 * Version : 0.1
	 * Author: Battulga Myagmarjav
	 */
	/* get connect to the MYSQL server */
	require 'connect.php';

	session_start();
	$username = $_SESSION['username'];
	/* selecting all cards for reference */
	$query = "SELECT Card_number
			  FROM payment_information
			  WHERE Username = '" . $username . "';";
	$cards = mysql_query($query);
	/* inserting a new card */
	$query_insert_card = "INSERT INTO payment_information
						  VALUES(";
	/* deleting a card*/
	$query_delete_card = "DELETE FROM payment_information
						  WHERE Card_number = ";
	$error_msg = $error_deletion = "";
	$card_number = $name = $exp_date = $cvv = "";

	$legal = true;
	/* save card based on user input */
	if (isset($_POST['save'])) {
		if (empty($_POST['name']) ||
			empty($_POST['card_number'] ||
			empty($_POST['exp_date']) ||
			empty($_POST['cvv'])))
		{
			$error_msg = "All inputs are required!";
			$legal = false;
		}


		if(!ctype_digit($_POST['card_number'])) { 
			$error_msg = "Card number must be only numbers!"; 
			$legal = false; 
		}

		if(!ctype_digit($_POST['cvv'])) { 
			$error_msg = "CVV must be only numbers!"; 
			$legal = false; 
		}

		if(!ctype_alpha($_POST['name'])) {
			$error_msg = "Name cannot contain numbers!"; 
			$legal = false; 
		}

		// if(ctype_alpha($_POST['exp_date']) < ) {
		// 	$error_msg = "Name cannot contain numbers!"; 
		// 	$legal = false; 
		// }

		
		/* if user inputs are legal, then process */
		if ($legal) {
			$card_number = $_POST['card_number'];
			$name = $_POST['name'];
			$exp_date = $_POST['exp_date'];
			$cvv = $_POST['cvv'];
			$query_insert_card .= $card_number . ", '" .
								  $name . "', '" .
								  $exp_date . "', " .
								  $cvv . ", '" .
								  $username . "');";
			

			mysql_query($query_insert_card);
			$cards = mysql_query($query); //refreshes 
		}
	}
	/* delete card based on user selection if there is any card */
	if (isset($_POST['delete']) && $_POST['card'] != 'none') {
		$query_delete_card .= $_POST['card'] . ";";
		mysql_query($query_delete_card);
		$cards = mysql_query($query); //refreshes
	}
	/* selection fails to pick */
	if (isset($_POST['delete']) && $_POST['card'] == 'none') {
		$error_deletion = "Select your card!";
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Payment Information</title>
	</head>
	<body style="text-align: center;">
		<h1>Payment Information</h1>

		<h3>Add Card</h3>
		<?php echo $error_msg;?>
		<form method="post">
			Name on Card
			<input type="text" name="name">
			<br>
			Card Number
			<input type="text" name="card_number" minlength='16' maxlength='16'>
			<br>
			Expiration Date
			<input type="date" name="exp_date">
			<br>
			CVV
			<input type="text" name="cvv" minlength='3' maxlength='3'>
			<br>
			<input type="submit" name="save" value="Save">
		<form>

		<h3>Deleta Card</h3>
		<?php echo $error_deletion;?>
		<br>
		<form method="post">
			Card Number
			<select name="card">
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
			<br>
			<input type="submit" name="delete" value="Delete">
		<form>
	</body>
</html>