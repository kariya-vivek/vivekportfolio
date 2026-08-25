<?php



    session_start();
    $ad_profile = $_SESSION["admin_id"];
    $adid = $ad_profile;
    if ($ad_profile == false) {
        header('location:adminlogin.php');
        exit;
    }
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_status'])) {
    $target  = $_POST['target'] ?? '';
    $id      = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $current = strtolower(trim($_POST['current_status'] ?? ''));
    $section = $_POST['section'] ?? ''; // 👈 get section from hidden field

    $next = ($current === 'active') ? 'suspend' : 'active';

    $map = [
        'product'  => ['table' => 'product_table', 'id_col' => 'product_id'],
        'customer' => ['table' => 'craftus_reg',   'id_col' => 'u_id'],
        'seller'   => ['table' => 'seller',        'id_col' => 'sellerid'],
    ];

    if (isset($map[$target]) && $id > 0) {
        $tbl = $map[$target]['table'];
        $col = $map[$target]['id_col'];

        $con = mysqli_connect("localhost", "root", "", "craftzon");
        if ($con) {
            $sql = "UPDATE `$tbl` SET `status` = ? WHERE `$col` = ?";
            if ($stmt = mysqli_prepare($con, $sql)) {
                mysqli_stmt_bind_param($stmt, "si", $next, $id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
            mysqli_close($con);
        }
    }

    // ✅ Redirect back to same section
    if ($section !== '') {
        header("Location: adminpanel.php#$section");
    } else {
        header("Location: adminpanel.php");
    }
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $target  = $_POST['target'] ?? '';
    $id      = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $section = $_POST['section'] ?? '';

    // only allow delete for craft_story & advertisement
    $map = [
        'craft_story'   => ['table' => 'crafter_story',    'id_col' => 'story_id'],
        'advertisement' => ['table' => 'advertisements',  'id_col' => 'ad_id'],
    ];

    if (isset($map[$target]) && $id > 0) {
        $tbl = $map[$target]['table'];
        $col = $map[$target]['id_col'];

        $con = mysqli_connect("localhost", "root", "", "craftzon");
        if ($con) {
            $sql = "DELETE FROM `$tbl` WHERE `$col` = ?";
            if ($stmt = mysqli_prepare($con, $sql)) {
                mysqli_stmt_bind_param($stmt, "i", $id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
            mysqli_close($con);
        }
    }

    // redirect back to same section
    if ($section !== '') {
        header("Location: adminpanel.php#$section");
    } else {
        header("Location: adminpanel.php");
    }
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Admin Panel</title>

<style>
/* ===== Simple, modern skin (CSS-only) ===== */

/* Color system */
:root{
  --bg: #f6f7fb;
  --surface: #ffffff;
  --text: #1f2937;
  --muted: #6b7280;
  --primary: #2563eb;        /* blue-600 */
  --primary-600: #1d4ed8;    /* hover */
  --success: #16a34a;
  --danger: #dc2626;
  --warning: #d97706;
  --border: #e5e7eb;
  --shadow: 0 8px 24px rgba(17,24,39,.08);
  --radius: 14px;

  /* aliases for your existing JS color refs */
  --royal-blue: var(--primary);
  --royal-green: var(--success);
  --royal-red: var(--danger);
  --royal-white: #ffffff;
  --dark-gray: #1f2937;
  --light-gray: #e5e7eb;
}

/* Base */
*{ box-sizing:border-box }
html,body{ height:100% }
body{
  margin:0; padding:0;
  font-family: ui-sans-serif, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji","Segoe UI Emoji";
  color:var(--text);
  background:var(--bg);
  display:flex; min-height:100vh;
}

/* ===== Left Side Panel ===== */
.side-panel{
  width:260px;
  height:auto;
  background:#0f172a;             /* slate-900 */
  color:#e5e7eb;
  padding:22px 16px 16px;
  position:relative;
  box-shadow: var(--shadow);
  transition: width .25s ease;
}
.side-panel.collapsed{ width:72px }

/* brand text */
.side-panel::before{
  content:"Craftzon Admin";
  display:block;
  font-weight:700;
  letter-spacing:.3px;
  font-size:18px;
  color:#fff;
  padding:8px 10px 14px 8px;
  border-bottom:1px solid rgba(255,255,255,.08);
  margin-bottom:10px;
  opacity:.95;
  white-space:nowrap;
}

/* Toggle button */
#toggle-btn{
  position:absolute; top:14px; right:-18px;
  width:36px; height:36px;
  background:var(--primary);
  color:#fff; border:none; border-radius:999px;
  display:flex; align-items:center; justify-content:center;
  font-size:18px; font-weight:700; cursor:pointer;
  box-shadow: var(--shadow);
  transition: transform .15s ease, background .15s ease;
}
#toggle-btn:hover{ background:var(--primary-600); transform: scale(1.05) }
.side-panel.collapsed #toggle-btn{ right:-18px }

/* Menu */
.menu-list{ list-style:none; margin:14px 0 0; padding:0 }
.menu-item{
  display:flex; align-items:center; gap:12px;
  padding:12px 12px;
  margin:6px 4px;
  border-radius:12px;
  cursor:pointer; user-select:none;
  color:#cbd5e1;
  transition: background .15s ease, color .15s ease, transform .05s ease;
}
.menu-item:hover{ background:rgba(255,255,255,.06); color:#fff }
.menu-item.active{ background:linear-gradient(90deg, rgba(37,99,235,.25), rgba(37,99,235,.05)); color:#fff; }
.menu-icon{ font-size:18px; line-height:1 }
.side-panel.collapsed .menu-item-text{ display:none }
.side-panel.collapsed .menu-icon{ margin-right:0; font-size:20px }

/* ===== Content area ===== */
.content{
  flex:1;
  padding:24px;
  overflow:auto;
  position:relative;
}

/* subtle app header “feel” */
.content::before{
  content:"Admin Panel";
  position:sticky; top:0; z-index:5;
  display:block;
  background:rgba(255,255,255,.8);
  backdrop-filter: blur(6px);
  -webkit-backdrop-filter: blur(6px);
  color:var(--text);
  font-weight:700; letter-spacing:.2px;
  padding:14px 18px;
  border:1px solid var(--border);
  border-radius:12px;
  box-shadow: var(--shadow);
  margin-bottom:18px;
}

/* Panels */
.panel{ display:none; position:relative; z-index:1 }
.panel.active{ display:block }
.panel h2{
  margin:0 0 18px;
  font-size:22px; font-weight:800;
  color:var(--text);
}

/* Panel inner surface */
.panel-placeholder{
  background:var(--surface);
  border:1px solid var(--border);
  border-radius: var(--radius);
  padding:18px;
  text-align:left;
  box-shadow: var(--shadow);
  color:var(--muted);
  overflow:auto;
}

/* ===== Cards / chart containers ===== */
.chart-container{
  background:var(--surface);
  border:1px solid var(--border);
  border-radius: var(--radius);
  padding:18px;
  box-shadow: var(--shadow);
  margin-bottom:22px;
  overflow:auto;
}
.chart-header{
  display:flex; align-items:center; justify-content:space-between;
  padding-bottom:10px; margin-bottom:12px;
  border-bottom:1px dashed var(--border);
}
.chart-header h3{ margin:0; font-size:18px; color:var(--text) }

/* Period selector buttons */
.period-selector{ display:flex; gap:8px }
.period-selector button{
  appearance:none; border:1px solid var(--border);
  background:#f8fafc;
  padding:8px 12px; border-radius:10px; cursor:pointer;
  font-weight:600; color:var(--text);
  transition: background .15s ease, border-color .15s ease, transform .03s ease;
}
.period-selector button:hover{ background:#eef2ff; border-color:#c7d2fe }
.period-selector button:active{ transform: translateY(1px) }
.period-selector button.active{
  background:var(--primary);
  color:#fff; border-color:var(--primary);
}

/* Bar chart look (JS builds bars) */
.bar-chart{
  display:flex; align-items:flex-end; gap:10px; height:220px;
  padding:10px; border:1px dashed var(--border);
  border-radius:12px; background:#f8fafc;
}
.bar{
  width:28px; background:var(--primary);
  border-radius:10px 10px 6px 6px;
  position:relative; display:flex; justify-content:flex-end; flex-direction:column;
  transition: height .45s ease;
  box-shadow: inset 0 -10px 20px rgba(255,255,255,.18);
}
.bar.bar-red{ background:var(--danger) }
.bar.bar-white{ background:#fff; border:1px solid var(--border) }
.bar-value{
  position:absolute; top:-22px; left:50%; transform:translateX(-50%);
  font-size:11px; font-weight:700; color:var(--text);
  background:#fff; border:1px solid var(--border); padding:2px 6px; border-radius:8px;
  box-shadow: var(--shadow);
}
.bar-label{
  position:absolute; bottom:-20px; left:50%; transform:translateX(-50%);
  font-size:11px; color:var(--muted);
}

/* Top products list */
.top-products-list{ list-style:none; margin:0; padding:0 }
.top-products-list li{
  display:flex; justify-content:space-between; align-items:center;
  padding:10px 0; border-bottom:1px dashed var(--border);
}
.top-products-list li:last-child{ border-bottom:none }

/* Circle graph */
.circle-graph-container{
  width:220px; height:220px; border-radius:50%;
  margin:10px auto 6px;
  border:6px solid #fff; box-shadow: var(--shadow);
}
.circle-graph-legend{
  list-style:none; display:flex; justify-content:center; gap:16px; margin:10px 0 0; padding:0;
}
.legend-item{ display:flex; align-items:center; gap:6px; color:var(--text) }
.legend-color{ width:14px; height:14px; border-radius:4px }

/* ===== Tables ===== */
table{ width:100%; border-collapse:separate; border-spacing:0; margin:0; }
.panel-placeholder > table,
#products-panel table{ overflow:auto; display:table }
th, td{
  text-align:center; padding:10px 12px;
  border-bottom:1px solid var(--border);
  background:#fff;
  vertical-align:middle;
}
th{
  position:sticky; top:0; z-index:2;
  background:#f9fafb;
  color:#111827; font-weight:800;
  border-bottom:1px solid var(--border);
}
tr:nth-child(even) td{ background:#fcfcff }
tr:hover td{ background:#f5f9ff }

/* Product images inside tables */
#products-panel td img{
  width:80px; height:80px; object-fit:cover;
  border-radius:10px; border:1px solid var(--border);
  box-shadow: var(--shadow);
}
#products-panel tr:nth-child(even) td{ background:#fbfdff }

/* Buttons */
.toggle-btn{
  appearance:none;
  border:1px solid var(--border);
  background:var(--primary);
  color:#fff; font-weight:700;
  padding:8px 12px; border-radius:10px; cursor:pointer;
  transition: background .15s ease, transform .03s ease, opacity .15s ease;
  box-shadow: var(--shadow);
}
.toggle-btn:hover{ background:var(--primary-600) }
.toggle-btn:active{ transform: translateY(1px) }
.toggle-btn.suspend{ background:var(--danger) }
.toggle-btn.suspend:hover{ background:#b91c1c }

/* Responsive */
@media (max-width: 1024px){
  .content{ padding:18px }
  .chart-container, .panel-placeholder{ padding:14px }
  .bar{ width:24px }
}
@media (max-width: 820px){
  .side-panel{ width:72px }
  .side-panel::before{ content:"Admin"; font-size:16px }
  .content{ padding:16px }
  th, td{ padding:9px 10px }
}
@media (max-width: 560px){
  .period-selector{ flex-wrap:wrap }
  .content::before{ font-size:15px; padding:10px 12px }
  .bar{ width:18px }
}
/* -------------------- ADDED CSS -------------------- */
.submenu{
  display:none;
  list-style:none;
  margin:0; padding:0 0 0 32px;
}
.submenu-item{
  padding:8px 10px;
  margin:4px 0;
  border-radius:8px;
  cursor:pointer;
  color:#cbd5e1;
  transition: background .15s ease;
}
.submenu-item:hover{ background:rgba(255,255,255,.08); color:#fff }
.has-submenu.active + .submenu{ display:block }

.stats-cards{
  display:grid;
  grid-template-columns: repeat(auto-fit, minmax(180px,1fr));
  gap:18px;
  margin-bottom:22px;
}
.stat-card{
  background:var(--surface);
  padding:16px;
  border:1px solid var(--border);
  border-radius:var(--radius);
  box-shadow:var(--shadow);
  text-align:center;
}
.stat-card h3{ margin:0 0 8px; font-size:16px; color:var(--muted) }
.stat-card p{ margin:0; font-size:22px; font-weight:800; color:var(--text) }
/* ------------------ END ADDED CSS ------------------ */
.stats-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-bottom: 25px;
}

/* Base card */
.stat-card {
  position: relative;
  padding: 20px;
  border-radius: 15px;
  box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
  text-align: center;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  overflow: hidden;
  background: #fff;
}

/* Hover effect */
.stat-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 14px 28px rgba(0, 0, 0, 0.12);
}

/* Optional gradient variants */
.stat-card.revenue {
  background: linear-gradient(135deg, #aab6f3, #c3a7e6); /* lighter purple gradient */
  color: #fffacd; /* light cream text for better contrast */
}

.stat-card.orders {
  background: linear-gradient(135deg, #8ee3d1, #7fb3e6); /* lighter teal-blue gradient */
  color: #d4f1ff; /* soft light cyan for contrast */
}

.stat-card.products {
  background: linear-gradient(135deg, #ffd38a, #fff4a3); /* lighter orange-yellow gradient */
  color: #2b2b2b; /* darker text for visibility on yellow */
}

.stat-card.customers {
  background: linear-gradient(135deg, #ffb3ba, #ffe3b3); /* lighter red-orange gradient */
  color: #2b2b2b; /* dark text for contrast */
}

.stat-card.sellers {
  background: linear-gradient(135deg, #a3e9ff, #8fbfff); /* lighter blue gradient */
  color: #d4f1ff; /* light cyan for better readability */
}
/* Card title */
.stat-card h3 {
  margin: 0 0 10px;
  font-size: 16px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

/* Card value */
.stat-card p {
  margin: 0;
  font-size: 28px;
  font-weight: 800;
  letter-spacing: 0.5px;
}

/* Card footer text */
.stat-card .card-footer {
  margin-top: 8px;
  font-size: 14px;
  color: rgba(255, 255, 255, 0.85); /* slightly brighter for visibility */
}
.delete-btn {
    background-color: #dc2626;   /* Tailwind red-600 */
    color: #fff;                 /* White text */
    border: none;
    padding: 6px 14px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s ease, transform 0.1s ease;
}

.delete-btn:hover {
    background-color: #b91c1c;   /* Darker red on hover */
    transform: scale(1.05);      /* Slight grow */
}

.delete-btn:active {
    background-color: #991b1b;   /* Even darker on click */
    transform: scale(0.95);      /* Press effect */
}


</style>
</head>

<body>
    <!-- Side Panel -->
    <div class="side-panel" id="side-panel">
        <button id="toggle-btn">&lt;</button>
        <ul class="menu-list">
			<li class="menu-item active" onclick="switchPanel('dashboard-panel', this)">
  <span class="menu-icon">🏠</span>
  <span class="menu-item-text">Dashboard</span>
</li>


            <!-- ---------------- ADDED Orders Submenu ---------------- -->
<li class="menu-item has-submenu" onclick="toggleSubmenu(this)">
  <span class="menu-icon">📦</span>
  <span class="menu-item-text">Orders ▾</span>
</li>
<ul class="submenu">
  <li class="submenu-item" onclick="switchPanel('orders-panel', this)">All Orders</li>
  <li class="submenu-item" onclick="switchPanel('auction-panel', this)">All Auctions</li>
	<li class="submenu-item" onclick="switchPanel('wishlist', this)">wishlist</li>
  <li class="submenu-item" onclick="switchPanel('return-orders-panel', this)">Return Orders</li>
  <li class="submenu-item" onclick="switchPanel('cancel-orders-panel', this)">Cancel Orders</li>
  <li class="submenu-item" onclick="switchPanel('cart-panel', this)">Cart</li>
  <li class="submenu-item" onclick="switchPanel('payment-panel', this)">Payments</li>
</ul>
<!-- -------------- END ADDED Orders Submenu -------------- -->

            <li class="menu-item" onclick="switchPanel('products-panel', this)">
                <span class="menu-icon">🛍️</span>
                <span class="menu-item-text">Products</span>
            </li>
            <li class="menu-item has-submenu" onclick="toggleSubmenu(this)">
			  <span class="menu-icon">👥</span>
			  <span class="menu-item-text">Customers ▾</span>
			</li>
			<ul class="submenu">
			  <li class="submenu-item" onclick="switchPanel('customers-panel', this)">All Customers</li>
			  <li class="submenu-item" onclick="switchPanel('email-otp-panel', this)">email-otp</li>
			  <li class="submenu-item" onclick="switchPanel('feedback-panel', this)">Feedback</li>
			  <li class="submenu-item" onclick="switchPanel('follow', this)">follow</li>
			</ul>

            <li class="menu-item has-submenu" onclick="toggleSubmenu(this)">
			  <span class="menu-icon">🏪</span>
			  <span class="menu-item-text">Seller ▾</span>
			</li>
			<ul class="submenu">
			  <li class="submenu-item" onclick="switchPanel('allseller-panel', this)">All Sellers</li>
			  <li class="submenu-item" onclick="switchPanel('sellercommission-panel', this)">Seller Commission</li>
			</ul>
            <li class="menu-item" onclick="switchPanel('advertise-panel', this)">
    <span class="menu-icon">📈</span>
    <span class="menu-item-text">Advertise</span>
</li>
            <li class="menu-item" onclick="switchPanel('contact-panel', this)">
				<span class="menu-icon">📧</span>
				<span class="menu-item-text">Contact Us</span>
			</li>

           <li class="menu-item" onclick="switchPanel('craft_story', this)">
    <span class="menu-icon">🎨</span>
    <span class="menu-item-text">Craft Story</span>
</li>
			<li class="menu-item" onclick="location.href='adminlogout.php'">
				<span class="menu-icon">🚪</span>
				<span class="menu-item-text">Logout</span>
			</li>

        </ul>
    </div>

    <!-- Content -->
    <div class="content">

        <!-- ---------------- Dashboard Panel ---------------- -->
<div id="dashboard-panel" class="panel active">
  <h2>Dashboard</h2>

  <!-- ---------------- ADDED Summary Cards ---------------- -->
		 <div class="stats-cards">
    
<?php



$con = mysqli_connect("localhost", "root", "", "Craftzon");

// --- Step 0: Set current month-year ---
$monthYear = date('Y-m'); // e.g., "2025-09"

// --- Step 1: Get delivered orders for current month, grouped by seller ---
$deliveredSalesQuery = "
    SELECT seller_id, SUM(price * quantity) AS delivered_sales
    FROM craftorder
    WHERE LOWER(order_status) = 'delivered'
      AND MONTH(ordertime) = MONTH(CURDATE())
      AND YEAR(ordertime) = YEAR(CURDATE())
    GROUP BY seller_id
";

$result = mysqli_query($con, $deliveredSalesQuery);

if($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $seller_id = $row['seller_id'];
        $delivered_sales = $row['delivered_sales'];
        $commission = round($delivered_sales * 0.05, 2); // 5% commission

        // --- Step 2: Check if commission for this seller and month already exists ---
        $checkQuery = "
            SELECT id 
            FROM seller_commission 
            WHERE seller_id = $seller_id 
              AND month_year = '$monthYear'
        ";
        $checkRes = mysqli_query($con, $checkQuery);

        if (mysqli_num_rows($checkRes) > 0) {
            // Update existing commission record
            $updateQuery = "
                UPDATE seller_commission
                SET delivered_sales = $delivered_sales,
                    commission = $commission
                WHERE seller_id = $seller_id 
                  AND month_year = '$monthYear'
            ";
            mysqli_query($con, $updateQuery);
        } else {
            // Insert new commission record
            $insertQuery = "
                INSERT INTO seller_commission 
                (seller_id, month_year, delivered_sales, commission, status)
                VALUES ($seller_id, '$monthYear', $delivered_sales, $commission, 'unpaid')
            ";
            mysqli_query($con, $insertQuery);
        }
    }
}

// --- Optional: Display total commission for all sellers ---
$totalCommissionQuery = "
    SELECT SUM(commission) AS totalCommission 
    FROM seller_commission
    WHERE status IN ('paid')
";
$totalRes = mysqli_query($con, $totalCommissionQuery);
$totalRow = mysqli_fetch_assoc($totalRes);
$totalCommission = $totalRow['totalCommission'] ?? 0;

$formattedTotalCommission = number_format($totalCommission, 2);
?>

<!-- Total Revenue Card -->
<div class="stat-card revenue">
    <h3>Total Revenue</h3>
     <?php


 echo $formattedTotalCommission; ?> <br>
    <div class="card-footer">
        Based on all delivered orders (5% commission) + all active auction fees
    </div>
</div>

    <!-- Total Orders -->
    <div class="stat-card orders">
        <h3>Total Orders</h3>
        <p><?php


 
            $res = mysqli_query($con,"SELECT COUNT(*) AS total FROM craftorder");
            $row = mysqli_fetch_assoc($res);
            echo $row['total'] ?? 0;
        ?></p>
    </div>

    <!-- Active Products -->
    <div class="stat-card products">
        <h3>Active Products</h3>
        <p><?php


 
            $res = mysqli_query($con,"SELECT COUNT(*) AS total FROM product_table WHERE status='active'");
            $row = mysqli_fetch_assoc($res);
            echo $row['total'] ?? 0;
        ?></p>
    </div>

    <!-- Active Customers -->
    <div class="stat-card customers">
        <h3>Active Customers</h3>
        <p><?php


 
            $res = mysqli_query($con,"SELECT COUNT(*) AS total FROM craftus_reg WHERE status='active'");
            $row = mysqli_fetch_assoc($res);
            echo $row['total'] ?? 0;
        ?></p>
    </div>

    <!-- Active Sellers -->
    <div class="stat-card sellers">
        <h3>Active Sellers</h3>
        <p><?php


 
            $res = mysqli_query($con,"SELECT COUNT(*) AS total FROM seller WHERE status='active'");
            $row = mysqli_fetch_assoc($res);
            echo $row['total'] ?? 0;
            mysqli_close($con);
        ?></p>
    </div>
</div>

  <!-- -------------- END ADDED Summary Cards --------------- -->

<!-- -------------- END Dashboard Panel -------------- -->

		<!-- Data Charts Panel -->
            <div class="chart-container">
                <div class="chart-header">
                    <h3>Gross Sales Calculation</h3>
                    <div class="period-selector">
                        <button class="active" onclick="renderSalesGraph('year', this)">Year</button>
                        <button onclick="renderSalesGraph('month', this)">Month</button>
                        <button onclick="renderSalesGraph('week', this)">Week</button>
                    </div>
                </div>
                <div class="bar-chart" id="sales-graph"></div>
            </div>

            <div class="chart-container">
                <h3>Recent Order Analytics (Today)</h3>
                <div class="bar-chart" id="recent-orders-graph"></div>
            </div>

            <div class="chart-container">
                <h3>Top 5 Selling Products</h3>
                <ul class="top-products-list" id="top-products-list"></ul>
            </div>

            <div class="chart-container">
                <h3>Website Visitor Funnel</h3>
                <div class="circle-graph-container" id="visit-circle-graph"></div>
                <ul class="circle-graph-legend" id="visit-legend"></ul>
            </div>
        </div>

        <!-- Orders Panel -->
        <div id="orders-panel" class="panel">
            <h2>Orders Management</h2>
            <div class="panel-placeholder">
                <table>
                    <tr>
                        <th>UID</th>
                        <th>Product ID</th>
                        <th>Order ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Address</th>
                        <th>Payment Method</th>
                        <th>Order Time</th>
                        <th>Order Status</th>
                        <th>Expected Delivery Date</th>
                        <th>Order Request Status</th>
                        <th>Processed Date</th>
                    </tr>
                    <?php



                        $con = mysqli_connect("localhost", "root", "", "craftzon");
                        if (!$con) { die("Connection failed: " . mysqli_connect_error()); }
                        $sql = "SELECT * FROM craftorder";
                        $result = mysqli_query($con, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>
                                    <td>{$row['uid']}</td>
                                    <td>{$row['productid']}</td>
                                    <td>{$row['orderid']}</td>
                                    <td>{$row['fullname']}</td>
                                    <td>{$row['email']}</td>
                                    <td>{$row['productnm']}</td>
                                    <td>{$row['quantity']}</td>
                                    <td>{$row['price']}</td>
                                    <td>{$row['address']}</td>
                                    <td>{$row['paymentmethod']}</td>
                                    <td>{$row['ordertime']}</td>
                                    <td>{$row['order_status']}</td>
                                    <td>{$row['excepdelivdate']}</td>
                                    <td>{$row['order_request_status']}</td>
                                    <td>{$row['processed_date']}</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='15'>No records found</td></tr>";
                        }
                        // do not close $con here; it will be reused by other panels' own connections as in your original code
                    ?>
                </table>
            </div>
        </div>
	<div id="follow" class="panel">
    <h2>Follow Panel</h2>
    <div class="panel-placeholder">
        <table>
            <tr>
                <th>Follow ID</th>
                <th>Seller ID</th>
                <th>User ID</th>
            </tr>
            <?php



                $con = mysqli_connect("localhost", "root", "", "craftzon");
                if (!$con) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                $sql = "SELECT * FROM follow ORDER BY followid DESC";
                $result = mysqli_query($con, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                            <td>{$row['followid']}</td>
                            <td>{$row['sellerid']}</td>
                            <td>{$row['userid']}</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No follow records found</td></tr>";
                }

                mysqli_close($con);
            ?>
        </table>
    </div>
</div>
<div id="wishlist" class="panel">
    <h2>Wishlist</h2>
    <div class="panel-placeholder">
        <table>
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>Product ID</th>
                <th>Created At</th>
            </tr>
            <?php



                $con = mysqli_connect("localhost", "root", "", "craftzon");
                if (!$con) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                $sql = "SELECT * FROM wishlist ORDER BY created_at DESC";
                $result = mysqli_query($con, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['user_id']}</td>
                            <td>{$row['product_id']}</td>
                            <td>{$row['created_at']}</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No wishlist entries found</td></tr>";
                }

                mysqli_close($con);
            ?>
        </table>
    </div>
</div>
<div id="craft_story" class="panel">
    <h2>Craft Story</h2>
    <div class="panel-placeholder">
        <table>
            <tr>
                <th>Story ID</th>
                <th>Seller ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Media Type</th>
                <th>Media</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>

            <?php



                // Connect to database
                $con = mysqli_connect("localhost", "root", "", "craftzon");
                if (!$con) {
                    die("Connection failed: " . mysqli_connect_error());
                }

               

                // Fetch stories
                $sql = "SELECT * FROM crafter_story ORDER BY created_at DESC";
                $result = mysqli_query($con, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>{$row['story_id']}</td>
                                <td>{$row['seller_id']}</td>
                                <td>" . htmlspecialchars($row['title']) . "</td>
                                <td>" . htmlspecialchars($row['description']) . "</td>
                                <td>{$row['media_type']}</td>
                                <td>";

                        // Show media
                        if ($row['media_type'] === 'image') {
                            echo "<img src='../" . htmlspecialchars($row['media_path']) . "' 
                                      alt='Craft Media' 
                                      width='100' 
                                      height='80' 
                                      style='object-fit:cover; border-radius:6px;'>";
                        } elseif ($row['media_type'] === 'video') {
                            echo "<video width='150' height='80' controls>
                                      <source src='../" . htmlspecialchars($row['media_path']) . "' type='video/mp4'>
                                      Your browser does not support the video tag.
                                  </video>";
                        } else {
                            echo htmlspecialchars($row['media_path']);
                        }

                        echo "</td>
    <td>{$row['created_at']}</td>
    <td>
        <form method='post' action='adminpanel.php'>
            <input type='hidden' name='target' value='craft_story'>
            <input type='hidden' name='id' value='{$row['story_id']}'>
            <input type='hidden' name='section' value='craft_story'>
            <button type='submit' name='delete' class='delete-btn'  onclick=\"return confirm('Delete this story?')\">
                Delete
            </button>
        </form>
    </td>
</tr>";

                    }
                } else {
                    echo "<tr><td colspan='8'>No craft stories found</td></tr>";
                }

                mysqli_close($con);
            ?>
        </table>
    </div>
</div>

<div id="email-otp-panel" class="panel">
    <h2>Email OTP Panel</h2>
    <div class="panel-placeholder">
        <table>
            <tr>
                <th>Email ID</th>
                <th>OTP</th>
            </tr>
            <?php



                $con = mysqli_connect("localhost", "root", "", "craftzon");
                if (!$con) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                $sql = "SELECT * FROM email_otp ORDER BY emailid ASC";
                $result = mysqli_query($con, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                            <td>{$row['emailid']}</td>
                            <td>{$row['otp']}</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No OTP records found</td></tr>";
                }

                mysqli_close($con);
            ?>
        </table>
    </div>
</div>
        <!-- Products Panel -->
        <div id="products-panel" class="panel">
            <h2>Product Management</h2>
            <div class="panel-placeholder">
                <table>
                    <tr>
                        <th>Product ID</th>
                        <th>Product Name</th>
                        <th>Crafted By</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock Quantity</th>
                        <th>Product Description</th>
                        <th>Image</th>
                        <th>Created At</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    <?php



                        $con = mysqli_connect("localhost", "root", "", "craftzon");
                        if (!$con) { die("Connection failed: " . mysqli_connect_error()); }
                        $sql = "SELECT * FROM product_table";
                        $result = mysqli_query($con, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $pid    = (int)$row['product_id'];
                                $status = htmlspecialchars($row['status']);
                                $btnTxt = (strtolower($status) === 'active') ? 'Suspend' : 'Activate';
                                $btnCls = (strtolower($status) === 'active') ? 'toggle-btn suspend' : 'toggle-btn';
                                echo "<tr>
                                    <td>{$row['product_id']}</td>
                                    <td>{$row['product_name']}</td>
                                    <td>{$row['crafted_by']}</td>
                                    <td>{$row['category']}</td>
                                    <td>{$row['price']}</td>
                                    <td>{$row['stock_quantity']}</td>
                                    <td>{$row['product_description']}</td>
                                    <td><img src='../{$row['image']}' alt='Product Image'></td>
                                    <td>{$row['created_at']}</td>
                                    <td>{$status}</td>
                                    <td>
                                        <form method='post' action=''>
                                            <input type='hidden' name='toggle_status' value='1'/>
                                            <input type='hidden' name='target' value='product'/>
                                            <input type='hidden' name='id' value='{$pid}'/>
											<input type='hidden' name='section' value='products-panel'>
                                            <input type='hidden' name='current_status' value='{$status}'/>
                                            <button type='submit' class='{$btnCls}'>{$btnTxt}</button>
                                        </form>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='11'>No records found</td></tr>";
                        }
                    ?>
                </table>
            </div>
        </div>

        <!-- Customers Panel -->
        <div id="customers-panel" class="panel">
            <h2>Customer & Feedback</h2>
            <div class="panel-placeholder">
                <table>
                    <tr>
                        <th>User ID</th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Mobile No</th>
                        <th>Password</th>
						<th>profile photo</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    <?php



                        $con = mysqli_connect("localhost", "root", "", "craftzon");
                        if (!$con) { die("Connection failed: " . mysqli_connect_error()); }
                        $sql = "SELECT * FROM craftus_reg";
                        $result = mysqli_query($con, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $uid    = (int)$row['u_id'];
                                $status = htmlspecialchars($row['status']);
                                $btnTxt = (strtolower($status) === 'active') ? 'Suspend' : 'Activate';
                                $btnCls = (strtolower($status) === 'active') ? 'toggle-btn suspend' : 'toggle-btn';
                                echo "<tr>
                                    <td>{$row['u_id']}</td>
                                    <td>{$row['uname']}</td>
                                    <td>{$row['email']}</td>
                                    <td>{$row['mobile_no']}</td>
                                    <td>{$row['password']}</td>
									<td><img src='../{$row['profile_img']}' alt='Profile' style='width:70px; height:70px; object-fit:cover;'></td>
                                    <td>{$status}</td>
                                    <td>
                                        <form method='post' action=''>
                                            <input type='hidden' name='toggle_status' value='1'/>
                                            <input type='hidden' name='target' value='customer'/>
                                            <input type='hidden' name='id' value='{$uid}'/>
											<input type='hidden' name='section' value='customers-panel'>
                                            <input type='hidden' name='current_status' value='{$status}'/>
                                            <button type='submit' class='{$btnCls}'>{$btnTxt}</button>
                                        </form>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>No users found</td></tr>";
                        }
                        mysqli_close($con);
                    ?>
                </table>
            </div>
        </div>
		<div id="auction-panel" class="panel">
			<h3>Auction Table</h3>
			<div class="panel-placeholder">
			<table>
				<thead>
					<tr>
						<th>Auction ID</th>
						<th>Product ID</th>
						<th>Starting Price</th>
						<th>Current Bid</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					<?php



					// --- DB Connection ---
					$con = mysqli_connect("localhost", "root", "", "craftzon");
					if (!$con) {
						die("Database connection failed: " . mysqli_connect_error());
					}

					// Simple query for main admin panel
					$query = "SELECT * FROM auction_table";
					$result = mysqli_query($con, $query);

					if ($result && mysqli_num_rows($result) > 0) {
						while ($row = mysqli_fetch_assoc($result)) {
							echo "<tr>";
							echo "<td>" . $row['auction_id'] . "</td>";
							echo "<td>" . $row['product_id'] . "</td>";
							echo "<td>₹" . $row['start_price'] . "</td>";
							echo "<td>₹" . $row['current_price'] . "</td>";
							echo "<td>" . ucfirst($row['status']) . "</td>";
							echo "</tr>";
						}
					} else {
						echo "<tr><td colspan='5'>No auctions found</td></tr>";
					}
					?>
				</tbody>
			</table>
			</div>
		</div>

        <!-- Seller Panel -->
        <div id="allseller-panel" class="panel">
            <h2>Seller Panel</h2>
            <div class="panel-placeholder">
                <table>
                    <tr>
                        <th>Seller ID</th>
                        <th>Store Name</th>
                        <th>Seller Name</th>
                        <th>Email</th>
                        <th>GSTIN No</th>
                        <th>Shop Image</th>
                        <th>Description</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    <?php



                        $con = mysqli_connect("localhost", "root", "", "craftzon");
                        if (!$con) { die("Connection failed: " . mysqli_connect_error()); }
                        $sql = "SELECT * FROM seller";
                        $result = mysqli_query($con, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $sid    = (int)$row['sellerid'];
                                $status = htmlspecialchars($row['status']);
                                $btnTxt = (strtolower($status) === 'active') ? 'Suspend' : 'Activate';
                                $btnCls = (strtolower($status) === 'active') ? 'toggle-btn suspend' : 'toggle-btn';
                                echo "<tr>
                                    <td>{$row['sellerid']}</td>
                                    <td>{$row['storenm']}</td>
                                    <td>{$row['sellernm']}</td>
                                    <td>{$row['selleremailid']}</td>
                                    
                                    <td>{$row['gstinno']}</td>
                                    <td><img src='../{$row['shopimage']}' alt='Shop Image' width='80' height='80' style='object-fit:cover;border-radius:6px;'></td>
                                    <td>{$row['description']}</td>
                                    <td>{$row['time']}</td>
                                    <td>{$status}</td>
                                    <td>
                                        <form method='post' action=''>
                                            <input type='hidden' name='toggle_status' value='1'/>
                                            <input type='hidden' name='target' value='seller'/>
                                            <input type='hidden' name='id' value='{$sid}'/>
											<input type='hidden' name='section' value='allseller-panel'> 
                                            <input type='hidden' name='current_status' value='{$status}'/>
                                            <button type='submit' class='{$btnCls}'>{$btnTxt}</button>
                                        </form>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='11'>No sellers found</td></tr>";
                        }
                        
                    ?>
                </table>
            </div>
        </div>
	<div id="sellercommission-panel" class="panel">
    <h2>Seller Commission</h2>
    <div class="panel-placeholder">
        <table>
            <tr>
                <th>ID</th>
                <th>Seller ID</th>
                <th>Month/Year</th>
                <th>Delivered Sales</th>
                <th>Commission</th>
                <th>Status</th>
                <th>Payment Method</th>
                <th>UPI ID</th>
                <th>Created At</th>
                <th>Last Order ID</th>
            </tr>
            <?php



                $con = mysqli_connect("localhost", "root", "", "craftzon");
                if (!$con) { die("Connection failed: " . mysqli_connect_error()); }

                $sql = "SELECT * FROM seller_commission";
                $result = mysqli_query($con, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['seller_id']}</td>
                            <td>{$row['month_year']}</td>
                            <td>₹{$row['delivered_sales']}</td>
                            <td>₹{$row['commission']}</td>
                            <td>{$row['status']}</td>
                            <td>{$row['payment_method']}</td>
                            <td>{$row['upi_id']}</td>
                            <td>{$row['created_at']}</td>
                            <td>{$row['last_order_id']}</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='10'>No commission records found</td></tr>";
                }

                mysqli_close($con);
            ?>
        </table>
    </div>
</div>

<div id="feedback-panel" class="panel">
  <h2>Customer Feedback</h2>
  <div class="panel-placeholder">
    <table>
      <tr>
        <th>Feedback ID</th>
        <th>Order ID</th>
        <th>User Name</th>
        <th>Rating</th>
        <th>Comment</th>
        <th>Created At</th>
      </tr>
      <?php



      $con = mysqli_connect("localhost", "root", "", "craftzon");
      if (!$con) die("Connection failed: " . mysqli_connect_error());

      $sql = "SELECT * FROM feedbacks ORDER BY created_at DESC";
      $result = mysqli_query($con, $sql);

      if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr>
                      <td>{$row['fid']}</td>
                      <td>{$row['order_id']}</td>
                      <td>".htmlspecialchars($row['user_name'])."</td>
                      <td>{$row['rating']}</td>
                      <td>".nl2br(htmlspecialchars($row['comment']))."</td>
                      <td>{$row['created_at']}</td>
                    </tr>";
          }
      } else {
          echo "<tr><td colspan='6' style='text-align:center;'>No feedback found</td></tr>";
      }

      mysqli_close($con);
      ?>
    </table>
  </div>
</div>


        <!-- Marketing Panel -->
      <div id="advertise-panel" class="panel">
    <h2>Advertise Panel</h2>
    <div class="panel-placeholder">
        <table>
            <tr>
                <th>Ad ID</th>
                <th>Seller ID</th>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Description</th>
                <th>User Email</th>
                <th>Image</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>

            <?php



                // Connect to database
                $con = mysqli_connect("localhost", "root", "", "craftzon");
                if (!$con) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                

                // Fetch all advertisements
                $sql = "SELECT * FROM advertisements ORDER BY created_at DESC";
                $result = mysqli_query($con, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                            <td>{$row['ad_id']}</td>
                            <td>{$row['seller_id']}</td>
                            <td>{$row['productid']}</td>
                            <td>" . htmlspecialchars($row['product_name']) . "</td>
                            <td>" . htmlspecialchars($row['category']) . "</td>
                            <td>₹" . htmlspecialchars($row['price']) . "</td>
                            <td>" . htmlspecialchars($row['description']) . "</td>
                            <td>" . htmlspecialchars($row['user_email']) . "</td>
                            <td>
                                <img src='../" . htmlspecialchars($row['image']) . "' 
                                     alt='Ad Image' 
                                     width='80' 
                                     height='80' 
                                     style='object-fit:cover; border-radius:6px;'>
                            </td>
                            <td>{$row['created_at']}</td>
                            <td>
								<form method='post' action='adminpanel.php' onsubmit='return confirm(\'Are you sure you want to delete this ad?\');'>
									<input type='hidden' name='target' value='advertisement'>	
									<input type='hidden' name='id' value='{$row['ad_id']}'>
									<input type='hidden' name='section' value='advertise-panel'>
									<button type='submit' name='delete' class='delete-btn'  cursor:pointer;'>Remove</button>
								</form>
							</td>

                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>No advertisements found</td></tr>";
                }

                mysqli_close($con);
            ?>
        </table>
    </div>
</div>


        <!-- contact-panel -->
        <div id="contact-panel" class="panel">
    <h2>Contact Messages</h2>
    <div class="panel-placeholder">
        <table>
            <tr>
                <th>Contact ID</th>
                <th>User ID</th>
                <th>Name</th>
				<th>uemailid</th>
                <th>Email</th>
                <th>Message</th>
                <th>Submitted At</th>
            </tr>
            <?php



                $con = mysqli_connect("localhost", "root", "", "craftzon");
                if (!$con) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                $sql = "SELECT * FROM contactus ORDER BY created_at DESC";
                $result = mysqli_query($con, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['user_id']}</td>
                            <td>{$row['name']}</td>
							<td>{$row['uemailid']}</td>
                            <td>{$row['email']}</td>
                            <td>" . nl2br(htmlspecialchars($row['message'])) . "</td>
                            <td>{$row['created_at']}</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No contact messages found</td></tr>";
                }
            ?>
        </table>
    </div>
</div>



        <!-- Settings Panel -->
        <div id="settings-panel" class="panel">
            <h2>Settings</h2>
            <div class="panel-placeholder">
                <p>This panel would contain administrative settings for the store, such as user permissions, payment gateways, and shipping options. (Simulated)</p>
            </div>
        </div>
		<!-- ---------------- ADDED New Panels ---------------- -->
<div id="return-orders-panel" class="panel">
  <h2>Return Orders</h2>
  <!-- ---------------- Return Orders Panel ---------------- -->
  <div class="panel-placeholder">
    <table>
      <tr>
        <th>Return ID</th>
        <th>User ID</th>
        <th>Order ID</th>
        <th>Reason</th>
        <th>Email</th>
        <th>Comments</th>
        <th>Photo</th>
        <th>Status</th>
        <th>Request Date</th>
        <th>Approve Date</th>
      </tr>
      <?php



        // ✅ Connect database (same style as your other panels)
        $con = mysqli_connect("localhost","root","","craftzon");
        if (!$con) { die("Connection failed: " . mysqli_connect_error()); }

        $sql = "SELECT * FROM return_requests";
        $result = mysqli_query($con, $sql);

        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$row['return_id']}</td>
                    <td>{$row['uretunid']}</td>
                    <td>{$row['order_id']}</td>
                    <td>{$row['reason']}</td>
                    <td>{$row['emailid']}</td>
                    <td>{$row['comments']}</td>
                    <td><img src='../{$row['photo']}' alt='Shop Image' width='80' height='80' style='object-fit:cover;border-radius:6px;'></td>
                    <td>{$row['status']}</td>
                    <td>{$row['request_date']}</td>
                    <td>{$row['approve_date']}</td>
                  </tr>";
          }
        } else {
          echo "<tr><td colspan='10'>No return requests found</td></tr>";
        }
      ?>
    </table>
</div>
<!-- -------------- END Return Orders Panel --------------- -->

  
  </div>


<div id="cancel-orders-panel" class="panel">
  <h2>Cancel Orders</h2>
  <div class="panel-placeholder">
    <table>
      <tr>
        <th>Cancel ID</th>
        <th>User ID</th>
        <th>Order ID</th>
        <th>Email</th>
        <th>Reason</th>
        <th>Comments</th>
        <th>Refund Amount</th>
      </tr>
      <?php



        $con = mysqli_connect("localhost","root","","craftzon");
        if (!$con) { die("Connection failed: " . mysqli_connect_error()); }

        $sql = "SELECT * FROM cancel_orders";
        $result = mysqli_query($con, $sql);

        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$row['cancel_id']}</td>
                    <td>{$row['ucancelid']}</td>
                    <td>{$row['order_id']}</td>
                    <td>{$row['user_email']}</td>
                    <td>{$row['reason']}</td>
                    <td>" . (!empty($row['comments']) ? htmlspecialchars($row['comments']) : '-') . "</td>
                    <td>" . (!empty($row['refund_amount']) ? '₹'.number_format($row['refund_amount'],2) : '-') . "</td>
                  </tr>";
          }
        } else {
          echo "<tr><td colspan='9'>No cancel orders found</td></tr>";
        }
        mysqli_close($con);
      ?>
    </table>
  </div>
</div>

<div id="cart-panel" class="panel">
  <h2>User Cart</h2>
  <div class="panel-placeholder">
    <table>
      <tr>
        <th>Cart ID</th>
        <th>User ID</th>
        <th>User Name</th>
        <th>Product ID</th>
        <th>Product Name</th>
        <th>Quantity</th>
        <th>Added At</th>
      </tr>
      <?php



      // Connect to database
      $con = mysqli_connect("localhost","root","","craftzon");
      if(!$con){ 
          die("Connection failed: " . mysqli_connect_error()); 
      }

      // Query to fetch cart data with user name and product name
      $sql = "SELECT uc.cart_id, uc.user_id, u.uname, uc.product_id, p.product_name, uc.quantity, uc.added_at
              FROM user_cart uc
              JOIN craftus_reg u ON uc.user_id = u.u_id
              JOIN product_table p ON uc.product_id = p.product_id
              ORDER BY uc.added_at DESC";

      $result = mysqli_query($con, $sql);

      if(mysqli_num_rows($result) > 0){
          while($row = mysqli_fetch_assoc($result)){
              echo "<tr>
                      <td>{$row['cart_id']}</td>
                      <td>{$row['user_id']}</td>
                      <td>{$row['uname']}</td>
                      <td>{$row['product_id']}</td>
                      <td>{$row['product_name']}</td>
                      <td>{$row['quantity']}</td>
                      <td>{$row['added_at']}</td>
                    </tr>";
          }
      } else {
          echo "<tr><td colspan='7'>No items in cart</td></tr>";
      }

      mysqli_close($con);
      ?>
    </table>
  </div>
</div>

<div id="payment-panel" class="panel">
  <h2>All Payments</h2>
  <div class="panel-placeholder">
    <table>
      <tr>
        <th>Payment ID</th>
        <th>Order ID</th>
        <th>User ID</th>
        <th>User Name</th>
        <th>Product ID</th>
        <th>Product Name</th>
        <th>Seller Name</th>
        <th>Payment Method</th>
        <th>Payment Status</th>
        <th>Amount</th>
        <th>Transaction ID</th>
        <th>Payment Date</th>
      </tr>

<?php



$con = mysqli_connect("localhost", "root", "", "craftzon");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query all payments with order, product, seller, and user info
$sql = "
SELECT p.*, co.productid, co.uid, co.productnm, pt.crafted_by AS seller_name, cu.uname AS user_name
FROM payments p
JOIN craftorder co ON p.order_id = co.orderid
JOIN product_table pt ON co.productid = pt.product_id
JOIN craftus_reg cu ON co.uid = cu.u_id
ORDER BY p.payment_date DESC
";

$result = mysqli_query($con, $sql);

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
            <td>{$row['payment_id']}</td>
            <td>{$row['order_id']}</td>
            <td>{$row['uid']}</td>
            <td>{$row['user_name']}</td>
            <td>{$row['productid']}</td>
            <td>{$row['productnm']}</td>
            <td>{$row['seller_name']}</td>
            <td>{$row['payment_method']}</td>
            <td>{$row['payment_status']}</td>
            <td>₹{$row['amount']}</td>
            <td>{$row['transaction_id']}</td>
            <td>{$row['payment_date']}</td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='12' style='text-align:center;'>No payments found</td></tr>";
}
mysqli_close($con);
?>
    </table>
  </div>
