<?php 
    include("includes/header.php");
    include("includes/form_handlers/jobcost_handler.php");

?>
    <div class="main_column column">

    <h1>REPORTS - JOB COSTING</h1>
    <a href="user_manual.pdf#page=37" target="_blank" style="float: right; padding: 0 100px;">Help <i class="fa fa-question fa-lg"></i></a><br>
    <div id="first">
        <h3>Report for: <?php echo $company . " - " . $jobName;?></h3>
        <label>Status:</label> <?php echo $status;?><br>
        <label>Start Date:</label> <?php echo $start;?><br>
        <label>Complete Date:</label> <?php echo $compDisplay;?><br> 
        <label>Quoted Hours:</label> <?php echo $quotedHours;?><br>
        <label>Actual Hours:</label> <?php echo $actualHours;?><br>
        <label>Over/Short:</label> <?php echo $overShort;?><br><br>
    </div>
    <div id="second">
        <h3>Sorry...</h3>
        The selected job does not exist.
    </div>

    <hr>
    <a href="reports_jobcost.php">
		    <button type="button" class="btn btn-secondary">
		    <i class="fa fa-arrow-circle-o-left fa-lg"></i> Return to Reports - Job Costing
		    </button>
		</a>
    <hr>
    </div>
</div>
</body>
</html>