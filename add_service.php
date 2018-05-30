<?php
    include("includes/header.php");
    include("includes/form_handlers/add_service_handler.php");

    if(isset($_POST['save_request'])) {
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
        <h1>Add New Service Request</h1>
        <a href="user_manual.pdf#page=47" target="_blank" style="float: right; padding: 0 100px;">Help <i class="fa fa-question fa-lg"></i></a><br>

        <div id="tabs">
            <ul>
                <li><a href="#tabs-1">Appointment</a></li>
                <li><a href="#tabs-2">Technician</a></li>
                <li><a href="#tabs-3">Confirm</a></li>
            </ul>

            <div id="tabs-1">
                <h4>Appointment Information</h4>
                <form action="add_service.php#tabs-2" method="POST">
                    <p><label>Customer:</label>

                        <select id="customer" name="customer" required>
                            <option value="">Select a customer</option>
                            <?php
                            $customer = new Customer($con);
                            $customer->dropdownLoadCustomers();
                            ?>
                        </select>

                        &nbsp;&nbsp;&nbsp;OR&nbsp;&nbsp;&nbsp;
                        <a href="add_customer.php">
                            <button type="button" class="btn btn-success">
                                <i class="fa fa-plus fa-lg"></i> New Customer
                            </button>
                        </a>
                    </p>
                    <p><label>Date of Service:</label>&nbsp;<input type="text" name="service_date" id="datepicker" value="<?php 
                    if (isset($_SESSION['servDate'])){
                        echo $displayDate;
                    }else{
                        echo date('m/d/Y');
                    }?>" readonly>
                    </p>
                    <p><label>Time of Service:</label>&nbsp;<input type="time" name="service_time" value="<?php

                    if (isset($_SESSION['time'])){
                        echo $displayTime;
                    }else{
                        echo '10:00';
                    }?>">
                    </p>
                    <p><label>Request:</label>&nbsp;<textarea name="request_body" required><?php
                            if(isset($_SESSION['request'])){
                                echo $request;
                            } 
                        ?></textarea></p>
                    <p><label></label><input type="submit" class="btn btn-success" name="save_apt_info" value="Next"></p>

                </form>              
            </div>

            <div id="tabs-2">
                <h4>Assign a Technician</h4>
                <?php 
                    if (isset($date) && isset($time)){
                        
                        echo '
                        <form action="add_service.php#tabs-3" method="POST">

                            <p><label>Technician:</label>
                                <select id="service_tech" name="service_tech" required>
                                <option value="">Select a technician</option>
                                    ';

                                        $emp = new Employee($con);
                                        echo $emp->dropdownLoadServiceTechs($date, $time)
                                    .'
                                </select>
                            </p>
                            <p><label></label><input type="submit" class="btn btn-success" name="save_tech_info" value="Next"></p>
                        </form>

                        ';

                    } else {
                        echo "<p><b><i>Please select a Date and Time for service</i></b></p>";

                    }
                ?>





            </div>

            <div id="tabs-3">
                <h4>Confirm Appointment</h4>
                <?php
                    if (isset($customerId) & isset($tech)){
                        

                        echo '<form action="add_service.php" method="POST">
                            <p><label>Customer: </label> <select disabled>
                            <option value="'. $customerId .'">';
                        $cust = new Customer($con);
                        echo $cust->getCustomerName($customerId) .'</option></select>
                            </p>
                            <p><label>Date: </label> <input type="text" id="datepicker" value="' . $displayDate . '" disabled></p>
                            <p><label>Time: </label> <input type="time" value="'. $displayTime . '" disabled></p>
                            <p><label>Technician: </label> <select disabled>
                            <option value="'. $tech .'">';
                        $emp = new Employee($con);
                        echo $emp->getEmployeeName($tech) .'</option></select></p>
                            <p><label>Request: </label> <textarea name="request_body" disabled>'. $request .'</textarea></p>

                            <input type="hidden" name="customer"  name="" value="'.$customerId.'">
                            <input type="hidden" name="service_date"  name="" value="'.$displayDate.'">
                            <input type="hidden" name="service_time" name="" value="'.$displayTime.'">
                            <input type="hidden" name="service_tech" name="" value="'.$tech.'">
                            <input type="hidden" name="request_body" name="" value="'.$request.'">

                            <p><label></label><input type="submit" class="btn btn-success" name="save_request" value="Save new appointment">
                            </form>

                        ';


                    } else {
                        echo "<p><b><i>Please complete the 'Appointment' and 'Technician' tabs before continuing</i></b></p>";
                    }


                ?>




            </div>

        </div>
    </div>
    <div id="second">
            <h1>Service Request added!</h1>
    </div>
    <hr>
    <a href="workorders.php">
        <button type="button" class="btn btn-secondary">
            <i class="fa fa-arrow-circle-o-left fa-lg"></i> Return to Work Orders
        </button>
    </a>
    <hr>
</div>
</div>
</body>
<script>


$(function() {
    $("#tabs").tabs();
});

$(function() {
    $("#datepicker").datepicker({ minDate: -0});
    $("#datepicker").mask("00/00/0000");
    $("#datepicker").keypress(function(event) {event.preventDefault();});
});

<?php
if (isset($_SESSION['customer'])){


    echo "
        var cust = '".$customerId."';
        var custSelect = document.getElementById('customer');

        for(var i, j = 0; i = custSelect.options[j]; j++) {
            if(i.value == cust) {
                custSelect.selectedIndex = j;
                break;
            }
        }
    ";

}
if (isset($_SESSION['tech'])){


    echo "
        var tech = '".$tech."';
        var techSelect = document.getElementById('service_tech');

        for(var i, j = 0; i = techSelect.options[j]; j++) {
            if(i.value == tech) {
                techSelect.selectedIndex = j;
                break;
            }
        }
    ";

}
?>

</script>
</html>
