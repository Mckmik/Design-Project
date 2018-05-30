<?php
$timesheet = new Timesheet($con);
$emp = new Employee($con);

if (isset($_GET['start'])){
    $strt = date("Y-m-d", $_GET['start']);
    $body = '<div class="payroll-grid">
    <div style="border-bottom: 1px solid black">#</div>
    <div style="border-bottom: 1px solid black">Name</div>
    <div style="border-bottom: 1px solid black" class="ln-curr">Regular Hours</div>
    <div style="border-bottom: 1px solid black" class="ln-curr">Overtime Hours</div>
    <div style="border-bottom: 1px solid black" class="ln-curr">Days per Diem</div>
    <div style="border-bottom: 1px solid black"></div>';
    
    $query = mysqli_query($con, "SELECT * FROM timesheets
    JOIN users
    ON timesheets.emp_id=users.user_id
     WHERE week_start='$strt'");

    if(mysqli_num_rows($query) > 0){
        $count = 0;
        while ($row = mysqli_fetch_array($query)){

            $empNum = sprintf("%04d",$row['emp_num']);
            $fName = $row['first_name'];
            $lName = $row['last_name'];

            $start = strtotime($row['week_start']);
            $start = date("m/d/Y", $start);
            $end = strtotime($row['week_end']);
            $end = date("m/d/Y", $end);

            $empId = $row['emp_id'];

            $timesheetId = $row['timesheet_id'];
            $hours = $timesheet->getHours($empId, $timesheetId);
            if ($hours > 80){
                $regHours = number_format(80,2);
                $otHours = number_format(($hours - 80),2);
            } else {
                $regHours = $hours;
                $otHours = number_format(0,2);
            }

            $perDiem = $row['per_diem'];
            if (($count%2) == 0){
                $body .='<div class="payroll-grid-item">'.$empNum.'</div>
                <div class="payroll-grid-item">'.$lName.", ".$fName. '</div>
                <div class="payroll-grid-item ln-curr"> '.$regHours .'</div>
                <div class="payroll-grid-item ln-curr">'.$otHours . '</div>
                <div class="payroll-grid-item ln-curr">'.$perDiem. '</div>
                <div class="payroll-grid-item"></div>';
            } else {
                $body .='<div class="payroll-grid-alt">'.$empNum.'</div>
                <div class="payroll-grid-alt">'.$lName.", ".$fName. '</div>
                <div class="payroll-grid-alt ln-curr"> '.$regHours .'</div>
                <div class="payroll-grid-alt ln-curr">'.$otHours . '</div>
                <div class="payroll-grid-alt ln-curr">'.$perDiem. '</div>
                <div class="payroll-grid-alt"></div>';
            }

            $count++;

        }
        $body .="</div>";
    }
}

?>