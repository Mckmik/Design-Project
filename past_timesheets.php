<?php 
    include("includes/header.php");
?>
    <div class="main_column column">
    <h1>PAST TIMESHEETS</h1>
    <hr>

    <h3>Past Timesheets</h3>
    <a href="user_manual.pdf#page=50" target="_blank" style="float: right; padding: 0 100px;">Help <i class="fa fa-question fa-lg"></i></a><br><br>
    <?php

    $ts = new Timesheet($con);
    $ts->loadCompleteTimesheets($user['user_id']);

    ?>
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