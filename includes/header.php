<?php

    require 'config/config.php';
	require 'lib/fpdf/fpdf.php';
	require 'lib/phpMailer/PHPMailer.php';
	require 'lib/phpMailer/SMTP.php';
	require 'lib/phpMailer/POP3.php';
	require 'lib/phpMailer/Exception.php';
    include("includes/classes/User.php");
    include("includes/classes/Customer.php");
	include("includes/classes/WorkOrder.php");
	include("includes/classes/Employee.php");
	include("includes/classes/Timesheet.php");
	include("includes/classes/Job.php");

    if (isset($_SESSION['username'])){
        $userLoggedIn = $_SESSION['username'];
		$user_details_query = mysqli_query($con,"SELECT * FROM users WHERE username='$userLoggedIn' AND is_deleted='no'");
		if(mysqli_num_rows($user_details_query)==1){
			$user = mysqli_fetch_array($user_details_query);
		}else{
			unset($_SESSION['username']);
			header("Location: login.php");
		}
    }
    else {
        header("Location: login.php");
    }
?>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<head>
    <title>TEOS</title>

    <!--JS-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/bootbox.min.js"></script>
	<script src="assets/js/oriontest.js"></script>
	<script src="assets/js/jquery-ui.min.js"></script>
	<script src="assets/js/jquery.mask.js"></script>


    <!-- CSS -->
	<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<link rel="stylesheet" type="text/css" href="assets/css/jquery-ui.min.css">


</head>
<?php 
if($user['is_admin'] == "yes"){
	echo '
	<script>
		$(document).ready(function() {
			$("#emp_nav").show();
			$("#rep_nav").show();
			$("#cust_nav").show()
			$("#workorder_nav").show();
			$("#mobile_emp_nav").show();
			$("#mobile_rep_nav").show();
			$("#mobile_cust_nav").show()
			$("#mobile_workorder_nav").show();
		});
	</script>

	';   	
}
if($user['is_tech'] == "yes"){
	echo '
	<script>
		$(document).ready(function() {
			$("#workorder_nav").show();
			$("#mobile_workorder_nav").show();
		});
	</script>

	';     	
}
?>

<body>
	<div class="user_logged">
		Logged in as: <?php echo $user['first_name'] .' ' . $user['last_name'];?>&nbsp;&nbsp;
		<a href="user_manual.pdf" target="_blank" style="float: right; padding: 0 10px; color: #fff; ">Help <i class="fa fa-question fa-lg"></i></a>
	</div>
    <div class="top_bar">
		<div class="logo">
			<a href="index.php"><img src="assets/logos/orionelectric.png"></a>
		</div>

		<nav id="desktop">
			<ul>
			<a href="index.php">
				Home <i class="fa fa-home fa-lg"></i>
			</a>
			<a href="timesheets.php">		
				Timesheets <i class="fa fa-clock-o fa-lg"></i>
            </a>
			<a href="workorders.php" id="workorder_nav" style="display: none;">		
				Work Orders <i class="fa fa-pencil-square-o fa-lg"></i>
			</a>	            
			<a href="employees.php" id="emp_nav" style="display: none;">		
				Employees <i class="fa fa-users fa-lg"></i>
			</a>
            <a href="customers.php" id="cust_nav" style="display: none;">		
				Customers <i class="fa fa-list-alt fa-lg" ></i>
			</a>
            <a href="reports.php" id="rep_nav" style="display: none;">		
				Reports <i class="fa fa-book fa-lg" ></i>
			</a>
			<a href="settings.php">		
				Settings <i class="fa fa-cogs fa-lg"></i>
			</a>
			<a href="includes/handlers/logout.php">		
				Logout <i class="fa fa-sign-out fa-lg"></i>
			</a>
		</ul>	
		</nav>
		
		<!-- DANNYS HAMBURGER-->
		<nav id="mobile" >
			<div id="menuToggle">
				<input type="checkbox" />
					<span></span>
					<span></span>
					<span></span>
					<ul id= "menu">
					<li><a href="index.php">
						Home <i class="fa fa-home fa-lg"></i></a></li>
					<li><a href="timesheets.php">		
						Timesheets <i class="fa fa-clock-o fa-lg"></i></a></li>
					<li><a href="workorders.php" id="mobile_workorder_nav" style="display: none;">		
						Work Orders <i class="fa fa-pencil-square-o fa-lg"></i></a>	</li>            
					<li><a href="employees.php" id="mobile_emp_nav" style="display: none;">Employees <i class="fa fa-users fa-lg"></i>
					</a></li>
					<li><a href="customers.php" id="mobile_cust_nav" style="display: none;">		
						Customers <i class="fa fa-list-alt fa-lg" ></i>
					</a></li>
					<li><a href="reports.php" id="mobile_rep_nav" style="display: none;">		
						Reports <i class="fa fa-book fa-lg" ></i>
					</a></li>
					<li><a href="settings.php">		
						Settings <i class="fa fa-cogs fa-lg"></i>
					</a></li>
					<li><a href="includes/handlers/logout.php">		
						Logout <i class="fa fa-sign-out fa-lg"></i></a></li>
				</ul>
			</div>
		</nav>
		<!--END DANNYS HAMBURGER-->

	</div>

	<div class="wrapper">