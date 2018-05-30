<?php 
    include("includes/header.php");
    $timesheet = new Timesheet($con);
?>
    <div class="main_column column">
    <h1>REPORTS - PAYROLL</h1>
    <a href="user_manual.pdf#page=35" target="_blank" style="float: right; padding: 0 100px;">Help <i class="fa fa-question fa-lg"></i></a><br>
    <h3>Pay Periods</h3>
    <?php $timesheet->loadTimesheetContainersReports();?>

    <hr>
    <a href="reports.php">
		    <button type="button" class="btn btn-secondary">
		    <i class="fa fa-arrow-circle-o-left fa-lg"></i> Return to Reports
		    </button>
		</a>
    <hr>
    </div>
</div>
</body>
</html>