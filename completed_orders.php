<?php 
    include("includes/header.php");
?>

    <div class="main_column column">
    <h1>COMPLETED ORDERS</h1>
    <a href="user_manual.pdf#page=55" target="_blank" style="float: right; padding: 0 100px;">Help <i class="fa fa-question fa-lg"></i></a>
    <br><br>
    <div class="work_order_head">
    <b><p>Service Date</p><p>Customer</p><p>Technician</p></b>
    </div>
    <br>

    <?php 
        $workorder = new WorkOrder($con);
        $workorder->loadClosedWorkOrders($userLoggedIn);
    ?>
    <hr>

    <a href="workorders.php">
    <button type="button" class="btn btn-secondary">
    <i class="fa fa-arrow-circle-o-left fa-lg"></i> Return to Work Orders
    </button>
    </a>
    <hr>
    </div>

</div>
</body>
</html>