<?php
include_once 'session.php'; 
ob_start();
$title = "Receipts";
?>

<div class="container-fluid px-4">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-primary text-white py-3">
            <h4 class="mb-0">Transaction History</h4>
        </div>
        <div class="card-body">
            <?php
            include_once '../backend/utils.php';
            include_once '../backend/txnutils.php';
            include_once './restrictedpage.php';

            $_class = "";
            isset($_GET['class']) ? $_class=$_GET['class'] : "";
            $_user_id = "";
            isset($_GET['user_id']) ? $_user_id=$_GET['user_id'] : "";
            $_txn_status = "";
            isset($_GET['txn_status']) ? $_txn_status=$_GET['txn_status'] : "";

            $rows_allclasses = getAllClasses(); 
            $rows_allusers = getUserDetailsForClass($_class!="all"?$_class:"",$error);
            $rows_receipts = getReceiptsForAdmin($_class!="all"?$_class:"", $_user_id != "all"?$_user_id:"", $_txn_status != "all"? $_txn_status : "",$error);
            ?>

            <form id='txnlist_form' name='txnlist_form' method='get' class="row g-3 mb-4">
                <div class="col-md-4">
                    <select class="form-select" id="class" name="class" onchange="submitForm(this)">
                        <option value='all'>All Classes</option>
                        <?php 
                            foreach ($rows_allclasses as $row) {
                                echo "<option value='".$row['ID']."'". checkSelected($row['ID'],$_class) .">".$row['NAME']."</option>";
                            }
                        ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <select class="form-select" id="txnlist_user_id" name="user_id" onchange="submitForm(this)">
                        <option value='all'>All Users</option>
                        <?php 
                            foreach ($rows_allusers as $row) {
                                echo "<option value='".$row['ID']."'". checkSelected($row['ID'],$_user_id) .">".$row['NAME']."</option>";
                            }
                        ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <select class="form-select" id="txnlist_status" name="txn_status" onchange="submitForm(this)">
                        <option value='all' <?php if($_txn_status == "all") echo "selected"; ?>>All Status</option>
                        <option value='success' <?php if($_txn_status == "success") echo "selected"; ?>>Success</option>
                        <option value='failed' <?php if($_txn_status == "failed") echo "selected"; ?>>Failed</option>
                    </select>
                </div>
            </form>

            <?php
            if($rows_receipts != [])
            {
                echo "<div class='table-responsive'>";
                echo "<table class='table table-striped table-hover align-middle'>";
                echo "<thead class='table-light'>";
                echo "<tr>";
                echo "<th>S/N #</th>";
                echo "<th>CLASS</th>";
                echo "<th>NAME</th>";
                echo "<th>Receipt #</th>";
                echo "<th>Order ID</th>";
                echo "<th>Package Name</th>";
                echo "<th>Price</th>";
                echo "<th>Status</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                $i = 1;
                foreach ($rows_receipts as $row){
                    echo "<tr>";
                    echo "<td>{$i}</td>";
                    $i++;
                    echo "<td>{$row['CLASS_NAME']}</td>";
                    echo "<td>{$row['NAME']}</td>";
                    echo "<td>{$row['ID']}</td>";
                    echo "<td>{$row['ORDER_ID']}</td>";
                    echo "<td>{$row['PACKAGE_NAME']}</td>";
                    $priceInRs = $row['AMOUNT']/100.00;
                    echo "<td>₹{$priceInRs}</td>";
                    $status_color = getStatusColor($row['STATUS']);
                    echo "<td><span class='badge text-dark  bg-{$status_color}'>{$row['STATUS']}</span></td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
                echo "</div>";
            }
            else
            {
                echo "<div class='alert alert-info'>{$error}</div>";
            }
            ?>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 15px;
    margin-top: 1rem;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

.table th {
    font-weight: 600;
    background-color: #f8f9fa;
}

.badge {
    padding: 0.5em 0.75em;
    font-weight: 500;
}

.form-select {
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
}

.table-responsive {
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.05);
}
</style>

<script>
function submitForm(obj) 
{
    formObj = document.getElementById('txnlist_form');

    if(obj.name == "class"){
        formObj.elements['user_id'].selectedIndex = 0;
        formObj.elements['txn_status'].selectedIndex = 0;
    }

    if(obj.name == "user_id"){
        formObj.elements['txn_status'].selectedIndex = 0;
    }

    formObj.submit();
}
</script>

<?php 
$content = ob_get_contents();
ob_end_clean();
require_once 'master.php'
?>