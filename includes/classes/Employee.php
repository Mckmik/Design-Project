<?php
    
class Employee {

    private $con;

	public function __construct($con){
		$this->con = $con;

    }

    //add new employee 
    public function addNewEmployee($empNum, $firstName, $lastName, $username, $password, $email, $phone, $isTech, $isAdmin, $salt){

        $empNum = mysqli_real_escape_string($this->con, $empNum);

        $firstName = strip_tags($firstName);
        $firstName = mysqli_real_escape_string($this->con, $firstName);
        $lastName = strip_tags($lastName);
        $lastName = mysqli_real_escape_string($this->con, $lastName);


        $username = strip_tags($username);
        $username = str_replace(' ', '', $username);
        $username = mysqli_real_escape_string($this->con, $username);
        
        $password = mysqli_real_escape_string($this->con, $password);


        $email = strip_tags($email);
        $email = str_replace(' ', '', $email);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        $email = mysqli_real_escape_string($this->con, $email);


        $phone = mysqli_real_escape_string($this->con, $phone);

        $query = mysqli_query($this->con, "INSERT INTO users VALUES('', '$firstName', '$lastName', '$email', '$username', '$password', '$empNum', '$isTech', '$isAdmin', '$phone', 'no')");
        $returnedId = mysqli_insert_id($this->con);
        $query = mysqli_query($this->con, "INSERT INTO user_salts VALUES('$returnedId', '$salt')");

        return $returnedId;

    }

    //loads employees
    public function loadEmployees($page){

        $start = ($page * 10) - 10;
        $stop = $page * 10;

        $count = 0;

        $str = '<div class="work_order_area">';

        $query  = mysqli_query($this->con, "SELECT * FROM users ORDER BY emp_num");
        while ($row = mysqli_fetch_array($query)) {

            $empId = $row['user_id'];
            $empNum = $row['emp_num'];
            $lastName = $row['last_name'];
            $firstName = $row['first_name'];
            $empName = $lastName . ", " . $firstName;
            $isDeleted = $row['is_deleted'];
            
            if ($isDeleted == "yes") {
                $status = "<i>Inactive</i>";
            } else {
                $status = "Active";
            }

            if ($count >= $start && $count < $stop){
                $str .= '
                <a href="employee_details.php?emp_id='.$empId.'">
                <div class="work_order">
                    <b><p>'.sprintf("%04d",$empNum).'</p><p>'.$empName.'</p><p>'.$status.'</p></b>
                </div>
                </a>
                <br>
                ';                
            }
            $count++;
        }
        echo $str.'</div>';
    }

    //loads employees by search criteria
    public function searchEmployees($search){

        $str = '

        <h3>Results for "'.$search.'"</h3>
        <div class="work_order_area">';

        $count = 0;

        $query  = mysqli_query($this->con, "SELECT * FROM users ORDER BY emp_num");
        while ($row = mysqli_fetch_array($query)) {

            $empId = $row['user_id'];
            $empNum = $row['emp_num'];
            $lastName = $row['last_name'];
            $firstName = $row['first_name'];
            $empName = $lastName . ", " . $firstName;
            $isDeleted = $row['is_deleted'];
            
            if ($isDeleted == "yes") {
                $status = "<i>Inactive</i>";
            } else {
                $status = "Active";
            }

            if ((strpos(strtoupper($empName), strtoupper($search)) !== false) || (strpos($empNum, ltrim($search, '0')) !== false)){
                $str .= '
                <a href="employee_details.php?emp_id='.$empId.'">
                <div class="work_order">
                    <b><p>'.sprintf("%04d",$empNum).'</p><p>'.$empName.'</p><p>'.$status.'</p></b>
                </div>
                </a>
                <br>
                ';
                $count++;                
            }
        }

        echo $str.'</div>';
        if ($count < 1 ){
            echo "<p><b>No results returned.</b></p>";
        }
    }

    //Loads all service techs for use in a dropdown select input. 
    public function dropdownLoadServiceTechs($date, $time){
  
        $time = strtotime($time);
        $str="";
        $dataQuery = mysqli_query($this->con, "SELECT * FROM users WHERE is_tech='yes' AND is_deleted='no' ORDER BY last_name");

        if(mysqli_num_rows($dataQuery) >0){

            while ($row = mysqli_fetch_array($dataQuery)) {
                $id = $row['user_id'];
                $firstName = $row['first_name'];
                $lastName = $row['last_name'];
                $isAvailable = true;

                
                $query = mysqli_query($this->con, "SELECT * FROM work_orders WHERE tech_id='$id' AND service_date='$date' AND is_deleted='no'");
                if(mysqli_num_rows($query) >0){
                    while ($newRow = mysqli_fetch_array($query)){
                        $serviceTime = strtotime($newRow['service_time']);
                        if (($time > ($serviceTime - (60*60*2))) && ($time < ($serviceTime + (60*60*2)))){
                            $isAvailable = false;

                            echo '
                                <script>
                                    console.log("'. $time . '  '.$serviceTime.'")

                                </script>


                            ';

                        }

                            echo '
                                <script>
                                    console.log("'. $time . '  '.$serviceTime.'")

                                </script>


                            ';
                    }   
                }
                

                if ($isAvailable == true){
                   $str .= '<option value="'.$id.'">'.$lastName. ', '.$firstName.' </option>'; 
                }
            }

            echo $str;
                    
        }
    }
    //Loads all service techs for use in a dropdown select input. FOR REPORT FUNCTION 
    public function dropdownLoadServiceTechsReports(){
  
        $time = strtotime($time);
        $str="";
        $dataQuery = mysqli_query($this->con, "SELECT * FROM users WHERE is_tech='yes'");

        if(mysqli_num_rows($dataQuery) >0){

            while ($row = mysqli_fetch_array($dataQuery)) {
                $id = $row['user_id'];
                $firstName = $row['first_name'];
                $lastName = $row['last_name'];
                
                $str .= '<option value="'.$id.'">'.$lastName. ', '.$firstName.' </option>'; 
                
            }

            echo $str;
                    
        }
    }
    //gets employee name
    public function getEmployeeName($id){

        $query = mysqli_query($this->con, "SELECT * FROM users WHERE user_id='$id'");
        $row = mysqli_fetch_array($query);
        $lastName = $row['last_name'];
        $firstName = $row['first_name'];

        echo $lastName . ', ' . $firstName;
    }

    public function getNumberOfEmployees(){
        $query = mysqli_query($this->con, "SELECT * FROM users");

        return mysqli_num_rows($query);
    }


    
}

?>