<?php

$error_array = array(); //Holds error messages 

if(isset($_POST['login_button'])){

	$username = $_POST['log_username']; //Get username
	$username = strip_tags($username);
	//$username = str_replace(' ', '', $username);
	$username = mysqli_real_escape_string($con, $username);
	$_SESSION['log_username'] = $username; //Store username into session variable

	//Salt and hash password
	$query = mysqli_query($con, "SELECT salt FROM user_salts JOIN users ON users.user_id=user_salts.user_id WHERE users.username='$username'");
	$row = mysqli_fetch_array($query);
	$salt = $row['salt'];
	$password = $_POST['log_password'];
	$password = strip_tags($password);
	//$password = str_replace(' ', '', $password);
	$password = mysqli_real_escape_string($con, $password); 

	for ($i = 0; $i < 1000; $i++){
		$password = md5($salt . $password);
	}
	

	$check_database_query = mysqli_query($con, "SELECT * FROM users WHERE username='$username' AND password='$password' AND is_deleted='no'");

	$check_login_query = mysqli_num_rows($check_database_query);

	if($check_login_query == 1){
		$row = mysqli_fetch_array($check_database_query);
		$username = $row['username'];

		$user_closed_query = mysqli_query($con, "SELECT * FROM users WHERE email='$email' AND user_closed='yes'");

		$_SESSION['username'] = $username;
		header("Location: index.php");
		exit();
	} 
	else {
		array_push($error_array,"Username or password was incorrect<br>");
	}

}

?>