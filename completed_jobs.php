<?php 
    include("includes/header.php");
    include("includes/form_handlers/customer_details_handler.php");
?>
    <div class="main_column column">
   	<h3>Completed Jobs</h3>
       <a href="user_manual.pdf#page=27" target="_blank" style="float: right; padding: 0 100px;">Help <i class="fa fa-question fa-lg"></i></a><br><br>
    <div class="jobs-area">

        <div class="jobs-grid-head">
            <div>Customer</div>
            <div>Job</div>
            <div>Start Date</div>
            <div>End Date</div>
        </div>
        <br>
         <?php
        $job = new Job($con);
        $job->loadClosedJobs();
        ?>
    </div>

    	
    <hr>
    <a href="jobs.php">
    <button type="button" class="btn btn-secondary">
        <i class="fa fa-arrow-circle-o-left fa-lg"></i> Return to Jobs
    </button>
    </a>
    <hr>
	</div>
</div>
</body>
</html>