<?php 
//require '../../config/config.php';

  

	/*if(isset($_GET['workorder_id'])){
		$workorder_id = $_GET['workorder_id'];
    }*/

	if(isset($_POST['result'])) {
		if($_POST['result'] == 'true') {
			$query = mysqli_query($con, "UPDATE work_orders SET is_deleted='yes' WHERE id='$workorder_id'");
		}
    }



?>




<script> 
console.log("test")
</script>