<?php 
    include("includes/header.php");
    $cust = new Customer($con);

    if (isset($_POST['create_csv'])){
        $query = mysqli_query($con, "SELECT * FROM csv_dates WHERE entity='customer'");
        $row = mysqli_fetch_array($query);
        $lastCreate = strtotime($row['last_create']);
        $lastTime = strtotime($row['time']);
        $cust->createCSV($lastCreate, $lastTime);
    }

?>
    <div class="main_column column">
    <h1>CUSTOMERS</h1>
    <a href="user_manual.pdf#page=16" target="_blank" style="float: right; padding: 0 100px;">Help <i class="fa fa-question fa-lg"></i></a><br>
    <a href="all_customers.php?page=1">
        <button type="button" class="btn btn-secondary">
            All Customers
        </button>
    </a>
    <a href="add_customer.php?from_cust=true">
        <button type="button" class="btn btn-success">
            <i class="fa fa-plus fa-lg"></i> New Customer
        </button>
    </a>

    <br>
    <hr>

    <h3>Customer Lookup</h3>
    <a href="user_manual.pdf#page=21" target="_blank" style="float: right; padding: 0 100px;">Help <i class="fa fa-question fa-lg"></i></a><br>
    <form action="customers.php" method="POST">
        <input type="text" name="search" placeholder="search customers" maxlength="25" required><br>
        <input type="submit" class="btn btn-primary" name="sub_search" value="Search">
    </form>
    <?php 
    if (isset($_POST['sub_search'])){
        $search = $_POST['search'];
        $cust->searchCustomers($search);
    }
    ?>
    <hr>
    <a href="user_manual.pdf#page=23" target="_blank" style="float: right; padding: 0 100px;">Help <i class="fa fa-question fa-lg"></i></a><br>
    <form action="customers.php" method="POST">
        <input type="submit" class="btn btn-primary" name="create_csv" value="Create Customer CSV">
    </form>
    
    <?php 
        if (isset($_POST['create_csv'])){
            $query = mysqli_query($con, "SELECT * FROM csv_dates WHERE entity='customer'");
            $row = mysqli_fetch_array($query);
            $lastCreate = $row['last_create'];
            $lastTime = $row['time'];
            $ftime = date_create($lastTime);
            $ftime = date_format($ftime, "His");

            echo '
            <a href="assets/csvs/customers'.$lastCreate.$ftime.'.csv" download>
                <button type="button" class="btn btn-success">Download CSV</button>
            </a>';
        }
    ?>
    <h3> Past CSVs <button type="button" class="btn btn-secondary" id="show_past">Show/Hide</button></h3>
    
    <div id="past_rep" style="display: none;">
    <?php
    $files = array_slice(scandir('assets/csvs'), 2);
    $str = "";
    foreach ($files as $f){
        $str = '
        <a href="assets/csvs/'.$f.'" download style="color: blue;">
            '.$f.'
        </a><br><br>'.$str;
    }
    echo $str;
    ?>
    </div>
    <hr>
    </div>

</div>
</body>
<script>
    $('#show_past').on('click', function() {
        $('#past_rep').toggle();
    });
</script>
</html>