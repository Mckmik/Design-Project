<?php

if(isset($_GET['cust_id'])){
    $custId = $_GET['cust_id'];

    $idExists = false;
    $existsQuery = mysqli_query($con, "SELECT cust_id FROM customers");
    while ($existsRow = mysqli_fetch_array($existsQuery)){
        if ($custId == $existsRow['cust_id']){
            $idExists = true;
        }
    }

    if($idExists == false){
        header("location: customer_does_not_exists.php");
    }

    $query = mysqli_query($con, "SELECT * FROM customers WHERE cust_id='$custId'");
    $row = mysqli_fetch_array($query);

    $firstName = $row['first_name'];
    $lastName = $row['last_name'];
    $company = $row['company_name'];
    $email = $row['cust_email'];
    $phone = $row['cust_phone'];
    $street = $row['street'];
    $city = $row['city'];
    $state = $row['state'];
    $zip = $row['zip'];
    $isDeleted = $row['is_deleted'];
    $companyDisplay = $company;

    $openOrder = false;
    $ordersQuery = mysqli_query($con, "SELECT * FROM work_orders WHERE cust_id='$custId'");
    if (mysqli_num_rows($ordersQuery) >0){
        while ($newRow = mysqli_fetch_array($ordersQuery)){
            if ($newRow['is_complete'] =="no"){
                $openOrder = true;
            }
        }
    }

    if ($company == ""){
        $companyDisplay = "<i>Residence</i>";
    }

    if ($isDeleted == "yes"){
        $status = "Inactive";
        echo '
        <script>

        $(document).ready(function() {
            $("#deactivate").hide();
            $("#activate").show();
        });

        </script>

        ';
    } else {
        $status = "Active";
        echo '
        <script>

        $(document).ready(function() {
            $("#activate").hide();
            $("#deactivate").show();
        });

        </script>

        ';
    }
    if ($openOrder){
        echo '
        <script>

        $(document).ready(function() {
            $("#deactivate").hide();
        });

        </script>

        ';

    }



}

//Update customer is_deleted to 'yes'
if (isset($_POST['deactivate'])){
    $query = mysqli_query($con, "UPDATE customers SET is_deleted='yes' WHERE cust_id='$custId'");
    header("location: customer_details.php?cust_id=".$custId);
}

//Update customer is_deleted to 'no'
if (isset($_POST['activate'])){
    $query = mysqli_query($con, "UPDATE customers SET is_deleted='no' WHERE cust_id='$custId'");
    header("location: customer_details.php?cust_id=".$custId);
}

//edit and save basic info
if (isset($_POST['save_basic'])){
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $company = $_POST['company'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $firstName = strip_tags($firstName);
    $firstName = mysqli_real_escape_string($con, $firstName);
    $lastName = strip_tags($lastName);
    $lastName = mysqli_real_escape_string($con, $lastName);
    $company = strip_tags($company);
    $company = mysqli_real_escape_string($con, $company);
    $email = strip_tags($email);
    $email = str_replace(' ', '', $email);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);



    $query = mysqli_query($con, "UPDATE customers 
        SET  
        first_name='$firstName',
        last_name='$lastName',
        company_name='$company',
        cust_email='$email',
        cust_phone='$phone'
        WHERE cust_id='$custId'");
    header("location: customer_details.php?cust_id=".$custId);

}

//edit and save address info
if (isset($_POST['save_address'])){
    $street = $_POST['street'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip = $_POST['zip'];

    $street = strip_tags($street);
    $street = mysqli_real_escape_string($con, $street);
    $city = strip_tags($city);
    $city = mysqli_real_escape_string($con, $city);


    
    $query = mysqli_query($con, "UPDATE customers 
        SET  
        street='$street',
        city='$city',
        state='$state',
        zip='$zip'
        WHERE cust_id='$custId'");
    header("location: customer_details.php?cust_id=".$custId);
}





?>