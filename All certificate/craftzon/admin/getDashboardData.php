<?php
header("Content-Type: application/json");

// --- DB connection ---
$conn = new mysqli("localhost", "root", "", "craftzon");
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => $conn->connect_error]);
    exit;
}

// --- Sales Data (year, month, week) ---
$salesData = ["year" => [], "month" => [], "week" => []];

// Yearly (group by month)
$yearRes = $conn->query("
    SELECT MONTH(ordertime) as m, SUM(price*quantity) as total 
    FROM craftorder 
    WHERE order_status='Delivered' 
    GROUP BY m ORDER BY m
");
$months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
$monthlySales = array_fill(1, 12, 0);
while ($r = $yearRes->fetch_assoc()) {
    $monthlySales[(int)$r['m']] = (int)$r['total'];
}
foreach ($months as $i => $m) {
    $salesData["year"][] = ["label" => $m, "value" => $monthlySales[$i + 1]];
}

// Monthly (last 4 weeks)
// Get current ISO week number
$currentWeek = (int)date('W');

// Query total sales grouped by ISO week number from last 1 month
$monthRes = $conn->query("
    SELECT WEEK(ordertime, 1) as week_number, SUM(price * quantity) as total 
    FROM craftorder 
    WHERE ordertime >= DATE_SUB(NOW(), INTERVAL 1 MONTH) AND order_status = 'Delivered'
    GROUP BY week_number 
    ORDER BY week_number
");

// Store the sales totals with week numbers
$weeklySales = [];
while ($r = $monthRes->fetch_assoc()) {
    $weeklySales[(int)$r['week_number']] = (int)$r['total'];
}

// Get last 4 weeks, including the current one
for ($i = 3; $i >= 0; $i--) {
    $week = $currentWeek - $i;
    $label = "W" . $week;
    $salesData["month"][] = [
        "label" => $label,
        "value" => $weeklySales[$week] ?? 0
    ];
}


// Weekly (last 7 days)
$weekRes = $conn->query("
    SELECT DAYNAME(ordertime) as d, SUM(price*quantity) as total 
    FROM craftorder 
    WHERE ordertime >= DATE_SUB(NOW(), INTERVAL 7 DAY) AND order_status='Delivered'
    GROUP BY d
");
$days = ["Mon","Tue","Wed","Thu","Fri","Sat","Sun"];
$daySales = array_fill_keys($days, 0);
while ($r = $weekRes->fetch_assoc()) {
    $abbr = substr($r['d'], 0, 3);
    if (isset($daySales[$abbr])) {
        $daySales[$abbr] = (int)$r['total'];
    }
}
foreach ($days as $d) {
    $salesData["week"][] = ["label" => $d, "value" => $daySales[$d]];
}

// --- Recent Orders Data (new, sells, returns) ---
$newOrders = $conn->query("SELECT COUNT(*) as c FROM craftorder WHERE order_status='ordered'")->fetch_assoc()['c'];
$sells     = $conn->query("SELECT COUNT(*) as c FROM craftorder WHERE order_status='completed'")->fetch_assoc()['c'];
$returns   = $conn->query("SELECT COUNT(*) as c FROM return_requests")->fetch_assoc()['c'];
$recentOrdersData = [
    ["label" => "New",     "value" => (int)$newOrders, "color" => "var(--royal-blue)"],
    ["label" => "Sells",   "value" => (int)$sells,     "color" => "var(--royal-green)"],
    ["label" => "Returns", "value" => (int)$returns,   "color" => "var(--royal-red)"]
];

// --- Top Products Data ---
$topRes = $conn->query("
    SELECT productnm as name, SUM(quantity) as sales 
    FROM craftorder 
    GROUP BY productid 
    ORDER BY sales DESC 
    LIMIT 5
");
$topProductsData = [];
while ($r = $topRes->fetch_assoc()) {
    $topProductsData[] = ["name" => $r['name'], "sales" => (int)$r['sales']];
}

// --- Visit Data ---
$totalVisits  = $conn->query("SELECT COUNT(*) as c FROM craftus_reg")->fetch_assoc()['c'] * 50;
$productViews = $conn->query("SELECT COUNT(*) as c FROM wishlist")->fetch_assoc()['c'];
$purchases    = $conn->query("SELECT COUNT(*) as c FROM craftorder")->fetch_assoc()['c'];
$visitData    = ["visits" => (int)$totalVisits, "productViews" => (int)$productViews, "purchases" => (int)$purchases];

// --- Final JSON response ---
echo json_encode([
    "status"            => "success",
    "salesData"         => $salesData,
    "recentOrdersData"  => $recentOrdersData,
    "topProductsData"   => $topProductsData,
    "visitData"         => $visitData,
    "debug" => [
        "yearRes_rows"  => $yearRes->num_rows,
        "monthRes_rows" => $monthRes->num_rows,
        "weekRes_rows"  => $weekRes->num_rows
    ]
], JSON_PRETTY_PRINT);

$conn->close();
?>
