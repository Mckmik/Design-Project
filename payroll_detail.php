<?php 
    include("includes/header.php");
    include("includes/form_handlers/payroll_handler.php");

?>
    <div class="main_column column">

    <h1>REPORTS - PAYROLL</h1>
    <a href="user_manual.pdf#page=35" target="_blank" style="float: right; padding: 0 100px;">Help <i class="fa fa-question fa-lg"></i></a><br>
    <div id="first">
        <h3>Report for Period: <?php echo $start . " - " . $end;?></h3>
        <?php echo $body;?>

    </div>

    <hr>
    <a href="reports_payroll.php">
		    <button type="button" class="btn btn-secondary">
		    <i class="fa fa-arrow-circle-o-left fa-lg"></i> Return to Reports - Payroll
		    </button>
		</a>
    <hr>
    </div>
</div>
</body>
</html>