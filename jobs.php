<?php 
    include("includes/header.php");
?>
    <div class="main_column column">
    <h1>JOBS</h1>
    <a href="user_manual.pdf#page=24" target="_blank" style="float: right; padding: 0 100px;">Help <i class="fa fa-question fa-lg"></i></a><br>
    <a href="completed_jobs.php">
        <button type="button" class="btn btn-secondary">
            <i class="fa fa-wrench fa-lg"></i> Completed Jobs
        </button>
    </a>
    <a href="add_job.php">
        <button type="button" class="btn btn-success">
            <i class="fa fa-plus fa-lg"></i> New Job
        </button>
    </a>
    <h3>Open Jobs</h3>
    <div class="jobs-area">

        <div class="jobs-grid-head">
            <div>Customer</div>
            <div>Job</div>
            <div>Start Date</div>
            <div>Quoted Hours</div>
        </div>
        <br>
         <?php
        $job = new Job($con);
        $job->loadOpenJobs();
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