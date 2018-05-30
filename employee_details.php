<?php 
    include("includes/header.php");
    include("includes/form_handlers/employee_details_handler.php");
?>
    <div class="main_column column">
        <h2><?php echo $firstName . ' ' . $lastName; ?> - #<?php echo sprintf("%04d", $empNum);?></h2>


        <hr>
        <a href="user_manual.pdf#page=11" target="_blank" style="float: right; padding: 0 100px;">Help <i class="fa fa-question fa-lg"></i></a><br>
        <h5>Status</h5>
        <p><?php echo $status; ?> </p>
        <p>
            <form active="employee_details.php?emp_id=<?php echo $empId; ?>" method="POST">
            <input type="submit" class="btn btn-danger" style="display: none;" id="deactivate" name="deactivate" value="Deactivate">
            <input type="submit" class="btn btn-success" style="display: none;" id="activate" name="activate" value="Activate">
            </form>
        </p>


        <hr>
        <h5>Basic Information</h5>
        <form active="employee_details.php?emp_id=<?php echo $empId; ?>" method="POST">
        <p><label>Employee #:</label> <input type="text" id="emp_num" name="emp_num" maxlength="4" value="<?php echo sprintf("%04d", $empNum);?>" disabled> </p>
        <?php 
            if (in_array("Employee number is already in use", $errorArray)){ 
                echo '<span style="color: red;">Employee number is already in use. Please use a unique number.</span><br>';
            }
        ?>
        <p><label>First Name:</label> <input type="text" id="first_name" name="first_name" maxlength="25" value="<?php echo $firstName; ?>" disabled></p>
        <p><label>Last Name:</label> <input type="text" id="last_name" name="last_name" maxlength="25" value="<?php echo $lastName; ?>" disabled></p>
        <p><label>Email:</label> <input type="email" id="email" name="email" maxlength="50" value="<?php echo $email; ?>" disabled></p>
        <p><label>Phone:</label> <input type="text" id="phone" name="phone" minlength="12" maxlength="12" value="<?php echo $phone; ?>" disabled></p>
        <input type="submit" class="btn btn-success" id="save_basic" name="save_basic" style="display: none;" value="Save">
        </form>
        <p><button type="button" class="btn btn-primary" id="edit_basic">Edit</button></p>


        <hr>
        <h5>Login Information</h5>
        <form active="employee_details.php?emp_id=<?php echo $empId; ?>" method="POST" id="login_form">
        <p><label>Username:</label> <input type="text" id="username" name="username" maxlength="25" value="<?php echo $empUsername; ?>" disabled></p>
        <?php 
            if (in_array("Username is already in use", $errorArray)){ 
                echo '<span style="color: red;">Username is already in use. Please use a unique username.</span><br>';
            }
        ?>
        </form>
        <p><label>Password:</label> *************  
        	<a href="reset_password.php?emp_id=<?php echo $empId;?>">
        		<button class="btn btn-danger">Reset Password</button></p>
        	</a>
        
   		
		<input type="submit" form="login_form" class="btn btn-success" id="save_login" name="save_login" style="display: none;" value="Save">
        <p><button type="button" class="btn btn-primary" id="edit_login">Edit</button></p>


        <hr>
        <h5>Permissions</h5>
        <form active="employee_details.php?emp_id=<?php echo $empId; ?>" method="POST">
        <p><input type="checkbox" checked disabled> Basic</p>
        <p><input type="checkbox" id="is_tech" name="is_tech" disabled> Service Technician</p>
        <p><input type="checkbox" id="is_admin" name="is_admin" disabled> Admin</p>
        <input type="submit" class="btn btn-success" id="save_perms" name="save_perms" style="display: none;" value="Save">
        </form>
        <p><button type="button" class="btn btn-primary" id="edit_perms">Edit</button></p>




        <hr>
        <a href="employees.php">
            <button type="button" class="btn btn-secondary">
            <i class="fa fa-arrow-circle-o-left fa-lg"></i> Return to Employees
            </button>
        </a>
        <hr>
    </div>
</div>
</body>
<script>
      $(document).ready(function(){

        $("#emp_num").mask("9999");
        $('#phone').mask("999-999-9999");

        $('#edit_basic').on('click', function() {

            $("#emp_num").prop('disabled', false);
            $("#first_name").prop('disabled', false);
            $("#last_name").prop('disabled', false);
            $("#email").prop('disabled', false);
            $("#phone").prop('disabled', false);
            $("#edit_basic").hide();
            $("#save_basic").show();

        });

        $('#edit_perms').on('click', function() {

            $("#is_tech").prop('disabled', false);
            $("#is_admin").prop('disabled', false);
            $("#edit_perms").hide();
            $("#save_perms").show();

        });

        $('#edit_login').on('click', function() {

            $("#username").prop('disabled', false);
            $("#edit_login").hide();
            $("#save_login").show();

        });

      });
</script>

</html>