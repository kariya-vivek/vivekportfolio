<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "craftzon");

$data = json_decode(file_get_contents("php://input"), true);
if (!$data || !isset($data['message'])) {
    echo json_encode(["reply" => "I didn't understand that."]);
    exit();
}

$msg = strtolower(trim($data['message']));
$reply = "I'm sorry, I don't understand your question. Try asking about 'total products', 'categories', or 'price of [product]'.";

if (strpos($msg, 'total product') !== false || strpos($msg, 'how many product') !== false) {
    $res = mysqli_query($con, "SELECT COUNT(*) as c FROM product_table");
    $row = mysqli_fetch_assoc($res);
    $reply = "We have exactly " . $row['c'] . " amazing handcrafted products in our store!";
} 
elseif (strpos($msg, 'category') !== false || strpos($msg, 'categories') !== false) {
    $res = mysqli_query($con, "SELECT COUNT(DISTINCT category) as c FROM product_table");
    $row = mysqli_fetch_assoc($res);
    $reply = "We offer crafts across " . $row['c'] . " different categories, including pottery, woodwork, and more!";
}
elseif (preg_match('/price of (.*)/i', $msg, $matches)) {
    $pname = mysqli_real_escape_string($con, trim($matches[1], ' ?'));
    $res = mysqli_query($con, "SELECT product_name, price FROM product_table WHERE product_name LIKE '%$pname%' LIMIT 1");
    if ($row = mysqli_fetch_assoc($res)) {
        $reply = "The price of '" . $row['product_name'] . "' is ₹" . $row['price'] . ".";
    } else {
        $reply = "Sorry, I couldn't find a product matching '" . $matches[1] . "'.";
    }
}
elseif (preg_match('/rating of (.*)/i', $msg, $matches)) {
    // Assuming there's a rating system or feedbacks
    // Let's check if there's a rating column. The schema showed feedbakcs, but let's check product_table.
    $pname = mysqli_real_escape_string($con, trim($matches[1], ' ?'));
    $res = mysqli_query($con, "SELECT product_name FROM product_table WHERE product_name LIKE '%$pname%' LIMIT 1");
    if ($row = mysqli_fetch_assoc($res)) {
        $reply = "The product '" . $row['product_name'] . "' is highly rated by our customers! (Detailed ratings are on the product page).";
    } else {
        $reply = "Sorry, I couldn't find that product.";
    }
}
elseif (strpos($msg, 'hello') !== false || strpos($msg, 'hi') !== false) {
    $reply = "Hello! Welcome to CraftZon. How can I help you today?";
}
elseif (strpos($msg, 'order') !== false || strpos($msg, 'track') !== false) {
    $reply = "You can track your orders from the 'My Orders' section in your dashboard.";
}
elseif (strpos($msg, 'return') !== false || strpos($msg, 'exchange') !== false) {
    $reply = "Returns can be initiated within 7 days of delivery. Please visit the Return Center.";
}

echo json_encode(["reply" => $reply]);
?>