</div>

<!-- -------------- END ADDED New Panels ---------------- -->

    </div>
<!-- ---------------- ADDED JS ---------------- -->
<script>
function toggleSubmenu(element){
  element.classList.toggle("active");
  const submenu = element.nextElementSibling;
  submenu.style.display = submenu.style.display === "block" ? "none" : "block";
}
</script>
<!-- -------------- END ADDED JS ---------------- -->

<script>
const sidePanel = document.getElementById('side-panel');
const toggleBtn = document.getElementById('toggle-btn');
const panels = document.querySelectorAll('.panel');
const menuItems = document.querySelectorAll('.menu-item');

toggleBtn.addEventListener('click', () => {
  sidePanel.classList.toggle('collapsed');
  toggleBtn.innerHTML = sidePanel.classList.contains('collapsed') ? '>' : '<';
});

function switchPanel(panelId, element) {
  panels.forEach(p => p.classList.remove('active'));
  document.getElementById(panelId).classList.add('active');
  menuItems.forEach(i => i.classList.remove('active'));
  element.classList.add('active');
}
// --- Load all dashboard data dynamically ---
fetch("getDashboardData.php")
  .then(res => res.json())
  .then(data => {
    if (!data.status || data.status !== "success") {
      console.error("Error loading dashboard data:", data.message);
      return;
    }

    // Replace static with DB data
    salesData = data.salesData;
    recentOrdersData = data.recentOrdersData;
    topProductsData = data.topProductsData;
    visitData = data.visitData;

    isDatabaseFound = true;

    // Render charts
    renderSalesGraph('year');
    renderRecentOrdersGraph();
    renderTopProducts();
    renderCircleGraph();
  })
  .catch(err => console.error("Error loading dashboard data:", err));

