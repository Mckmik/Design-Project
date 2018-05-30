<?php 
    include("includes/header.php");
    $job = new Job($con);
?>
    <div class="main_column column">
    <h1>REPORTS - JOB COSTING</h1>
    <a href="user_manual.pdf#page=37" target="_blank" style="float: right; padding: 0 100px;">Help <i class="fa fa-question fa-lg"></i></a><br>
    <h3>Job Lookup</h3>
    <form action="reports_jobcost.php" method="POST">
        <input type="text" name="search" placeholder="Type a customer name" maxlength="25" required><br>
        <input type="submit" class="btn btn-primary" name="sub_search" value="Search">
    </form>
    <?php 
    if (isset($_POST['sub_search'])){
        $search = $_POST['search'];
        $job->searchJobs($search);
    }
    ?>
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