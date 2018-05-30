<?php

$admin_view = '';

if (isset($_GET['timesheet_id'])){
	$timesheetId = $_GET['timesheet_id'];
	if (isset($_GET['admin_view'])){
		$empId = $_GET['emp_id'];
		$admin_view = "&admin_view=true&emp_id=".$empId."&start=".$_GET['start'];
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



}
if (isset($_POST['signature_uri'])){
    $data_uri = $_POST['signature_uri'];
    $encoded_image = explode(",", $data_uri)[1];
    $decoded_image = base64_decode($encoded_image);
    file_put_contents("assets/images/signatures/e".$empId."ts".$timesheetId.".png", $decoded_image);
    $sigLoaction = "assets/images/signatures/e".$empId."ts".$timesheetId.".png";

    $query = mysqli_query($con, "UPDATE timesheets SET authorization='$sigLoaction', is_submitted='yes' WHERE emp_id='$empId' AND timesheet_id='$timesheetId'");

	echo '
    <script>

    $(document).ready(function() {
        $("#first").hide();
        $("#second").show();
    });

    </script>
    ';

}

?>