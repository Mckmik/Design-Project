<?php 
    include("includes/header.php");


    $timesheet = new Timesheet($con);

    if (isset($_GET['start'])){
        $startDate = $_GET['start'];
    }


?>
    <div class="main_column column">
    <h1>ALL TIMESHEETS</h1>
    <a href="user_manual.pdf#page=32" target="_blank" style="float: right; padding: 0 100px;">Help <i class="fa fa-question fa-lg"></i></a><br>
    <h4>Pay Period <?php echo date("m/d/Y", $startDate). ' - ' .date("m/d/Y", ($startDate + (60*60*24*13)));?></h4>

    <div class="work_order_area">

        <?php 
            if (isset($_GET['start'])){
                $timesheet->loadAllTimesheets($startDate);
            }
        ?>

    </div>
        <hr>
        <a href="all_timesheets.php">
            <button type="button" class="btn btn-secondary">
            <i class="fa fa-arrow-circle-o-left fa-lg"></i> Return to All Timesheets
            </button>
        </a>
    <hr>

    </div>
</div>
</body>
</html>