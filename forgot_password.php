<!DOCTYPE html>
<?php
require 'config/config.php';
include("includes/handlers/fp_handler.php");


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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <title>Password Recovery</title>
    <?php
    if (isset($_GET['sent'])){
        echo'
        <script>
        $(document).ready(function() {
            $("#first").hide();
            $("#second").show();
        });
        </script>
        ';
    }
    ?>
    
</head>
<body>
<center>
<div class="wrapper">
	<div class="login_box">
		<div class="login_header">
			<h1>TEOS</h1>
		</div>
		<div id="first">
            Password Recovery<br>
			<form action="forgot_password.php" method="POST">
				<input type="text" name="fp_username" placeholder="Username" maxlength="25" value="<?php
				if(isset($_SESSION['fp_username'])) {
					echo $_SESSION['fp_username'];
				}
				?>" required>
				<br>
				<input type="submit" name="fp_submit" value="Send reset link">
				<br>

				<?php if(in_array("Username was incorrect<br>", $error_array)) echo "Username was invalid<br>"; ?>
				<br>
			

			</form>
			
        </div>
        <div id="second" style="display: none;">
            An email has been sent to the address associated with this account. Please check your email.
        </div>
        <a href="login.php">Back to Login</a><br>
		<a href="user_manual.pdf#page=8" target="_blank" style="padding: 0 100px;">Help?</a><br>
	</div>
</div>
</center>
</body>
</html>