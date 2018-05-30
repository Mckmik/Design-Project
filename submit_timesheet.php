<?php 
    include("includes/header.php");
    include("includes/form_handlers/submit_timesheet_handler.php");
?>
    <div class="main_column column">
    <h1>Submit Timesheet</h1>
		<a href="user_manual.pdf#page=60" target="_blank" style="float: right; padding: 0 100px;">Help <i class="fa fa-question fa-lg"></i></a><br>
    <div id="first">
		<?php 
		$timesheet = new Timesheet($con);
		$timesheet->loadTimesheet($empId, $timesheetId);
		?>
		<br>
		<br>
	    <div class="description" style="padding: 0 20px;"><b>I hereby acknowledge the accuracy of all hours worked stated on this timesheet.</b></div>
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
	    				<form id="sig_form" action="submit_timesheet.php?timesheet_id=<?php echo $timesheetId.$admin_view; ?>" method="POST">
	      					<input type="hidden" id="signature_uri" name="signature_uri">  
	   	 				</form>
						<button type="button" class="button clear btn btn-primary" data-action="clear">Clear</button>
						<button type="button" class="button btn btn-primary" data-action="undo">Undo</button>
	    				<input form="sig_form" type="submit" class="button save btn btn-primary" data-action="save-png" value="Authorize">
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="second">
		<h3> Timesheet Submitted!</h3>
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