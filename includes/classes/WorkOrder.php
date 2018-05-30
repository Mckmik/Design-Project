<?php
class WorkOrder {

    private $con;

	public function __construct($con){
		$this->con = $con;

    }

    //Creates new order
    public function addServiceOrder($custId, $userId, $date, $time, $body){

        $body = strip_tags($body);
        $body = mysqli_real_escape_string($this->con, $body);

        $query = mysqli_query($this->con, "INSERT INTO work_orders VALUES('', '$custId', '$userId', '$date', '$time', '$body', '', '', 'no', 'no', '0000-00-00','')");
        $returnedId = mysqli_insert_id($this->con);

    }

    //Loads orders for main order page based on user privilages
    public function loadOpenWorkOrders($username){

        $str = '<div class="work_order_area">';

        $userQuery  = mysqli_query($this->con, "SELECT * FROM users WHERE username='$username'");
        $userRow = mysqli_fetch_array($userQuery);

        $userId = $userRow['user_id'];
        $isTech = $userRow['is_tech'];
        $isAdmin = $userRow['is_admin'];


        if($isAdmin=='yes'){

            $query = mysqli_query($this->con, "SELECT * FROM work_orders WHERE is_deleted='no' AND is_complete='no' ORDER BY service_date, service_time");                
    
        } 
        else{

            $query = mysqli_query($this->con, "SELECT * FROM work_orders WHERE tech_id='$userId' AND is_deleted='no' AND is_complete='no'  ORDER BY service_date, service_time");

        }

        if(mysqli_num_rows($query) >0){

            while ($row = mysqli_fetch_array($query)) {
                $id = $row['wo_id'];
                $customerId = $row['cust_id'];
                $techId = $row['tech_id'];
                $serviceDate = strtotime($row['service_date']);
                $serviceTime = strtotime($row['service_time']);
                $request = $row['request_body'];
                $isComplete = $row['is_complete'];

                $customerQuery = mysqli_query($this->con, "SELECT * FROM customers WHERE cust_id='$customerId'");
                $customerRow = mysqli_fetch_array($customerQuery);
                if ($customerRow['company_name']==""){
                    $customerName = $customerRow['first_name'] . " " . $customerRow['last_name'];
                } else {
                    $customerName = $customerRow['company_name'];
                }


                $techQuery = mysqli_query($this->con, "SELECT first_name, last_name FROM users WHERE user_id='$techId'");
                $techRow = mysqli_fetch_array($techQuery);
                $techName = $techRow['first_name'] . " " . $techRow['last_name'];


                if ($serviceDate < (time()-(60*60*24*2))){

                    $str .= '
                    <a href="workorder_details.php?workorder_id='.$id.'">
                    <div class="work_order" style="color: red;">
                        <b><p>'.date("m/d/Y",$serviceDate).'</p><p>'.$customerName.'</p><p>'.$techName.'</p></b>
                    </div>
                    </a>
                    <br>
                    ';


                } else {
                    $str .= '
                    <a href="workorder_details.php?workorder_id='.$id.'">
                    <div class="work_order">
                        <b><p>'.date("m/d/Y",$serviceDate).'</p><p>'.$customerName.'</p><p>'.$techName.'</p></b>
                    </div>
                    </a>
                    <br>
                    ';

                }

            }

            echo $str.'</div>';

        }
        else{
            echo $str."</div><div>No Work Orders to show!</div><br>";
        }
    }
        public function loadClosedWorkOrders($username){

        $str = '<div class="work_order_area">';

        $userQuery  = mysqli_query($this->con, "SELECT * FROM users WHERE username='$username'");
        $userRow = mysqli_fetch_array($userQuery);

        $userId = $userRow['user_id'];
        $isTech = $userRow['is_tech'];
        $isAdmin = $userRow['is_admin'];


        if($isAdmin=='yes'){

            $query = mysqli_query($this->con, "SELECT * FROM work_orders WHERE is_deleted='no' AND is_complete='yes' ORDER BY service_date, service_time");                
    
        } 
        else{

            $query = mysqli_query($this->con, "SELECT * FROM work_orders WHERE tech_id='$userId' AND is_deleted='no' AND is_complete='yes'  ORDER BY service_date, service_time");

        }

        if(mysqli_num_rows($query) >0){

            while ($row = mysqli_fetch_array($query)) {
                $id = $row['wo_id'];
                $customerId = $row['cust_id'];
                $techId = $row['tech_id'];
                $serviceDate = strtotime($row['service_date']);
                $serviceTime = strtotime($row['service_time']);
                $request = $row['request_body'];
                $isComplete = $row['is_complete'];

                $customerQuery = mysqli_query($this->con, "SELECT * FROM customers WHERE cust_id='$customerId'");
                $customerRow = mysqli_fetch_array($customerQuery);
                if ($customerRow['company_name']==""){
                    $customerName = $customerRow['first_name'] . " " . $customerRow['last_name'];
                } else {
                    $customerName = $customerRow['company_name'];
                }


                $techQuery = mysqli_query($this->con, "SELECT first_name, last_name FROM users WHERE user_id='$techId'");
                $techRow = mysqli_fetch_array($techQuery);
                $techName = $techRow['first_name'] . " " . $techRow['last_name'];


                $str .= '
                <a href="workorder_details.php?workorder_id='.$id.'">
                <div class="work_order">
                    <b><p>'.date("m/d/Y",$serviceDate).'</p><p>'.$customerName.'</p><p>'.$techName.'</p></b>
                </div>
                </a>
                <br>
                ';


            }

            echo $str.'</div>';

        }
        else{
            echo $str."</div><div>No Work Orders to show!</div><br>";
        }
    }

