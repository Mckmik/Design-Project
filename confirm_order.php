<?php 
    include("includes/header.php");
    include("includes/form_handlers/edit_workorder_handler.php");
?>
<div class="main_column column">
	<h1>Customer Authorizaion</h1>
    <a href="user_manual.pdf#page=45" target="_blank" style="float: right; padding: 0 100px;">Help <i class="fa fa-question fa-lg"></i></a><br>
	<hr>
	<div id="first">
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
            <p><?php $wo->getOrderDescription($id);?></p>


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
        <br>
        <br>


		<div class="description" style="padding: 0 20px;"><b>I hereby acknowledge the satisfactory completion of the above described work.</b></div>
		<div id="signature-pad" class="signature-pad" style="margin: 0 auto;" >
			<div class="signature-pad--body" style="margin: 0 auto;">
				<canvas style=" padding-left: 0;
								padding-right: 0;
								margin-left: auto;
								margin-right: auto;
								display: block; 
								border: solid; 
								border-width: 2px; 
								height: 104px; 
								width: 95%; ">
				</canvas>
			</div>
			<div class="signature-pad--footer">
				<div class="description" style="padding: 2px; text-align: center;"><b>SIGNATURE</b></div>
				<div class="signature-pad--actions">
					<div>
            <form id="sig_form" action="confirm_order.php?workorder_id=<?php echo $id;?>" method="POST">
              <input type="hidden" id="signature_uri" name="signature_uri">
              <input type="checkbox" name="email" checked> Send Email?
            </form>
						<button type="button" class="button clear btn btn-primary" data-action="clear">Clear</button>
                        <button type="button" class="button btn btn-primary" data-action="undo">Undo</button>
            <input form="sig_form" type="submit" class="button save btn btn-primary" data-action="save-png" value="Authorize">

					</div>
				</div>
			</div>
		</div>

    </div>  

	<hr>
	<a href="workorder_details.php?workorder_id=<?php echo $id;?>">
	    <button type="button" class="btn btn-secondary">
	    <i class="fa fa-arrow-circle-o-left fa-lg"></i> Return to Order Details
	    </button>
	</a>
  <hr>

</div>
</div>
</body>
<script>

var wrapper = document.getElementById("signature-pad");
var clearButton = wrapper.querySelector("[data-action=clear]");
var undoButton = wrapper.querySelector("[data-action=undo]");
var savePNGButton = wrapper.querySelector("[data-action=save-png]");
var canvas = wrapper.querySelector("canvas");
var signaturePad = new SignaturePad(canvas, {
  // It's Necessary to use an opaque color when saving image as JPEG;
  // this option can be omitted if only saving as PNG or SVG
  backgroundColor: 'rgb(255, 255, 255)'
});

// Adjust canvas coordinate space taking into account pixel ratio,
// to make it look crisp on mobile devices.
// This also causes canvas to be cleared.
function resizeCanvas() {
  // When zoomed out to less than 100%, for some very strange reason,
  // some browsers report devicePixelRatio as less than 1
  // and only part of the canvas is cleared then.
  var ratio =  Math.max(window.devicePixelRatio || 1, 1);

  // This part causes the canvas to be cleared
  canvas.width = canvas.offsetWidth * ratio;
  canvas.height = canvas.offsetHeight * ratio;
  canvas.getContext("2d").scale(ratio, ratio);

  // This library does not listen for canvas changes, so after the canvas is automatically
  // cleared by the browser, SignaturePad#isEmpty might still return false, even though the
  // canvas looks empty, because the internal data of this library wasn't cleared. To make sure
  // that the state of this library is consistent with visual state of the canvas, you
  // have to clear it manually.
  signaturePad.clear();
}

// On mobile devices it might make more sense to listen to orientation change,
// rather than window resize events.
window.onresize = resizeCanvas;
resizeCanvas();


clearButton.addEventListener("click", function (event) {
  signaturePad.clear();
});

undoButton.addEventListener("click", function (event) {
  var data = signaturePad.toData();

  if (data) {
    data.pop(); // remove the last dot or line
    signaturePad.fromData(data);
  }
});

savePNGButton.addEventListener("click", function (event) {
  if (signaturePad.isEmpty()) {
    alert("Please provide a signature first.");
  } else {
    var dataURL = signaturePad.toDataURL();
    $('#signature_uri').prop("value", dataURL);
  }
});

</script>
</html>
