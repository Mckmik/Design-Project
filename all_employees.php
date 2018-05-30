<?php 
    include("includes/header.php");

    $emp = new Employee($con);
    if (isset($_GET['page'])){
        $page = $_GET['page'];
    }
    $numEmps = $emp->getNumberOfEmployees();

    $numPages = ceil($numEmps / 10);

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
    <h1>ALL EMPLOYEES</h1>
    <a href="user_manual.pdf#page=12" target="_blank" style="float: right; padding: 0 100px;">Help <i class="fa fa-question fa-lg"></i></a><br>
    <br>
    <div class="work_order_head">
    <b><p>Number</p><p>Name</p><p>Status</p></b>
    </div>
    <br>
    <?php $emp->loadEmployees($page);?>

    <div class="pagination pagination-centered">
      <ul>
        <?php 

            if ($page == 1){
                echo '<li id="prev"><span>Prev</span></li>';
            } else {
                echo '<li id="prev"><a href="all_employees.php?page='.($page - 1).'">Prev</a></li>';
            }

            for ($i = 0; $i < $numPages; $i++){
                echo '<li id="page'.($i+1).'"><a href="all_employees.php?page='.($i+1).'">'.($i+1).'</a></li>';
            }

            if ($page == $numPages){
                echo '<li id="next"><span>Next</span></li>';
            } else {
                echo '<li id="next"><a href="all_employees.php?page='.($page + 1).'">Next</a></li>';
            }

        ?>
      </ul>
    </div>


    <hr>
        <a href="employees.php">
            <button type="button" class="btn btn-secondary">
            <i class="fa fa-arrow-circle-o-left fa-lg"></i> Return to Employees
            </button>
        </a>
    <hr>
    </div>
</div>
</body>
</html>