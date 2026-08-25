<?php
$con = mysqli_connect("localhost","root","","Craftzon");

$pid =$_GET['pid'];
$qty =$_GET['qty'];

$q = "SELECT stock_quantity FROM product_table WHERE product_id = $pid";
$res = mysqli_query($con, $q);
$row = mysqli_fetch_assoc($res);

if ($row) {
    if ($qty > $row['stock_quantity']) {
        echo "Only ".$row['stock_quantity']." items are available!";
    } else {
        echo "Stock available";
    }
}
?>
