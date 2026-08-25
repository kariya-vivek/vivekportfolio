<?php
	$snm=$_GET['sellernm'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <title>Craftzon Seller Admin Panel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background: #f4f7fa;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: #fff;
            height: 100vh;
            position: fixed;
            transition: width 0.3s;
            overflow-y: auto;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar .logo {
            padding: 20px;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            border-bottom: 1px solid #34495e;
        }

        .sidebar.collapsed .logo {
            font-size: 16px;
            padding: 10px;
        }

        .sidebar ul {
            list-style: none;
            padding: 10px 0;
        }

        .sidebar ul li {
            padding: 15px 20px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .sidebar ul li:hover, .sidebar ul li.active {
            background: #34495e;
        }

        .sidebar ul li i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .sidebar.collapsed ul li span {
            display: none;
        }

        /* Header */
        .header {
            background: #fff;
            padding: 15px 20px;
            margin-left: 250px;
            width: calc(100% - 250px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            transition: margin-left 0.3s, width 0.3s;
        }

        .header.collapsed {
            margin-left: 70px;
            width: calc(100% - 70px);
        }

        .toggle-btn {
            font-size: 20px;
            cursor: pointer;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            margin-top: 60px;
            padding: 20px;
            width: calc(100% - 250px);
            transition: margin-left 0.3s, width 0.3s;
        }

        .main-content.collapsed {
            margin-left: 70px;
            width: calc(100% - 70px);
        }

        .content-section {
            display: none;
        }

        .content-section.active {
            display: block;
        }

        .card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card h2 {
            margin-bottom: 15px;
            font-size: 20px;
            color: #2c3e50;
        }

        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background: #f4f7fa;
        }

        .action-btn {
            padding: 5px 10px;
            background: #3498db;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }

            .sidebar .logo {
                font-size: 16px;
                padding: 10px;
            }

            .sidebar ul li span {
                display: none;
            }

            .header, .main-content {
                margin-left: 70px;
                width: calc(100% - 70px);
            }

            .header.collapsed, .main-content.collapsed {
                margin-left: 70px;
                width: calc(100% - 70px);
            }
        }
		/* Table image styling */
		table td img {
			width: 80px;              /* fixed width */
			height: 80px;             /* fixed height */
			object-fit: cover;        /* crop and fit nicely */
			border-radius: 6px;       /* rounded corners */
			border: 1px solid #ddd;   /* light border */
			box-shadow: 0 2px 5px rgba(0,0,0,0.1); /* subtle shadow */
		}
	

	.process-btn 
	{
		background-color: #4CAF50; /* Green */
		color: white;
		border: none;
		padding: 6px 12px;
		border-radius: 4px;
		cursor: pointer;
	}

	.process-btn:hover 
	{
		background-color: #388E3C; /* Darker green */
	}
	.card 
	{
		overflow-x: auto;
	}
	.process-btn:disabled 
	{
		background-color: #9E9E9E; /* grey */
		color: #ffffff;
		cursor: not-allowed;
		opacity: 0.7; /* slight transparency */
	}
	.sidebar ul li.has-submenu {position:relative;}
.sidebar ul li .submenu {
    display:none;
    list-style:none;
    padding-left:20px;
}
.sidebar ul li.active .submenu {
    display:block;
}
.sidebar ul li .submenu li {
    padding:10px 20px;
    cursor:pointer;
    background:#3a4a5a;
}
.sidebar ul li .submenu li:hover {
    background:#4b5b6b;
}
.dashboard-card h3 {
    font-size: 18px;
    margin-bottom: 10px;
}
.dashboard-card p {
    font-size: 24px;
    font-weight: bold;
}
:root {
  --primary: #4f46e5;
  --danger: #ef4444;
  --text: #1f2937;
  --muted: #6b7280;
  --border: #d1d5db;
  --surface: #ffffff;
  --radius: 12px;
  --shadow: 0 2px 6px rgba(0,0,0,0.08);
}

/* Chart Container */
.chart-container {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 18px;
  box-shadow: var(--shadow);
  margin-bottom: 22px;
  overflow: auto;
}

/* Chart Header */
.chart-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding-bottom: 10px;
  margin-bottom: 12px;
  border-bottom: 1px dashed var(--border);
}

.chart-header h3 {
  margin: 0;
  font-size: 18px;
  color: var(--text);
}

/* Period Selector Buttons */
.period-selector {
  display: flex;
  gap: 8px;
}

.period-selector button {
  appearance: none;
  border: 1px solid var(--border);
  background: #f8fafc;
  padding: 8px 12px;
  border-radius: 10px;
  cursor: pointer;
  font-weight: 600;
  color: var(--text);
  transition: background 0.15s ease, border-color 0.15s ease, transform 0.03s ease;
}

.period-selector button:hover {
  background: #eef2ff;
  border-color: #c7d2fe;
}

.period-selector button:active {
  transform: translateY(1px);
}

.period-selector button.active {
  background: var(--primary);
  color: #fff;
  border-color: var(--primary);
}

/* Bar Chart Container */
.bar-chart {
  display: flex;
  align-items: flex-end;
  gap: 10px;
  height: 220px;
  padding: 10px;
  border: 1px dashed var(--border);
  border-radius: 12px;
  background: #f8fafc;
}

/* Individual Bar */
.bar {
  width: 28px;
  background: var(--primary);
  border-radius: 10px 10px 6px 6px;
  position: relative;
  display: flex;
  justify-content: flex-end;
  flex-direction: column;
  transition: height 0.45s ease;
  box-shadow: inset 0 -10px 20px rgba(255,255,255,0.18);
}

.bar.bar-red {
  background: var(--danger);
}

.bar.bar-white {
  background: #fff;
  border: 1px solid var(--border);
}

/* Bar Value Label */
.bar-value {
  position: absolute;
  top: -22px;
  left: 50%;
  transform: translateX(-50%);
  font-size: 11px;
  font-weight: 700;
  color: var(--text);
  background: #fff;
  border: 1px solid var(--border);
  padding: 2px 6px;
  border-radius: 8px;
  box-shadow: var(--shadow);
}

/* Bar Label */
.bar-label {
  position: absolute;
  bottom: -20px;
  left: 50%;
  transform: translateX(-50%);
  font-size: 11px;
  color: var(--muted);
}

/* Top Products List */
.top-products-list {
  list-style: none;
  margin: 0;
  padding: 0;
}

.top-products-list li {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 0;
  border-bottom: 1px dashed var(--border);
}

.top-products-list li:last-child {
  border-bottom: none;
}

/* Circle Graph */
.circle-graph-container {
  width: 220px;
  height: 220px;
  border-radius: 50%;
  margin: 10px auto 6px;
  border: 6px solid #fff;
  box-shadow: var(--shadow);
}

.circle-graph-container canvas {
  width: 100%;
  height: 100%;
  display: block;
  border-radius: 50%;
}

.circle-graph-container::before {
  content: '';
  display: block;
  width: 100%;
  height: 100%;
  border-radius: 50%;
  background: conic-gradient(
    #4f46e5 0% 80%,
    #10b981 80% 82%,
    #ef4444 82% 100%
  );
}

.circle-graph-legend {
  list-style: none;
  display: flex;
  justify-content: center;
  gap: 16px;
  margin: 10px 0 0;
  padding: 0;
}

.legend-item {
  display: flex;
  align-items: center;
  gap: 6px;
  color: var(--text);
}

.legend-color {
  width: 14px;
  height: 14px;
  border-radius: 4px;
}

/* Common button base style */
button[name="edit"],
button[name="remove"],
button[name="delete"],
button[name="remove_ad"] {
  width: 90px;         /* same width */
  height: 36px;        /* same height */
  border: none;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.3s, transform 0.2s;
  color: #fff;
}

/* Edit button (green) */
button[name="edit"] {
  background: #27ae60;
}

button[name="edit"]:hover {
  background: #1e8449;
  transform: translateY(-2px);
}

/* Remove / Delete buttons (red) */
button[name="remove"],
button[name="delete"],
button[name="remove_ad"] {
  background: #e74c3c;
}

button[name="remove"]:hover,
button[name="delete"]:hover,
button[name="remove_ad"]:hover {
  background: #c0392b;
  transform: translateY(-2px);
}


    </style>
<link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css' rel='stylesheet'>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('submit', function(e) {
        if (e.target && e.target.tagName === 'FORM') {
            if (e.target.dataset.submitted) {
                e.preventDefault();
                return;
            }
            e.target.dataset.submitted = 'true';
            var btn = e.target.querySelector('button[type=\'submit\'], input[type=\'submit\']');
            if (btn) {
                setTimeout(function() {
                    btn.disabled = true;
                    if (btn.tagName === 'BUTTON') {
                        btn.innerHTML = 'Processing...';
                    } else if (btn.tagName === 'INPUT') {
                        btn.value = 'Processing...';
                    }
                }, 10);
            }
        }
    });
});
</script>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="logo">Craftzon</div>
        <ul>
           <li class="active" onclick="showSection('sales')"><i>🏠</i><span>Dashboard</span></li>
            <li class="has-submenu">
    <i>📦</i><span>Orders</span>
    <ul class="submenu">
        <li onclick="showSection('orders_all')">All Orders</li>
		<li onclick="showSection('auction')">Auction Panel</li>
		<li onclick="showSection('wishlist')">wishlist</li>
        <li onclick="showSection('returns')">Returns</li>
        <li onclick="showSection('cancellations')">Cancellations</li>
        <li onclick="showSection('payments')">Payments</li>
        <li onclick="showSection('cart')">Cart</li>
    </ul>
