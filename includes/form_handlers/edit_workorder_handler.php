<?php
//use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\Exception;

$wo = new WorkOrder($con);

 if(isset($_GET['workorder_id'])){
    $id = $_GET['workorder_id'];

    $idExists = false;
    $existsQuery = mysqli_query($con, "SELECT wo_id FROM work_orders");
    while ($existsRow = mysqli_fetch_array($existsQuery)){
        if ($id == $existsRow['wo_id']){
            $idExists = true;
        }
    }

    
    
    $workorder_details_query = mysqli_query($con, "SELECT * FROM work_orders WHERE wo_id='$id'");
    $workorder_array = mysqli_fetch_array($workorder_details_query);


    $cust_id = $workorder_array['cust_id'];
    $tech_id = $workorder_array['tech_id'];
    $service_date = strtotime($workorder_array['service_date']);
    $service_time = strtotime($workorder_array['service_time']);
    $compDate = $workorder_array['complete_date'];
    if ($compDate != "0000-00-00") {
        $complete_date = strtotime($compDate);
    }
    $request_body = $workorder_array['request_body'];
    $auth = $workorder_array['authorization'];


    $customer_details_query = mysqli_query($con, "SELECT * FROM customers WHERE cust_id='$cust_id'");
    $customer_array = mysqli_fetch_array($customer_details_query);
    $customer_first_name = $customer_array['first_name'];
    $customer_last_name = $customer_array['last_name'];
    $customer_company = $customer_array['company_name'];
    $customer_street = $customer_array['street'];
    $customer_city = $customer_array['city'];
    $customer_state = $customer_array['state'];
    $customer_zip = sprintf("%05d",$customer_array['zip']);
    $customerEmail = $customer_array['cust_email'];
    

    if ($customer_company ==""){
        $customer_display = $customer_first_name . " " . $customer_last_name;
    } else {
        $customer_display = $customer_company;
    }

    $tech_details_query = mysqli_query($con, "SELECT * FROM users WHERE user_id='$tech_id'");
    $tech_array = mysqli_fetch_array($tech_details_query);
    $tech_name = $tech_array['first_name'] . " " . $tech_array['last_name'];


    if($user['user_id']!=$tech_id && $user['is_admin']=='no') {
        echo '
        <script>

        $(document).ready(function() {
            $("#first").hide();
            $("#second").show();
        });

        </script>

        ';
    }
    if($idExists == false){
        header("location: order_no_longer_exists.php");
    }
}

if(isset($_GET['delete_order'])){
    if($_GET['delete_order']=="yes"){
        $query = mysqli_query($con, "UPDATE work_orders SET is_deleted='yes' WHERE wo_id='$id'");
        header("location: order_deleted.php");
    }
}

if(isset($_GET['edit_order'])){
    if($_GET['edit_order']=="yes"){
        $query = mysqli_query($con, "UPDATE work_orders SET authorization='', is_complete='no' WHERE wo_id='$id'");
        header("location: workorder_details.php?workorder_id=".$id);
    }
}

if(isset($_GET['delete_line'])){
    if($_GET['delete_line']=="yes"){
        $line =$_GET['line_num'];
        $query = mysqli_query($con, "DELETE FROM work_order_lines WHERE wo_id='$id' AND ol_id='$line'");
        $q= mysqli_query($con,"UPDATE work_order_lines SET ol_id=ol_id-1 WHERE wo_id='$id' AND ol_id > '$line'");
        header("location: workorder_details.php?workorder_id=".$id."#tabs-2");
    }
}


if(isset($_POST['save_desc'])){
    $desc = $_POST['job_description'];
    $jobAdd = $_POST['job_address'];

    $wo->addDesc($id, $desc);
    $wo->addJobAdd($id, $jobAdd);
}
if (isset($_POST['save_comp_date'])){
    $compleDate = date_create($_POST['comp_date']);
    $compleDate = date_format($compleDate, "Y-m-d");

    $q = mysqli_query($con, "UPDATE work_orders SET complete_date='$compleDate' WHERE wo_id='$id'");
    header("location: workorder_details.php?workorder_id=". $id."#tabs-4");

}


if (isset($_POST['add_order_line'])){

    $lineNum = $wo->getNextLineNumber($id);
    $lineType = $_POST['line_type'];
    $lineQty = $_POST['line_qty'];
    $lineDesc = $_POST['line_desc'];
    $lineCost = $_POST['line_cost'];


    $wo->addOrderLine($id, $lineNum, $lineType, $lineQty, $lineDesc, $lineCost);


}

if (isset($_POST['signature_uri'])){
    $data_uri = $_POST['signature_uri'];
    $encoded_image = explode(",", $data_uri)[1];
    $decoded_image = base64_decode($encoded_image);
    file_put_contents("assets/images/signatures/wo".$id.".png", $decoded_image);
    $emHead = "";
    if (isset($_POST['email'])){
        $emHead = "&send=yes";
    }

    //used to send post to invoice.php
    $url = 'http://localhost/orion/test/includes/handlers/invoice.php'; //change in production
    $data = array('id' => $id);

    // use key 'http' even if you send the request to https://...
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === FALSE) { /* Handle error */ }

    var_dump($result);

	if ($result == false){

        $pdfLocation = "assets/pdfs/work_orders/wo".$id.".pdf";

	    $query = mysqli_query($con, "UPDATE work_orders SET authorization='$pdfLocation', is_complete='yes' WHERE wo_id='$id'");

	    header("location: complete_order.php?order_id=".$id.$emHead);		
	}


}



?>