// --- Initialize with empty data ---
let salesData = { year: [], month: [], week: [] };
let recentOrdersData = [];
let topProductsData = [];
let visitData = {};
let isDatabaseFound = false;

// --- Renderers (unchanged from your code) ---
function renderSalesGraph(period, button) {
  const data = isDatabaseFound ? salesData[period] : salesData[period].map(d => ({ ...d, value: 0 }));
  const graphContainer = document.getElementById('sales-graph');
  graphContainer.innerHTML = '';
  const maxValue = Math.max(...data.map(d => d.value), 0);
  data.forEach(item => {
    const bar = document.createElement('div');
    bar.className = 'bar';
    const height = maxValue > 0 ? (item.value / maxValue) * 100 : 0;
    bar.style.height = `${height}%`;
    bar.innerHTML = `<span class="bar-value">${item.value}</span><span class="bar-label">${item.label}</span>`;
    graphContainer.appendChild(bar);
  });
  const periodButtons = document.querySelectorAll('.period-selector button');
  periodButtons.forEach(btn => btn.classList.remove('active'));
  if (button) button.classList.add('active'); else if(periodButtons[0]) periodButtons[0].classList.add('active');
}

function renderRecentOrdersGraph() {
  const data = isDatabaseFound ? recentOrdersData : recentOrdersData.map(d => ({ ...d, value: 0 }));
  const graphContainer = document.getElementById('recent-orders-graph');
  graphContainer.innerHTML = '';
  const maxValue = Math.max(...data.map(d => d.value), 0);
  data.forEach(item => {
    const bar = document.createElement('div');
    bar.className = 'bar';
    bar.style.backgroundColor = item.color;
    const height = maxValue > 0 ? (item.value / maxValue) * 100 : 0;
    bar.style.height = `${height}%`;
    bar.innerHTML = `<span class="bar-value">${item.value}</span><span class="bar-label">${item.label}</span>`;
    graphContainer.appendChild(bar);
  });
}

