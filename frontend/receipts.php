<?php 
include 'showerror.php';
ob_start();
$title = "Receipts";
?>

<div class='receipts_contatiner'>
<?php
include_once 'session.php';
include_once '../backend/utils.php';
include_once './paymenthandler.php';

$class = "";
if(isset($_SESSION['user_class']))
{
    $class = $_SESSION['user_class'];
}

$user_id = "";
if(isset($_SESSION['user_id']))
{
    $user_id = $_SESSION['user_id'];
}

$package_id = "";
$package_name = "";
$package_price = "";
$receipt_id= "";
$payment_initiated = false;

//Get the current package set for this class
if(getPackageDetails($class,$row,$error))
{
    $package_id = $row['ID'];
    $package_name = $row['NAME'];
    $package_price = $row['PRICE'];
}

if(isset($_POST['init_payment']))
{
    //Create receipt based on current package for this class
    if(createReceipt($user_id,$package_id,$row,$error))
    {
        $receipt_id = $row['ID'];
        //TRICKY --- clickRazorPayButton will programatically click hidden razorpaybutton
        echo "<script>";
        echo " 
            window.onload = function exampleFunction()
            {
                clickRazorPayButton(); 
            }";
        echo "</script>";
        $payment_initiated = true;
    }
    else
    {
        die($error);
    }
}

$__order_id = "";
$mesg = "";
//Do we have any order id whose request is pending to be validated
if(isset($_GET['order_id']))
{
    $__order_id = $_GET['order_id'];
}

if(isset($_SESSION['razorpay_order_id']))
{
    $__order_id = $_SESSION['razorpay_order_id'];
}

if(!empty($__order_id))
{
    handlePostPayment($__order_id,$mesg);   
}

//remove this order id from session 
unset($_SESSION['razorpay_order_id']);

$error="";
$init_form_display = "block";
//Get all receipts even if some other package was set earlier for this class
//Don't delete any package from packages table - Anyway foreigh key won't allow it
if(getReceiptsForThisUser($user_id,$rows,$error))
{
    echo "<table class='receipts' border='1'>";
    echo "<tr>";
    echo "<th>Receipt #</th>";
    echo "<th>Package #</th>";
    echo "<th>Package Name</th>";
    echo "<th>Price</th>";
    echo "<th>Status</th>";
    //echo "<th>Created ON</th>";
    echo "<th>Updated ON</th>";
    echo "</tr>";
    foreach ($rows as $row){
        echo "<tr>";
        echo "<td>{$row['ID']}</td>";
        echo "<td>{$row['PACKAGE_ID']}</td>";
        echo "<td>{$row['PACKAGE_NAME']}</td>";
        echo "<td>{$row['PACKAGE_PRICE']}</td>";
        $status_color = getStatusColor($row['STATUS']);
        echo "<td style='background-color:$status_color'>{$row['STATUS']}</td>";
        //echo "<td>{$row['CREATED_ON']}</td>";
        echo "<td>{$row['UPDATED_ON']}</td>";
        echo "</tr>";
    }
    echo "<tr>";
    echo "<td colspan='6' align='left'>";
    echo "<form method='post' style='display:{$init_form_display}'>";
    echo "<button class='btn_init_payment' id='init_payment' name='init_payment'>Pay {$package_price}</button>";
    echo "</form>";
    if($payment_initiated)
    {
        //echo "Payment initiated";
        $param = array();
        $params['receipt'] = $receipt_id;
        $params['amount'] = $package_price;
        $params['description'] = $package_name;
        $params['image'] = $_SERVER['HTTP_HOST'] . "/itt/images/icon.jpeg";
        $params['name'] = $_SESSION['full_name'];
        $params['email'] = $_SESSION['email'];
        $params['contact'] = $_SESSION['phone'];
        $params['address'] = "Bimal Chowk, Mundipur";
        $params['merchant_order_id'] = $receipt_id;
        createOrder($params,$json,$error);
        //provide the form with hidden button
        echo getForm($json);
    }
    echo $mesg;
    echo "</td>";
    echo "</tr>";
    echo "</table>";
}
else
{
    echo "<div class='receipts_error'>{$error}</div>";
}
?>
</div>
<?php 
$content = ob_get_contents();
ob_end_clean();
require_once 'master.php'
?>