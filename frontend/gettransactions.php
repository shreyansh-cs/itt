<?php
include_once 'session.php'; 
ob_start();
$title = "Receipts";
?>

<div class='txnlist_contatiner'>
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

//echo $_class,",",$_user_id,",",$_txn_status;

$rows_allclasses = getAllClasses(); 
$rows_allusers = getUserDetailsForClass($_class!="all"?$_class:"",$error);
$rows_receipts = getReceiptsForAdmin($_class!="all"?$_class:"", $_user_id != "all"?$_user_id:"", $_txn_status != "all"? $_txn_status : "",$error);

?>

<div class='txnlist_form_container'>
<form id='txnlist_form' name='txnlist_form' method='get'>
<select id="class" name="class" onchange="submitForm(this)">
    <option value='all'>All</option>
    <?php 
        foreach ($rows_allclasses as $row) {
            echo "<option value='".$row['ID']."'". checkSelected($row['ID'],$_class) .">".$row['NAME']."</option>";
        }
    ?>
</select>

<select id="txnlist_user_id" name="user_id" onchange="submitForm(this)">
    <option value='all'>All</option>
    <?php 
        foreach ($rows_allusers as $row) {
            echo "<option value='".$row['ID']."'". checkSelected($row['ID'],$_user_id) .">".$row['NAME']."</option>";
        }
    ?>
</select>
<select id="txnlist_status" name="txn_status" onchange="submitForm(this)">
    <option value='all' <?php if($_txn_status == "all") echo "selected"; ?>>All</option>
    <option value='success' <?php if($_txn_status == "success") echo "selected"; ?>>Success</option>
    <option value='failed' <?php if($_txn_status == "failed") echo "selected"; ?>>Failed</option>
</select>
</form>
</div>

<?php
if($rows_receipts != [])
{
    echo "<table class='receipts_container' border='1'>";
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
        echo "<td>{$priceInRs}</td>";
        $status_color = getStatusColor($row['STATUS']);
        echo "<td style='background-color:$status_color'>{$row['STATUS']}</td>";
        echo "</tr>";
    }
    echo "<tr>";
    echo "</table>";
}
else
{
    echo "<div class='receipts_error'>{$error}</div>";
}
?>
</div>
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