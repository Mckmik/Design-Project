<?php
    $job = new Job($con);
    if (isset($_GET['job_id'])){
        $jobId = $_GET['job_id'];

        $query = mysqli_query($con, "SELECT * 
        FROM jobs 
        JOIN customers 
        ON jobs.cust_id=customers.cust_id 
        WHERE job_id='$jobId'");

        if (mysqli_num_rows($query)>0){
        
            $row = mysqli_fetch_array($query);

            $custId = $row['cust_id'];
            $lastName = $row['last_name'];
            $firstName = $row['first_name'];
            $custName = $lastName . ", " . $firstName;
            $company = $row['company_name'];
            $jobName = $row['job_name'];
            if ($company == ""){
                $company = $custName;
            }
            $start = strtotime($row['start_date']);
            $start = date("m/d/Y", $start);
            $complete = $row['complete_date'];
            $compDisplay = "";
            if ($complete == "0000-00-00"){
                $status = "Open";
            }else{
                $status = "Closed";
                $complete = strtotime($complete);
                $compDisplay = date("m/d/Y", $complete);
            }
            $quotedHours = $row['quoted_hours'];
            $actualHours = $job->getHours($jobId);
            $overShort = $quotedHours - $actualHours;
            //$overShort = $actualHours - $quotedHours; //testing overage value
            if ($overShort < 0){
                $overShort = '<span style="color: red;"><b>'.number_format($overShort,2).'</b></span>';
            } else {
                $overShort = '<span style="color: green;"><b>'.number_format($overShort,2).'</b></span>';
            }

        } else {
            echo '
            <script>
            $(document).ready(function() {
                $("#first").hide();
                $("#second").show();
            });
            </script>
            ';
        }


    }
?>