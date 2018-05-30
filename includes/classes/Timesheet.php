<?php 

class Timesheet {
  private $con;

  public function __construct($con){
	  $this->con = $con;
  }

  //generates current timesheets for all active employees
  public function generateTimesheets(){

    //Check db for active users
    $query = mysqli_query($this->con, "SELECT * FROM users WHERE is_deleted='no'");
    if (mysqli_num_rows($query)>0){

      while ($row = mysqli_fetch_array($query)){
        $empId = $row['user_id'];
        $hasCurrent = false;
        $count=0;

        //check db for existing timesheets
        $timesheetQuery = mysqli_query($this->con, "SELECT * FROM timesheets WHERE emp_id='$empId'");
        if (mysqli_num_rows($timesheetQuery)>0){
          while ($timesheetRow = mysqli_fetch_array($timesheetQuery)){
            $sheetDate = $timesheetRow['week_start'];
            if (strtotime($sheetDate) == strtotime($this->getCurrentTimesheetDate())){
              $hasCurrent = true;
            }
            $count++;
          }
        }
        $start = $this->getCurrentTimesheetDate();
        $end = date("Y-m-d", (strtotime($start) + (60*60*24*13)));
        $count++;

        //creates new current timesheet if user is not current
        if ($hasCurrent == false){
          $addQuery = mysqli_query($this->con, "INSERT INTO timesheets VALUES('$empId', '$count', '$start', '$end', '', 'no', 'no','0','no')");
        }
      }
    }
  }

  //gets the start date for current payperiod
  public function getCurrentTimesheetDate(){
    $currentDate = date_create();
    $startDate = date_create("2018-03-17");
    $diff = date_diff($currentDate, $startDate);
    $diff = $diff->format("%a");
    $daysToTimesheet = $diff % 14;
    $currentDate = strtotime(date_format($currentDate, "Y-m-d"));
    $newDate = date("Y-m-d", ($currentDate - ($daysToTimesheet * 60*60*24)));
    return $newDate;
  }

  //loads timesheets that are not complete
  public function loadOpenTimesheets($userId){
    $query = mysqli_query($this->con, "SELECT * FROM timesheets WHERE emp_id='$userId' AND is_complete='no' ORDER BY timesheet_id DESC");
    $str = '<div class="work_order_area">';
    if (mysqli_num_rows($query)){
      while ($row = mysqli_fetch_array($query)){
        $timesheetId = $row['timesheet_id'];
        $start = strtotime($row['week_start']);
        $end = strtotime($row['week_end']);
        $submitted = $row['is_submitted'];
        if ($submitted == "yes"){
          $status = "Pending Approval";
        } else {
          $status = "Not Submitted";
        }

        $str.='<a href="timesheet_details.php?timesheet_id='.$timesheetId.'">
          <div class="work_order"><b><p>Pay Period:</p><p>'.date("m/d/Y",$start).' - '.date("m/d/Y",$end).'</p><p>'. $status.'</p></b></div>
          </a><br>
        
        ';
      }
      echo $str.='</div>';
    }
  }
    //loads timesheets that are complete
  public function loadCompleteTimesheets($userId){
    $query = mysqli_query($this->con, "SELECT * FROM timesheets WHERE emp_id='$userId' AND is_complete='yes' ORDER BY timesheet_id DESC");
    $str = '<div class="work_order_area">';
    if (mysqli_num_rows($query)){
      while ($row = mysqli_fetch_array($query)){
        $timesheetId = $row['timesheet_id'];
        $start = strtotime($row['week_start']);
        $end = strtotime($row['week_end']);
        $submitted = $row['is_submitted'];
        $status = "Complete";

        $str.='<a href="timesheet_details.php?timesheet_id='.$timesheetId.'">
          <div class="work_order"><b><p>Pay Period:</p><p>'.date("m/d/Y",$start).' - '.date("m/d/Y",$end).'</p><p>'. $status.'</p></b></div>
          </a><br>
        
        ';
      }
      echo $str.='</div>';
    }
  }

