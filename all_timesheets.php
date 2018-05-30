<?php 
    include("includes/header.php");
?>
    <div class="main_column column">
    <h1>ALL TIMESHEETS</h1>
    <a href="user_manual.pdf#page=32" target="_blank" style="float: right; padding: 0 100px;">Help <i class="fa fa-question fa-lg"></i></a><br>


    <hr>
    <h3>Pay Periods</h3>
        <div class="work_order_area">

        <?php 
        $ts = new Timesheet($con);
        $ts->loadTimesheetContainers();
        ?>
        </div>




        <hr>
        <a href="timesheets.php">
            <button type="button" class="btn btn-secondary">
            <i class="fa fa-arrow-circle-o-left fa-lg"></i> Return to Timesheets
            </button>
        </a>
    <hr>

    </div>
</div>
</body>
</html>