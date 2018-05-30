<?php

$header = "";
$error_array = array();

if (isset($_GET['emp_id'])){
	$empId = $_GET['emp_id'];
	$header = "?emp_id=" . $empId;
	echo '
	<script>
		$(document).ready(function() {
            $("#details").show();
        });
	</script>
	';

} elseif (isset($_POST['emp_id'])){
	//still working on email pass recovery

} else {
	$empId = $user['user_id'];
	echo '
	<script>
		$(document).ready(function() {
            $("#settings").show();
        });
	</script>
	';
}

$query = mysqli_query($con, "SELECT * FROM users WHERE user_id='$empId'");
$row = mysqli_fetch_array($query);

$name = $row['first_name'] .  ' ' . $row['last_name'];


if (isset($_POST['change_pass'])){
	$oldPass = $_POST['old_pass'];
	$newPass1 = $_POST['new_pass1'];
	$newPass2 = $_POST['new_pass2'];

	//check if old pass matches db
	//Salt and hash password
	$query = mysqli_query($con, "SELECT salt FROM user_salts WHERE user_id='$empId'");
	$row = mysqli_fetch_array($query);
	$salt = $row['salt'];
	$oldPass = strip_tags($oldPass);
	$oldPass = mysqli_real_escape_string($con, $oldPass); 

	for ($i = 0; $i < 1000; $i++){
		$oldPass = md5($salt . $oldPass);
	}

	$check_database_query = mysqli_query($con, "SELECT * FROM users WHERE user_id='$empId' AND password='$oldPass'");

	$check_login_query = mysqli_num_rows($check_database_query);

	if($check_login_query == 1){

		if ($newPass1 == $newPass2){

			$newPass = strip_tags($newPass1);
			$newPass = mysqli_real_escape_string($con, $newPass); 

			for ($i = 0; $i < 1000; $i++){
				$newPass = md5($salt . $newPass);
			}

			$passQuery = mysqli_query($con, "UPDATE users SET password='$newPass' WHERE user_id='$empId'");

			echo '
			<script>
		        $(document).ready(function() {
		            $("#first").hide();
		            $("#second").show();
		        });
		     </script>
			';

		} else {
			array_push($error_array,"New passwords do not match");
		}

	} 
	else {
		array_push($error_array,"Old password was incorrect");
	}



}




?>