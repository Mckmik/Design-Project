<?php
    
class Customer {

    private $con;

	public function __construct($con){
		$this->con = $con;

    }
    
    //creates new customer
    public function createCustomer($company, $email, $phone, $fName, $lName, $street, $city, $state, $zip, $date, $time){

        $company = mysqli_real_escape_string($this->con, $company);
        $email = mysqli_real_escape_string($this->con, $email);
        $phone = mysqli_real_escape_string($this->con, $phone);
        $fName = mysqli_real_escape_string($this->con, $fName);
        $lName = mysqli_real_escape_string($this->con, $lName);
        $street = mysqli_real_escape_string($this->con, $street);
        $city = mysqli_real_escape_string($this->con, $city);
        $state = mysqli_real_escape_string($this->con, $state);
        $zip = mysqli_real_escape_string($this->con, $zip);

        $query = mysqli_query($this->con, "INSERT INTO customers VALUES('', '$lName', '$fName', '$street', '$city', '$state', '$zip', '$company', '$email', '$phone', '$date', '$time', 'no')");
        $returnedId = mysqli_insert_id($this->con);
    }

    //gets customer name
    public function getCustomerName($id){

        $query = mysqli_query($this->con, "SELECT * FROM customers WHERE cust_id='$id'");
        $row = mysqli_fetch_array($query);
        $firstName = $row['first_name'];
        $lastName = $row['last_name'];
        $company = $row['company_name'];
        if ($company == ""){
            $loadName = $lastName . ", " . $firstName;
        } else {
            $loadName = $company;
        }
        echo $loadName;
    }

    //loads customers for dropdown selection
    public function dropdownLoadCustomers(){

        $str = ""; //String to return
        $dataQuery = mysqli_query($this->con, "SELECT * FROM customers ORDER BY company_name");
        
        if(mysqli_num_rows($dataQuery) >0){

            while ($row = mysqli_fetch_array($dataQuery)) {
				$id = $row['cust_id'];
				$firstName = $row['first_name'];
				$lastName = $row['last_name'];
                $company = $row['company_name'];
                
                $isDeleted = $row['is_deleted'];
                

                if ($company == ""){
                    $loadName = $lastName . ", " . $firstName;
                } else {
                    $loadName = $company;
                }
                
                if ($isDeleted == "no"){
                    $str .= '<option value="' . $id . '">'.$loadName.'</option>';
                }
                
                //$str .= '<option value="' . $id . '">'.$loadName.'</option>';
            }
            echo $str;
        }
    }

    //Used to get customers for customer lookup table
    public function getCustomers(){

        $str = ""; //String to return
        $dataQuery = mysqli_query($this->con, "SELECT * FROM customers ORDER BY company_name");
        
        if(mysqli_num_rows($dataQuery) >0){

            while ($row = mysqli_fetch_array($dataQuery)) {
				$id = $row['cust_id'];
				$firstName = $row['first_name'];
				$lastName = $row['last_name'];
                $company = $row['company_name'];
                /*
                $isDeleted = $row['is_deleted'];
                */

                if ($company == ""){
                    $loadName = $lastName . ", " . $firstName;
                } else {
                    $loadName = $company;
                }
                /*
                if ($isDeleted == "no"){
                    $str .= '<option value="' . $id . '">'.$loadName.'</option>';
                }
                */
                $str .='"'.$loadName.'",';
            }
            echo $str;
        }
    }

    //loads customers to customers.php
    public function loadCustomers($page){

        $start = ($page * 10) - 10;
        $stop = $page * 10;

        $count = 0;

        $str = '<div class="work_order_area">';

        $query  = mysqli_query($this->con, "SELECT * FROM customers ORDER BY last_name, first_name");
        while ($row = mysqli_fetch_array($query)) {

            $custId = $row['cust_id'];
            $lastName = $row['last_name'];
            $firstName = $row['first_name'];
            $custName = $lastName . ", " . $firstName;
            $company = $row['company_name'];
            $isDeleted = $row['is_deleted'];
            if ($company == ""){
                $company = "<i>Residence</i>";
            }
            
            if ($isDeleted == "yes") {
                $status = "<i>Inactive</i>";
            } else {
                $status = "Active";
            }
            if ($count >= $start && $count < $stop){
                $str .= '
                <a href="customer_details.php?cust_id='.$custId.'">
                <div class="work_order">
                    <b><p>'.$custName.'</p><p>'.$company.'</p><p>'.$status.'</p></b>
                </div>
                </a>
                <br>
                ';
            }
            $count++;
        }
        echo $str.'</div>';
    }

    //loads customers to customers.php
    public function searchCustomers($search){

        $str = '

        <h3>Results for "'.$search.'"</h3>
        <div class="work_order_area">';

        $count = 0;

        $query  = mysqli_query($this->con, "SELECT * FROM customers ORDER BY last_name, first_name");
        while ($row = mysqli_fetch_array($query)) {

            $custId = $row['cust_id'];
            $lastName = $row['last_name'];
            $firstName = $row['first_name'];
            $custName = $lastName . ", " . $firstName;
            $company = $row['company_name'];
            $isDeleted = $row['is_deleted'];
            if ($company == ""){
                $company = "<i>Residence</i>";
            }
            
            if ($isDeleted == "yes") {
                $status = "<i>Inactive</i>";
            } else {
                $status = "Active";
            }

            if ((strpos(strtoupper($custName), strtoupper($search)) !== false) || (strpos(strtoupper($company), strtoupper($search)) !== false)){
                $str .= '
                <a href="customer_details.php?cust_id='.$custId.'">
                <div class="work_order">
                    <b><p>'.$custName.'</p><p>'.$company.'</p><p>'.$status.'</p></b>
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

    public function getNumberOfCustomers(){
        $query = mysqli_query($this->con, "SELECT * FROM customers");

        return mysqli_num_rows($query);
    }

    public function createCSV($lastCreate, $lastTime){

        $date = date("Y-m-d");
        $time = date("H:i:s");
        $ftime = date_create($time);
        $ftime = date_format($ftime, "His");
        $file = fopen("assets/csvs/customers".$date.$ftime.".csv","w");

        $line = "First Name,Last Name,Company,Email,Phone,Street,City,State,Zipcode";

        fputcsv($file,explode(',',$line));

        $query = mysqli_query($this->con, "SELECT * FROM customers");

        if (mysqli_num_rows($query) >0){

            while($row = mysqli_fetch_array($query)){
                $firstName = $row['first_name'];
                $lastName = $row['last_name'];
                $company = $row['company_name'];
                $email = $row['cust_email'];
                $phone = $row['cust_phone'];
                $street = $row['street'];
                $city = $row['city'];
                $state = $row['state'];
                $zip = $row['zip'];
                $dateCreated = strtotime($row['date_created']);
                $timeCreated = strtotime($row['time_created']);
                $deleted = $row['is_deleted'];

                if ($dateCreated > $lastCreate){
                    $line = $firstName . ',' . $lastName . ',' . $company . ',' . $email . ',' . $phone . ',' . $street . ',' . $city . ',' . $state . ',' . $zip;
                    fputcsv($file,explode(',',$line));

                } elseif($dateCreated == $lastCreate){
                    if ($timeCreated > $lastTime){
                        $line = $firstName . ',' . $lastName . ',' . $company . ',' . $email . ',' . $phone . ',' . $street . ',' . $city . ',' . $state . ',' . $zip;
                        fputcsv($file,explode(',',$line));
                    }
                }

            }

        }
        fclose($file);
        $q = mysqli_query($this->con, "UPDATE csv_dates SET last_create='$date', time='$time' WHERE entity='customer'");




    }
}

?>