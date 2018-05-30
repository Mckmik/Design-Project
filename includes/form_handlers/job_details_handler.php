<?php
$job = new Job($con);

if(isset($_GET['job_id'])){

	$jobId = $_GET['job_id'];

    $idExists = false;
    $existsQuery = mysqli_query($con, "SELECT job_id FROM jobs");
    while ($existsRow = mysqli_fetch_array($existsQuery)){
        if ($jobId == $existsRow['job_id']){
            $idExists = true;
        }
    }

    if($idExists == false){
        header("location: job_does_not_exists.php");
    }

    $query = mysqli_query($con, "SELECT * 
            FROM jobs 
            JOIN customers 
            ON jobs.cust_id=customers.cust_id 
            WHERE job_id='$jobId'");

    $row = mysqli_fetch_array($query);

    $custId = $row['cust_id'];
    $company = $row['company_name'];
    $custFirst = $row['first_name'];
    $custLast = $row['last_name'];

    if ($company == ""){
    	$display = $custLast . ', '. $custFirst;
    }else{
    	$display = $company;
    }

    $jobName = $row['job_name'];
    $quotedHours = $row['quoted_hours'];
    $startDate = strtotime($row['start_date']);
    $endDate = strtotime($row['complete_date']);

    if ($endDate == strtotime("0000-00-00")){
        $status = "Open";
    } else {
        $status = "Complete";
    }

    $firstTime = $job->getFirstTimesheet($jobId);
    if ($firstTime != "none"){
        $firstTime = strtotime($firstTime);
        $firstTime = date("m/d/Y", $firstTime);
    }
    $lastTime = $job->getLastTimesheet($jobId);
    if ($lastTime != "none"){
        $lastTime = strtotime($lastTime);
        $lastTime = date("m/d/Y", $lastTime); 
    }


}

if (isset($_POST['save_job'])){

    $custId = $_POST['customer'];
    $jobName = $_POST['job_name'];
    $jobName = strip_tags($jobName);
    $jobName = mysqli_real_escape_string($con, $jobName);
    $startDate = date_create($_POST['start_date']);
    $startDate = date_format($startDate, "Y-m-d");
    if ($status == "Complete"){
        $endDate = date_create($_POST['complete_date']);
        $endDate = date_format($endDate, "Y-m-d");
        $endDateQ = ", complete_date='$endDate'";
    } else {
        $endDateQ = "";
    }

    $quotedHours = $_POST['quoted_hours'];
    $quotedHours = mysqli_real_escape_string($con, $quotedHours);

    $query = mysqli_query($con, "UPDATE jobs 
    SET cust_id='$custId', 
    job_name='$jobName',
    quoted_hours='$quotedHours',
    start_date='$startDate'
    ".$endDateQ."
    WHERE job_id='$jobId'");

    header("location: job_details.php?job_id=".$jobId);
}

if (isset($_POST['complete_job'])){

    $completeDate = date_create($_POST['complete_date']);
    $completeDate = date_format($completeDate, "Y-m-d");

    $query = mysqli_query($con, "UPDATE jobs 
    SET complete_date='$completeDate'
    WHERE job_id='$jobId'");

    header("location: job_details.php?job_id=".$jobId);
}

?>