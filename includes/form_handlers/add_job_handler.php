<?php

if (isset($_POST['save_job'])){
	$customer = $_POST['customer'];
	$jobName = $_POST['job_name'];
	$startDate = date_create($_POST['start_date']);
    $startDate = date_format($startDate, "Y-m-d");
    $quotedHours = $_POST['quoted_hours'];


    $job = new Job($con);
    $job->addJob($customer, $jobName, $startDate, $quotedHours);

}

?>