</li>

            <li onclick="showSection('products')"><i>🛍️</i><span>Products</span></li>
            <li class="has-submenu">
    <i>👥</i><span>Customers</span>
    <ul class="submenu">
        <li onclick="showSection('customers')">All Customers</li>
        <li onclick="showSection('feedback')">Feedback</li>
    </ul>
</li>

           <li onclick="showSection('advertisement')"><i>📣</i><span>Advertisement</span></li>
			<li onclick="showSection('craft_story')">
  <i>🖋️</i><span>Craft a Story</span>
</li>

			<li onclick="showSection('contactus')"><i>📬</i><span>Contact Us</span></li>
			
            
			<li onclick="window.location.href='logout.php'">
					<i>🚪</i><span>Logout</span>
			</li>
        </ul>
    </div>

    <!-- Header -->
    <div class="header" id="header">
        <div class="toggle-btn" onclick="toggleSidebar()">☰</div>
        <div class="user-info">
			<?php 
				$con = mysqli_connect("localhost", "root", "", "craftzon"); 
				$sel="SELECT * from seller where sellernm='$snm'";
				$re=mysqli_query($con,$sel);
				$roi=mysqli_fetch_array($re);
			?>
            <span style="color:red"><?php echo $snm; ?></span>
            <img src="../<?php echo $roi['shopimage']; ?>" alt="User">
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="main-content">

    <!-- Dashboard Cards Section -->
    <div class="content-section active" id="sales">
        <div class="card" style="display:flex; gap:20px; flex-wrap:wrap;">
            <!-- Total Revenue -->
            <!-- Total Revenue -->
