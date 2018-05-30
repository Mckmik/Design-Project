<?php 
    include("includes/header.php");
    $emp = new Employee($con);
?>
    <div class="main_column column">
    <h1>EMPLOYEES</h1>
    <a href="user_manual.pdf#page=11" target="_blank" style="float: right; padding: 0 100px;">Help <i class="fa fa-question fa-lg"></i></a><br>
    <a href="all_employees.php?page=1">
        <button type="button" class="btn btn-secondary">
            All Employees
        </button>
    </a>
    <a href="add_employee.php">
        <button type="button" class="btn btn-success">
            <i class="fa fa-plus fa-lg"></i> New Employee
        </button>
    </a>
    <br>
    <br>
    <h3>Employee Lookup</h3>
    <form action="employees.php" method="POST">
        <input type="text" name="search" placeholder="search employees" maxlength="25" required><br>
        <input type="submit" class="btn btn-primary" name="sub_search" value="Search">
    </form>
    <?php 
    if (isset($_POST['sub_search'])){
        $search = $_POST['search'];
        $emp->searchEmployees($search);
    }
    ?>
    <hr>
    </div>
</div>
</body>
</html>