    //gets description for order
    public function getOrderDescription($orderId){
        $query = mysqli_query($this->con, "SELECT work_description FROM work_orders WHERE wo_id='$orderId'");
        $row = mysqli_fetch_array($query);
        $desc = $row['work_description'];
        echo $desc;
    }
    //gets job address for order
    public function getJobAddress($orderId){
        $query = mysqli_query($this->con, "SELECT job_address FROM work_orders WHERE wo_id='$orderId'");
        $row = mysqli_fetch_array($query);
        $jobAdd = $row['job_address'];
        echo $jobAdd;
    }
    //Adds description data to order
    public function addDesc($orderId, $description){

        $description = strip_tags($description);
        $description = mysqli_real_escape_string($this->con, $description);

        $query = mysqli_query($this->con, "UPDATE work_orders SET work_description='$description' WHERE wo_id='$orderId'");

    }
    //Adds job address data to order
    public function addJobAdd($orderId, $jobAdd){

        $jobAdd = strip_tags($jobAdd);
        $jobAdd = mysqli_real_escape_string($this->con, $jobAdd);

        $query = mysqli_query($this->con, "UPDATE work_orders SET job_address='$jobAdd' WHERE wo_id='$orderId'");

    }

    //Adds order line data for order
    public function addOrderLine($orderId, $lineId, $lineType, $quantity, $description, $cost){

        $description = strip_tags($description);
        $description = mysqli_real_escape_string($this->con, $description);

        $query = mysqli_query($this->con, "INSERT INTO work_order_lines VALUES('$orderId', '$lineId', '$lineType', '$quantity', '$description', '$cost')");


    }

    //Loads the order lines for a specific order
    public function loadOrderLines($orderId){

        $str ="";
        $query  = mysqli_query($this->con, "SELECT * FROM work_order_lines WHERE wo_id='$orderId' ORDER BY ol_id");

        if(mysqli_num_rows($query) >0){


            while($row = mysqli_fetch_array($query)){
                $lineNum = $row['ol_id'];
                $lineType = $row['type'];
                $lineQty = $row['quantity'];
                $lineMat = $row['material'];
                $lineCost = $row['cost'];
                $totCost = 0.00;
                $totCost = $lineCost * $lineQty;

                $str.= '   
                    <div class="order-line-grid-item">'.$lineNum.'</div>
                    <div class="order-line-grid-item">'.$lineType.'</div>
                    <div class="order-line-grid-item">'.$lineQty.'</div>
                    <div class="order-line-grid-item">'.$lineMat.'</div>
                    <div class="order-line-grid-item ln-curr">$'.$lineCost.'</div>
                    <div class="order-line-grid-item ln-curr">$'. number_format($totCost,2).'</div>
                    <div class="order-line-grid-item ln-curr"><button type="button" class="btn btn-danger btn-sm" id="delete_line'.$lineNum.'"><i class="fa fa-times"></i></button></div>                
                
                ';
                ?>
                <script>
                $(document).ready(function(){
                    $('#delete_line<?php echo $lineNum; ?>').on('click', function() {
                        if(confirm("Are you sure you want to delete this work order?")){
                            console.log("yes");
                            location.assign("workorder_details.php?workorder_id=<?php echo $orderId;?>&delete_line=yes&line_num=<?php echo $lineNum; ?>");


                        }else{
                            console.log("no");
                        }
                    });
                });
                </script>

                <?php
            }
            echo $str;
        }
    }

    //Load hours from order lines for specific order
    public function loadOrderLineHours($orderId){

        $str ="";
        $labor = 0.00;
        $query  = mysqli_query($this->con, "SELECT * FROM work_order_lines WHERE wo_id='$orderId' AND type='Labor' ORDER BY ol_id");

        if(mysqli_num_rows($query) >0){


            while($row = mysqli_fetch_array($query)){
                $lineQty = $row['quantity'];
                $lineMat = $row['material'];
                $lineCost = $row['cost'];
                $totCost = 0.00;
                $totCost = $lineCost * $lineQty;
                $labor += $totCost;

                $str.= '
                <div class="order-line-grid-item">'.$lineMat.'</div>
                <div class="order-line-grid-item">'.$lineQty.'</div>
                <div class="order-line-grid-item ln-curr">$'.$lineCost.'</div>
                <div class="order-line-grid-item ln-curr">$'. number_format($totCost,2).'</div>
                ';
            }
            $str.= '<div></div><div></div>
            <div class="ln-curr"><b>TOTAL: </b></div>
            <div class="ln-curr"><b>$'.number_format($labor,2).'</b></div>
            ';

            echo $str;
        }
    }

