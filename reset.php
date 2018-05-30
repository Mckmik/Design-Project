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
    <title>Change Password</title>
    <?php
    if (isset($_GET['password_changed'])){
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
        Change Password<br>
			<form action="reset.php?key=<?php echo $un . '&auth='.$ps;?>" method="POST">

                <input type="hidden" name="key" value="<?php echo $userId;?>"><br>
                <input type="password" name="pass1" placeholder="New Password"  maxlength="25"><br>
                <input type="password" name="pass2" placeholder="Confirm Password"  maxlength="25"><br>
				<br>
				<input type="submit" name="reset_submit" value="Change Password">
				<br>

				<?php if(in_array("Passwords do not match<br>", $error_array)) echo "Passwords do not match<br>"; ?>
				<br>
			

			</form>
			
        </div>
        <div id="second" style="display: none;">
            Password has been changed!
        </div>
        <a href="login.php">Back to Login</a><br>
        <a href="user_manual.pdf#page=8" target="_blank" style="padding: 0 100px;">Help?</a><br>
	</div>
</div>
</center>
</body>
</html>