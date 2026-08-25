<?php
$con = mysqli_connect("localhost", "root", "", "craftzon");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// ===== Monthly revenue (sum per month) =====
$monthly = [];
$result = mysqli_query($con, "
    SELECT MONTH(order_date) AS month, SUM(total_amount) AS revenue
    FROM order_detail
    GROUP BY MONTH(order_date)
    ORDER BY MONTH(order_date)
");
while ($row = mysqli_fetch_assoc($result)) {
    $monthly[] = $row;
}

// ===== Yearly revenue (sum per year) =====
$yearly = [];
$result = mysqli_query($con, "
    SELECT YEAR(order_date) AS year, SUM(total_amount) AS revenue
    FROM order_detail
    GROUP BY YEAR(order_date)
    ORDER BY YEAR(order_date)
");
while ($row = mysqli_fetch_assoc($result)) {
    $yearly[] = $row;
}

// ===== Seller share (total revenue per seller) =====
$seller = [];
$result = mysqli_query($con, "
    SELECT s.seller_name, SUM(o.total_amount) AS revenue
    FROM order_detail o
    JOIN sellers s ON o.seller_id = s.seller_id
    GROUP BY s.seller_name
");
while ($row = mysqli_fetch_assoc($result)) {
    $seller[] = $row;
}

// ===== Return JSON =====
header('Content-Type: application/json');
echo json_encode([
    "monthly" => $monthly,
    "yearly"  => $yearly,
    "seller"  => $seller
]);
?>
