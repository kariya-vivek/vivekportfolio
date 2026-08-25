<?php
function sendLowStockAlert($seller_id, $product_name, $new_stock) {
    $con = mysqli_connect("localhost", "root", "", "craftzon");

    if (!$con) {
        echo "❌ DB connection failed";
        return;
    }

    $sellerEmailQuery = mysqli_query($con, "SELECT selleremailid FROM seller WHERE sellerid = $seller_id");

    if ($sellerRow = mysqli_fetch_assoc($sellerEmailQuery)) {
        $sellerEmail = $sellerRow['selleremailid'];

        $subjectSeller = "Low Stock Alert - Craftzon";
        $messageSeller = "Hello Seller,<br>
        Your product <b>{$product_name}</b> has only <b>{$new_stock}</b> items left in stock.<br>
        Please restock soon to avoid running out of inventory.";

        $headersSeller = "From: webmaster@craftzon.com\r\n";
        $headersSeller .= "Content-type: text/html\r\n";

        if (mail($sellerEmail, $subjectSeller, $messageSeller, $headersSeller)) {
            echo "✅ Mail sent to {$sellerEmail} (Stock left: {$new_stock})";
        } else {
            echo "❌ Mail failed to {$sellerEmail}";
        }
    } else {
        echo "❌ Seller not found for ID {$seller_id}";
    }
}

// ✅ Allow calling this script directly via GET
if (isset($_GET['seller_id'], $_GET['product_name'], $_GET['new_stock'])) {
    sendLowStockAlert($_GET['seller_id'], $_GET['product_name'], intval($_GET['new_stock']));
}
?>
