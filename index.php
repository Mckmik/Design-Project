
<?php 
    include("includes/header.php");

    $userObj = new User($con, $userLoggedIn);
?>
    <div class="main_column column">
    <h1>Hello <?php echo $userObj->getFirstAndLastName(); ?>!</h1>
    <br>
    
    <a href="user_manual.pdf" target="_blank" style="float: right; padding: 0 100px;">Help <i class="fa fa-question fa-lg"></i></a>
    <br>
    <br>
    <div class="home-options" style="text-align:center;">
    <a href="timesheets.php">
        <button class="btn btn-primary timesheet-button">Timesheets</button>
    </a>
    <?php 
    if (($user['is_tech'] == "yes")||($user['is_admin'] == "yes")){
        echo '
        &nbsp;&nbsp;&nbsp;&nbsp;
        <a href="workorders.php">
            <button class="btn btn-success timesheet-button">Work Orders</button>
        </a>
        ';
    }
    if ($user['is_admin'] == "yes"){
        echo '
        &nbsp;&nbsp;&nbsp;&nbsp;
        <a href="reports.php">
            <button class="btn btn-warning timesheet-button">Reports</button>
        </a>
        
        ';
    }
    ?>

    </div>

    <!--
    <form>
    <h4>
        Customer Lookup:  
        <select name="users" onchange="showUser(this.value); this.size=0;" onmousedown="if(this.options.length>8){this.size=8;}" onblur="this.size=0;" style="position: absolute;">
            <option value="">Select a customer</option>
            <?php
            $customer = new Customer($con);
            $customer->dropdownLoadCustomers();
            ?>

        </select>
       
    </h4>
-->


    
    </form>
    <div id="txtHint"><b></b></div>
    



    <hr>
   </div>
</div>


<script>

//Loads get_customer.php for selected customer
function showUser(str) {
    if (str == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","includes/handlers/get_customer.php?q="+str,true);
        xmlhttp.send();
    }
}
</script>
</body>
</html>