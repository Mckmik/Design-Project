<?php
$job = new Job($con);
$timesheet = new Timesheet($con);
$wo = new WorkOrder($con);

$admin_view = '';

$error_array = array();



if (isset($_GET['timesheet_id']) && isset($_GET['line_num'])){
	$timesheetId = $_GET['timesheet_id'];
	if (isset($_GET['admin_view'])){
		$empId = $_GET['emp_id'];
		$admin_view = "&admin_view=true&emp_id=".$empId."&start=".$_GET['start'];
	} else {
		$empId = $user['user_id'];
	}
	
	$lineNum = $_GET['line_num'];

	$idExists = false;
    $existsQuery = mysqli_query($con, "SELECT timesheet_id FROM timesheets WHERE emp_id='$empId'");
    while ($existsRow = mysqli_fetch_array($existsQuery)){
        if ($timesheetId == $existsRow['timesheet_id']){
            $idExists = true;
        }
    }

    if($idExists == false){
        header("location: timesheet_does_not_exists.php");
    }

    $lineIdExists = false;
    $existsNewQuery = mysqli_query($con, "SELECT tl_id FROM timesheet_lines WHERE emp_id='$empId' AND timesheet_id='$timesheetId'");
    while ($existsNewRow = mysqli_fetch_array($existsNewQuery)){
        if ($lineNum == $existsNewRow['tl_id']){
            $lineIdExists = true;
        }
    }

    if($lineIdExists == false){
        header("location: timesheet_does_not_exists.php");
    }


    $query = mysqli_query($con, "SELECT * FROM timesheet_lines
    	JOIN timesheets
    	ON timesheet_lines.emp_id=timesheets.emp_id
    	AND timesheet_lines.timesheet_id=timesheets.timesheet_id
		WHERE timesheet_lines.emp_id='$empId' AND timesheet_lines.timesheet_id='$timesheetId' AND tl_id='$lineNum'");

    $row = mysqli_fetch_array($query);

    $start = strtotime($row['week_start']);
    $end = strtotime($row['week_end']);
    $auth = $row['authorization'];
    $submitted = $row['is_submitted'];
    $complete = $row['is_complete'];

    $jbId = $row['job_id'];
    $order = $row['wo_id'];
    $dateW = strtotime($row['date_worked']);
    $dbArr = $row['arrival'];
    $dbDep = $row['departure'];
    $dbAh = substr($dbArr, 0, 2);
    $dbAm = substr($dbArr, 3, 2);
    $dbAp = "am";
    if ($dbAh > 12){
        $dbAh = $dbAh - 12;
        $dbAp = "pm";
    }
    if ($dbAh == 12){
    	$dbAp = "pm";
    }


    $dbDh = substr($dbDep, 0, 2);
    $dbDm = substr($dbDep, 3, 2);
    $dbDp = "am";
    if ($dbDh > 12){
        $dbDh = $dbDh - 12;
        $dbDp = "pm";
    }
    if ($dbDh == 12){
    	$dbDp = "pm";
    }

    if (isset($_GET['from_details'])){
    	
    	unset($_SESSION['project']);
		unset($_SESSION['date']);
		unset($_SESSION['ah']);
		unset($_SESSION['am']);
		unset($_SESSION['ap']);
		unset($_SESSION['dh']);
		unset($_SESSION['dm']);
		unset($_SESSION['dp']);
	    $_SESSION['project'] = $jbId . ',' . $order;
	    $_SESSION['date'] = date("m/d/Y", $dateW);
        $_SESSION['ah'] = sprintf("%02d", $dbAh);
        $_SESSION['am'] = $dbAm;
        $_SESSION['ap'] = $dbAp;
        $_SESSION['dh'] = sprintf("%02d", $dbDh);
        $_SESSION['dm'] = $dbDm;
        $_SESSION['dp'] = $dbDp;
	}


}


if (isset($_POST['save_project'])){
    $_SESSION['project'] = $_POST['project'];
    if (isset($_SESSION['date'])){
        unset($_SESSION['date']);
    }
}

if (isset($_POST['save_date'])){
    $_SESSION['date'] = $_POST['date_worked'];
}

if (isset($_POST['save_hours'])){


	$checkDate = date_create($_SESSION['date']);
	$checkDate = date_format($checkDate, "Y-m-d");

	$arrHour = $_POST['arrival_hour'];
    $arrMin = $_POST['arrival_min'];
    $arrPer = $_POST['arrival_period'];
    if ($arrPer == "pm"){
    	if ($arrHour < 12){
        	$arrHour += 12;
        }

    } else {
    	if ($arrHour == 12){
    		$arrHour = 0;
    	}
    }
    $arrTime = sprintf("%02d", $arrHour) . ':' . sprintf("%02d", $arrMin) .':00';

    $depHour = $_POST['departure_hour'];
    $depMin = $_POST['departure_min'];
    $depPer = $_POST['departure_period'];
    if ($depPer == "pm"){
    	if ($depHour < 12){
        	$depHour += 12;
        }

    } else {
    	if ($depHour == 12){
    		$depHour = 0;
    	}
    }
    $depTime = sprintf("%02d", $depHour) . ':' . sprintf("%02d", $depMin) .':00';


    $valid = true;

    //error for overlapping hours
    $q = mysqli_query($con, "SELECT * FROM timesheet_lines WHERE emp_id='$empId' AND timesheet_id='$timesheetId' AND date_worked='$checkDate'");

    if (mysqli_num_rows($q) >0){
    	while($r = mysqli_fetch_array($q)){
    		if ($lineNum == $r['tl_id']){
    			continue;
    		} else {
	    		$checkArr = strtotime($r['arrival']);
	    		$checkDep = strtotime($r['departure']);

	    		if ((strtotime($arrTime) >= $checkArr) && (strtotime($arrTime) < $checkDep)){
	    			$valid = false;
	    			array_push($error_array,"Error: Arrival time conflicts with an existing timesheet entry <br>");
	    		}
	    		if ((strtotime($depTime) > $checkArr) && (strtotime($depTime) <= $checkDep)){
	    			$valid = false;
	    			array_push($error_array,"Error: Departure time conflicts with an existing timesheet entry <br>");
	    		}
	    		if ((strtotime($arrTime) <= $checkArr) && (strtotime($depTime) >= $checkDep)){
	    			$valid = false;
	    			array_push($error_array,"Error: Selected time span conflicts with an existing timesheet entry <br>");
	    		} 

	    		echo '
	    		<script>
	    		console.log("checkArr: '.$checkArr.', checkDep: '.$checkDep.', arrTime: '.strtotime($arrTime).', depTime: '.strtotime($depTime).'")
	    		</script>
	    		';
	    	}
    	}
    }
    //error for departure before arrival
    if (strtotime($arrTime) > strtotime($depTime)){
    	$valid = false;
    	array_push($error_array,"Error: Departure time cannot be before arrival time<br>");
    }

    if ($valid){

	    $_SESSION['ah'] = $_POST['arrival_hour'];
	    $_SESSION['am'] = $_POST['arrival_min'];
	    $_SESSION['ap'] = $_POST['arrival_period'];
	    $_SESSION['dh'] = $_POST['departure_hour'];
	    $_SESSION['dm'] = $_POST['departure_min'];
	    $_SESSION['dp'] = $_POST['departure_period'];
	}
}

if (isset($_POST['confirm_hours'])){

    $project = $_SESSION['project'];
    $splitter = '';

    for ($i = 0; $i < strlen($project); $i++ ){
        if ($project{$i}==','){
            $splitter = $i;
        }
    }
    $jobId = substr($project, 0, $splitter);
    $orderId = substr($project, ($splitter + 1));

    $date = date_create($_SESSION['date']);
    $date = date_format($date, "Y-m-d");

    $ah = $_SESSION['ah'];
    $am = $_SESSION['am'];
    $ap = $_SESSION['ap'];
    if ($ap == "pm"){
    	if ($ah < 12){
        	$ah += 12;
        }

    } else {
    	if ($ah == 12){
    		$ah = 0;
    	}
    }
    $arr = sprintf("%02d", $ah) . ':' . sprintf("%02d", $am) .':00';

    $dh = $_SESSION['dh'];
    $dm = $_SESSION['dm'];
    $dp = $_SESSION['dp'];
    if ($dp == "pm"){
    	if ($dh < 12){
        	$dh += 12;
        }

    } else {
    	if ($dh == 12){
    		$dh = 0;
    	}
    }
    $dep = sprintf("%02d", $dh) . ':' . sprintf("%02d", $dm) .':00';

    $updateQuery = mysqli_query($con, "UPDATE timesheet_lines 
    	SET job_id='$jobId', wo_id='$orderId', date_worked='$date', arrival='$arr', departure='$dep'
    	WHERE emp_id='$empId' AND timesheet_id='$timesheetId' AND tl_id='$lineNum'");




    unset($_SESSION['project']);
    unset($_SESSION['date']);
    unset($_SESSION['ah']);
    unset($_SESSION['am']);
    unset($_SESSION['ap']);
    unset($_SESSION['dh']);
    unset($_SESSION['dm']);
    unset($_SESSION['dp']);

}
if(isset($_GET['delete_line'])){
    if($_GET['delete_line']=="yes"){
        $line = $_GET['line_num'];
        $query = mysqli_query($con, "DELETE FROM timesheet_lines WHERE emp_id='$empId' AND timesheet_id='$timesheetId' AND tl_id='$line'");
        $q= mysqli_query($con,"UPDATE timesheet_lines SET tl_id=tl_id-1 WHERE emp_id='$empId' AND timesheet_id='$timesheetId' AND tl_id > '$line'");
        header("location: timesheet_details.php?timesheet_id=".$timesheetId.$admin_view);
    }
}
?>