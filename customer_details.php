<?php 
    include("includes/header.php");
    include("includes/form_handlers/customer_details_handler.php");
?>
    <div class="main_column column">
        <h2><?php echo $firstName . ' ' . $lastName; ?> - <?php echo $companyDisplay; ?></h2>
        <a href="user_manual.pdf#page=20" target="_blank" style="float: right; padding: 0 100px;">Help <i class="fa fa-question fa-lg"></i></a><br>


        <hr>
        <h5>Status</h5>
        <p><?php echo $status; ?> </p>
        <p>
            <form active="customer_details.php?emp_id=<?php echo $custId; ?>" method="POST">
            <input type="submit" class="btn btn-danger" id="deactivate" name="deactivate" value="Deactivate">
            <input type="submit" class="btn btn-success" id="activate" name="activate" value="Activate">
            </form>
        </p>


        <hr>
        <h5>Basic Information</h5>
        <form active="customer_details.php?emp_id=<?php echo $custId; ?>" method="POST">
		    <p><label>First Name:</label> <input type="text" id="first_name" name="first_name" maxlength="25" required value="<?php echo $firstName; ?>" disabled></p>
		    <p><label>Last Name:</label> <input type="text" id="last_name" name="last_name" maxlength="25" required value="<?php echo $lastName; ?>" disabled></p>
		    <p><label>Company:</label> <input type="text" id="company" name="company" maxlength="25" value="<?php echo $company; ?>" disabled></p>
		    <p><label>Email:</label> <input type="email" id="email" name="email" maxlength="50" required value="<?php echo $email; ?>" disabled></p>
		    <p><label>Phone:</label> <input type="text" id="phone" name="phone" minlength="12" maxlength="12" required value="<?php echo $phone; ?>" disabled></p>
		    <input type="submit" class="btn btn-success" id="save_basic" name="save_basic" style="display: none;" value="Save">
        </form>
        <p><button type="button" class="btn btn-primary" id="edit_basic">Edit</button></p>


        <hr>
        <h5>Address</h5>
        <form active="customer_details.php?emp_id=<?php echo $custId; ?>" method="POST">
	        <p><label>Street:</label> <input type="text" id="street" name="street" maxlength="25" pattern="[A-Za-z0-9'. ]{1,25}" title="Please any of the following characters: A-Z a-z 0-9 ' . " required value="<?php echo $street; ?>" disabled></p>
	        <p><label>City:</label> <input type="text" id="city" name="city" maxlength="25" pattern="[A-Za-z0-9'. ]{1,25}" title="Please any of the following characters: A-Z a-z 0-9 ' . " required value="<?php echo $city; ?>" disabled></p>
            <p><label>State:</label> 
            <select name="state" id="state" disabled>
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
                            <option value="MO">MO</option>	
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
        
        
        
        </p>
	        <p><label>Zipcode:</label> <input type="text" id="zip" name="zip" maxlength="5" value="<?php echo $zip; ?>" disabled></p>
	        <input type="submit" class="btn btn-success" id="save_address" name="save_address" style="display: none;" value="Save">
    	</form>
        <p><button type="button" class="btn btn-primary" id="edit_address">Edit</button></p>


	    <hr>
	    <a href="customers.php">
	        <button type="button" class="btn btn-secondary">
	        <i class="fa fa-arrow-circle-o-left fa-lg"></i> Return to Customers
	        </button>
	    </a>
        <hr>
    </div>
</div>
</body>
<script>
      $(document).ready(function(){

        $('#phone').mask("999-999-9999");
        $('#zip').mask("99999");

        $('#edit_basic').on('click', function() {

            $("#first_name").prop('disabled', false);
            $("#last_name").prop('disabled', false);
            $("#company").prop('disabled', false);
            $("#email").prop('disabled', false);
            $("#phone").prop('disabled', false);
            $("#edit_basic").hide();
            $("#save_basic").show();
        });

        $('#edit_address').on('click', function() {

            $("#street").prop('disabled', false);
            $("#city").prop('disabled', false);
            $("#state").prop('disabled', false);
            $("#zip").prop('disabled', false);
            $("#edit_address").hide();
            $("#save_address").show();
        });

      });

//To set State dropdown to stored value
var state = "<?php echo $state; ?>";
var stateSelect = document.getElementById('state');

for(var i, j = 0; i = stateSelect.options[j]; j++) {
    if(i.value == state) {
        stateSelect.selectedIndex = j;
        break;
    }
}

</script>

</html>