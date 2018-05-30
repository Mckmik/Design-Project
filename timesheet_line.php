<?php 
    include("includes/header.php");
    include("includes/form_handlers/timesheet_line_handler.php");

    if(isset($_POST['confirm_hours'])) {
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
	<div class="main_column column">
    <div id="first">    
        <h1>Edit Hours </h1>
        <a href="user_manual.pdf#page=57" target="_blank" style="float: right; padding: 0 100px;">Help <i class="fa fa-question fa-lg"></i></a><br>

        <form action="timesheet_line.php?timesheet_id=<?php echo $timesheetId.$admin_view;?>&line_num=<?php echo $lineNum;?>" method="POST">
            <p><label>Project:</label><br>
            <select id="project" name="project"  required>
                <option value="">Select a project</option>
                <?php
                    $job->dropDownLoadJobs($start, $end);
                    $wo->dropDownLoadOrders($empId, $start, $end);
                ?>
            </select></p>
            <p>
            <input type="submit" class="btn btn-success" id="save_project" name="save_project" value="Next">
            <button type="button" class="btn btn-primary" id="change_project" style="display: none;">Change</button>
            </p>
        </form>

        <div id="date" style="display: none;">
            <form action="timesheet_line.php?timesheet_id=<?php echo $timesheetId.$admin_view;?>&line_num=<?php echo $lineNum;?>" method="POST">
            <p><label>Date Worked:</label><br>
            <input type="text" name="date_worked" id="datepicker" required readonly ><input type="text" id="dateH" style="display: none;" required></p>
            <p>
            <input type="submit" class="btn btn-success" id="save_date" name="save_date" value="Next">
            <button type="button" class="btn btn-primary" id="change_date" style="display: none;">Change</button>
            </p>
            </form>
            
        </div>
        <div id="hours" style="display: none;">
        <form action="timesheet_line.php?timesheet_id=<?php echo $timesheetId.$admin_view;?>&line_num=<?php echo $lineNum;?>" method="POST">
            <p>
            <label>Arrival:</label><br>
                <select id="arrival_hour" name="arrival_hour" style="width: 100px">
                    <option value="01">01</option>
                    <option value="02">02</option>
                    <option value="03">03</option>
                    <option value="04">04</option>
                    <option value="05">05</option>
                    <option value="06">06</option>
                    <option selected value="07">07</option>
                    <option value="08">08</option>
                    <option value="09">09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                </select>&nbsp;:&nbsp;
                <select id="arrival_min" name="arrival_min" style="width: 100px">
                    <option value="00">00</option>
                    <option value="15">15</option>
                    <option value="30">30</option>
                    <option value="45">45</option>
                </select>
                <select id="arrival_period" name="arrival_period" style="width: 100px">
                    <option value="am">AM</option>
                    <option value="pm">PM</option>                    
                </select>
            </p><p>
                <label>Departure:</label><br>
                <select id="departure_hour" name="departure_hour" style="width: 100px">
                <option value="01">01</option>
                    <option value="02">02</option>
                    <option selected value="03">03</option>
                    <option value="04">04</option>
                    <option value="05">05</option>
                    <option value="06">06</option>
                    <option value="07">07</option>
                    <option value="08">08</option>
                    <option value="09">09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                </select>&nbsp;:&nbsp;
                <select id="departure_min" name="departure_min" style="width: 100px">
                    <option value="00">00</option>
                    <option value="15">15</option>
                    <option selected value="30">30</option>
                    <option value="45">45</option>
                </select>
                <select id="departure_period" name="departure_period" style="width: 100px">
                    <option value="am">AM</option>
                    <option selected value="pm">PM</option>                    
                </select>
            </p>
            <p>
            <input type="submit" class="btn btn-success" id="save_hours" name="save_hours" value="Next">
            <button type="button" class="btn btn-primary" id="change_hours" style="display: none;">Change</button>
            </p>
            </form>
            <?php
            if(in_array("Error: Arrival time conflicts with an existing timesheet entry <br>", $error_array)){ echo '<span style="color: red;">Error: Arrival time conflicts with an existing timesheet entry</span><br>';}
            if(in_array("Error: Departure time conflicts with an existing timesheet entry <br>", $error_array)){ echo '<span style="color: red;">Error: Departure time conflicts with an existing timesheet entry</span><br>';}
            if(in_array("Error: Selected time span conflicts with an existing timesheet entry <br>", $error_array)){ echo '<span style="color: red;">Error: Selected time span conflicts with an existing timesheet entry</span><br>';}
            if(in_array("Error: Departure time cannot be before arrival time<br>", $error_array)){ echo '<span style="color: red;">Error: Departure time cannot be before arrival time</span><br>';}
            ?>
        </div>
        <div id="confirm" style="display: none;">
            <form action="timesheet_line.php?timesheet_id=<?php echo $timesheetId.$admin_view;?>&line_num=<?php echo $lineNum;?>" method="POST">
            <p>
            <input type="submit" class="btn btn-success" id="confirm_hours" name="confirm_hours" value="Confirm this entry?">
            </p>
            </form>

        </div>
        <p><button type="button" class="btn btn-danger btn-sm" id="delete_line">Delete this Entry?</button></p>
    </div>
    <div id="second">
        <h1>Hours Added!</h1>
    </div>  
        
        <hr>
		<a href="timesheet_details.php?timesheet_id=<?php echo $timesheetId.$admin_view;?>">
		    <button type="button" class="btn btn-secondary">
		    <i class="fa fa-arrow-circle-o-left fa-lg"></i> Return to Timesheet Details
		    </button>
		</a>
		<hr> 
	</div>
</div>
</body>
<script>
$("#datepicker").change(function(){
    $("#dateH").val($(this).val());
});

<?php
//To set project dropdown to stored value
if (isset($_SESSION['project'])){
    $project = $_SESSION['project'];
    $splitter = '';

    for ($i = 0; $i < strlen($project); $i++ ){
        if ($project{$i}==','){
            $splitter = $i;
        }
    }
    $jobId = substr($project, 0, $splitter);
    $orderId = substr($project, ($splitter + 1));

    if ($jobId > 0){
        $q = mysqli_query($con, "SELECT * FROM jobs WHERE job_id='$jobId'");
        $r = mysqli_fetch_array($q);
        $jobSt = strtotime($r['start_date']);
        if ($jobSt < $start){
            $jobSt = $start;
        }
        $jobSt = date("m/d/Y", $jobSt);
        $jobEn = $r['complete_date'];
        if($jobEn == "0000-00-00"){
            $complete = false;
        } else {
            $complete = true;
            $jobEn = strtotime($jobEn);
            if($jobEn > $end){
                $jobEn = $end;
            }
            $jobEn = date("m/d/Y", $jobEn);
            
        }
        if ($complete){
            echo '$("#datepicker").datepicker({minDate: "'.$jobSt.'", maxDate: "'.$jobEn.'"});';
            
        } else {
            echo '$("#datepicker").datepicker({minDate: "'.$jobSt.'", maxDate: "'.date("m/d/Y",$end).'"});';
        }


    } else {
        $q = mysqli_query($con, "SELECT * FROM work_orders WHERE wo_id='$orderId'");
        $r = mysqli_fetch_array($q);
        $orderDate = strtotime($r['service_date']);
        $orderDate = date("m/d/Y", $orderDate);

        echo '$("#datepicker").datepicker({minDate: "'.$orderDate.'", maxDate: "'.date("m/d/Y",$end).'"});';
    }
    
    echo 'var proj = "'.$project.'";';
    echo "
    var projSelect = document.getElementById('project');

    for(var i, j = 0; i = projSelect.options[j]; j++) {
        if(i.value == proj) {
            projSelect.selectedIndex = j;
            break;
        }
    }
    ";

    echo "
    $('#project').prop('disabled', true);
    $('#save_project').hide();
    $('#change_project').show();
    $('#date').show(); 
    ";

    echo 'console.log("'.$jobId.' - '.$orderId.'");';
}

if (isset($_SESSION['date'])){
    
    $date = $_SESSION['date'];
    echo "$('#datepicker').prop('value', '$date');";
    echo "$('#datepicker').prop('disabled', true);";
    echo "$('#save_date').hide();
        $('#change_date').show();
        $('#hours').show();";
}
if (isset($_SESSION['ah'])){
    
    $ah = $_SESSION['ah'];
    $am = $_SESSION['am'];
    $ap = $_SESSION['ap'];
    $dh = $_SESSION['dh'];
    $dm = $_SESSION['dm'];
    $dp = $_SESSION['dp'];

    echo 'var ah = "'.$ah.'";';
    echo "
    var ahSelect = document.getElementById('arrival_hour');

    for(var i, j = 0; i = ahSelect.options[j]; j++) {
        if(i.value == ah) {
            ahSelect.selectedIndex = j;
            break;
        }
    }
    ";
    echo 'var am = "'.$am.'";';
    echo "
    var amSelect = document.getElementById('arrival_min');

    for(var i, j = 0; i = amSelect.options[j]; j++) {
        if(i.value == am) {
            amSelect.selectedIndex = j;
            break;
        }
    }
    ";
    echo 'var ap = "'.$ap.'";';
    echo "
    var apSelect = document.getElementById('arrival_period');

    for(var i, j = 0; i = apSelect.options[j]; j++) {
        if(i.value == ap) {
            apSelect.selectedIndex = j;
            break;
        }
    }
    ";
    echo 'var dh = "'.$dh.'";';
    echo "
    var dhSelect = document.getElementById('departure_hour');

    for(var i, j = 0; i = dhSelect.options[j]; j++) {
        if(i.value == dh) {
            dhSelect.selectedIndex = j;
            break;
        }
    }
    ";
    echo 'var dm = "'.$dm.'";';
    echo "
    var dmSelect = document.getElementById('departure_min');

    for(var i, j = 0; i = dmSelect.options[j]; j++) {
        if(i.value == dm) {
            dmSelect.selectedIndex = j;
            break;
        }
    }
    ";
    echo 'var dp = "'.$dp.'";';
    echo "
    var dpSelect = document.getElementById('departure_period');

    for(var i, j = 0; i = dpSelect.options[j]; j++) {
        if(i.value == dp) {
            dpSelect.selectedIndex = j;
            break;
        }
    }
    ";


    echo "
    $('#arrival_hour').prop('disabled', true);
    $('#arrival_min').prop('disabled', true);
    $('#arrival_period').prop('disabled', true);
    $('#departure_hour').prop('disabled', true);
    $('#departure_min').prop('disabled', true);
    $('#departure_period').prop('disabled', true);
    $('#save_hours').hide();
    $('#change_hours').show();";


    echo 'console.log("'.$ah.$am.$ap.$dh.$dm.$dp.'");';
}
if (isset($_SESSION['date']) && isset($_SESSION['ah'])){
    echo "$('#confirm').show();";
}

?>
$('#change_project').on('click', function() {

    $('#project').prop('disabled', false);
    $('#save_project').show();
    $('#change_project').hide();
    $('#date').hide();
    $('#hours').hide();
    $('#confirm').hide();  

});
$('#change_date').on('click', function() {

    $('#datepicker').prop('disabled', false);
    $('#save_date').show();
    $('#change_date').hide();
    $('#hours').hide();
    $('#confirm').hide();
});
$('#change_hours').on('click', function() {

    $('#arrival_hour').prop('disabled', false);
    $('#arrival_min').prop('disabled', false);
    $('#arrival_period').prop('disabled', false);
    $('#departure_hour').prop('disabled', false);
    $('#departure_min').prop('disabled', false);
    $('#departure_period').prop('disabled', false);
    $('#save_hours').show();
    $('#change_hours').hide();
    $('#confirm').hide();
});


$(document).ready(function(){
    $('#delete_line').on('click', function() {
        if(confirm("Are you sure you want to delete this entry?")){
            console.log("yes");
            location.assign("timesheet_line.php?timesheet_id=<?php echo $timesheetId.$admin_view;?>&delete_line=yes&line_num=<?php echo $lineNum; ?>");


        }else{
            console.log("no");
        }
    });
});

$("#datepicker").mask("00/00/0000");
$("#datepicker").keypress(function(event) {event.preventDefault();});
</script>
</html>