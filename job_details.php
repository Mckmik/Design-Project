<?php 
    include("includes/header.php");
    include("includes/form_handlers/job_details_handler.php");
?>
    <div class="main_column column">
        <h1>Job Details</h1>
        <hr>
        <a href="user_manual.pdf#page=28" target="_blank" style="float: right; padding: 0 100px;">Help <i class="fa fa-question fa-lg"></i></a><br>
        <h4><?php echo $display.": ".$jobName." ".date("m/d/Y", $startDate); ?></h4>
        <br>
        <p><label>Status:</label><b><?php echo $status;?></b>&nbsp;&nbsp;&nbsp;&nbsp;<buttontype="button" class="btn btn-danger" id="edit_status" style="display: none;">Close Job?</button></p>
        
        <div id="end_date" style="display: none;">
            <form action="job_details.php?job_id=<?php echo $jobId;?>" method="POST">
                <p><label>End Date:</label><input type="text" name="complete_date" id="datepicker2" value="<?php if ($lastTime != "none"){ echo $lastTime;} else { echo date("m/d/Y");}?>" readonly></p>
                <p><label></label><input type="submit" class="btn btn-success" id="complete_job" name="complete_job" value="Complete Job"></p>
            </form>
        </div>
        
        <hr>

    	<form action="job_details.php?job_id=<?php echo $jobId;?>" method="POST">
    		<p>
    			<div id="display">
    				<label>Customer:</label><input type="text" value="<?php echo $display; ?>" disabled>
    			</div>
            	

            	<div id="edit_cust" style="display: none;">
            	<label>Customer:</label>
                <select id="customer" name="customer"  required>
                    <option value="">Select a customer</option>
                    <?php
                    $cust = new Customer($con);
                    $cust->dropdownLoadCustomers();
                    ?>
                </select>

                &nbsp;&nbsp;&nbsp;OR&nbsp;&nbsp;&nbsp;
                <a href="add_customer.php?from_jobs=true">
                    <button type="button" class="btn btn-success">
                        <i class="fa fa-plus fa-lg"></i> New Customer
                    </button>
                </a>
            	</div>
            </p>
            <p><label>Job Name:</label><input type="text" id="job_name" name="job_name" maxlength="25" required disabled value="<?php echo $jobName; ?>"></p>
            <p><label>Start Date:</label><input type="text" name="start_date" id="datepicker" disabled value="<?php echo date("m/d/Y", $startDate); ?>" readonly></p>
            <?php if ($status == "Complete"){
                echo '<p><label>End Date:</label><input type="text" name="complete_date" id="datepicker3" value="'.date("m/d/Y", $endDate).'" disabled readonly></p>';
            }
            ?>
            <p><label>Quoted Hours:</label><input type="number" id="hours" name="quoted_hours" max="10000" required disabled value="<?php echo $quotedHours; ?>"></p>
            <p><label></label><input type="submit" class="btn btn-success" id="save_job" name="save_job" value="Save" style="display: none;"></p>
        </form>
        <p><label></label><button type="button" class="btn btn-primary" id="edit_job">Edit</button></p>


    <hr>
    <a href="jobs.php">
    <button type="button" class="btn btn-secondary">
        <i class="fa fa-arrow-circle-o-left fa-lg"></i> Return to Jobs
    </button>
    </a>
    <hr>
	</div>
</div>
</body>
<script>

<?php 
    if ($status == "Open"){
        echo '
            $("#edit_status").show();
        ';
    }
?>

$(function() {
    $("#datepicker").datepicker(<?php if ($firstTime != "none"){ echo '{maxDate: "'.$firstTime.'"}';} ?>);
    $("#datepicker").mask("00/00/0000");
    $("#datepicker").keypress(function(event) {event.preventDefault();});
    $("#datepicker2").datepicker(<?php if ($lastTime != "none"){ echo '{minDate: "'.$lastTime.'"}';} else { echo '{minDate: "'.date("m/d/Y", $startDate).'"}';} ?>);
    $("#datepicker2").mask("00/00/0000");
    $("#datepicker2").keypress(function(event) {event.preventDefault();});
    $("#datepicker3").datepicker(<?php if ($lastTime != "none"){ echo '{minDate: "'.$lastTime.'"}';} else { echo '{minDate: "'.date("m/d/Y", $startDate).'"}';} ?>);
    $("#datepicker3").mask("00/00/0000");   
    $("#datepicker3").keypress(function(event) {event.preventDefault();}); 
});

	$(document).ready(function(){

		$('#edit_job').on('click', function() {

			$("#display").hide();
			$("#edit_cust").show();
			$("#job_name").prop("disabled", false);
            $("#datepicker").prop("disabled", false);
            <?php if ($status == "Complete"){ echo '$("#datepicker3").prop("disabled", false);';}?>
			$("#hours").prop("disabled", false);
			$("#edit_job").hide();
            $("#save_job").show();
        });
        
        $('#edit_status').on('click', function() {
            $("#end_date").show();
            $("#edit_status").hide();

        });

	});


//To set customer dropdown to stored value
var cust = "<?php echo $custId; ?>";
var custSelect = document.getElementById('customer');

for(var i, j = 0; i = custSelect.options[j]; j++) {
    if(i.value == cust) {
        custSelect.selectedIndex = j;
        break;
    }
}

</script>
</html>