<!DOCTYPE html>
<?php
require 'config/config.php';
require 'includes/form_handlers/login_handler.php';
?>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
.login_box{
	margin: 80px auto;
	top: 80px;
	width: 300px;
	border: 1px solid #d3d3d3;
	border-radius: 5px;
	box-shadow: 2px 2px 1px #d3d3d3;
	background-color: #fff;
	padding: 10px;
}
body{
	background: #e6eae8;;
}
</style>
<head>
	<title>Login to TEOS</title>
</head>
<body>
<center>
<div class="wrapper">
	<div class="login_box">
		<div class="login_header">
			<h1>TEOS</h1>
			Login
		</div>
		<div id="first">
			<form action="login.php" method="POST">
				<input type="text" name="log_username" placeholder="Username" maxlength="25" value="<?php
				if(isset($_SESSION['log_username'])) {
					echo $_SESSION['log_username'];
				}
				?>" required>
				<br>
				<input type="password" name="log_password" maxlength="25" placeholder="Password">
				<br>
				<input type="submit" name="login_button" value="Login">
				<br>

				<?php if(in_array("Username or password was incorrect<br>", $error_array)) echo "Username or password was incorrect<br>"; ?>
				<br>
			

			</form>
			<a href="forgot_password.php">Forgot Password?</a><br>
			<a href="user_manual.pdf#page=6" target="_blank" style="padding: 0 100px;">Help?</a><br>
		</div>
	</div>
</div>
</center>
</body>
</html>