<?php
	/**
	 * Version : 0.1
	 * Author: Battulga Myagmarjav
	 */

	/* get connect to MYSQL servr */
	require 'connect.php';
	/* instances for registration*/
	$username = $password = $cpassword = $email = "";
	$query = $result = "";
	$SQL_insert = "INSERT INTO customer VALUES";
	$SQL_select = "SELECT * FROM customer WHERE ";
	$error1 = $error2 = $error3 = $error4 = "";
	$legal = true;

	if (isset($_POST["register"])) { //button is on click
		if (empty($_POST['username'])) {
			$error1 = "Username is required!";
			$legal = false;
		} else {
			$username = $_POST['username'];
		}
		if (empty($_POST['password'])) {
			$error2 = "Password is required!";
			$legal = false;
		}
		if (empty($_POST['cpassword'])) {
			$error3 = "Confirm your password!";
			$legal = false;
		}
		if (empty($_POST['email'])) {
			$error4 = "Email is required!";
			$legal = false;
		} else {
			$email = $_POST['email'];
		}
		/* check username and email are valid to be registered */
		if ($legal) {
			$result = mysql_query($SQL_select . "username = '" . $_POST['username'] . "'");
			if (!empty($result) && mysql_num_rows($result) > 0) {
				$error1 = "This username is already taken!";
				$legal = false;
			}
			$result = mysql_query($SQL_select . "email = '" . $_POST['email'] . "'");
			if (!empty($result) && mysql_num_rows($result) > 0) {
				$error4 = "This email is already used!";
				$legal = false;
			}
		}
		/**
		 * if everything falls into legal and password matches
		 * otherwise it will send an message that says what 
		 * needs to be done.
		 */
		if ($legal && $_POST['password'] != $_POST['cpassword']) {
			$error3 = "Password has to match!";
		}
		if ($legal && $_POST['password'] == $_POST['cpassword']) {
			$username = $_POST['username'];
			$password = $_POST['password'];
			$email = $_POST['email'];
			$query = $SQL_insert . "('" . $username . "', '" . $password . "', '" . $email . "')";
			$result = mysql_query($query);
			header("Location: customer_panel.php");
		}
	}
	mysql_close();
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Registration Page</title>
	</head>
	<body>
		<form method="post">
			<input type="text" name="username" value="<?php echo $username;?>" placeholder="username" required>
			<?php echo $error1;?>
			<br>
			<input type="password" name="password" placeholder="password" required>
			<?php echo $error2;?>
			<br>
			<input type="password" name="cpassword" placeholder="repeat password" required>
			<?php echo $error3;?>
			<br>
			<input type="text" name="email" value='<?php echo $email;?>' placeholder="email" required/>
			<?php echo $error4;?>
			<br>
			<input type="submit" name="register" value="SUBMIT">
		</form>
	</body>
</html>