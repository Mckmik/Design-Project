<?php
$errorArray = array(); //Holds error messages
if(isset($_GET['emp_id'])){
    $empId = $_GET['emp_id'];

    $idExists = false;
    $existsQuery = mysqli_query($con, "SELECT user_id FROM users");
    while ($existsRow = mysqli_fetch_array($existsQuery)){
        if ($empId == $existsRow['user_id']){
            $idExists = true;
        }
    }

    if($idExists == false){
        header("location: employee_does_not_exists.php");
    }

    $query = mysqli_query($con, "SELECT * FROM users WHERE user_id='$empId'");
    $row = mysqli_fetch_array($query);

    $empNum = $row['emp_num'];
    $firstName = $row['first_name'];
    $lastName = $row['last_name'];
    $empUsername = $row['username'];
    $email = $row['email'];
    $phone = $row['user_phone'];
    $isTech = $row['is_tech'];
    $isAdmin = $row['is_admin'];
    $isDeleted = $row['is_deleted'];

    $openOrder = false;
    $ordersQuery = mysqli_query($con, "SELECT * FROM work_orders WHERE tech_id='$empId'");
    if (mysqli_num_rows($ordersQuery) >0){
        while ($newRow = mysqli_fetch_array($ordersQuery)){
            if ($newRow['is_complete'] =="no"){
                $openOrder = true;
            }
        }
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
        if ($openOrder){
            echo '
            <script>
    
            $(document).ready(function() {
                $("#deactivate").hide();
                $("#edit_perms").hide();
            });
    
            </script>
    
            ';
    
        } else {
            
            echo '
            <script>

            $(document).ready(function() {
                $("#activate").hide();
                $("#deactivate").show();
            });

            </script>

            ';
        }
    }
    
    if ($isTech=="yes"){

        echo '
        <script>

        $(document).ready(function() {
            $("#is_tech").prop("checked",true);
        });

        </script>

        ';
    }

    if ($isAdmin=="yes"){

        echo '
        <script>

        $(document).ready(function() {
            $("#is_admin").prop("checked",true);
        });

        </script>

        ';
    }
}

//hides buttons if employee selected is current user
if ($user['user_id'] == $empId){
    echo '
        <script>

        $(document).ready(function() {
            $("#deactivate").hide();
            $("#edit_perms").hide();
        });

        </script>

    ';
}



//Edit and save basic info
if (isset($_POST['save_basic'])){
    $empNum = $_POST['emp_num'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    if ($empNum == $row['emp_num']){

        $empNum = mysqli_real_escape_string($con, $empNum);

        $firstName = strip_tags($firstName);
        $firstName = mysqli_real_escape_string($con, $firstName);
        $lastName = strip_tags($lastName);
        $lastName = mysqli_real_escape_string($con, $lastName);

        $email = strip_tags($email);
        $email = str_replace(' ', '', $email);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        $email = mysqli_real_escape_string($con, $email);

        $phone = mysqli_real_escape_string($con, $phone);

        $query = mysqli_query($con, "UPDATE users 
            SET emp_num='$empNum', 
            first_name='$firstName',
            last_name='$lastName',
            email='$email',
            user_phone='$phone'
            WHERE user_id='$empId'");
        header("location: employee_details.php?emp_id=".$empId);

    } else {
        $numChk = mysqli_query($con, "SELECT emp_num FROM users WHERE emp_num='$empNum'");
        $numR = mysqli_num_rows($numChk);
        if ($numR > 0){
            array_push($errorArray, "Employee number is already in use");
            $empNum = $row['emp_num'];
        } else {
            $empNum = mysqli_real_escape_string($con, $empNum);

            $firstName = strip_tags($firstName);
            $firstName = mysqli_real_escape_string($con, $firstName);
            $lastName = strip_tags($lastName);
            $lastName = mysqli_real_escape_string($con, $lastName);

            $email = strip_tags($email);
            $email = str_replace(' ', '', $email);
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            $email = filter_var($email, FILTER_VALIDATE_EMAIL);
            $email = mysqli_real_escape_string($con, $email);

            $phone = mysqli_real_escape_string($con, $phone);

            $query = mysqli_query($con, "UPDATE users 
                SET emp_num='$empNum', 
                first_name='$firstName',
                last_name='$lastName',
                email='$email',
                user_phone='$phone'
                WHERE user_id='$empId'");
            header("location: employee_details.php?emp_id=".$empId);
        }
    }

}

if (isset($_POST['save_login'])){
    $username = $_POST['username'];
    if ($username == $empUsername){
        $username = strip_tags($username);
        $username = str_replace(' ', '', $username);
        $username = mysqli_real_escape_string($con, $username);

        $query = mysqli_query($con, "UPDATE users 
            SET username='$username' 
            WHERE user_id='$empId'");
        header("location: employee_details.php?emp_id=".$empId);
    }else {
        $userChk = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");
        $numRows = mysqli_num_rows($userChk);
        if ($numRows > 0){
            array_push($errorArray, "Username is already in use");
        } else {
            $username = strip_tags($username);
            $username = str_replace(' ', '', $username);
            $username = mysqli_real_escape_string($con, $username);

            $query = mysqli_query($con, "UPDATE users 
                SET username='$username' 
                WHERE user_id='$empId'");
            header("location: employee_details.php?emp_id=".$empId);
        }
    }
}

//Edit and save permissions info
if (isset($_POST['save_perms'])){
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
    $query = mysqli_query($con, "UPDATE users 
        SET is_tech='$isTech', is_admin='$isAdmin' 
        WHERE user_id='$empId'");
    header("location: employee_details.php?emp_id=".$empId);

}

//Update employee is_deleted to 'yes'
if (isset($_POST['deactivate'])){
    $query = mysqli_query($con, "UPDATE users 
        SET is_deleted='yes' 
        WHERE user_id='$empId'");
    header("location: employee_details.php?emp_id=".$empId);
}

//Update employee is_deleted to 'no'
if (isset($_POST['activate'])){
    $query = mysqli_query($con, "UPDATE users 
        SET is_deleted='no' 
        WHERE user_id='$empId'");
    header("location: employee_details.php?emp_id=".$empId);
}

?>