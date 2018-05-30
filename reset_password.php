<?php 
    include("includes/header.php");
    include("includes/form_handlers/reset_password_handler.php");
?>
    <div class="main_column column">
    	<div id="first">
    	<h2><?php echo $name;?> - Reset Password</h2>
		<a href="user_manual.pdf#page=14" target="_blank" style="float: right; padding: 0 100px;">Help <i class="fa fa-question fa-lg"></i></a><br>
    	<hr>
    
    	<form action="reset_password.php<?php echo $header;?>" method="POST">
    		<p><label>Old Password</label><input type="password" name="old_pass" maxlength="25" required></p>
    		<?php if(in_array("Old password was incorrect", $error_array)) echo "<p><label></label>Old password was incorrect</p>"; ?>
    		<p><label>New Password</label><input type="password" name="new_pass1" maxlength="25" required></p>
    		<p><label>Retype New Password</label><input type="password" name="new_pass2" maxlength="25" required></p>
    		<?php if(in_array("New passwords do not match", $error_array)) echo "<p><label></label>New passwords do not match</p>"; ?>
    		<p><label></label><input type="submit" name="change_pass" class="btn btn-primary" value="Change Passowrd"></p>
    		
    	</form>
    	</div>
    	<div id="second">
    		<h2>Password has been reset!</h2>
    	</div>	

    	<hr>
    	<div id="details" style="display: none;">
        <a href="employee_details.php?emp_id=<?php echo $empId;?>">
            <button type="button" class="btn btn-secondary">
            <i class="fa fa-arrow-circle-o-left fa-lg"></i> Return to Employee Details
            </button>
        </a>
    	</div>
    	<div id="settings" style="display: none;">
        <a href="settings.php">
            <button type="button" class="btn btn-secondary">
            <i class="fa fa-arrow-circle-o-left fa-lg"></i> Return to Settings
            </button>
        </a>
    	</div>
        <hr>
    </div>
</div>
</body>