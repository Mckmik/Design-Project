<?php 
    include("includes/header.php");
    $timesheet = new Timesheet($con);

?>
    <div class="main_column column">
    <h1>REVIEW TIMESHEETS</h1>
    <a href="user_manual.pdf#page=33" target="_blank" style="float: right; padding: 0 100px;">Help <i class="fa fa-question fa-lg"></i></a><br>
    <hr>

    <div class="work_order_area">

        <?php 
            
            $timesheet->loadReviewTimesheets();
            
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
<script>
</script>
</html>