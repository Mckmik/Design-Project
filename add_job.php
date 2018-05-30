<?php 
    include("includes/header.php");
    include("includes/form_handlers/add_job_handler.php");


    if(isset($_POST['save_job'])) {
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
        <h1>Add New Job</h1>
        <a href="user_manual.pdf#page=26" target="_blank" style="float: right; padding: 0 100px;">Help <i class="fa fa-question fa-lg"></i></a><br>
        <br>
        <form action="add_job.php" method="POST">
            <p><label>Customer:</label>

                <select id="customer" name="customer" required>
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
            </p>
            <p><label>Job Name:</label><input type="text" name="job_name" maxlength="25" required></p>
            <p><label>Start Date:</label><input type="text" name="start_date" id="datepicker" value="<?php echo date('m/d/Y'); ?>" readonly></p>
            <p><label>Quoted Hours:</label><input type="number" id="hours" name="quoted_hours" max="10000" required></p>
            <p><label></label><input type="submit" class="btn btn-success" name="save_job" value="Add Job"></p>
        </form>
    </div>
    <div id="second">
        <h1>Job added!</h1>

    </div>
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
$(function() {
    $("#datepicker").datepicker();
    $("#datepicker").mask("00/00/0000");
    $("#datepicker").keypress(function(event) {event.preventDefault();});
    //$("#hours").mask('#,###.00', {reverse: true});
});

</script>
</html>