<div class="dashboard-card" style="flex:1; min-width:200px; background:#4CAF50; color:#fff; padding:20px; border-radius:12px; text-align:center;">
    <h3>Total Revenue</h3>
    <p>₹<?php
        $res = mysqli_query($con, "
    SELECT SUM(co.price * co.quantity) AS total
    FROM craftorder co
    WHERE co.productid IN (
        SELECT product_id FROM product_table WHERE crafted_by='$snm'
    )
    AND co.orderid IN (
        SELECT order_id FROM payments WHERE payment_status='Completed'
    )
");
$row = mysqli_fetch_assoc($res);
echo $row['total'] ?? 0;

    ?></p>
</div>


            <!-- Total Orders -->
            <div class="dashboard-card" style="flex:1; min-width:200px; background:#2196F3; color:#fff; padding:20px; border-radius:12px; text-align:center;">
                <h3>Total Orders</h3>
                <p><?php
                    $res = mysqli_query($con, "SELECT COUNT(*) AS orders FROM craftorder WHERE productid IN (SELECT product_id FROM product_table WHERE crafted_by='$snm')");
                    $row = mysqli_fetch_assoc($res);
                    echo $row['orders'] ?? 0;
                ?></p>
            </div>

            <!-- Total Products -->
            <div class="dashboard-card" style="flex:1; min-width:200px; background:#FF9800; color:#fff; padding:20px; border-radius:12px; text-align:center;">
                <h3>Total Products</h3>
                <p><?php
                    $res = mysqli_query($con, "SELECT COUNT(*) AS products FROM product_table WHERE crafted_by='$snm'");
                    $row = mysqli_fetch_assoc($res);
                    echo $row['products'] ?? 0;
                ?></p>
            </div>

            <!-- Pending Returns -->
           <!-- Pending Returns -->
<div class="dashboard-card" style="flex:1; min-width:200px; background:#f44336; color:#fff; padding:20px; border-radius:12px; text-align:center;">
    <h3>Pending Returns</h3>
    <p><?php
        // Get all product IDs for this seller
        $product_res = mysqli_query($con, "SELECT product_id FROM product_table WHERE crafted_by='$snm'");
        $product_ids = [];
        while ($row = mysqli_fetch_assoc($product_res)) {
            $product_ids[] = $row['product_id'];
        }

        if (!empty($product_ids)) {
            $ids = implode(',', $product_ids);
            // Count return requests with status 'Pending'
            $res = mysqli_query($con, "SELECT COUNT(*) AS pending_returns 
                                       FROM return_requests rr
                                       JOIN craftorder co ON rr.order_id = co.orderid
                                       WHERE co.productid IN ($ids) AND rr.status='Pending'");
            $row = mysqli_fetch_assoc($res);
            echo $row['pending_returns'] ?? 0;
        } else {
            echo 0;
        }
    ?></p>
</div>

		</div>

 <!-- Other sections like Sales, Orders, Products, etc. come here -->

		      <div class="chart-container">
                <div class="chart-header">
                    <h3>Product Sales</h3>
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
                <div class="bar-chart  colorb" id="recent-orders-graph"></div>
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
       <?php
$con = mysqli_connect("localhost", "root", "", "craftzon"); 
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['processed'])) {
    if (!empty($_POST['order_id'])) {
        $order_id = intval($_POST['order_id']);

        // Check current status before updating
        $chk = "SELECT order_request_status FROM craftorder WHERE orderid = $order_id";
        $chkres = mysqli_query($con, $chk);

        if ($chkres && mysqli_num_rows($chkres) > 0) {
            $row = mysqli_fetch_assoc($chkres);

            if ($row['order_request_status'] !== 'processed') {
                // Update only if not already processed
                $upd = "UPDATE craftorder 
                        SET processed_date = NOW(), 
                            order_request_status = 'processed' 
                        WHERE orderid = $order_id";
                mysqli_query($con, $upd);
            }
        }

        // Preserve seller name from POST or GET
       $snm = $_POST['sellernm'] ?? ($_GET['sellernm'] ?? '');
echo "<script>window.location.href='selleradminpanel.php?sellernm=" . urlencode($snm) . "&section=orders_all';</script>";
exit;

    }
}
?>

<div class="content-section" id="orders_all">
    <div class="card">
        <h2>Orders</h2>
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
                <th>Action</th>
            </tr>

<?php
$sel = "SELECT product_id FROM product_table WHERE crafted_by='$snm'";
$res = mysqli_query($con, $sel);
$count = 0;

while ($pid = mysqli_fetch_array($res)) {
    $sql = "SELECT * FROM craftorder WHERE productid='" . $pid['product_id'] . "'";
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
                <td>
                    <form method='post'>
                        <input type='hidden' name='order_id' value='{$row['orderid']}'>
                        <input type='hidden' name='sellernm' value='" . htmlspecialchars($snm) . "'>
                        <input type='submit' name='processed' value='Process' class='process-btn' " 
                        . ($row['order_request_status'] == 'processed' ? "disabled" : "") . ">
                    </form>
                </td>
            </tr>";
            $count++;
        }
    }
}

if ($count == 0) {
    echo "<tr><td colspan='16'>No records found</td></tr>";
}
?>
        </table>
    </div>
</div>

<div class="content-section" id="products">
    <div class="card">
        <h2>Products</h2>
        <table>
            <tr>
                <th>Product ID</th>
                <th>Name</th>
                <th>Crafted By</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock Quantity</th>
                <th>Stock Status</th>
                <th>Description</th>
                <th>Image</th>
                <th>Created At</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php
            // Connect to database
            $con = mysqli_connect("localhost", "root", "", "craftzon");
            if (!$con) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Fetch products
            $sql = "
    SELECT * 
    FROM product_table 
    WHERE crafted_by = '$snm' 
    ORDER BY created_at DESC