function renderTopProducts() {
  const list = document.getElementById('top-products-list');
  list.innerHTML = '';
  const data = isDatabaseFound ? topProductsData : topProductsData.map(d => ({ ...d, sales: 0 }));
  data.forEach(product => {
    const li = document.createElement('li');
    li.innerHTML = `<span>${product.name}</span><strong>${product.sales} sales</strong>`;
    list.appendChild(li);
  });
}

function renderCircleGraph() {
  const graph = document.getElementById('visit-circle-graph');
  const legend = document.getElementById('visit-legend');
  legend.innerHTML = '';
  const data = isDatabaseFound ? visitData : { visits: 0, productViews: 0, purchases: 0 };
  const total = data.visits;
  const productViewsPercent = total > 0 ? (data.productViews / total) * 100 : 0;
  const purchasesPercent = total > 0 ? (data.purchases / total) * 100 : 0;

  const background = `conic-gradient(
    var(--royal-blue) 0% ${productViewsPercent}%,
    var(--royal-green) ${productViewsPercent}% ${productViewsPercent + purchasesPercent}%,
    var(--royal-white) ${productViewsPercent + purchasesPercent}% 100%
  )`;
  graph.style.backgroundImage = background;

  const legendItems = [
    { label: 'Visits', color: 'var(--royal-blue)' },
    { label: 'Product Views', color: 'var(--royal-green)' },
    { label: 'Purchases', color: 'var(--royal-white)', borderColor: 'var(--dark-gray)' }
  ];
  legendItems.forEach(item => {
    const li = document.createElement('li');
    li.className = 'legend-item';
    const colorSpan = document.createElement('span');
    colorSpan.className = 'legend-color';
    colorSpan.style.backgroundColor = item.color;
    if (item.borderColor) colorSpan.style.border = `1px solid ${item.borderColor}`;
    li.appendChild(colorSpan);
    li.innerHTML += `<span>${item.label} (${item.label === 'Visits' ? data.visits : (item.label === 'Product Views' ? data.productViews : data.purchases)})</span>`;
    legend.appendChild(li);
  });
}



// Init
window.onload = function() {
  renderSalesGraph('year');
  renderRecentOrdersGraph();
  renderTopProducts();
  renderCircleGraph();
}
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    if (location.hash) {
        let hash = location.hash.substring(1); // remove "#"
        // Directly call switchPanel with the panel id
        switchPanel(hash);
    }
});
</script>

</body>
</html>
