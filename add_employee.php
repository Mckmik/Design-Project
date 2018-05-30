<?php 
    include("includes/header.php");
    include("includes/form_handlers/add_employee_handler.php");

    if(isset($saved)) {
        echo '
        <script>

        $(document).ready(function() {
            $("#first").hide();
            $("#second").show();
        });

        </script>

        ';
    }
?>
    <div class="main_column column">
        <div id="first">
            <center><h1>Add New Employee</h1></center>
            <a href="user_manual.pdf#page=11" target="_blank" style="float: right; padding: 0 100px;">Help <i class="fa fa-question fa-lg"></i></a><br>
            <br>
            <center> 
            <?php 
            if (in_array("Employee number is already in use", $errorArray)){ 
                echo '<span style="color: red;">Employee number is already in use. Please use a unique number.</span><br>';
            }
            ?>
            <?php 
            if (in_array("Username is already in use", $errorArray)){ 
                echo '<span style="color: red;">Username is already in use. Please use a unique username.</span><br>';
            }
            ?>
            </center>
            <form action="add_employee.php" method="POST">
                <div class="add-emp-grid">
                    <div>Employee #</div>
                    <div><input type="text" id="emp_num" name="emp_num" maxlength="4" required></div>

                    <div>First Name</div>
                    <div><input type="text" name="first_name" maxlength="25" required></div>
                    <div>Last Name</div>
                    <div><input type="text" name="last_name" maxlength="25" required></div>
                    <div>Username</div>
                    <div><input type="text" name="emp_username" maxlength="25" required></div>

                    <div>Email</div>
                    <div><input type="email" name="email" maxlength="50" required></div>
                    <div>Phone</div>
                    <div><input type="text" id="phone" name="phone" minlength="12" maxlength="12" name="cust_phone" required></div>
                    <div>Permissions</div>
                    <div><input type="checkbox" name="is_tech"> Service Tech</div>
                    <div></div>
                    <div><input type="checkbox" name="is_admin"> Admin</div>
                    <div></div>
                </div>
                <br>
                <br>
                <center><input type="submit" class="btn btn-success" name="save_employee" value="Add Employee"></center>
                
            </form>
        </div>
        <div id="second">
            <h1>Employee added!</h1>

        </div>
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

    $("#emp_num").mask("9999");
    $("#phone").mask("999-999-9999");

</script>
</html>