  //Loads the timesheet lines for a specific timesheet
  public function loadTimesheetLines($empId,$timesheetId, $adminView, $strt){


    if ($adminView){
      $adminView = "&admin_view=true&emp_id=".$empId."&start=".$strt;
    }else{
      $adminView='';
    }

    $q = mysqli_query($this->con, "SELECT * FROM timesheets WHERE emp_id='$empId' AND timesheet_id='$timesheetId'");
    $r = mysqli_fetch_array($q);

    $start = strtotime($r['week_start']);
    $mid = $start + (60*60*24*7);

    $hasHeader = false;



    $str ='<hr><h4>WEEK 1</h4>
      <div class="timesheet-grid-head">
        <div class="timesheet-grid-item">Project</div>
        <div class="timesheet-grid-item">Date</div>
        <div class="timesheet-grid-item ln-curr">Hours</div>
        <div class="timesheet-grid-item"></div>
      </div>
      <br>';


    $query  = mysqli_query($this->con, "SELECT * FROM timesheet_lines WHERE emp_id='$empId' AND timesheet_id='$timesheetId' ORDER BY date_worked, arrival");

    if(mysqli_num_rows($query) >0){


        while($row = mysqli_fetch_array($query)){
            $lineNum = $row['tl_id'];
            $jobId = $row['job_id'];
            $orderId = $row['wo_id'];
            $date = strtotime($row['date_worked']);
            $arrival = strtotime($row['arrival']);
            $departure = strtotime($row['departure']);
            $hours = ($departure - $arrival)/(60*60);


            if ($jobId > 0){

              $dataQuery = mysqli_query($this->con, "SELECT * FROM jobs 
                  JOIN customers 
                  ON jobs.cust_id=customers.cust_id
                  WHERE job_id='$jobId'");
              
              if(mysqli_num_rows($dataQuery) >0){

                while ($row = mysqli_fetch_array($dataQuery)) {
                  $jobName = $row['job_name'];
                  $firstName = $row['first_name'];
                  $lastName = $row['last_name'];
                  $company = $row['company_name'];
                  if ($company == ""){
                      $loadName = $lastName . ", " . $firstName;
                  } else {
                      $loadName = $company;
                  }
                  $loadName.=": ".$jobName;
                }
              }
            } else {

              $dataQuery = mysqli_query($this->con, "SELECT * FROM work_orders 
                  JOIN customers 
                  ON work_orders.cust_id=customers.cust_id
                  WHERE wo_id='$orderId'");
              
              if(mysqli_num_rows($dataQuery) >0){

                while ($row = mysqli_fetch_array($dataQuery)) {
                  $firstName = $row['first_name'];
                  $lastName = $row['last_name'];
                  $company = $row['company_name'];

                  $servDate = strtotime($row['service_date']);
                  if ($company == ""){
                      $loadName = $lastName . ", " . $firstName;
                  } else {
                      $loadName = $company;
                  }
                  $loadName.=": SERVICE ". date("m/d/Y", $servDate);
                }
              }
            }




            if ($hasHeader == false){

              if($date >= $mid){
                $str.= '<hr><h4>WEEK 2</h4>
                      <div class="timesheet-grid-head">
                        <div class="timesheet-grid-item">Project</div>
                        <div class="timesheet-grid-item">Date</div>
                        <div class="timesheet-grid-item ln-curr">Hours</div>
                        <div class="timesheet-grid-item"></div>
                      </div>
                      <br>';
                $hasHeader = true;
              }

            }

            
            $str.= '
            <a href="timesheet_line.php?timesheet_id='.$timesheetId.'&line_num='.$lineNum.'&from_details=true'.$adminView.'">   
            <div class="timesheet-grid">
                <div class="timesheet-grid-item">'.$loadName.'</div>
                <div class="timesheet-grid-item">'.date("l, m/d/Y",$date).'</div>
                <div class="timesheet-grid-item ln-curr">'.number_format($hours,2).'</div>

                <div class="timesheet-grid-item ln-curr"></div>                
            </div>
            </a><br>'
            ;
        } //end while

        echo $str;
    } 
    else echo "<h4>No time added yet!</h4>";
  }

  public function addTimesheetLine($empId, $timesheetId, $lineNum, $jobId, $orderId, $date, $arrival, $departure){

    $query = mysqli_query($this->con, "INSERT INTO timesheet_lines VALUES('$empId', '$timesheetId', '$lineNum', '$jobId', '$orderId', '$date', '$arrival', '$departure')");

  }
    //Gets next order line number
  public function getNextLineNumber($empId, $timesheetId){

      $query  = mysqli_query($this->con, "SELECT * FROM timesheet_lines WHERE emp_id='$empId' AND timesheet_id='$timesheetId' ORDER BY tl_id");

      $count = 1;

      if(mysqli_num_rows($query) >0){
          while($row = mysqli_fetch_array($query)){
              $count++;
          }
      }
      return $count;
  }


  public function getHours($empId, $timesheetId){

    $total = 0.00; 

    $query = mysqli_query($this->con, "SELECT * FROM timesheet_lines WHERE emp_id='$empId' AND timesheet_id='$timesheetId'");

    if (mysqli_num_rows($query) >0){
        while ($row = mysqli_fetch_array($query)){
          $arrival = strtotime($row['arrival']);
          $departure = strtotime($row['departure']);
          $total += ($departure - $arrival)/(60*60);
        }
    }
    return number_format($total, 2);
  }

  public function loadTimesheetContainers(){

    $str = '';

    $currentDate = date_create();
    $currentDate = strtotime(date_format($currentDate, "Y-m-d"));
    $startDate = date_create("2018-03-17");
    $checkDate = strtotime(date_format($startDate, "Y-m-d"));

    while($checkDate <= $currentDate){

      $date = date("Y-m-d", $checkDate);
      $total = 0.00;

      $query = mysqli_query($this->con, "SELECT * FROM timesheet_lines
        JOIN timesheets
        ON timesheet_lines.emp_id=timesheets.emp_id
        AND timesheet_lines.timesheet_id=timesheets.timesheet_id
        WHERE timesheets.week_start='$date'");

      if (mysqli_num_rows($query)>0){
        while ($row = mysqli_fetch_array($query)){
          $arrival = strtotime($row['arrival']);
          $departure = strtotime($row['departure']);
          $total += ($departure - $arrival)/(60*60);
        }
      }

      $str= '
      <a href="payperiod.php?start='.$checkDate.'">
          <div class="work_order"><b><p>Pay Period:</p><p>'.date("m/d/Y", $checkDate).' - '.date("m/d/Y", ($checkDate + (60*60*24*13))) .'</p><p>Hours: '.number_format($total,2).'</p></b></div>
      </a><br>
        
        '.$str;

      $checkDate = $checkDate + (60*60*24*14);

    }
    echo $str;
  }
  
  public function loadTimesheetContainersReports(){

    $str = '';

    $currentDate = date_create();
    $currentDate = strtotime(date_format($currentDate, "Y-m-d"));
    $startDate = date_create("2018-03-17");
    $checkDate = strtotime(date_format($startDate, "Y-m-d"));

    while($checkDate <= $currentDate){

      $date = date("Y-m-d", $checkDate);
      $total = 0.00;

      $query = mysqli_query($this->con, "SELECT * FROM timesheet_lines
        JOIN timesheets
        ON timesheet_lines.emp_id=timesheets.emp_id
        AND timesheet_lines.timesheet_id=timesheets.timesheet_id
        WHERE timesheets.week_start='$date'");

      if (mysqli_num_rows($query)>0){
        while ($row = mysqli_fetch_array($query)){
          $arrival = strtotime($row['arrival']);
          $departure = strtotime($row['departure']);
          $total += ($departure - $arrival)/(60*60);
        }
      }

      $str= '
      <a href="payroll_detail.php?start='.$checkDate.'">
          <div class="work_order"><b><p>Pay Period:</p><p>'.date("m/d/Y", $checkDate).' - '.date("m/d/Y", ($checkDate + (60*60*24*13))) .'</p><p>Hours: '.number_format($total,2).'</p></b></div>
      </a><br>
        
        '.$str;

      $checkDate = $checkDate + (60*60*24*14);

    }
    echo $str;
  }

  public function loadAllTimesheets($start){

    $str = '';

    $start = date("Y-m-d", $start);

    $query = mysqli_query($this->con, "SELECT * FROM timesheets
      JOIN users
      ON timesheets.emp_id=users.user_id
      WHERE timesheets.week_start='$start'
      ORDER BY users.emp_num");

    if (mysqli_num_rows($query) > 0){
      while ($row = mysqli_fetch_array($query)){
        $timesheetId = $row['timesheet_id'];
        $empId = $row['user_id'];

        $empNum = sprintf("%04d",$row['emp_num']);

        $name = $row['last_name'] . ', ' . $row['first_name'];

        $complete = $row['is_complete'];

        $submitted = $row['is_submitted'];

        if ($complete == "yes"){
        $str.= '
          <a href="timesheet_details.php?timesheet_id='.$timesheetId.'&emp_id='.$empId.'&admin_view=true&start='.strtotime($start).'">
            <div class="work_order"><b><p>'. $empNum.'</p><p>'.$name.'</p><p><span style="color: green;">Complete</span></p></b></div>
          </a><br>
        
        ';
        } elseif ($submitted == "yes"){
        $str.= '
          <a href="timesheet_details.php?timesheet_id='.$timesheetId.'&emp_id='.$empId.'&admin_view=true&start='.strtotime($start).'">
            <div class="work_order"><b><p>'. $empNum.'</p><p>'.$name.'</p><p>Pending Approval</p></b></div>
          </a><br>
        
        ';
        } else {
        $str.= '
          <a href="timesheet_details.php?timesheet_id='.$timesheetId.'&emp_id='.$empId.'&admin_view=true&start='.strtotime($start).'">
            <div class="work_order"><b><p>'. $empNum.'</p><p>'.$name.'</p><p><span style="color: red;">Not Submitted</span></p></b></div>
          </a><br>
        
        ';
        }

        



      }
      echo $str;
    }

  }

  public function loadReviewTimesheets(){

    $str = '';

    $query = mysqli_query($this->con, "SELECT * FROM timesheets
      JOIN users
      ON timesheets.emp_id=users.user_id
      WHERE timesheets.is_submitted='yes'
      AND timesheets.is_complete='no'
      ORDER BY users.emp_num");

    if (mysqli_num_rows($query) > 0){
      while ($row = mysqli_fetch_array($query)){
        $timesheetId = $row['timesheet_id'];
        $empId = $row['user_id'];

        $empNum = sprintf("%04d",$row['emp_num']);

        $name = $row['last_name'] . ', ' . $row['first_name'];

        $hours = $this->getHours($empId, $timesheetId);

        $start = strtotime($row['week_start']);
        $end = strtotime($row['week_end']);

        $start = date("m/d/Y", $start);

        $end = date("m/d/Y", $end);

        
        $str.= '
          <a href="timesheet_details.php?timesheet_id='.$timesheetId.'&emp_id='.$empId.'&admin_view=true">
            <div class="work_order"><b><p>'. $empNum.' ' .$name.'</p><p>'.$start.' - '.$end.'</p><p>Hours: '.number_format($hours,2).'</p></b></div>
          </a><br>
        
        ';


      }
      echo $str;
    } else {
      echo "<b>No timesheets to review!</b>";
    }

  }
  //loads submitted and completed timesheets for readonly purpose
  public function loadTimesheet($empId, $timesheetId) {
    $str = '<hr><h4>WEEK 1</h4>
            <div class="sub-timesheet-grid">
            <div style="border-bottom: 1px solid #000">Date</div>
            <div style="border-bottom: 1px solid #000">Project</div>
            <div style="border-bottom: 1px solid #000">Arrival</div>
            <div style="border-bottom: 1px solid #000">Departure</div>
            <div style="border-bottom: 1px solid #000" class="ln-curr">Hours</div>';

    $q = mysqli_query($this->con, "SELECT * FROM timesheets WHERE emp_id='$empId' AND timesheet_id='$timesheetId'");
    $r = mysqli_fetch_array($q);

    $start = strtotime($r['week_start']);
    $mid = $start + (60*60*24*7);

    $hasHeader = false;

    $totalHours = 0.00;

    $query  = mysqli_query($this->con, "SELECT * FROM timesheet_lines WHERE emp_id='$empId' AND timesheet_id='$timesheetId' ORDER BY date_worked, arrival");

    $count=0;

    if(mysqli_num_rows($query) >0){


        while($row = mysqli_fetch_array($query)){
            $lineNum = $row['tl_id'];
            $jobId = $row['job_id'];
            $orderId = $row['wo_id'];
            $date = strtotime($row['date_worked']);
            $arrival = strtotime($row['arrival']);
            $departure = strtotime($row['departure']);
            $hours = ($departure - $arrival)/(60*60);

            $totalHours += $hours;


            if ($jobId > 0){

              $dataQuery = mysqli_query($this->con, "SELECT * FROM jobs 
                  JOIN customers 
                  ON jobs.cust_id=customers.cust_id
                  WHERE job_id='$jobId'");
              
              

              if(mysqli_num_rows($dataQuery) >0){

                while ($row = mysqli_fetch_array($dataQuery)) {
                  $jobName = $row['job_name'];
                  $firstName = $row['first_name'];
                  $lastName = $row['last_name'];
                  $company = $row['company_name'];
                  if ($company == ""){
                      $loadName = $lastName . ", " . $firstName;
                  } else {
                      $loadName = $company;
                  }
                  $loadName.=": ".$jobName;
                }
              }
            } else {

              $dataQuery = mysqli_query($this->con, "SELECT * FROM work_orders 
                  JOIN customers 
                  ON work_orders.cust_id=customers.cust_id
                  WHERE wo_id='$orderId'");
              
              if(mysqli_num_rows($dataQuery) >0){

                while ($row = mysqli_fetch_array($dataQuery)) {
                  $firstName = $row['first_name'];
                  $lastName = $row['last_name'];
                  $company = $row['company_name'];

                  $servDate = strtotime($row['service_date']);
                  if ($company == ""){
                      $loadName = $lastName . ", " . $firstName;
                  } else {
                      $loadName = $company;
                  }
                  $loadName.=": SERVICE ". date("m/d/Y", $servDate);
                }
              }
            }




            if ($hasHeader == false){

              if($date >= $mid){
                $str.= '</div><h4>WEEK 2</h4>
                      <div class="sub-timesheet-grid">
                      <div style="border-bottom: 1px solid #000">Date</div>
                      <div style="border-bottom: 1px solid #000">Project</div>
                      <div style="border-bottom: 1px solid #000">Arrival</div>
                      <div style="border-bottom: 1px solid #000">Departure</div>
                      <div style="border-bottom: 1px solid #000" class="ln-curr">Hours</div>';
                $hasHeader = true;
              }

            }

            if (($count%2)==0){
              $str.= '
              <div class="sub-timesheet-grid-item">'.date("m/d l",$date).'</div>
              <div class="sub-timesheet-grid-item"> '.$loadName.'</div>
              <div class="sub-timesheet-grid-item"> '.date("h:ia", $arrival).'</div>
              <div class="sub-timesheet-grid-item"> '.date("h:ia", $departure).'</div>
              <div class ="sub-timesheet-grid-item ln-curr"> '.number_format($hours,2).'</div>';
            }else{
              $str.= '
              <div class="sub-timesheet-grid-alt">'.date("m/d l",$date).'</div>
              <div class="sub-timesheet-grid-alt"> '.$loadName.'</div>
              <div class="sub-timesheet-grid-alt"> '.date("h:ia", $arrival).'</div>
              <div class="sub-timesheet-grid-alt"> '.date("h:ia", $departure).'</div>
              <div class ="sub-timesheet-grid-alt ln-curr"> '.number_format($hours,2).'</div>';
            }

            $count++;
        } //end while

        if ($totalHours > 80){

          $reg = 80.00;
          $ot = $totalHours - 80;

        } else {
          $reg = $totalHours;
          $ot = 0.00;
        }

        echo $str.'</div><br>
        <div class="sub-timesheet-grid" style="font-weight: bold;">
        <div></div>
        <div></div>
        <div></div>
        <div>Regular:</div>
        <div class="ln-curr">'.number_format($reg,2).'</div>
        <div></div>
        <div></div>
        <div></div>
        <div style="border-bottom: 1px solid #000">Overtime:</div>
        <div class="ln-curr" style="border-bottom: 1px solid #000">'.number_format($ot,2).'</div>
        <div></div>
        <div></div>
        <div></div>
        <div>Total:</div>
        <div class="ln-curr">'.number_format($totalHours,2).'</div></div>';
    } 

  }

}

?>