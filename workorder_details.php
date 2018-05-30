<?php 
    include("includes/header.php");
    include("includes/form_handlers/edit_workorder_handler.php");

    if($auth != ""){
        echo '

        <script>
        $(document).ready(function() {
            $("#tabs").hide();
            $("#authorized").show();
        });
        </script>
        
        ';
    }
?>
<div class="main_column column">
<div id="first">
    <h1>Work Order #<?php echo $id?></h1>
    <a href="user_manual.pdf#page=49" target="_blank" style="float: right; padding: 0 100px;">Help <i class="fa fa-question fa-lg"></i></a><br>

    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Job Details</a></li>
            <li><a href="#tabs-2">Time &amp; Materials</a></li>
            <li><a href="#tabs-4">Work Information</a></li>
            <li><a href="#tabs-5">Confirm</a></li>

        </ul>

        <div id="tabs-1">
            
            <h3><?php echo $customer_display; ?></h3>
            <div class="request_info">
                <p><b>Service Date: 
                    <?php echo date("m/d/Y",$service_date); ?><br>
                    Time: 
                    <?php echo date("h:ia", $service_time); ?></b><br>
                    <b>Technician: 
                    </b> <?php echo $tech_name;?>
                </p><p><b>Address</b>
                    <br><?php echo $customer_street; ?>
                    <br><?php echo $customer_city . ", " . $customer_state . " " . $customer_zip; ?>
                </p>

            </div>
            <p><b>Job Information:</b> 
            <?php echo $request_body;?> &nbsp;
            </p>
            <?php if($user['is_admin']=='yes'){
                    echo '<button type="button" class="btn btn-danger" id="delete_workorder">Delete this work order?</button>';
            }?> 
        </div>




        <div id="tabs-2">
        
            <h4>Time &amp; Materials:</h4>
            <div class="order-line-grid">
                <div class="order-line-grid-item">Line</div>
                <div class="order-line-grid-item">Type</div>
                <div class="order-line-grid-item">Quantity</div>
                <div class="order-line-grid-item">Description</div>
                <div class="order-line-grid-item ln-curr">Unit Cost</div>
                <div class="order-line-grid-item ln-curr">Extended Cost</div>
                <div class="order-line-grid-item"></div>
                <?php $wo->loadOrderLines($id);?>
            </div>
            <form action="workorder_details.php?workorder_id=<?php echo $id;?>#tabs-2" method="POST">  
            <div class="order-line-grid"> 
                <div class="order-line-grid-item"><?php echo ($wo->getNextLineNumber($id));?></div>
                <div class="order-line-grid-item">
                    <select name="line_type">
                        <option value="Material">Material</option>
                        <option value="Labor">Labor</option>
                    </select>
                </div>
                <div class="order-line-grid-item"><input type="number" min="0.25"  step=".25" name="line_qty" id="qty"></div>
                <div class="order-line-grid-item"><input type="text" name="line_desc"></div>
                <div class="order-line-grid-item ln-curr"><input type="number" min="0.01" step=".01" name="line_cost" id="cost"></div>
                <div class="order-line-grid-item"></div>
                <div class="order-line-grid-item ln-curr"><button class="btn btn-success btn-sm" name="add_order_line"><i class='fa fa-plus'></i></button></div>
            </div>
            </form>
            <br>
            <div class="right_div">
            <b>Total: $<?php  echo $wo->getMaterialsTotal($id);?></b>
            </div>
        </div>




        <div id="tabs-4">
            <form action="workorder_details.php?workorder_id=<?php echo $id;?>#tabs-4" method="POST">
            <h4>Date of Completion:</h4>
            <input type="text" name="comp_date" id="datepicker" required value="<?php if(isset($complete_date)){ echo date("m/d/Y",$complete_date);} else {echo date("m/d/Y",$service_date);} ?>" readonly><br>
            <input type="submit" class="btn btn-success" name="save_comp_date" value="Save">   
            </form>
            <form action="workorder_details.php?workorder_id=<?php echo $id;?>#tabs-4" method="POST">
            <h4>Job Address:</h4><br>
            <textarea name="job_address" style="  width: 550px; height: 80px;"><?php $wo->getJobAddress($id);?></textarea><br>
            <h4>Description of work performed:</h4><br>
            <textarea name="job_description" style="  width: 550px; height: 100px;"><?php $wo->getOrderDescription($id);?></textarea><br>
            <input type="submit" class="btn btn-success" name="save_desc" value="Save">
            </form>
        </div>



        <div id="tabs-5">
           
            <div class="confirm_order_head">
                <div class="left_div">
                    <img src="assets/logos/orionelectric.png" width="194" height="45"><br>
                    <p>
                        <!--Hard-coded address, can store somewhere if preferable-->
                        84 Hubble Drive #100<br>
                        Dardenne Prairie, MO 63368<br>
                        (636) 445-3010
                    </p>
                    <h4>BILL TO:</h4>
                    <p>
                    <?php echo $customer_display; ?>
                    <br><?php echo $customer_street; ?>
                    <br><?php echo $customer_city . ", " . $customer_state . " " . $customer_zip; ?>
                    </p>


                </div>
                
                <div class="right_div">
                    <h2>ELECTRICAL</h2>
                    <h4>Work Order/Invoice #<?php echo sprintf("%05d",$id);?></h4>
                    <p>DATE OF ORDER:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b><?php echo date("m/d/Y",$service_date); ?></b></p>
                    <p>DATE OF COMPLETION: <b><?php if(isset($complete_date)){ echo date("m/d/Y",$complete_date);} ?></b></p>
                    <p>JOB ADDRESS:</p>
                    <p style="word-wrap: break-word;"><b><?php $wo->getJobAddress($id);?></b></p>

                </div>
            </div>
            <br>

            <div class="confirm_order_body">
                <h4>DESCRIPTION OF WORK</h4>
                <p style="word-wrap: break-word;"><?php $wo->getOrderDescription($id);?></p>


            </div>
            <br>

            <div class="confirm_order_body">
                <h4>LABOR</h4>
                
                <div class="confirm-labor-grid">
                    
                    <div class="order-line-grid-item"><b>LABOR</b></div>
                    <div class="order-line-grid-item"><b>HOURS</b></div>
                    <div class="order-line-grid-item ln-curr"><b>@</b></div>
                    <div class="order-line-grid-item ln-curr"><b>AMOUNT</b></div>
                    
                    <?php $wo->loadOrderLineHours($id);?>

                </div>
            </div>
            

            <div class="confirm_order_body">
                <h4>MATERIALS</h4>

                <div class="confirm-mats-grid">
                    <div class="order-line-grid-item"><b>QTY</b></div>
                    <div class="order-line-grid-item"><b>MATERIALS</b></div>
                    <div class="order-line-grid-item ln-curr"><b>@</b></div>
                    <div class="order-line-grid-item ln-curr"><b>AMOUNT</b></div>
                    <?php $wo->loadOrderLineMats($id);?>

                </div>

            </div>
            <hr>

            <div class="confirm-total-grid">
                <div></div>
                <div>TOTAL LABOR:</div>
                <div class="ln-curr">$<?php echo $wo->getHoursCostTotal($id);?></div>
                <div></div>
                <div class="order-line-grid-item" >TOTAL MATERIALS:</div>
                <div class="order-line-grid-item ln-curr">$<?php echo $wo->getMatsCostTotal($id);?></div>
                <div></div>
                <div><b>TOTAL:</b></div>
                <div class="ln-curr"><b>$<?php echo number_format(($wo->getHoursCostTotal($id) + $wo->getMatsCostTotal($id)),2);?></b></div>
            </div>
            <?php if (isset($complete_date)){
                echo'
                <div class="center_div">
                <a href="confirm_order.php?workorder_id='.$id.'"><button type="button" class="btn btn-success">CONFIRM</button></a>
                
                </div>
                ';
            }

            ?>

        </div>
    </div>
    <div id="authorized" style="display: none;">

        <a href="assets/pdfs/work_orders/wo<?php echo $id;?>.pdf" download>
        <button type="button" class="btn btn-success">Download PDF</button>
        </a>

        <button type="button" class="btn btn-primary" name="edit_authorized" id="edit_authorized">Edit</button>
        <br><br>
            <div class="confirm_order_head">
                <div class="left_div">
                    <img src="assets/logos/orionelectric.png" width="194" height="45"><br>
                    <p>
                        <!--Hard-coded address, can store somewhere if preferable-->
                        84 Hubble Drive #100<br>
                        Dardenne Prairie, MO 63368<br>
                        (636) 445-3010
                    </p>
                    <h4>BILL TO:</h4>
                    <p>
                    <?php echo $customer_display; ?>
                    <br><?php echo $customer_street; ?>
                    <br><?php echo $customer_city . ", " . $customer_state . " " . $customer_zip; ?>
                    </p>


                </div>
                
                <div class="right_div">
                    <h2>ELECTRICAL</h2>
                    <h4>Work Order/Invoice #<?php echo sprintf("%05d",$id);?></h4>
                    <p>DATE OF ORDER:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b><?php echo date("m/d/Y",$service_date); ?></b></p>
                    <p>DATE OF COMPLETION: <b><?php if(isset($complete_date)){ echo date("m/d/Y",$complete_date);} ?></b></p>
                    <p>JOB ADDRESS:</p>
                    <p style="word-wrap: break-word;"><b><?php $wo->getJobAddress($id);?></b></p>

                </div>
            </div>
            <br>

            <div class="confirm_order_body" >
                <h4>DESCRIPTION OF WORK</h4>
                <p style="word-wrap: break-word;"><?php $wo->getOrderDescription($id);?></p>


            </div>
            <br>

            <div class="confirm_order_body">
                <h4>LABOR</h4>
                
                <div class="confirm-labor-grid">
                    
                    <div class="order-line-grid-item"><b>LABOR</b></div>
                    <div class="order-line-grid-item"><b>HOURS</b></div>
                    <div class="order-line-grid-item ln-curr"><b>@</b></div>
                    <div class="order-line-grid-item ln-curr"><b>AMOUNT</b></div>
                    
                    <?php $wo->loadOrderLineHours($id);?>

                </div>
            </div>
            

            <div class="confirm_order_body">
                <h4>MATERIALS</h4>

                <div class="confirm-mats-grid">
                    <div class="order-line-grid-item"><b>QTY</b></div>
                    <div class="order-line-grid-item"><b>MATERIALS</b></div>
                    <div class="order-line-grid-item ln-curr"><b>@</b></div>
                    <div class="order-line-grid-item ln-curr"><b>AMOUNT</b></div>
                    <?php $wo->loadOrderLineMats($id);?>

                </div>

            </div>
            <hr>

            <div class="confirm-total-grid">
                <div></div>
                <div>TOTAL LABOR:</div>
                <div class="ln-curr">$<?php echo $wo->getHoursCostTotal($id);?></div>
                <div></div>
                <div class="order-line-grid-item" >TOTAL MATERIALS:</div>
                <div class="order-line-grid-item ln-curr">$<?php echo $wo->getMatsCostTotal($id);?></div>
                <div></div>
                <div><b>TOTAL:</b></div>
                <div class="ln-curr"><b>$<?php echo number_format(($wo->getHoursCostTotal($id) + $wo->getMatsCostTotal($id)),2);?></b></div>
            </div>
            <b>SIGNATURE</b><br>
            <?php
            echo '
            <img src="assets/images/signatures/wo'.$id.'.png?dummy='.rand(1000000, 9999999).'" width="358" height="60"><br>
            ';?>
    </div>
</div>

<div id="second">
    <p>This job is not assigned to you!</p>
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
    $("#datepicker").datepicker({ minDate: "<?php echo date("m/d/Y", $service_date);?>"});
    $("#datepicker").mask("00/00/0000");
    $("#datepicker").keypress(function(event) {event.preventDefault();});
});

$(document).ready(function(){
    $('#delete_workorder').on('click', function() {
        if(confirm("Are you sure you want to delete this work order?")){
            console.log("yes");
            location.assign("workorder_details.php?workorder_id=<?php echo $id;?>&delete_order=yes");


        }else{
            console.log("no");
        }
    });
});
$(document).ready(function(){
    $('#edit_authorized').on('click', function() {
        if(confirm("Are you sure you want to edit this work order? Doing so will delete any previous versions or signatures.")){
            console.log("yes");
            location.assign("workorder_details.php?workorder_id=<?php echo $id;?>&edit_order=yes");


        }else{
            console.log("no");
        }
    });
});
</script>
</html>