";

            $res = mysqli_query($con, $sql);

            if (mysqli_num_rows($res) > 0) {
                while ($row = mysqli_fetch_assoc($res)) {
                    $product_id = (int)$row['product_id'];
                    $product_name = htmlspecialchars($row['product_name']);
                    $crafted_by = htmlspecialchars($row['crafted_by']);
                    $category = htmlspecialchars($row['category']);
                    $price = number_format($row['price'], 2);
                    $stock_quantity = (int)$row['stock_quantity'];
                    $stock_status = htmlspecialchars($row['stock_status']);
                    $description = nl2br(htmlspecialchars($row['product_description']));
                    $imagePath = htmlspecialchars($row['image']);
                    $created_at = $row['created_at'];
                    $status = htmlspecialchars($row['status']);

                    // Image preview
                    $imagePreview = !empty($imagePath)
                        ? "<img src='../{$imagePath}' alt='Product Image' style='width:70px; height:70px; object-fit:cover;'>"
                        : "No Image";

                    echo "<tr>
                        <td>{$product_id}</td>
                        <td>{$product_name}</td>
                        <td>{$crafted_by}</td>
                        <td>{$category}</td>
                        <td>₹{$price}</td>
                        <td>{$stock_quantity}</td>
                        <td>{$stock_status}</td>
                        <td>{$description}</td>
                        <td>{$imagePreview}</td>
                        <td>{$created_at}</td>
                        <td>{$status}</td>
                        <td>
                            
							<form method='post' action='update_product.php' style='display:inline-block; margin-top:5px;'>
                                <input type='hidden' name='product_id' value='{$product_id}'>
                                <input type='hidden' name='crafted_by' value='{$crafted_by}'>
                                <button type='submit' name='edit' style='background-color:green; color:white; padding: 5px 10px;'>Edit</button>
                            </form><br><br>
							<form action='delete_product.php' method='POST' style='display:inline;'
              onsubmit=\"return confirm('Are you sure you want to delete this product?');\">
            <input type='hidden' name='product_id' value='{$product_id}'>
            <input type='hidden' name='crafted_by' value='{$snm}'>
            <button type='submit' style='background-color:red; color:white; padding: 5px 10px;'>
                Remove
            </button>
        </form>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='12'>No products found.</td></tr>";
            }

            mysqli_close($con);
            ?>
        </table>
    </div>
</div>
	
	
	


        <div class="content-section" id="customers">
    <div class="card">
        <h2>Customers</h2>
        <table>
            <tr>
                <th>User ID</th>
                <th>User Name</th>
                <th>Email</th>
                <th>Mobile No</th>
                <th>Photo</th>
                <th>Status</th>
            </tr>

            <?php
            $con = mysqli_connect("localhost", "root", "", "craftzon");
            if (!$con) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Initialize $reor as empty array
            $reor = [];

            // Example: load all orders (or whatever you intended for $reor)
            $orderResult = mysqli_query($con, "SELECT uid FROM craftorder");
            if ($orderResult && mysqli_num_rows($orderResult) > 0) {
                while ($orderRow = mysqli_fetch_assoc($orderResult)) {
                    $reor[] = $orderRow;
                }
            }

            $uniqueUsers = [];
            foreach ($reor as $row) {
                $uid = $row['uid'];
                if (!isset($uniqueUsers[$uid])) {
                    $uniqueUsers[$uid] = $row; // Store the first occurrence
                }
            }

            foreach ($uniqueUsers as $rowor) {
                $sql = "SELECT * FROM craftus_reg WHERE u_id=" . intval($rowor['uid']);
                $result = mysqli_query($con, $sql);
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                            <td>{$row['u_id']}</td>
                            <td>{$row['uname']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['mobile_no']}</td>
                            <td><img src='../{$row['profile_img']}' alt='Profile' style='width:70px; height:70px; object-fit:cover;'></td>
                            <td>{$row['status']}</td>
                        </tr>";
                    }
                }
            }

            if (empty($uniqueUsers)) {
                echo "<tr><td colspan='6'>No users found</td></tr>";
            }

            mysqli_close($con);
            ?>
        </table>
    </div>
</div>

