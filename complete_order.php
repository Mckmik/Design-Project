<?php 
    include("includes/header.php");
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    if (isset($_GET['order_id'])){
        $id = $_GET['order_id'];

        $query = mysqli_query($con, "SELECT * FROM work_orders JOIN customers ON work_orders.cust_id=customers.cust_id WHERE wo_id='$id'");
        $row = mysqli_fetch_array($query);

        $to = $row['cust_email'];

    }
    
?>
    <div class="main_column column">
    <h1>Work Order # <?php echo $id?></h1>
    <h3>Order Complete!</h3>
    <?php
    if (isset($_GET['send'])){
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
            $mail->addAddress($to);     // Add a recipient

            //Attachments
            $mail->addAttachment('assets/pdfs/work_orders/wo'.$id.'.pdf', 'orion_electric_invoice.pdf');    // Optional name
            

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Orion Electric Invoice #'. sprintf("%05d", $id);
            $mail->Body    = 'Thank you for choosing Orion Electric! Please see the attached invoice for the work performed';
            $mail->AltBody = 'Thank you for choosing Orion Electric! Please see the attached invoice for the work performed';

            $mail->send();
            echo 'Order is complete. An email has been sent to ' . $to;
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }
    ?>
    <hr>
    <a href="workorders.php">
        <button type="button" class="btn btn-secondary">
            <i class="fa fa-arrow-circle-o-left fa-lg"></i> Return to Work Orders
        </button>
    </a>

    <hr>
    </div>
</div>
</body>
</html>