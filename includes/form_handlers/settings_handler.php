<?php
$errorArray = array(); //Holds error messages

$userId = $user['user_id'];

if ($user['is_tech']=="yes"){

    echo '
    <script>

    $(document).ready(function() {
        $("#is_tech").prop("checked",true);
    });

    </script>

    ';
}

if ($user['is_admin']=="yes"){

    echo '
    <script>

    $(document).ready(function() {
        $("#is_admin").prop("checked",true);
    });

    </script>

    ';
}


//Edit and save basic info
if (isset($_POST['save_basic'])){
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $query = mysqli_query($con, "UPDATE users 
        SET email='$email',
        user_phone='$phone'
        WHERE user_id='$userId'");
    header("location: settings.php");

}


if (isset($_POST['save_login'])){
    $username = $_POST['username'];
    if ($username == $user['username']){
        $_SESSION['username'] = $username;//Store username into session variable
        $query = mysqli_query($con, "UPDATE users 
        SET username='$username' 
        WHERE user_id='$userId'");
     
        header("location: settings.php"); 
    }else {

        $userChk = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");
        $numRows = mysqli_num_rows($userChk);
        if ($numRows > 0){
            array_push($errorArray, "Username is already in use");
        } else {
            $_SESSION['username'] = $username;//Store username into session variable
            $query = mysqli_query($con, "UPDATE users 
            SET username='$username' 
            WHERE user_id='$userId'");
        
            header("location: settings.php");
        }
    }
}



?>