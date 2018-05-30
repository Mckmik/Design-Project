<?php
require 'lib/phpMailer/PHPMailer.php';
require 'lib/phpMailer/SMTP.php';
require 'lib/phpMailer/POP3.php';
require 'lib/phpMailer/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error_array = array();
if (isset($_POST['fp_submit'])){
    $username = $_POST['fp_username'];
    $_SESSION['fp_username'] = $username;

    $query = mysqli_query($con, "SELECT email, password FROM users WHERE username='$username'");
    if (mysqli_num_rows($query)==1){
        $row = mysqli_fetch_array($query);
        $email = $row['email'];
        $password = $row['password'];

        $link = '<a href="http://localhost/orion/test/reset.php?key='.md5($username).'&auth='.md5($password).'">Please click here to reset your password.</a>';

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
            $mail->setFrom('teostest6@gmail.com', 'Orion Electric');
            $mail->addAddress($email);   
            

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'TEOS Password Reset';
            $mail->Body    = "This email is to reset the password associated with user: ".$username.". ".$link;
            $mail->AltBody = 'An HTML capable viewer is required for this content';

            $mail->send();
            echo '<script> console.log("An email has been sent to ' . $email .'.")</script>';
            header("location: forgot_password.php?sent=yes");
        } catch (Exception $e) {
            echo '<script> console.log("Message could not be sent. Mailer Error: ', $mail->ErrorInfo .'.")</script>';
        }
        

    } else {
        array_push($error_array,"Username was incorrect<br>");
    }
}
if (isset($_GET['key']) && isset($_GET['auth'])){
    $un = $_GET['key'];
    $ps = $_GET['auth'];
    
    $query = mysqli_query($con, "SELECT * FROM users WHERE md5(username)='$un' AND md5(password)='$ps'");

    if (mysqli_num_rows($query)==1){
        $row = mysqli_fetch_array($query);
        $userId = $row['user_id'];
    } else {
        header("location: index.php");
    }
}
if (isset($_POST['reset_submit'])){
    $uId = $_POST['key'];
    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];
    $que = mysqli_query($con, "SELECT * FROM user_salts WHERE user_id='$uId'");
    $row = mysqli_fetch_array($que);
    $salt = $row['salt'];
    if ($pass1 == $pass2){
        

        for ($i = 0; $i < 1000; $i++){
            $pass1 = md5($salt . $pass1);
        }

        $q = mysqli_query($con, "UPDATE users SET password='$pass1' WHERE user_id='$uId'");

        header("location: reset.php?password_changed=yes&key=".$un."&auth=".$ps);

    } else {
        array_push($error_array,"Passwords do not match<br>");
    }

}

?>