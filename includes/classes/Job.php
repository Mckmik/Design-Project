<?php
class Job {
	private $con;


	public function __construct($con){
		$this->con = $con;

    }

    //adds new job for timesheets and job costing
    public function addJob($custId, $jobName, $startDate, $quotedHours){

    	$jobName = strip_tags($jobName);
    	$jobName = mysqli_real_escape_string($this->con, $jobName);

    	$quotedHours = mysqli_real_escape_string($this->con, $quotedHours);

    	$query = mysqli_query($this->con, "INSERT INTO jobs VALUES('','$custId', '$jobName', '$quotedHours', '$startDate', '0000-00-00')");
    }

    //loads open jobs
    public function loadOpenJobs(){

    	$str ="";

    	$query = mysqli_query($this->con, "SELECT * 
    		FROM jobs 
    		JOIN customers 
    		ON jobs.cust_id=customers.cust_id 
    		WHERE complete_date='0000-00-00' 
    		ORDER BY customers.cust_id, job_id");

    	while ($row = mysqli_fetch_array($query)) {

            $jobId = $row['job_id'];
    		$company = $row['company_name'];
    		$firstName = $row['first_name'];
    		$lastName = $row['last_name'];
    		if ($company == ""){
    			$display = $lastName .', '. $firstName;
    		}else{
    			$display = $company;
    		}

    		$jobName = $row['job_name'];
    		$quotedHours = $row['quoted_hours'];
    		$startDate = strtotime($row['start_date']);
    		$startDate = date("m/d/Y",$startDate);

            $str .= '
            <a href="job_details.php?job_id='.$jobId.'">
            <div class="jobs-grid">
                <div>'.$display.'</div>
                <div>'.$jobName.'</div>
				<div>'.$startDate.'</div>
                <div>'.$quotedHours.'</div>
            </div>
            </a>
            <br>
            ';

    	}
    	echo $str;
    }

    //loads closed jobs
    public function loadClosedJobs(){

        $str ="";

        $query = mysqli_query($this->con, "SELECT * 
            FROM jobs 
            JOIN customers 
            ON jobs.cust_id=customers.cust_id 
            WHERE complete_date<>'0000-00-00' 
            ORDER BY customers.cust_id, job_id");

        while ($row = mysqli_fetch_array($query)) {

            $jobId = $row['job_id'];
            $company = $row['company_name'];
            $firstName = $row['first_name'];
            $lastName = $row['last_name'];
            if ($company == ""){
                $display = $lastName .', '. $firstName;
            }else{
                $display = $company;
            }

            $jobName = $row['job_name'];
            $startDate = strtotime($row['start_date']);
            $startDate = date("m/d/Y",$startDate);
            $endDate = strtotime($row['complete_date']);
            $endDate = date("m/d/Y",$endDate);

            $str .= '
            <a href="job_details.php?job_id='.$jobId.'">
            <div class="jobs-grid">
                <div>'.$display.'</div>
                <div>'.$jobName.'</div>
                <div>'.$startDate.'</div>
                <div>'.$endDate.'</div>
            </div>
            </a>
            <br>
            ';

        }
        echo $str;
    }

    public function getFirstTimesheet($jobId){

        $query = mysqli_query($this->con, "SELECT * FROM timesheet_lines WHERE job_id='$jobId' ORDER BY date_worked");

        if (mysqli_num_rows($query)>0){

            while ($row = mysqli_fetch_array($query)){
                $date = $row['date_worked'];
                break;
            }
            return $date;

        } else {

            return "none";
        }
    }
    public function getLastTimesheet($jobId){

        $query = mysqli_query($this->con, "SELECT * FROM timesheet_lines WHERE job_id='$jobId' ORDER BY date_worked DESC");

        if (mysqli_num_rows($query)>0){

            while ($row = mysqli_fetch_array($query)){
                $date = $row['date_worked'];
                break;
            }
            return $date;

        } else {

            return "none";
        }
    }
    //loads jobs for dropdown selection
    public function dropdownLoadJobs($start, $end){

        $str = ""; 
        $dataQuery = mysqli_query($this->con, "SELECT * FROM jobs 
            JOIN customers 
            ON jobs.cust_id=customers.cust_id
            ORDER BY start_date");
        
        if(mysqli_num_rows($dataQuery) >0){

            while ($row = mysqli_fetch_array($dataQuery)) {
				$id = $row['job_id'];
                $jobName = $row['job_name'];
                $firstName = $row['first_name'];
				$lastName = $row['last_name'];
                $company = $row['company_name'];
                if ($company == ""){
                    $loadName = $lastName . ", " . $firstName;
                } else {
                    $loadName = $company;
                }
                $jobStart = strtotime($row['start_date']);
                $jobEnd = $row['complete_date'];
                if($jobEnd == "0000-00-00"){
                    $complete = false;
                } else {
                    $complete = true;
                    $jobEnd = strtotime($jobEnd);
                }
                if ($complete){
                    if(($jobStart <= $end) && ($jobEnd >= $start)){

                        $str .= '<option value="' . $id . ',0">'.$loadName.': '.$jobName.'</option>';
                    }

                } else {
                    if ($jobStart <= $end){
                        $str .= '<option value="' . $id . ',0">'.$loadName.': '.$jobName.'</option>';
                    }
                }                
                
                
            }
            echo $str;
        }
    }
    //loads jobs to reports_jobcost.php
    public function searchJobs($search){

        $str = '

        <h3>Results for "'.$search.'"</h3>
        <div class="work_order_area">';

        $count = 0;
    	$query = mysqli_query($this->con, "SELECT * 
    		FROM jobs 
    		JOIN customers 
    		ON jobs.cust_id=customers.cust_id 
    		ORDER BY start_date DESC");
        while ($row = mysqli_fetch_array($query)) {

            $custId = $row['cust_id'];
            $lastName = $row['last_name'];
            $firstName = $row['first_name'];
            $custName = $lastName . ", " . $firstName;
            $company = $row['company_name'];
            $jobName = $row['job_name'];
            if ($company == ""){
                $company = $custName;
            }
            $complete = $row['complete_date'];
            if ($complete == "0000-00-00"){
                $status = "Open";
            }else{
                $status = "Closed";
            }
            $jobId = $row['job_id'];
            

            if (strpos(strtoupper($company), strtoupper($search)) !== false){
                $str .= '
                <a href="jobcost_detail.php?job_id='.$jobId.'">
                <div class="work_order">
                    <b><p>'.$company.'</p><p>'.$jobName.'</p><p>'.$status.'</p></b>
                </div>
                </a>
                <br>
                ';
                $count++;
            }
            
        }
        echo $str.'</div>';
        if ($count < 1 ){
            echo "<p><b>No results returned.</b></p>";
        }
    }

    public function getHours($jobId){

        $total = 0.00; 
    
        $query = mysqli_query($this->con, "SELECT * FROM timesheet_lines WHERE job_id='$jobId'");
    
        if (mysqli_num_rows($query) >0){
            while ($row = mysqli_fetch_array($query)){
              $arrival = strtotime($row['arrival']);
              $departure = strtotime($row['departure']);
              $total += ($departure - $arrival)/(60*60);
            }
        }
        return number_format($total, 2);
      }

}

?>