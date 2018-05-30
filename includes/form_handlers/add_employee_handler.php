<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$errorArray = array(); //Holds error messages

$u = new User($con, $userLoggedIn);

if(isset($_POST['save_employee'])){
    $empNum = $_POST['emp_num']; 
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $empUsername = $_POST['emp_username'];
    $empPassword = $u->generateRandomString();
    $empEmail = $_POST['email'];
    $empPhone = $_POST['phone'];


    if(isset($_POST['is_tech'])){
        $isTech = "yes";
    } else {
        $isTech = "no";
    }
    if(isset($_POST['is_admin'])){
        $isAdmin = "yes";
    } else {
        $isAdmin = "no";
    }

    //Salt and hash password
    $salt = rand(1000000, 9999999);
    for($i = 0; $i < 1000; $i++){
        $empPassword = md5($salt . $empPassword);
    }

    $numChk = mysqli_query($con, "SELECT emp_num FROM users WHERE emp_num='$empNum'");
    $numR = mysqli_num_rows($numChk);
    if ($numR > 0){
        array_push($errorArray, "Employee number is already in use");
    }

    $userChk = mysqli_query($con, "SELECT username FROM users WHERE username='$empUsername'");
    $numRows = mysqli_num_rows($userChk);
    if ($numRows > 0){
        array_push($errorArray, "Username is already in use");
    }

    if (($numR==0) && ($numRows==0)){
        $emp = new Employee($con);
        $uId = $emp->addNewEmployee($empNum, $firstName, $lastName, $empUsername, $empPassword, $empEmail, $empPhone, $isTech, $isAdmin, $salt);
        $saved = true;

        $query = mysqli_query($con, "SELECT * FROM users WHERE user_id='$uId'");
        if (mysqli_num_rows($query)==1){
            $row = mysqli_fetch_array($query);
            $fname = $row['first_name'];
            $lname = $row['last_name'];
            $username = $row['username'];
            $email = $row['email'];
            $password = $row['password'];

            $link = '<a href="http://localhost/orion/test/reset.php?key='.md5($username).'&auth='.md5($password).'">Please click here to change your password and Login!</a>';

            $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
            try {
                //Server settings
                //$mail->SMTPDebug = 2;                                 // Enable verbose debug output
                $mail->isSMTP();                                      // Set mailer to use SMTP
                $mail->Host = 'email-smtp.us-west-2.amazonaws.com';  // Specify main and backup SMTP servers
                $mail->SMTPAuth = true;                               // Enable SMTP authentication
                $mail->Username = 'username';                 // SMTP username
                $mail->Password = 'password';                           // SMTP password
                $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 587;                                    // TCP port to connect to

                //Recipients
                $mail->setFrom('admin@orionelectricstl.com', 'Orion Electric');
                $mail->addAddress($email);   
                

                //Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = 'Welcome to TEOS!';
                $mail->Body    = "<h3>Hello ".$fname." ".$lname."! Welocome to Teos for Orion Electric!</h3><br> 
                                    Your username is: <b>".$username."</b><br>".$link;
                $mail->AltBody = 'An HTML capable viewer is required for this content';

                $mail->send();
                echo '<script> console.log("An email has been sent to ' . $email .'.")</script>';
            } catch (Exception $e) {
                echo '<script> console.log("Message could not be sent. Mailer Error: ', $mail->ErrorInfo .'.")</script>';
            }
        }

    }
}





?>