    //Load materials from order lines for specific order
    public function loadOrderLineMats($orderId){

        $str ="";
        $mats = 0.00;
        $query  = mysqli_query($this->con, "SELECT * FROM work_order_lines WHERE wo_id='$orderId' AND type='Material' ORDER BY ol_id");

        if(mysqli_num_rows($query) >0){


            while($row = mysqli_fetch_array($query)){
                $lineQty = $row['quantity'];
                $lineMat = $row['material'];
                $lineCost = $row['cost'];
                $totCost = 0.00;
                $totCost = $lineCost * $lineQty;
                $mats += $totCost;

                $str.= '
                <div class="order-line-grid-item">'.$lineQty.'</div>
                <div class="order-line-grid-item">'.$lineMat.'</div>
                <div class="order-line-grid-item ln-curr">$'.$lineCost.'</div>
                <div class="order-line-grid-item ln-curr">$'. number_format($totCost,2).'</div>
                ';
            }
            $str.= '<div></div><div></div>
            <div class="ln-curr"><b>TOTAL: </b></div>
            <div class="ln-curr"><b>$'.number_format($mats,2).'</b></div>
            ';

            echo $str;
        }
    }

    //Get total cost of materials on order lines for specific order
    public function getMaterialsTotal($orderId){

        $matCost = 0.00;

        $lineQuery = mysqli_query($this->con, "SELECT quantity, cost FROM work_order_lines WHERE wo_id='$orderId'");
        
        if(mysqli_num_rows($lineQuery)>0){

            while($lineRow = mysqli_fetch_array($lineQuery)){

                $lineQty = $lineRow['quantity'];
                $lineCost = $lineRow['cost'];
                $lineTotal = ($lineQty * $lineCost);
                
                $matCost += number_format($lineTotal,2);
            }
        }
        return number_format($matCost,2);
    }

    //get total hours cost
    public function getHoursCostTotal($orderId){

        $matCost = 0.00;

        $lineQuery = mysqli_query($this->con, "SELECT quantity, cost FROM work_order_lines WHERE wo_id='$orderId' AND type='Labor'");
        
        if(mysqli_num_rows($lineQuery)>0){

            while($lineRow = mysqli_fetch_array($lineQuery)){

                $lineQty = $lineRow['quantity'];
                $lineCost = $lineRow['cost'];
                $lineTotal = ($lineQty * $lineCost);
                
                $matCost += number_format($lineTotal,2);
            }
        }
        return number_format($matCost,2);
    }

    //get total materials cost
    public function getMatsCostTotal($orderId){

        $matCost = 0.00;

        $lineQuery = mysqli_query($this->con, "SELECT quantity, cost FROM work_order_lines WHERE wo_id='$orderId' AND type='Material'");
        
        if(mysqli_num_rows($lineQuery)>0){

            while($lineRow = mysqli_fetch_array($lineQuery)){

                $lineQty = $lineRow['quantity'];
                $lineCost = $lineRow['cost'];
                $lineTotal = ($lineQty * $lineCost);
                
                $matCost += number_format($lineTotal,2);
            }
        }
        return number_format($matCost,2);
    }

    //Gets next order line number
    public function getNextLineNumber($orderId){

        $query  = mysqli_query($this->con, "SELECT * FROM work_order_lines WHERE wo_id='$orderId' ORDER BY ol_id");

        $count = 1;

        if(mysqli_num_rows($query) >0){
            while($row = mysqli_fetch_array($query)){
                $count++;
            }
        }
        return $count;
    }

    //loads orders for dropdown selection
    public function dropdownLoadOrders($empId, $start, $end){

        $str = ""; 
        $dataQuery = mysqli_query($this->con, "SELECT * FROM work_orders 
            JOIN customers 
            ON work_orders.cust_id=customers.cust_id
            WHERE tech_id='$empId'");
        
        if(mysqli_num_rows($dataQuery) >0){

            while ($row = mysqli_fetch_array($dataQuery)) {
				$id = $row['wo_id'];
                $firstName = $row['first_name'];
				$lastName = $row['last_name'];
                $company = $row['company_name'];
                if ($company == ""){
                    $loadName = $lastName . ", " . $firstName;
                } else {
                    $loadName = $company;
                }
                $date = strtotime($row['service_date']);
                
                if (($date >= $start) && ($date <= $end)){
                    $str .= '<option value="0,' . $id . '">'.$loadName.': SERVICE '.date("m/d/Y", $date).'</option>';
                }

                            
            }
            echo $str;
        }
    }


}

?>
