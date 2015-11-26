<?php
	/**
	 * Version : 0.1
	 * Author: Battulga Myagmarjav
	 */

	/* get connect to MYSQL server */
	require 'connect.php';
	/* instances for login */
	$alert_msg = "";
	$username = "";
	$password = "";
	$query = "";
	$result = "";
	$query_select = "SELECT * FROM";

	session_start();

	if (!empty($_POST['username']) && !empty($_POST['password'])) {
		$username = $_POST['username'];
		$password = $_POST['password'];
		$condition = "WHERE username = " . "'" . $username . "'" .
					  " AND password = " . "'" . $password . "'";
		$_SESSION['username'] = $username;
		/* check what type of user is trying to access */
		if ($_POST["user_type"] ==  "customer") {
			// SQL for customer table
			$query = $query_select . " customer " . $condition;
		}
		/* check what type of user is trying to access */
		if ($_POST["user_type"] ==  "manager") {
			// SQL for management table
			$query = $query_select . " management " . $condition; 
		}
		/* check query is empty */
		if (!empty($query)) {
			$result = mysql_query($query);
		}
		/** 
		 * If person is found then send him/her proper page
		 * otherwise stay on login page and display what warn
		 * them. 
		 */
		if (mysql_num_rows($result) > 0) {
			if ($_POST["user_type"] == "customer") {
				header("Location: customer_panel.php");
			} else {
				header("Location: management_panel.php");
			}
		} else {
			$alert_msg = "Wrong password or username! Please try again!";
		} 
	}
	if (empty($_POST['username']) && !empty($_POST['password'])) {
		$alert_msg = "Your username is required!";
	}
	if (!empty($_POST['username']) && empty($_POST['password'])) {
		$username = $_POST['username'];
		$alert_msg = "Your password is required!";
	}
	if (isset($_POST["login"]) && empty($_POST['username']) && empty($_POST['password'])) {
		$alert_msg = "Your username and password are required!";
	}
	
	mysql_close();
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Welcome to Login Page</title>
	</head>
	<body>
		<?php echo $alert_msg;?>
		<form method="post">
			<br>
			<input type="text" name="username" value="<?php echo $username;?>" placeholder="username" required>
			<br>
			<input type="password" name="password" placeholder="password" required>
			<br>
			<input type="submit" name="login" value="LOGIN">
			<br>
			<input type="radio" name="user_type" value = "customer" checked> Customer <br>
			<input type="radio" name="user_type" value = "manager"> Manager
			<br>
			<a href="registration.php"> click on registration </a>
		</form>
	</body>
</html>