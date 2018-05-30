

//Modal ajax
/*
$(document).ready(function() {

	//Button for profile post
	$('#save_customer').click(function(){
		
		$.ajax({
			type: "POST",
			url: "includes/handlers/ajax_test_handler.php",
			data: $('form.customer_add').serialize(),
			success: function(msg) {
				$("#post_form").modal('hide');
				location.reload();
			},
			error: function() {
				alert('Failure');
            }

            
        });
        console.log("Serial is: " + $('form.customer_add').serialize());
        
    });
    console.log("Serial is: " + $('form.customer_add').serialize());

});
*/