<?php 
    include("includes/header.php");
	include("includes/form_handlers/edit_timesheet_handler.php");


    $adminView = false;
    $strt = '';

    if(isset($_GET['admin_view'])){
    	$adminView = true;
    	if (isset($_GET['start'])){
	    	$strt = $_GET['start'];
	    	echo '
	    	<script>
	    	$(document).ready(function() {
	            $("#first").hide();
	            $("#second").show();
	        });
	    	</script>
	    	';
	    } else {
	    	echo '
	    	<script>
	    	$(document).ready(function() {
	            $("#first").hide();
	            $("#third").show();
	        });
	    	</script>
	    	';
	    }
    }
    if ($complete == "yes"){
    	echo '
    	<script>
    	$(document).ready(function() {
    		$("#not_sub").hide();
    		$("#complete").show();
    		$("#submit_timesheet").hide();
    		$("#add_hours").hide();
            $("#timesheet_area").hide();
            
            $("#submitted").show();

        });
    	</script>
    	';
    }    
    if (($submitted == "yes") && ($complete == "no")){
    	echo '
    	<script>
    	$(document).ready(function() {
    		$("#not_sub").hide();
    		$("#sub").show();
    		$("#submit_timesheet").hide();
    		$("#add_hours").hide();
            $("#timesheet_area").hide();
            $("#edit_timesheet").show();
            $("#submitted").show();
        });
    	</script>
    	';
    }
?>
	<div class="main_column column">
		<h1>Timesheet Details - <?php echo $e->getEmployeeName($empId);?></h1>
		<a href="user_manual.pdf#page=57" target="_blank" style="float: right; padding: 0 100px;">Help <i class="fa fa-question fa-lg"></i></a>
		<h3>Pay Period: <?php echo date("m/d/Y",$start).' - '.date("m/d/Y",$end); ?></h3>
		<div class="timesheet-head">
			<p>Total Hours: <?php echo $timesheet->getHours($empId, $timesheetId);?></p>
			<p>Regular Hours: <?php if ($timesheet->getHours($empId, $timesheetId)>80){
				echo number_format(80,2);
			} else {echo $timesheet->getHours($empId, $timesheetId);}?></p>
			<p>Overtime Hours: <?php if ($timesheet->getHours($empId, $timesheetId)>80){
				echo number_format(($timesheet->getHours($empId, $timesheetId) - 80),2);
			} else {echo number_format(0,2);}?></p>
		</div>
		<hr>

		<div id="not_sub">
			Status: <b>Not Submitted</b>
			
		</div>

		<div id="sub" style="display: none;">
			Status: <b>Pending Approval</b>
			
		</div>

		<div id="complete" style="display: none;">
			Status: <b>Complete</b>
			
		</div>
		<br>

		<button type="button" class="btn btn-primary" id="edit_timesheet" style="display: none;">
			Edit Timesheet
		</button>
			<?php if (strtotime(date("m/d/Y"))>$end){
				echo '
				<button type="button" class="btn btn-primary" id="submit_timesheet">
				Submit Timesheet
				</button>
				';

			}
			?>



		<a href="add_timesheet_line.php?timesheet_id=<?php echo $timesheetId.$admin_view; ?>&from_details=true">
		    <button type="button" id="add_hours" class="btn btn-success">
		    	<i class="fa fa-plus fa-lg"></i> Add Hours
		    </button>
		</a>
		<?php 
		if ($adminView) {

			if(($submitted == "yes") && ($complete == "no")){
				echo '
			    <button type="button" id="approve" class="btn btn-success">
			    	<i class="fa fa-check fa-lg"></i> Approve
				</button>
				';
			}

		}

		?>




		<div  id="timesheet_area" class="timesheet-area">
			<?php $timesheet->loadTimesheetLines($empId, $timesheetId, $adminView, $strt);?>
		</div>
		
		<div id="submitted" style="display: none;">
			<?php $timesheet->loadTimesheet($empId, $timesheetId);?>

			<b>SIGNATURE</b><br>
			<?php
			if ($submitted == "yes"){
			echo '
				<img src="assets/images/signatures/e'.$empId.'ts'.$timesheetId.'.png?dummy=.'.rand(1000000, 9999999).'" width="358" height="60">

			';}?>

		</div>
		<br>
		<br>
		<div style="border: 1px solid black; width: 200px; padding: 10px;">
		<form action="timesheet_details.php?timesheet_id=<?php echo $timesheetId.$admin_view;?>" method="POST">
			Days Per Diem: <input type="number" name="per_diem" id="per_diem" value="<?php echo $perDiem;?>" min="0" max="14" disabled><br><br>
			Injured on the job: <b>No</b><input type="radio" name="injury" id="inj_no" value="no" checked disabled>&nbsp;&nbsp; <span style="color: red"><b>Yes</b></span><input type="radio" name="injury" id="inj_yes" value="yes" disabled><br>
			<br><input  type="submit" class="btn btn-success" style="display: none;" name="ts_info" id="ts_info" value="Save">
		</form>
		<button type="button" class="btn btn-primary" id="change_ts_info">Change</button>
		</div>

		<hr>
		<div id="first">
		<a href="timesheets.php">
		    <button type="button" class="btn btn-secondary">
		    <i class="fa fa-arrow-circle-o-left fa-lg"></i> Return to Timesheets
		    </button>
		</a>
		</div>
		<div id="second">
		<a href="payperiod.php?start=<?php if (isset($_GET['start'])){ echo $_GET['start'];}?>">
		    <button type="button" class="btn btn-secondary">
		    <i class="fa fa-arrow-circle-o-left fa-lg"></i> Return to Pay Period
		    </button>
		</a>
		</div>
		<div id="third" style="display: none;">
		<a href="review_timesheets.php">
		    <button type="button" class="btn btn-secondary">
		    <i class="fa fa-arrow-circle-o-left fa-lg"></i> Return to Review Timesheets
		    </button>
		</a>
		</div>
		<hr> 
	</div>
</div>
</body>
<script>
<?php
if ($submitted == "yes"){
	echo '
	$("#change_ts_info").hide();
	';
}
?>
$(document).ready(function(){
	$('#change_ts_info').on('click', function(){
		$('#per_diem').prop("disabled", false);
		$('#inj_no').prop("disabled", false);
		$('#inj_yes').prop("disabled", false);
		$('#ts_info').show();
		$('#change_ts_info').hide();
	});
    $('#submit_timesheet').on('click', function() {
        if(confirm("Are you sure you want to submit this timesheet for review?")){
            console.log("yes");
			location.assign("submit_timesheet.php?timesheet_id=<?php echo $timesheetId.$admin_view;?>");
        }else{
            console.log("no");
        }
    });

    $('#edit_timesheet').on('click', function() {
        if(confirm("Are you sure you want to edit this timesheet? Doing so will remove the current authorization and require a new signature.")){
            console.log("yes");
			location.assign("timesheet_details.php?timesheet_id=<?php echo $timesheetId.$admin_view;?>&edit_timesheet=yes");
        }else{
            console.log("no");
        }
    });
});
<?php
if(($submitted == "yes") && ($complete == "no")){
	echo '
	$("#approve").on("click", function() {
        if(confirm("Approve this timesheet?")){
            console.log("yes");
			location.assign("timesheet_details.php?timesheet_id='.$timesheetId.$admin_view.'&approve_timesheet=yes");
        }else{
            console.log("no");
        }
    });

	';
}
if ($injury == "yes"){
	echo '
	$("#inj_yes").prop("checked", true);
	';
}
?>
</script>
</html>