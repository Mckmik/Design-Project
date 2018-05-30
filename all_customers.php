<?php 
    include("includes/header.php");

    $cust = new Customer($con);
    if (isset($_GET['page'])){
        $page = $_GET['page'];
    }
    $numCusts = $cust->getNumberOfCustomers();

    $numPages = ceil($numCusts / 10);

    if ($page == 1){
        echo "<script> 
        $(document).ready(function() {
            $('#prev').addClass('disabled');
        });
        </script>";

    }
    if ($page == $numPages){
        echo "<script> 
        $(document).ready(function() {
            $('#next').addClass('disabled'); 
        });
        </script>";

    }

    echo "
    <script> 
    $(document).ready(function() {
        $('#page".$page."').addClass('active'); 
    });
    </script>";


?>
    <div class="main_column column">
    <h1>ALL CUSTOMERS</h1>
    <a href="user_manual.pdf#page=18" target="_blank" style="float: right; padding: 0 100px;">Help <i class="fa fa-question fa-lg"></i></a><br>
    <br>
    <div class="work_order_head">
    <b><p>Name</p><p>Company</p><p>Status</p></b>
    </div>
    <br>
    <?php $cust->loadCustomers($page);?>

    <div class="pagination pagination-centered">
      <ul>
        <?php 

            if ($page == 1){
                echo '<li id="prev"><span>Prev</span></li>';
            } else {
                echo '<li id="prev"><a href="all_customers.php?page='.($page - 1).'">Prev</a></li>';
            }

            for ($i = 0; $i < $numPages; $i++){
                echo '<li id="page'.($i+1).'"><a href="all_customers.php?page='.($i+1).'">'.($i+1).'</a></li>';
            }

            if ($page == $numPages){
                echo '<li id="next"><span>Next</span></li>';
            } else {
                echo '<li id="next"><a href="all_customers.php?page='.($page + 1).'">Next</a></li>';
            }

        ?>
      </ul>
    </div>


    <hr>
        <a href="customers.php">
            <button type="button" class="btn btn-secondary">
            <i class="fa fa-arrow-circle-o-left fa-lg"></i> Return to Customers
            </button>
        </a>
    <hr>
    </div>
</div>
</body>
</html>