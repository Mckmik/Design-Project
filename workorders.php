<?php 
    include("includes/header.php");
?>

    <div class="main_column column">
    <h1>WORK ORDERS</h1>
    <hr>
    <a href="user_manual.pdf#page=46" target="_blank" style="float: right; padding: 0 100px;">Help <i class="fa fa-question fa-lg"></i></a><br>
    <div class="admin-options">
        <a href="completed_orders.php">
        <button type="button" class="btn btn-secondary">
            Completed Orders
        </button>
    </a>
    <a href="add_customer.php">
        <button type="button" class="btn btn-success">
            <i class="fa fa-plus fa-lg"></i> New Customer
        </button>
    </a>

    <a href="add_service.php">
        <button type="button" class="btn btn-success">
            <i class="fa fa-pencil-square-o fa-lg"></i> New Service Request
        </button>
    </a>
    </div>
    <hr> 
    <h3>Open Orders</h3>
    
    <div class="work_order_head">
    <b><p>Service Date</p><p>Customer</p><p>Technician</p></b>
    </div>
    <br>

    <?php 
        $workorder = new WorkOrder($con);
        $workorder->loadOpenWorkOrders($userLoggedIn);
    ?>
    <hr>
    </div>

</div>
</body>
</html>