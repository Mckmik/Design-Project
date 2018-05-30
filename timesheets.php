<?php 
    include("includes/header.php");
?>
    <div class="main_column column">
    <h1>TIMESHEETS</h1>




    <?php

    if ($user['is_admin'] == "yes"){
    	echo '
			<hr>
			
			<div class ="admin-options">
			<h3>Admin Options</h3>
			<a href="user_manual.pdf#page=31" target="_blank" style="float: right; padding: 0 100px;">Help <i class="fa fa-question fa-lg"></i></a><br>
		    <a href="jobs.php">
		        <button type="button" class="btn btn-secondary">
		            <i class="fa fa-wrench fa-lg"></i> Jobs
		        </button>
		    </a>
		    <a href="all_timesheets.php">
		        <button type="button" class="btn btn-secondary">
		            <i class="fa fa-book fa-lg"></i> All Timesheets
		        </button>
		    </a>
			<a href="review_timesheets.php">
		        <button type="button" class="btn btn-success">
		            <i class="fa fa-check fa-lg"></i> Review Timesheets
		        </button>
			</a>
			</div>
    	';
    }
    ?>

    <hr>

    <h3>My Timesheets</h3>
	<a href="user_manual.pdf#page=56" target="_blank" style="float: right; padding: 0 100px;">Help <i class="fa fa-question fa-lg"></i></a><br><br>
    <?php

    $ts = new Timesheet($con);
    $ts->generateTimesheets();
    $ts->loadOpenTimesheets($user['user_id']);

    ?>
    <a href="past_timesheets.php">
    	<button type="button" class="btn btn-secondary">
    		Past Timesheets
    	</button>
    </a>
    <hr>

    </div>
</div>
</body>
</html>