<?php

$fname= "";
$lname="";
$street="";
$city="";
$state="";
$zip="";
$company="";

if(isset($_POST['save_customer'])){

    $fname = $_POST['first_name'];
    $fname = strip_tags($fname);

    $lname = $_POST['last_name'];
    $fname = strip_tags($fname);


    $street = $_POST['street'];
    $street = strip_tags($street);

    $city = $_POST['city'];
    $city = strip_tags($city);

    $state = $_POST['state'];


    $zip = $_POST['zip'];
    $zip = strip_tags($zip);

    $company = $_POST['company'];
    $company = strip_tags($company);

    $email = $_POST['cust_email'];
    $email = strip_tags($email);
    $email = str_replace(' ', '', $email);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);

    $phone = $_POST['cust_phone'];


    $customer = new Customer($con);
    $customer->createCustomer($company, $email, $phone, $fname, $lname, $street, $city, $state, $zip, date("Y-m-d"), date("H:i:s"));


}


?>