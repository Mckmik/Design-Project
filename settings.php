<?php 
    include("includes/header.php");
    include("includes/form_handlers/settings_handler.php");
?>
    <div class="main_column column">
    <h1>SETTINGS</h1>
    <hr>
    <a href="user_manual.pdf#page=7" target="_blank" style="float: right; padding: 0 100px;">Help <i class="fa fa-question fa-lg"></i></a><br>
        
        <h5>Basic Information</h5>
        <form active="settings.php" method="POST">
        <p><label>Employee #:</label> <input type="text" id="emp_num" name="emp_num" value="<?php echo sprintf("%04d", $user['emp_num']);?>" disabled> </p>
        <p><label>First Name:</label> <input type="text" id="first_name" name="first_name" maxlength="25" value="<?php echo $user['first_name']; ?>" disabled></p>
        <p><label>Last Name:</label> <input type="text" id="last_name" name="last_name" maxlength="25" value="<?php echo $user['last_name']; ?>" disabled></p>
        <p><label>Email:</label> <input type="email" id="email" name="email" maxlength="50" value="<?php echo $user['email']; ?>" disabled></p>
        <p><label>Phone:</label> <input type="text" id="phone" name="phone" minlength="12" maxlength="12" value="<?php echo $user['user_phone']; ?>" disabled></p>
        <input type="submit" class="btn btn-success" id="save_basic" name="save_basic" style="display: none;" value="Save">
        </form>
        <p><button type="button" class="btn btn-primary" id="edit_basic">Edit</button></p>


        <hr>
        <h5>Login Information</h5>
        <form active="settings.php" method="POST" id="login_form">
        <p><label>Username:</label> <input type="text" id="username" name="username" maxlength="25" value="<?php echo $user['username']; ?>" disabled></p>
        <?php 
            if (in_array("Username is already in use", $errorArray)){ 
                echo '<span style="color: red;">Username is already in use. Please use a unique username.</span><br>';
            }
        ?>
        </form>
        <p><label>Password:</label> *************  
            <a href="reset_password.php">
                <button class="btn btn-danger">Reset Password</button></p>
            </a>
        
   		
		<input type="submit" form="login_form" class="btn btn-success" id="save_login" name="save_login" style="display: none;" value="Save">
        <p><button type="button" class="btn btn-primary" id="edit_login">Edit</button></p>


        <hr>
        <h5>Permissions</h5>
        <p><input type="checkbox" checked disabled> Basic</p>
        <p><input type="checkbox" id="is_tech" name="is_tech" disabled> Service Technician</p>
        <p><input type="checkbox" id="is_admin" name="is_admin" disabled> Admin</p>




        <hr>
    </div>
</div>
</body>
<script>
      $(document).ready(function(){

        $('#phone').mask("999-999-9999");

        $('#edit_basic').on('click', function() {

            //$("#emp_num").prop('disabled', false);
            //$("#first_name").prop('disabled', false);
            //$("#last_name").prop('disabled', false);
            $("#email").prop('disabled', false);
            $("#phone").prop('disabled', false);
            $("#edit_basic").hide();
            $("#save_basic").show();

        });

        $('#edit_login').on('click', function() {

            $("#username").prop('disabled', false);
            $("#edit_login").hide();
            $("#save_login").show();

        });

      });
</script>
</html>