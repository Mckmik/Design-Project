<?php 
    include("includes/header.php");
    $emp = new Employee($con);
?>
    <div class="main_column column">
    <h1>REPORTS - RECONCILE WORK ORDERS</h1>
    <hr>
    <a href="user_manual.pdf#page=39" target="_blank" style="float: right; padding: 0 100px;">Help <i class="fa fa-question fa-lg"></i></a><br>
    <form action="reports_workorder.php" method="POST">
        <label>Technician</label>
        <select name="tech_id" required>
            <option value="">Select a technician</option>
            <?php
            $emp->dropdownLoadServiceTechsReports();
            ?>
        </select><br>
        <label for="from">From</label>
        <input type="text" id="from" name="from" required readonly><input type="text" id="fromH" style="display: none;" required><br>
        <label for="to">To</label>
        <input type="text" id="to" name="to" required readonly><input type="text" id="toH" style="display: none;" required><br>
        <label></label>
        <input type="submit" class="btn btn-primary" name="tech_search" value="Search Work Orders">
    </form>
    <?php
    if (isset($_POST['tech_search'])){
        $techId = $_POST['tech_id'];
        $from = $_POST['from'];
        $to = $_POST['to'];

        echo '<hr>
            <h4>Work Order Report for ';
        $emp->getEmployeeName($techId);
        echo '</h4><h4>From '.$from.' to '.$to.'</h4>';

        $str = '<div class="order-report-grid">
        <div style="border-bottom: 1px solid black">Order #</div>
        <div style="border-bottom: 1px solid black">Start</div>
        <div style="border-bottom: 1px solid black">End</div>
        <div style="border-bottom: 1px solid black" class="ln-curr">Order Hours</div>
        <div style="border-bottom: 1px solid black" class="ln-curr">Timesheet Hours</div>
        <div style="border-bottom: 1px solid black" class="ln-curr">Over/Short</div>
        <div style="border-bottom: 1px solid black"></div>
        ';
        
        $fromDb = date_create($from);
        $fromDb = date_format($fromDb, "Y-m-d");
        $toDb = date_create($to);
        $toDb = date_format($toDb, "Y-m-d");
        $count = 0;


        $query = mysqli_query($con, "SELECT * FROM work_orders
        WHERE tech_id='$techId'
        AND is_complete='yes'
        AND service_date>='$fromDb'
        AND complete_date<='$toDb'");

        if(mysqli_num_rows($query)){
            while ($row = mysqli_fetch_array($query)){
                $woId = $row['wo_id'];
                $startDate = strtotime($row['service_date']);
                $startDate = date("m/d/Y", $startDate);
                $compDate = strtotime($row['complete_date']);
                $compDate = date("m/d/Y", $compDate);

                $oq = mysqli_query($con, "SELECT * FROM work_order_lines WHERE wo_id='$woId' AND type='Labor'");
                $orderHours = 0.00;
                if (mysqli_num_rows($oq) > 0){
                    while ($or = mysqli_fetch_array($oq)){
                        $orderHours += number_format($or['quantity'],2);
                    }
                } 


                $q = mysqli_query($con, "SELECT * FROM timesheet_lines WHERE wo_id='$woId'");
                $hours = 0.00;
                if (mysqli_num_rows($q) > 0){
                    while ($r = mysqli_fetch_array($q)){
                        $dateWorked = strtotime($r['date_worked']);
                        $arr = strtotime($r['arrival']);
                        $dep = strtotime($r['departure']);
                        $hours += number_format(($dep - $arr) / (60*60), 2);


                    }
                } 
                if (($count%2)==0){
                    $str .= '<div class="payroll-grid-item">'.$woId.'</div>
                    <div class="payroll-grid-item">'.$startDate.'</div>
                    <div class="payroll-grid-item">'.$compDate.'</div>
                    <div class="payroll-grid-item ln-curr">'.number_format($orderHours,2). '</div>
                    <div class="payroll-grid-item ln-curr">' .number_format($hours,2).'</div>
                    <div class="payroll-grid-item ln-curr">'.number_format(($hours - $orderHours),2).'</div>
                    <div class="payroll-grid-item ln-curr"></div>';
                } else {
                    $str .= '<div class="payroll-grid-alt">'.$woId.'</div>
                    <div class="payroll-grid-alt">'.$startDate.'</div>
                    <div class="payroll-grid-alt">'.$compDate.'</div>
                    <div class="payroll-grid-alt ln-curr">'.number_format($orderHours,2). '</div>
                    <div class="payroll-grid-alt ln-curr">' .number_format($hours,2).'</div>
                    <div class="payroll-grid-alt ln-curr">'.number_format(($hours - $orderHours),2).'</div>
                    <div class="payroll-grid-alt ln-curr"></div>';
                }


                $count++;
            }
            echo $str . '</div>';
        } else {
            echo 'No results found.';
        }

    }

    ?>
    <hr>
    <a href="reports.php">
		    <button type="button" class="btn btn-secondary">
		    <i class="fa fa-arrow-circle-o-left fa-lg"></i> Return to Reports
		    </button>
		</a>
    <hr>
    </div>
</div>
</body>
<script>
$("#from").change(function(){
	$("#fromH").val($(this).val());
});
$("#to").change(function(){
	$("#toH").val($(this).val());
});


  $( function() {
    var dateFormat = "mm/dd/yy",
      from = $( "#from" )
        .datepicker({
          defaultDate: "+1w",
          changeMonth: true,
          numberOfMonths: 1
        })
        .on( "change", function() {
          to.datepicker( "option", "minDate", getDate( this ) );
        }),
      to = $( "#to" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 1
      })
      .on( "change", function() {
        from.datepicker( "option", "maxDate", getDate( this ) );
      });
 
    function getDate( element ) {
      var date;
      try {
        date = $.datepicker.parseDate( dateFormat, element.value );
      } catch( error ) {
        date = null;
      }
 
      return date;
    }
  } );
  $("#to").mask("00/00/0000");
  $("#to").keypress(function(event) {event.preventDefault();});
  $("#from").mask("00/00/0000");
  $("#from").keypress(function(event) {event.preventDefault();});
</script>
</html>