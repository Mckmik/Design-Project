<?php 
    include("includes/header.php");
    include("includes/form_handlers/add_customer_handler.php");

    $origin = "";

    if(isset($_POST['save_customer'])) {
        echo '
        <script>
        $(document).ready(function() {
            $("#first").hide();
            $("#second").show();
        });
        </script>';
    }
    if (isset($_GET['from_cust'])){
        $origin = "?from_cust=true";
        echo '
        <script>
        $(document).ready(function() {
            $("#workorders").hide();
            $("#customers").show();
        });
        </script>';
    }
    if (isset($_GET['from_jobs'])){
        $origin = "?from_jobs=true";
        echo '
        <script>
        $(document).ready(function() {
            $("#workorders").hide();
            $("#jobs").show();
        });
        </script>';
    }
?>
    <div class="main_column column">
        <div id="first">
            <center><h1>Add New Customer</h1>
            <a href="user_manual.pdf#page=17" target="_blank" style="float: right; padding: 0 100px;">Help <i class="fa fa-question fa-lg"></i></a><br>

            <form action="add_customer.php<?php echo $origin;?>" method="POST">
                <table>
                <tr><td>First Name</td><td> <input type="text" id="first_name" name="first_name" maxlength="25" required></input></td></tr>
                <tr><td>Last Name</td><td> <input type="text" name="last_name" maxlength="25" required></input></td></tr>
                <tr><td>Company Name</td><td> <input type="text" name="company" maxlength="25"></input></td></tr>
                <tr><td>Email</td><td> <input type="email" name="cust_email" maxlength="50" required></input></td></tr>
                <tr><td>Phone</td><td> <input type="tel" id="phone" minlength="12" maxlength="12" name="cust_phone" required></input></td></tr>
                <tr><td>Address</td><td> <input type="text" name="street" maxlength="25" pattern="[A-Za-z0-9'. ]{1,25}" title="Please any of the following characters: A-Z a-z 0-9 ' . " required></input></td></tr>
                <tr><td>City</td><td> <input type="text" name="city" maxlength="25" pattern="[A-Za-z0-9'. ]{1,25}" title="Please any of the following characters: A-Z a-z 0-9 ' . " required></input></td></tr>
                <tr><td>State</td>
                    <td> 
                        <select name="state">
                            <option value="AL">AL</option>
                            <option value="AK">AK</option>
                            <option value="AR">AR</option>	
                            <option value="AZ">AZ</option>
                            <option value="CA">CA</option>
                            <option value="CO">CO</option>
                            <option value="CT">CT</option>
                            <option value="DC">DC</option>
                            <option value="DE">DE</option>
                            <option value="FL">FL</option>
                            <option value="GA">GA</option>
                            <option value="HI">HI</option>
                            <option value="IA">IA</option>	
                            <option value="ID">ID</option>
                            <option value="IL">IL</option>
                            <option value="IN">IN</option>
                            <option value="KS">KS</option>
                            <option value="KY">KY</option>
                            <option value="LA">LA</option>
                            <option value="MA">MA</option>
                            <option value="MD">MD</option>
                            <option value="ME">ME</option>
                            <option value="MI">MI</option>
                            <option value="MN">MN</option>
                            <option selected="selected" value="MO">MO</option>	
                            <option value="MS">MS</option>
                            <option value="MT">MT</option>
                            <option value="NC">NC</option>	
                            <option value="NE">NE</option>
                            <option value="NH">NH</option>
                            <option value="NJ">NJ</option>
                            <option value="NM">NM</option>			
                            <option value="NV">NV</option>
                            <option value="NY">NY</option>
                            <option value="ND">ND</option>
                            <option value="OH">OH</option>
                            <option value="OK">OK</option>
                            <option value="OR">OR</option>
                            <option value="PA">PA</option>
                            <option value="RI">RI</option>
                            <option value="SC">SC</option>
                            <option value="SD">SD</option>
                            <option value="TN">TN</option>
                            <option value="TX">TX</option>
                            <option value="UT">UT</option>
                            <option value="VT">VT</option>
                            <option value="VA">VA</option>
                            <option value="WA">WA</option>
                            <option value="WI">WI</option>	
                            <option value="WV">WV</option>
                            <option value="WY">WY</option>
                        </select>	
                    </td>
                </tr>
                <tr><td>Zipcode</td><td> <input type="text" id="zip" name="zip" minlength="5" maxlength="5" required></input></td></tr>
                </table>
                <input type="submit" class="btn btn-success" name="save_customer" id="save_customer" value="Add Customer">
            </form>
            </center>
        </div>
        <div id="second">
            <h1>Customer added!</h1>
        </div>
        <hr>
        <a href="workorders.php" id="workorders">
            <button type="button" class="btn btn-secondary">
                <i class="fa fa-arrow-circle-o-left fa-lg"></i> Return to Work Orders
            </button>
        </a>
        <a href="customers.php" style="display: none;" id="customers">
            <button type="button" class="btn btn-secondary">
            <i class="fa fa-arrow-circle-o-left fa-lg"></i> Return to Customers
            </button>
        </a>
        <a href="jobs.php" style="display: none;" id="jobs">
            <button type="button" class="btn btn-secondary">
            <i class="fa fa-arrow-circle-o-left fa-lg"></i> Return to Jobs
            </button>
        </a>
        <hr>
    </div>
</div>
</body>
<script>
    

    $("#phone").mask("999-999-9999");
    $("#zip").mask("99999");

    
</script>

</html>