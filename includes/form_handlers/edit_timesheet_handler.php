<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
    
$job = new Job($con);
$timesheet = new Timesheet($con);
$wo = new WorkOrder($con);
$e = new Employee($con);

$admin_view = '';

$error_array = array();

if (isset($_GET['from_details'])){
	unset($_SESSION['project']);
	unset($_SESSION['date']);
	unset($_SESSION['ah']);
	unset($_SESSION['am']);
	unset($_SESSION['ap']);
	unset($_SESSION['dh']);
	unset($_SESSION['dm']);
	unset($_SESSION['dp']);
}
if (isset($_GET['timesheet_id'])){
	$timesheetId = $_GET['timesheet_id'];
	if (isset($_GET['admin_view'])){
		$empId = $_GET['emp_id'];
        if (isset($_GET['start'])){
            $admin_view = "&admin_view=true&emp_id=".$empId."&start=".$_GET['start'];
        } else {
            $admin_view = "&admin_view=true&emp_id=".$empId;
        }
		
	} else {
		$empId = $user['user_id'];
	}

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

    $query = mysqli_query($con, "SELECT * FROM timesheets WHERE emp_id='$empId' AND timesheet_id='$timesheetId'");
    $row = mysqli_fetch_array($query);

    $start = strtotime($row['week_start']);
    $end = strtotime($row['week_end']);
    $auth = $row['authorization'];
    $submitted = $row['is_submitted'];
    $complete = $row['is_complete'];
    $perDiem = $row['per_diem'];
    $injury = $row['injury'];

    $nameQ = mysqli_query($con, "SELECT * FROM users WHERE user_id='$empId'");
    $nameR = mysqli_fetch_array($nameQ);
    $name = $nameR['first_name'] . " " . $nameR['last_name'];



}
if (isset($_POST['ts_info'])){
    $pd = $_POST['per_diem'];
    $inj = $_POST['injury'];
    $query = mysqli_query($con, "UPDATE timesheets SET per_diem='$pd', injury='$inj' WHERE emp_id='$empId' AND timesheet_id='$timesheetId'");
    //TO DO: SEND EMAIL TO ADMIN IF $inj == "yes"
    if ($inj=="yes"){
        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        try {
            //Server settings
            //$mail->SMTPDebug = 2;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'email-smtp.us-west-2.amazonaws.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'username';                 // SMTP username
            $mail->Password = 'password';                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('teostest6@gmail.com', 'Orion Electric');
            $mail->addAddress('teostest6@gmail.com');     // DEVELOP
        
            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Employee Injury Notice';
            $mail->Body    = 'This email was automatically generated to inform you that  ' .$name.' has checked "Yes" to "Injured on the job" 
            on '.date("l m/d/Y").'.';
            $mail->AltBody = 'This email was automatically generated to inform you that  ' .$name.' has checked "yes" to "Injured on the job" 
            on '.date("l m/d/Y").'.';

            $mail->send();
            echo 'Order is complete. An email has been sent.';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    header("location: timesheet_details.php?timesheet_id=".$timesheetId.$admin_view);

}

if(isset($_GET['edit_timesheet'])){
    if($_GET['edit_timesheet']=="yes"){
        $query = mysqli_query($con, "UPDATE timesheets SET authorization='', is_submitted='no' WHERE emp_id='$empId' AND timesheet_id='$timesheetId'");
        header("location: timesheet_details.php?timesheet_id=".$timesheetId.$admin_view);
    }
}

if(isset($_GET['approve_timesheet'])){
    if($_GET['approve_timesheet']=="yes"){
        $query = mysqli_query($con, "UPDATE timesheets SET is_complete='yes' WHERE emp_id='$empId' AND timesheet_id='$timesheetId'");
        header("location: timesheet_details.php?timesheet_id=".$timesheetId.$admin_view);
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

    $lineNum = $timesheet->getNextLineNumber($empId, $timesheetId);
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

    


    $timesheet->addTimesheetLine($empId, $timesheetId, $lineNum, $jobId, $orderId, $date, $arr, $dep);
    unset($_SESSION['project']);
    unset($_SESSION['date']);
    unset($_SESSION['ah']);
    unset($_SESSION['am']);
    unset($_SESSION['ap']);
    unset($_SESSION['dh']);
    unset($_SESSION['dm']);
    unset($_SESSION['dp']);

}

?>