<div class="content-section" id="returns">
    <div class="card">
        <h2>Return Requests</h2>
        <table>
            <tr>
                <th>Return ID</th>
                <th>Order ID</th>
                <th>User Email</th>
                <th>Reason</th>
                <th>Comments</th>
                <th>Photo</th>
                <th>Status</th>
                <th>Request Date</th>
                <th>Approve Date</th>
                <th>Action</th>
            </tr>

            <?php
            $con = mysqli_connect("localhost", "root", "", "craftzon");
            if (!$con) die("Connection failed: " . mysqli_connect_error());

            // Get all return requests where the product belongs to this seller
            $sql = "
                SELECT rr.*, co.productid, pt.product_name 
                FROM return_requests rr
                JOIN craftorder co ON rr.order_id = co.orderid
                JOIN product_table pt ON co.productid = pt.product_id
                WHERE pt.crafted_by = '$snm'
            ";
            $res = mysqli_query($con, $sql);

            if (mysqli_num_rows($res) > 0) {
                while ($row = mysqli_fetch_assoc($res)) {
                    $photo = !empty($row['photo']) ? "<img src='../{$row['photo']}' style='width:80px;height:80px;object-fit:cover;border-radius:6px;'>" : "No Photo";
                    $disabled = ($row['status'] === 'Approved') ? "disabled" : "";
                    echo "<tr>
                        <td>{$row['return_id']}</td>
                        <td>{$row['order_id']}</td>
                        <td>{$row['emailid']}</td>
                        <td>{$row['reason']}</td>
                        <td>{$row['comments']}</td>
                        <td>{$photo}</td>
                        <td>{$row['status']}</td>
                        <td>{$row['request_date']}</td>
                        <td>{$row['approve_date']}</td>
                        <td>
                            <form method='post'>
                                <input type='hidden' name='return_id' value='{$row['return_id']}'>
								<input type='hidden' name='sellernm' value='" . htmlspecialchars($snm) . "'>
                                <input type='submit' name='approve_return' value='Approve' class='process-btn' $disabled>
                            </form>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='10'>No return requests found for this seller.</td></tr>";
            }

            // Handle Approve button
           if (isset($_POST['approve_return'])) {
    $return_id = intval($_POST['return_id']);

    // Update return_requests table
    $upd = "UPDATE return_requests SET status='Approved', approve_date=CURDATE() WHERE return_id=$return_id";
    mysqli_query($con, $upd);

    // Get the corresponding order_id
    $res_order = mysqli_query($con, "SELECT order_id FROM return_requests WHERE return_id=$return_id");
    $row_order = mysqli_fetch_assoc($res_order);
    $order_id = $row_order['order_id'];

    // Update order status
    $upd_order = "UPDATE craftorder SET order_status='return' WHERE orderid=$order_id";
    mysqli_query($con, $upd_order);

    // Send email to user
    $user_email = $row_order_email = mysqli_fetch_assoc(mysqli_query($con, "SELECT emailid FROM return_requests WHERE return_id=$return_id"))['emailid'];
    $subject = "Your Return Request is Approved";
    $message = "Hello,\n\nYour return request (ID: $return_id) has been approved.\n\nThank you!";
    mail($user_email, $subject, $message);
	$snm = $_POST['sellernm'] ?? ($_GET['sellernm'] ?? '');

    // Optional: Reload to show updated status
  echo "<script>
        window.location.href = 'selleradminpanel.php?sellernm=" . urlencode($snm) . "&section=returns';
    </script>";
    exit;

}

            mysqli_close($con);
            ?>
        </table>
    </div>
</div>
<div class="content-section" id="wishlist">
    <div class="card">
        <h2>Wishlist</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Added On</th>
            </tr>

            <?php
            $con = mysqli_connect("localhost", "root", "", "craftzon");
            if (!$con) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Fetch all wishlist entries with product name
            $sel = "
					SELECT w.*, pt.product_name
					FROM wishlist w
					JOIN product_table pt ON w.product_id = pt.product_id
					WHERE pt.crafted_by = '$snm'
					ORDER BY w.created_at DESC
				";
            $res = mysqli_query($con, $sel);

            if (mysqli_num_rows($res) > 0) {
                while ($row = mysqli_fetch_assoc($res)) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['user_id']}</td>
                        <td>{$row['product_id']}</td>
                        <td>{$row['product_name']}</td>
                        <td>{$row['created_at']}</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No wishlist items found.</td></tr>";
            }

            mysqli_close($con);
            ?>
        </table>
    </div>
</div>
<?php
// Handle deletion
if (isset($_POST['remove'])) {
$seller = $_POST['seller'] ?? ($_GET['sellernm'] ?? '');
 
 $delete_id = $_POST['delete_id'];
  $con = mysqli_connect("localhost", "root", "", "craftzon");

  if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
  }

  $sql = "DELETE FROM crafter_story WHERE story_id = $delete_id";

  if (mysqli_query($con, $sql)) {
    echo "<script>
        alert('Story removed successfully');
        window.location.href = 'selleradminpanel.php?sellernm=" . urlencode($seller) . "&section=craft_story';
    </script>";
    exit;
  } else {
    echo "<script>alert('Failed to remove story');</script>";
  }

  mysqli_close($con);
}
?>

<div id="craft_story" class="content-section">
  <h2>Craft Story</h2>
  <div class="panel-placeholder">
    <table>
      <tr>
        <th>Story ID</th>
        <th>Seller ID</th>
        <th>Title</th>
        <th>Description</th>
        <th>Media Type</th>
        <th>Media Preview</th>
        <th>Created At</th>
        <th>Action</th>
      </tr>
      <?php
        $con = mysqli_connect("localhost", "root", "", "craftzon");
        if (!$con) { die("Connection failed: " . mysqli_connect_error()); }

        $sql = "SELECT * FROM crafter_story WHERE seller_id in (SELECT 	sellerid  FROM seller WHERE sellernm='$snm') ORDER BY created_at DESC";

        $result = mysqli_query($con, $sql);

        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            $sid = (int)$row['story_id'];
            $mediaType = $row['media_type'];
            $mediaPath = htmlspecialchars($row['media_path']);
            $preview = '';

            if ($mediaType === 'image') {
              $preview = "<img src='../{$mediaPath}' alt='Story Image' style='width:70px; height:70px; object-fit:cover;'>";
            } elseif ($mediaType === 'video') {
              $preview = "<video width='100' height='70' controls><source src='{$mediaPath}' type='video/mp4'>Video not supported</video>";
            }

            echo "<tr>
              <td>{$sid}</td>
              <td>{$row['seller_id']}</td>
              <td>{$row['title']}</td>
              <td>{$row['description']}</td>
              <td>{$mediaType}</td>
              <td>{$preview}</td>
              <td>{$row['created_at']}</td>
              <td>
                <form method='post' action=''>
                  <input type='hidden' name='delete_id' value='{$sid}'/>
				  <input type='hidden' name='seller' value='" . htmlspecialchars($snm) . "'>
                  <button type='submit' name='remove' style='background-color:red;color:white' class='toggle-btn suspend' onclick=\"return confirm('Are you sure you want to delete this story?');\">Remove</button>
                </form>
              </td>
            </tr>";
          }
        } else {
          echo "<tr><td colspan='8'>No stories found</td></tr>";
        }

        mysqli_close($con);
      ?>
    </table>
  </div>
</div>


