<?php

if (isset($_POST['save_apt_info'])){

	$customerId = $_POST['customer'];
	$date = date_create($_POST['service_date']);
	$date = date_format($date, "Y-m-d");
	$displayDate = date_create($_POST['service_date']);
	$displayDate = date_format($displayDate, "m/d/Y");
	$time = $_POST['service_time'];
	$displayTime = $time;
	$request = $_POST['request_body'];

	$_SESSION['customer'] = $customerId;
	$_SESSION['servDate'] = $date;
	$_SESSION['time'] = $time;
	$_SESSION['request'] = $request;
	if (isset($_SESSION['tech'])){
		unset($_SESSION['tech']);
	}

}

if (isset($_POST['save_tech_info'])){
	$tech = $_POST['service_tech'];
	$_SESSION['tech'] = $tech;
}


if (isset($_SESSION['customer'])){
	$customerId = $_SESSION['customer'];
	$date = date_create($_SESSION['servDate']);
	$date = date_format($date, "Y-m-d");
	$displayDate = date_create($_SESSION['servDate']);
	$displayDate = date_format($displayDate, "m/d/Y");
	$time = $_SESSION['time'];
	$displayTime = $time;
	$request = $_SESSION['request'];

}

if (isset($_SESSION['tech'])){
	$tech = $_SESSION['tech'];
}

if (isset($_POST['save_request'])){

	$customer = $_POST['customer'];
    $tech = $_POST['service_tech'];
    $date = date_create($_POST['service_date']);
    $date = date_format($date, "Y-m-d");
    $time = $_POST['service_time'];
    $body = $_POST['request_body'];

    $workOrder = new WorkOrder($con);
    $workOrder->addServiceOrder($customer, $tech, $date, $time, $body);

    unset($_SESSION['customer']);
    unset($_SESSION['servDate']);
	unset($_SESSION['time']);
	unset($_SESSION['request']);
	unset($_SESSION['tech']);

}

?>