<div class="content-section" id="cancellations">
    <div class="card">
        <h2>Cancelled Orders</h2>
        <table>
            <tr>
                <th>Cancel ID</th>
                <th>Order ID</th>
                <th>User Email</th>
                <th>Reason</th>
                <th>Comments</th>
                <th>Refund Amount</th>
            </tr>

            <?php
            $con = mysqli_connect("localhost", "root", "", "craftzon");
            if (!$con) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Get seller's product IDs
            $product_res = mysqli_query($con, "SELECT product_id FROM product_table WHERE crafted_by='$snm'");
            $product_ids = [];
            while ($row = mysqli_fetch_assoc($product_res)) {
                $product_ids[] = $row['product_id'];
            }

            if (!empty($product_ids)) {
                $ids = implode(',', $product_ids);

                // Fetch cancelled orders for this seller's products
                $sel = "SELECT c.* FROM cancel_orders c 
                        JOIN craftorder o ON c.order_id = o.orderid 
                        WHERE o.productid IN ($ids)";
                $res = mysqli_query($con, $sel);

                if (mysqli_num_rows($res) > 0) {
                    while ($row = mysqli_fetch_assoc($res)) {
                        echo "<tr>
                            <td>{$row['cancel_id']}</td>
                            <td>{$row['order_id']}</td>
                            <td>{$row['user_email']}</td>
                            <td>{$row['reason']}</td>
                            <td>{$row['comments']}</td>
                            <td>₹{$row['refund_amount']}</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No cancelled orders found for this seller.</td></tr>";
                }
            } else {
                echo "<tr><td colspan='6'>This seller has no products yet.</td></tr>";
            }

            mysqli_close($con);
            ?>
        </table>
    </div>
</div>

<div class="content-section" id="payments">
    <div class="card">
        <h2>Payments</h2>
        <table>
            <tr>
                <th>Payment ID</th>
                <th>Order ID</th>
                <th>User ID</th>
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

            // Get seller's product IDs
            $product_res = mysqli_query($con, "SELECT product_id FROM product_table WHERE crafted_by='$snm'");
            $product_ids = [];
            while ($row = mysqli_fetch_assoc($product_res)) {
                $product_ids[] = $row['product_id'];
            }

            if (!empty($product_ids)) {
                $ids = implode(',', $product_ids);

                // Fetch payments for this seller's orders
                $sel = "SELECT p.* FROM payments p
                        JOIN craftorder o ON p.order_id = o.orderid
                        WHERE o.productid IN ($ids)";
                $res = mysqli_query($con, $sel);

                if (mysqli_num_rows($res) > 0) {
                    while ($row = mysqli_fetch_assoc($res)) {
                        echo "<tr>
                            <td>{$row['payment_id']}</td>
                            <td>{$row['order_id']}</td>
                            <td>{$row['user_id']}</td>
                            <td>{$row['payment_method']}</td>
                            <td>{$row['payment_status']}</td>
                            <td>₹{$row['amount']}</td>
                            <td>{$row['transaction_id']}</td>
                            <td>{$row['payment_date']}</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No payments found for this seller.</td></tr>";
                }
            } else {
                echo "<tr><td colspan='8'>This seller has no products yet.</td></tr>";
            }

            mysqli_close($con);
            ?>
        </table>
    </div>
</div>
<div class="content-section" id="contactus">
    <div class="card">
        <h2>Contact Us Messages</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>Name</th>
				<th>uemailid</th>
                <th>Email</th>
                <th>Message</th>
                <th>Created At</th>
            </tr>

            <?php
            // --- Open connection ---
            $con_contact = mysqli_connect("localhost", "root", "", "craftzon");
            if (!$con_contact) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Fetch seller email
            $snm_safe = mysqli_real_escape_string($con_contact, $snm);
            $seller_email = '';
            $sel_res = mysqli_query($con_contact, "SELECT selleremailid FROM seller WHERE sellernm='$snm_safe'");
            if ($sel_res && mysqli_num_rows($sel_res) > 0) {
                $seller_email = mysqli_fetch_assoc($sel_res)['selleremailid'];
            }

            if ($seller_email != '') {
                // Fetch messages for this seller
                $res = mysqli_query($con_contact, "SELECT * FROM contactus WHERE email='$seller_email' ORDER BY created_at DESC");

                if ($res && mysqli_num_rows($res) > 0) {
                    while ($row = mysqli_fetch_assoc($res)) {
                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['user_id']}</td>
                            <td>{$row['name']}</td>
							<td>{$row['uemailid']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['message']}</td>
                            <td>{$row['created_at']}</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No messages found for this seller.</td></tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Seller email not found.</td></tr>";
            }

            // --- Close connection ---
            mysqli_close($con_contact);
            ?>
        </table>
    </div>
</div>
<div class="content-section" id="auction">
    <div class="card">
        <h2>Auction Panel</h2>
        <table>
            <tr>
                <th>Auction ID</th>
                <th>Product Name</th>
                <th>Start Price</th>
                <th>Current Price</th>
                <th>Highest Bidder</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Status</th>
            </tr>

            <?php
            $con = mysqli_connect("localhost", "root", "", "craftzon");
            if (!$con) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Use the seller name from the URL
            $sellerName = mysqli_real_escape_string($con, $snm);

            // Fetch auction products for this seller
            $sql = "SELECT a.auction_id, p.product_name, a.start_price, a.current_price, 
                           a.highest_bidder, a.start_time, a.end_time, a.status 
                    FROM auction_table a
                    JOIN product_table p ON a.product_id = p.product_id
                    WHERE p.crafted_by = '$sellerName'
                    ORDER BY a.start_time DESC";

            $res = mysqli_query($con, $sql);

            if ($res && mysqli_num_rows($res) > 0) {
                while ($row = mysqli_fetch_assoc($res)) {
                    echo "<tr>
                        <td>{$row['auction_id']}</td>
                        <td>{$row['product_name']}</td>
                        <td>₹{$row['start_price']}</td>
                        <td>₹{$row['current_price']}</td>
                        <td>{$row['highest_bidder']}</td>
                        <td>{$row['start_time']}</td>
                        <td>{$row['end_time']}</td>
                        <td>{$row['status']}</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No auction products found for this seller.</td></tr>";
            }

            mysqli_close($con);
            ?>
        </table>
    </div>
</div>

<div class="content-section" id="cart">
    <div class="card">
        <h2>Cart Items</h2>
        <table>
            <tr>
                <th>Cart ID</th>
                <th>User ID</th>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Added At</th>
            </tr>

            <?php
            $con = mysqli_connect("localhost", "root", "", "craftzon");
            if (!$con) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Get seller's product IDs
            $product_res = mysqli_query($con, "SELECT product_id, product_name FROM product_table WHERE crafted_by='$snm'");
            $products = [];
            while ($row = mysqli_fetch_assoc($product_res)) {
                $products[$row['product_id']] = $row['product_name'];
            }

            if (!empty($products)) {
                $ids = implode(',', array_keys($products));

                // Fetch cart items for this seller's products
                $sel = "SELECT * FROM user_cart WHERE product_id IN ($ids)";
                $res = mysqli_query($con, $sel);

                if (mysqli_num_rows($res) > 0) {
                    while ($row = mysqli_fetch_assoc($res)) {
                        $pname = $products[$row['product_id']] ?? 'Unknown';
                        echo "<tr>
                            <td>{$row['cart_id']}</td>
                            <td>{$row['user_id']}</td>
                            <td>{$row['product_id']}</td>
                            <td>{$pname}</td>
                            <td>{$row['quantity']}</td>
                            <td>{$row['added_at']}</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No cart items found for this seller.</td></tr>";
                }
            } else {
                echo "<tr><td colspan='6'>This seller has no products yet.</td></tr>";
            }

            mysqli_close($con);
            ?>
        </table>
    </div>
</div>
<div class="content-section" id="feedback">
    <div class="card">
        <h2>Feedback</h2>
        <table>
            <tr>
                <th>FID</th>
                <th>Order ID</th>
                <th>User Name</th>
                <th>Rating</th>
                <th>Comment</th>
                <th>Created At</th>
            </tr>

            <?php
            $con = mysqli_connect("localhost", "root", "", "craftzon");
            if (!$con) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Fetch all feedback for this seller's products
            $sql_products = "SELECT product_id FROM product_table WHERE crafted_by='$snm'";
            $res_products = mysqli_query($con, $sql_products);
            $product_ids = [];
            while ($row = mysqli_fetch_assoc($res_products)) {
                $product_ids[] = $row['product_id'];
            }

            if (!empty($product_ids)) {
                $ids = implode(',', $product_ids);
                $sql = "SELECT * FROM feedbacks WHERE order_id IN (SELECT orderid FROM craftorder WHERE productid IN ($ids)) ORDER BY created_at DESC";
                $result = mysqli_query($con, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                            <td>{$row['fid']}</td>
                            <td>{$row['order_id']}</td>
                            <td>{$row['user_name']}</td>
                            <td>{$row['rating']}</td>
                            <td>{$row['comment']}</td>
                            <td>{$row['created_at']}</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No feedback found for this seller.</td></tr>";
                }
            } else {
                echo "<tr><td colspan='7'>This seller has no products yet.</td></tr>";
            }

            // Handle Delete Feedback
            if (isset($_POST['delete_feedback'])) {
                $fid = intval($_POST['fid']);
                mysqli_query($con, "DELETE FROM feedbacks WHERE fid=$fid");
                // Reload page to reflect changes
                echo "<script>window.location.reload();</script>";
            }

            mysqli_close($con);
            ?>
        </table>
    </div>
</div>


        <?php
// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Connect to database
$con = mysqli_connect("localhost", "root", "", "craftzon");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle deletion request for advertisement
if (isset($_POST['remove_ad']) && isset($_POST['delete_ad_id'])) {
	$snm = $_GET['sellernm'] ?? '';

    $delete_ad_id = (int)$_POST['delete_ad_id'];

    $stmt = mysqli_prepare($con, "DELETE FROM advertisements WHERE ad_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $delete_ad_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
            alert('Advertisement deleted successfully');
            window.location.href = 'selleradminpanel.php?sellernm=" . urlencode($snm) . "&section=advertisement';
        </script>";
        exit;
    } else {
        echo "<script>alert('Error deleting advertisement: " . mysqli_error($con) . "');</script>";
    }

    mysqli_stmt_close($stmt);
}
?>

<!-- Advertisement Panel -->
<div class="content-section" id="advertisement">
    <div class="card">
        <h2>Advertisement</h2>
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

            

            // Fetch advertisements
            $sql = "SELECT adv.*, pt.product_name 
FROM advertisements adv
JOIN product_table pt ON adv.productid = pt.product_id
WHERE pt.crafted_by = '$snm'
ORDER BY adv.created_at DESC";

            $res = mysqli_query($con, $sql);

            if (mysqli_num_rows($res) > 0) {
                while ($row = mysqli_fetch_assoc($res)) {
                    $ad_id = (int)$row['ad_id'];
                    $seller_id = (int)$row['seller_id'];
                    $productid = (int)$row['productid'];
                    $product_name = htmlspecialchars($row['product_name']);
                    $category = htmlspecialchars($row['category']);
                    $price = number_format($row['price'], 2);
                    $description = nl2br(htmlspecialchars($row['description']));
                    $user_email = htmlspecialchars($row['user_email']);
                    $imagePath = htmlspecialchars($row['image']);
                    $createdAt = $row['created_at'];

                    // Image preview
                    $imagePreview = '';
                    if (!empty($imagePath)) {
                        $imagePreview = "<img src='../{$imagePath}' alt='Product Image' style='width:70px; height:70px; object-fit:cover;'>";
                    } else {
                        $imagePreview = "No Image";
                    }

                    echo "<tr>
                        <td>{$ad_id}</td>
                        <td>{$seller_id}</td>
                        <td>{$productid}</td>
                        <td>{$product_name}</td>
                        <td>{$category}</td>
                        <td>{$price}</td>
                        <td>{$description}</td>
                        <td>{$user_email}</td>
                        <td>{$imagePreview}</td>
                        <td>{$createdAt}</td>
                        <td>
                            <form method='post' action='' onsubmit=\"return confirm('Are you sure you want to delete this advertisement?');\">
                                <input type='hidden' name='delete_ad_id' value='{$ad_id}'>
								
                                <button type='submit' style='background-color:red;color:white' name='remove_ad'>Remove</button>
                            </form>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='11'>No advertisements found.</td></tr>";
            }

            mysqli_close($con);
            ?>
        </table>
    </div>
</div>


        </div>
      

		<div class="content-section" id="contactus">
    <div class="card">
        <h2>Contact Us Messages</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Created At</th>
            </tr>

            <?php
            $con = mysqli_connect("localhost", "root", "", "craftzon");
            if (!$con) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $sql = "SELECT * FROM contactus ORDER BY created_at DESC";
            $res = mysqli_query($con, $sql);

            if (mysqli_num_rows($res) > 0) {
                while ($row = mysqli_fetch_assoc($res)) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['user_id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['message']}</td>
                        <td>{$row['created_at']}</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No contact messages found.</td></tr>";
            }

            mysqli_close($con);
            ?>
        </table>
    </div>
</div>

        
    </div>
	<?php if (isset($_GET['section'])): ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    let section = "<?php echo $_GET['section']; ?>";

    // Map only action-required sections
    const sectionMap = {
        "products": "products",           // Products
        "orders_all": "orders_all",       // All Orders
        "returns": "returns",             // Returns
        "advertisement": "advertisement", // Advertisement
        "craft_story": "craft_story"      // Craft Story
    };

    if (sectionMap[section]) {
        showSection(sectionMap[section]);
    } else {
        showSection("sales"); // fallback to Dashboard
    }
});
</script>
<?php endif; ?>

	<script>
  // --- Initialize with empty data ---
  let salesData = { year: [], month: [], week: [] };
  let recentOrdersData = [];
  let topProductsData = [];
  let visitData = {};
  let isDatabaseFound = false;

  // Seller name from URL (must be in a PHP file)
  const sellerName = "<?php echo $_GET['sellernm'] ?? ''; ?>";

  // --- Fetch data from PHP file ---
  fetch("getselDashboardData.php?sellernm=" + encodeURIComponent(sellerName))
    .then(res => res.json())
    .then(data => {
      if (!data.status || data.status !== "success") {
        console.error("Error loading dashboard data:", data.message);
        return;
      }

      // Save DB data
      salesData = data.salesData;
      recentOrdersData = data.recentOrdersData;
      topProductsData = data.topProductsData;
      visitData = data.visitData;
      isDatabaseFound = true;

      // Render charts AFTER data is ready
      if (document.getElementById('sales-graph')) renderSalesGraph('year');
      if (document.getElementById('recent-orders-graph')) renderRecentOrdersGraph();
      if (document.getElementById('top-products-list')) renderTopProducts();
      if (document.getElementById('visit-circle-graph')) renderCircleGraph();
    })
    .catch(err => console.error("Error loading dashboard data:", err));

  // --- Render Functions ---
  function renderSalesGraph(period, button) {
    const data = isDatabaseFound ? salesData[period] : [];
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
    if (button) button.classList.add('active');
    else if (periodButtons[0]) periodButtons[0].classList.add('active');
  }

  function renderRecentOrdersGraph() {
    const data = isDatabaseFound ? recentOrdersData : [];
    const graphContainer = document.getElementById('recent-orders-graph');
    graphContainer.innerHTML = '';
    const maxValue = Math.max(...data.map(d => d.value), 0);
    data.forEach(item => {
      const bar = document.createElement('div');
      bar.className = 'bar bar-chart colorb';
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
    const data = isDatabaseFound ? topProductsData : [];
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

    graph.style.backgroundImage = `conic-gradient(
      var(--royal-blue) 0% ${productViewsPercent}%,
      var(--royal-green) ${productViewsPercent}% ${productViewsPercent + purchasesPercent}%,
      var(--royal-white) ${productViewsPercent + purchasesPercent}% 100%
    )`;

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
      li.innerHTML += `<span>${item.label} (${
        item.label === 'Visits' ? data.visits : 
        (item.label === 'Product Views' ? data.productViews : data.purchases)
      })</span>`;
      legend.appendChild(li);
    });
  }
</script>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const header = document.getElementById('header');
            const mainContent = document.getElementById('main-content');
            sidebar.classList.toggle('collapsed');
            header.classList.toggle('collapsed');
            mainContent.classList.toggle('collapsed');
        }

        function showSection(sectionId) {
            // Hide all sections
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.remove('active');
            });
            // Show selected section
            document.getElementById(sectionId).classList.add('active');
            // Update active menu item
            document.querySelectorAll('.sidebar ul li').forEach(li => {
                li.classList.remove('active');
            });
            document.querySelector(`.sidebar ul li[onclick="showSection('${sectionId}')"]`).classList.add('active');
        }
		document.querySelectorAll('.sidebar ul li.has-submenu > span').forEach(item => {
    item.addEventListener('click', function(e){
        const parentLi = this.parentElement;
        parentLi.classList.toggle('active');
    });
});

    </script>
</body>
</html>