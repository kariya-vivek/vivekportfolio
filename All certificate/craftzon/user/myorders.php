<?php
session_start();

$con = mysqli_connect("localhost", "root", "", "Craftzon");
$uid = $_POST['uid'];
$usid=$uid;
$sel = "SELECT * FROM craftorder WHERE uid=$uid";
$result = mysqli_query($con, $sel);
ob_start();
while ($rowp = mysqli_fetch_assoc($result)) 
{
    $pido = $rowp['productid'];
    $orderId = $rowp['orderid'];
    $customerName = $rowp['fullname'];
    $status = strtolower($rowp['order_status']);
    $query = "SELECT * FROM product_table WHERE product_id=$pido";
    $proresult = mysqli_query($con, $query);
    while ($row = mysqli_fetch_assoc($proresult))
    {
       displayProduct($row, $orderId, $customerName, $status,$uid);
    }
}
$productHTML = ob_get_clean();

function displayProduct($row, $orderId, $customerName, $status,$uid)
{
    echo '<div class="order-card" data-status="' . $status . '">';
    echo '<div class="order-header">';
    echo 'Order ID: <b>' . $orderId . '</b><br />';
    echo 'Supplier: <span class="supplier">' . $row['crafted_by'] . '</span><br />';
    echo 'Sold to <b>' . $customerName . '</b>';
    echo '</div>';
    echo '<div class="product">';
    echo '<img src="../' . $row['image'] . '" alt="Product" />';
    echo '<div class="product-info">';
    echo '<div><b>' . $row['product_name'] . '</b></div>';
    echo '<div class="status">' . ucfirst($status) . '</div>';
    echo '</div>';
    echo '</div>';
    echo '<div class="order-actions">';
    if ($status == "ordered" || $status == "shipped")
	{
		echo '<form action="cancelorder.php" method="post" style="display:inline;">
                <input type="hidden" name="orderid" value="' . $orderId . '">
                <input type="hidden" name="uid" value="' . $uid . '">
                <input type="hidden" name="prc" value="' . $row['price'] . '">
                <button type="submit" class="cancel-btn">Cancel Order</button>
              </form>';

        echo '<form action="trackorder.php" method="post" style="display:inline;">
                <input type="hidden" name="orderid" value="' . $orderId . '">
                <button type="submit" class="track-btn">Track Order</button>
              </form>';
	}
	echo '<form action="craftzonbill.php" method="post" style="display:inline;">
            <input type="hidden" name="orderid" value="' . $orderId . '">
            <input type="hidden" name="uid" value="' . $uid . '">
            <button type="submit" class="bill-btn">View Bill</button>
          </form>';
	if ($status == "out for delivery") {
        echo '<form action="trackorder.php" method="post" style="display:inline;">
                <input type="hidden" name="orderid" value="' . $orderId . '">
                <button type="submit" class="track-btn">Track Order</button>
              </form>';
    }
	
	if ($status == "delivered") 
{
    $orderIdSafe = intval($orderId);

    // 1. Check excepdelivdate + 5 days
    $dateQuery = "SELECT excepdelivdate FROM craftorder WHERE orderid=$orderIdSafe LIMIT 1";
    $dateResult = mysqli_query($GLOBALS['con'], $dateQuery);
    $rowDate = mysqli_fetch_assoc($dateResult);

    $canReturn = false;
    $expiryMessage = "";
    if ($rowDate && !empty($rowDate['excepdelivdate'])) {
        $excepdelivdate = $rowDate['excepdelivdate'];
        $expiryDate = date("Y-m-d", strtotime($excepdelivdate . " +5 days"));
        $today = date("Y-m-d");

        if ($today <= $expiryDate) {
            $canReturn = true;
        } else {
            $expiryMessage = "Return period expired on " . $expiryDate;
        }
    }

    // 2. Check if already return request exists
    $returnCheckQuery = "SELECT status FROM return_requests WHERE order_id=$orderIdSafe LIMIT 1";
    $returnCheckResult = mysqli_query($GLOBALS['con'], $returnCheckQuery);

    if (mysqli_num_rows($returnCheckResult) > 0) {
        $returnRow = mysqli_fetch_assoc($returnCheckResult);
        $canReturn = false;

        // ✅ Show return request status instead of button
        echo '<div class="return-status">Your return request is <b>' . htmlspecialchars($returnRow['status']) . '</b></div>';
    }

    // ✅ Show button only if eligible & no request exists
    if ($canReturn) {
       echo '<form action="returnorder.php" method="post" style="display:inline;">
                    <input type="hidden" name="orderid" value="' . $orderId . '">
                    <input type="hidden" name="uid" value="' . $uid . '">
                    <button type="submit" class="return-btn">Return</button>
                  </form>';
    } elseif (!$canReturn && empty($returnRow)) {
        // If expired and no request exists → show expiry message
        echo '<div class="return-status"><b>' . $expiryMessage . '</b></div>';
    }
}

	echo '</div>';

	if ($status == "delivered") 
{
    // Check if feedback already exists
    $feedbackQuery = "SELECT * FROM feedbacks WHERE order_id=$orderId AND user_name='$customerName'";
    $feedbackResult = mysqli_query($GLOBALS['con'], $feedbackQuery);
    $existingFeedback = mysqli_fetch_assoc($feedbackResult);

    if ($existingFeedback) {
        // Feedback exists → show View Feedback
        echo '<button class="feedback-toggle-btn" onclick="toggleFeedbackForm(this)">View Feedback</button>';
        echo '<div class="feedback-form" style="display:none;">';
        echo '<div class="feedback-stars">';
        for ($i = 1; $i <= 5; $i++) {
            $active = ($i <= $existingFeedback['rating']) ? 'active' : '';
            echo '<span class="feedback-star ' . $active . '" data-value="' . $i . '">★</span>';
        }
        echo '</div>';
        echo '<textarea class="feedback-text" readonly>' . htmlspecialchars($existingFeedback['comment']) . '</textarea>';
        echo '</div>';
    } else {
        // No feedback → show Give Feedback form
        echo '<button class="feedback-toggle-btn" onclick="toggleFeedbackForm(this)">Give Feedback</button>';
        echo '<div class="feedback-form" style="display:none;">';
        echo '<div class="feedback-stars">';
        for ($i = 1; $i <= 5; $i++) {
            echo '<span class="feedback-star" data-value="' . $i . '">★</span>';
        }
        echo '</div>';
        echo '<textarea class="feedback-text" placeholder="Write your feedback here"></textarea>';
        echo '<button class="feedback-submit-btn" onclick="submitFeedback(this, ' . $orderId . ', ' . $uid . ')">Submit</button>';
        echo '</div>';
    }
}



    echo '</div>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>My Orders</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
<link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css' rel='stylesheet'>
<style>
body {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 0;
  background: #f9f9fb;
  color: #333;
}
header {
  padding: 15px;
  background: #fff;
  border-bottom: 1px solid #ddd;
  font-weight: bold;
  text-align: center;
}
.search-container {
  max-width: 500px;
  margin: 15px auto;
}
.search-box {
  border: 1px solid black;
  border-radius: 8px;
  background-color: white;
  display: flex;
  align-items: center;
  padding: 4px 10px;
}
.search-box input {
  flex: 1;
  border: none;
  outline: none;
  font-size: 16px;
}
.search-box i {
  font-size: 18px;
  cursor: pointer;
  color: gray;
  padding-left: 8px;
}
.search-box i:hover {
  color: #c71585;
}
.tabs {
  display: flex;
  overflow-x: auto;
  background: #fff;
  padding: 10px;
  border-bottom: 1px solid #ddd;
}
.tabs button {
  padding: 8px 15px;
  border-radius: 20px;
  border: none;
  margin-right: 10px;
  background: #f2f2f2;
  cursor: pointer;
  white-space: nowrap;
  font-weight: 600;
  color: #555;
  transition: background-color 0.3s ease;
}
.tabs button.active {
  background: #ffebf7;
  color: #c71585;
  font-weight: bold;
}
.tabs button:hover:not(.active) {
  background: #f7d7e7;
}
.order-card {
  background: #fff;
  margin: 15px;
  border-radius: 12px;
  padding: 15px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
.order-header {
  font-size: 14px;
  margin-bottom: 5px;
}
.supplier {
  color: #555;
  font-weight: bold;
}
.product {
  display: flex;
  margin-top: 10px;
  align-items: center;
}
.product img {
  width: 70px;
  height: 70px;
  border-radius: 10px;
  object-fit: cover;
  margin-right: 15px;
}
.product-info {
  flex: 1;
}
.status {
  font-size: 12px;
  color: green;
  margin-top: 5px;
}
.order-actions {
  margin-top: 10px;
  display: flex;
  gap: 10px;
}
.order-actions button {
  padding: 8px 12px;
  border: none;
  border-radius: 6px;
  font-weight: 600;
  cursor: pointer;
  font-size: 14px;
}
.cancel-btn {
  background-color: #ff4d4d;
  color: white;
}
.cancel-btn:hover {
  background-color: #e60000;
}
.bill-btn {
  background-color: #4CAF50;
  color: white;
}
.bill-btn:hover {
  background-color: #45a049;
}
.return-btn {
  background-color: #007bff;
  color: white;
}
.return-btn:hover {
  background-color: #0056b3;
}

.feedback-toggle-btn {
  background-color: #17a2b8;
  color: white;
  border: none;
  border-radius: 6px;
  padding: 6px 12px;
  cursor: pointer;
  margin-top: 10px;
}
.feedback-toggle-btn:hover {
  background-color: #138496;
}
.feedback-form {
  display: none;
  margin-top: 10px;
  border-top: 1px solid #ddd;
  padding-top: 10px;
}
.feedback-stars span {
  font-size: 20px;
  cursor: pointer;
  color: #ccc;
}
.feedback-stars span.active {
  color: #f4b400;
}
.feedback-text {
  width: 100%;
  margin-top: 5px;
  padding: 6px;
  border-radius: 6px;
  border: 1px solid #ccc;
  resize: vertical;
}
.feedback-submit-btn {
  margin-top: 5px;
  background-color: #4CAF50;
  color: white;
  border-radius: 6px;
  padding: 6px 12px;
  border: none;
  cursor: pointer;
}
.feedback-submit-btn:hover {
  background-color: #45a049;
}
.track-btn {
  background-color: #6f42c1;
  color: white;
  border: none;
  border-radius: 6px;
  padding: 6px 12px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.track-btn:hover {
  background-color: #5936a2;
}
.sfdiv
			{
				background-color:#581845;
				height:auto;
				color:white;
				align-items:center;
			}
			a
			{
				text-decoration:none;
				color:white;
			}
.shdiv
			{
				background-color:#581845;
				height:8%;
				align-items:center;
			}
.opdivs
			{
				position: absolute;
				top: 10px; /* Adjust to suit your layout */
				left: 10px;
				width:18%;
				height:auto;
				background-color: #fff8dc;
				color: #000;
				padding: 10px;
				border: 1px solid #ccc;
				border-radius: 4px;
				z-index: 1000;
				margin-top:6%;
				text-align:center;
			}
			.mc:hover
			{
				background-color:#b08d57;
				cursor:pointer;
				text-align:center;
				justify-content:center;
				items-align:center;
			}
.mc.active {
    background-color: #b08d57;  /* gold highlight */
    font-weight: 600;
    border-radius: 4px;
}


</style>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
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
<body id="#top1">
<div class="container-fluid position-relative">
		<div class="row shdiv">
<div class="col-2 col-sm-1 col-md-1" id="option1" style="margin-top:-25px;font-size:30px;"><i class="fa-solid fa-bars" style="color: white;"></i>
</div>
			<div class="col-4 col-sm-3 col-md-3 d-flex align-items-center">
				<img src='../craftzonlogo.jpeg' class="img-fluid rounded-circle mt-1 mb-1" style="max-width: 100px; height: auto; border: 2px solid #FFFFFF; background-color: #581845;">

			</div>

				<div id="opdiv" class="opdivs" style="display:none">
					<!-- 🔘 Close Button -->
				  <span onclick="document.getElementById('opdiv').style.display='none';" 
						style="position:absolute; top:10px; right:10px; cursor:pointer; font-size:20px; color:#581845;">
					&times;
				  </span>
					<?php
						$con = mysqli_connect("localhost", "root", "", "craftzon");
                        $profileImg = 'userprofileimage/default.png';
                        if ($usid > 0) {
      						$sel1 = "SELECT uname, profile_img FROM craftus_reg WHERE u_id = $usid";
      						$select1 = mysqli_query($con, $sel1);
      						if($row1 = mysqli_fetch_array($select1)) {
        						$profileImg = $row1['profile_img'] ? $row1['profile_img'] : 'userprofileimage/default.png';
                            }
                        }
					?>
					
					<!-- 👇 Display profile image in circular shape -->
					<img src="../<?= $profileImg ?>" alt="Profile Image" style="width:120px; height:120px; border-radius:50%; object-fit:cover; border:3px solid #581845;">
					<br><br>


					<form id="editProfileForm" action="update.php" method="POST" style="display:none;">    <input type="hidden" name="userid" value="<?= $usid ?>">
</form>
<h4 style="cursor:pointer;color:#581845" onclick="if(<?php echo isset($_SESSION['users_id']) ? $_SESSION['users_id'] : 0; ?> == 0) { Swal.fire({title: 'Login Required', text: 'Please login first!', icon: 'warning', showCancelButton: true, confirmButtonText: 'Login Now'}).then((result) => { if(result.isConfirmed) { window.location.href = 'logincraft.php'; } }); } else { document.getElementById('editProfileForm').submit(); }">
    Edit Profile
</h4>

					<?php
							$con = mysqli_connect("localhost","root","","craftzon");
                            $uname = 'Guest';
                            if ($usid > 0) {
      							$sel1="select uname from craftus_reg where u_id=$usid";
      							$select1=mysqli_query($con,$sel1);
      							if($row1=mysqli_fetch_array($select1)) {
                                    $uname = $row1['uname'];
                                }
                            }
							$sel = "SELECT * FROM seller WHERE sellernm = '" . mysqli_real_escape_string($con, $uname) . "'";
							$select=mysqli_query($con,$sel);
							$row=mysqli_fetch_array($select);
							if($row!=null && $row['sellernm'] == $row1['uname'])
							{
								echo "
<form id='goStoreForm{$row['sellerid']}' action='store.php' method='POST' style='display:none;'>
    <input type='hidden' name='sellerid' value='{$row['sellerid']}'>
</form>
<h4 style='cursor:pointer;color:#581845'
    onclick=\"if({$usid} == 0) { Swal.fire({title: 'Login Required', text: 'Please login first!', icon: 'warning', showCancelButton: true, confirmButtonText: 'Login Now'}).then((result) => { if(result.isConfirmed) { window.location.href = 'logincraft.php'; } }); } else { document.getElementById('goStoreForm{$row['sellerid']}').submit(); }\">
    view shop
</h4>";

							}
							else
							{
								echo "
<form id='becomeSupplierForm{$usid}' action='../seller/create_craftzonstore.php' method='POST' style='display:none;'>
    <input type='hidden' name='userid' value='{$usid}'>
</form>
<h4 style='cursor:pointer;color:#581845'
    onclick=\"if({$usid} == 0) { Swal.fire({title: 'Login Required', text: 'Please login first!', icon: 'warning', showCancelButton: true, confirmButtonText: 'Login Now'}).then((result) => { if(result.isConfirmed) { window.location.href = 'logincraft.php'; } }); } else { document.getElementById('becomeSupplierForm{$usid}').submit(); }\">
    Become a supplier
</h4>";
							}
					?>
					<form id="myOrdersForm" action="myorders.php" method="POST" style="display:none;">
    <input type="hidden" name="uid" value="<?= $usid ?>">
</form>
<h4 style="cursor:pointer;color:#581845" onclick="if(<?php echo isset($_SESSION['users_id']) ? $_SESSION['users_id'] : 0; ?> == 0) { Swal.fire({title: 'Login Required', text: 'Please login first!', icon: 'warning', showCancelButton: true, confirmButtonText: 'Login Now'}).then((result) => { if(result.isConfirmed) { window.location.href = 'logincraft.php'; } }); } else { document.getElementById('myOrdersForm').submit(); }">
    My Orders
</h4>

					
					<h4 class="cursor:pointer;color:#581845" style="color:#581845; cursor:pointer;" onclick="window.location='abouuspage.php'">About Us</h4>
					<h4 class="cursor:pointer;color:#581845" style="color:#581845; cursor:pointer;" onclick="window.location='viewcraftstory.php'">Craft Story</h4>
					<form id="contactFormHidden" action="contectus.php" method="POST" style="display:none;">
    <input type="hidden" name="uid" value="<?= $usid ?>">
</form>
<h4 style="cursor:pointer;color:#581845" onclick="if(<?php echo isset($_SESSION['users_id']) ? $_SESSION['users_id'] : 0; ?> == 0) { Swal.fire({title: 'Login Required', text: 'Please login first!', icon: 'warning', showCancelButton: true, confirmButtonText: 'Login Now'}).then((result) => { if(result.isConfirmed) { window.location.href = 'logincraft.php'; } }); } else { document.getElementById('contactFormHidden').submit(); }">
    Contact Us
</h4>

					<?php if(isset($usid) && $usid > 0) { ?>
<h4 style="cursor:pointer;color:#581845" onclick="window.location.href='logout.php'">Logout</h4>
<?php } else { ?>
<h4 style="cursor:pointer;color:#581845" onclick="window.location.href='logincraft.php'">Login</h4>
<?php } ?>
				</div>
			
			<div class="col-12 col-sm-5 col-md-5 bg-pink text-center mt-2 mt-sm-0">
    <div class="row" style="padding:4px; align-items:center;">
        <div class="col-12 pc-menu" style="display:flex; flex-wrap: wrap; justify-content:space-around; gap:5px;">
           
		    <div class="mc" name="home" onclick="window.location='crafthome.php?category=home'" style="cursor:pointer; color:white; font-weight:400;">Home</div>
           <form id="ordersForm" method="POST" action="myorders.php" style="display:none;">
  <input type="hidden" name="uid" value="<?= $usid ?>">
</form>

<div class="mc active" onclick="if(<?php echo isset($_SESSION['users_id']) ? $_SESSION['users_id'] : 0; ?> == 0) { Swal.fire({title: 'Login Required', text: 'Please login first!', icon: 'warning', showCancelButton: true, confirmButtonText: 'Login Now'}).then((result) => { if(result.isConfirmed) { window.location.href = 'logincraft.php'; } }); } else { document.getElementById('ordersForm').submit(); }" 
     style="cursor:pointer; color:white; font-weight:400;">
  My Orders
</div>

            <div class="mc"  onclick="window.location='view_ads.php'" style="cursor:pointer; color:white; font-weight:400;">view advritsment</div>
           
            <div class="mc" onclick="window.location='abouuspage.php'" style="cursor:pointer; color:white; font-weight:400;">About Us</div>
            <div class="mc" onclick="window.location='viewcraftstory.php';" style="cursor:pointer; color:white; font-weight:400;">Craft Story</div>
           <form id="contactForm" method="POST" action="contectus.php" style="display:none;">
  <input type="hidden" name="uid" value="<?= $usid ?>">
</form>

<div class="mc" onclick="if(<?php echo isset($_SESSION['users_id']) ? $_SESSION['users_id'] : 0; ?> == 0) { Swal.fire({title: 'Login Required', text: 'Please login first!', icon: 'warning', showCancelButton: true, confirmButtonText: 'Login Now'}).then((result) => { if(result.isConfirmed) { window.location.href = 'logincraft.php'; } }); } else { document.getElementById('contactForm').submit(); }" 
     style="cursor:pointer; color:white; font-weight:400;">
  Contact Us
</div>

        </div>

        <div class="col-12 mt-2" style="border:1px solid black; border-radius:8px; background-color:white; display:flex; align-items:center; padding:4px;">
            <input type="text" id="searchInput" placeholder="search craft" style="flex:1; border:none; outline:none; font-size:16px;">
            <i class="fa-solid fa-magnifying-glass" style="font-size:18px; cursor:pointer;" onclick="f1();"></i>
        </div>
    </div>
</div>
	
	<div class="col-6 col-sm-3 col-md-3 text-end" style="margin-top:-20px; display: flex; justify-content: flex-end; gap: 15px;">
    <!-- Wishlist -->
   <form action="wishlist.php" method="POST" style="display:inline;">
    <input type="hidden" name="uid" value="<?php echo $usid; ?>">
    <button type="submit" onclick="if(<?php echo isset($_SESSION['users_id']) ? $_SESSION['users_id'] : 0; ?> == 0) { event.preventDefault(); Swal.fire({title: 'Login Required', text: 'Please login first!', icon: 'warning', showCancelButton: true, confirmButtonText: 'Login Now'}).then((result) => { if(result.isConfirmed) { window.location.href = 'logincraft.php'; } }); return false; }" style="background:none; border:none; padding:0; margin:0; cursor:pointer;">
        <i class="fa-solid fa-heart fa-xl" style="position: relative; font-size:27px;color:white;">
            <span id="wishlist-count" class="img-fluid rounded-circle" 
                  style="position: absolute; top: -13px; right: -12px; color: red; font-size: 12px; padding: 2px 5px; border-radius: 50%;">
                <?php
                $res = mysqli_query($con, "SELECT COUNT(*) AS total FROM wishlist WHERE user_id='$usid'");
                $row = mysqli_fetch_assoc($res);
                echo $row['total'] ?? 0;
                ?>
            </span>
        </i>
    </button>
</form>

    <!-- Cart -->
    <form action="cart.php" method="POST" style="display:inline; position: relative;">
    <input type="hidden" name="uid" value="<?php echo $usid; ?>">
    <button type="submit" onclick="if(<?php echo isset($_SESSION['users_id']) ? $_SESSION['users_id'] : 0; ?> == 0) { event.preventDefault(); Swal.fire({title: 'Login Required', text: 'Please login first!', icon: 'warning', showCancelButton: true, confirmButtonText: 'Login Now'}).then((result) => { if(result.isConfirmed) { window.location.href = 'logincraft.php'; } }); return false; }" style="all:unset; cursor:pointer; display:inline-block; position:relative;">
        <i class="fa-solid fa-cart-plus fa-xl" style="font-size:27px; color:white;">
            <span id="cart-count" class="img-fluid rounded-circle" 
                  style="position: absolute; top: -13px; right: -12px; color: red; font-size: 12px; padding: 2px 5px; border-radius: 50%; background:transparent;">
                <?php
                $res = mysqli_query($con, "SELECT COUNT(*) AS total FROM user_cart WHERE user_id='$usid'");
                $row = mysqli_fetch_assoc($res);
                echo $row['total'] ?? 0;
                ?>
            </span>
        </i>
    </button>
</form>
</div></div>

<header>MY ORDERS</header>

<div class="search-container">
  <div class="search-box">
    <input id="searchInput" type="text" placeholder="Search by Product or Order ID" />
    <i class="fa-solid fa-magnifying-glass" onclick="triggerSearch()"></i>
  </div>
</div>

<div class="tabs">
  <button class="active" data-status="all">All</button>
  <button data-status="ordered">Ordered</button>
  <button data-status="shipped">Shipped</button>
  <button data-status="delivered">Delivered</button>
  <button data-status="cancel">Cancelled</button>
  <button data-status="return">Return</button>
  <button data-status="others">Others</button>
</div>

<div id="orderContainer">
  <?php echo $productHTML; ?>
</div>
<div id="feedbackPopup" style="display:none; position:fixed; top:20px; right:20px; background:#4CAF50; color:white; padding:15px 20px; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.2); z-index:1000;">
    Feedback submitted successfully!
</div>
 <div class="container-fluid text-center mt-4">
			<div class="row mt-4" style="background-color:#f5deb3;">
				<div class="col-12">
					<a href="#top1" class="btn btn-link" style="text-decoration:none;color:#581845;">Back to Top</a>
				</div>
			</div>
		<div class="row sfdiv">
			<div class="col-6 col-md-6 col-lg-2 mb-4">
				<h5>Make Money with Us</h5>
				<ul class="list-unstyled">
					<!-- Hidden form -->
<form id="sellForm" action='../seller/create_craftzonstore.php' method="POST" style="display:none;">  <input type="hidden" name="userid" value="<?= $usid ?>">
</form>

<li><a href="#" onclick="if(<?= $usid ?> == 0) { event.preventDefault(); Swal.fire({title: 'Login Required', text: 'Please login first!', icon: 'warning', showCancelButton: true, confirmButtonText: 'Login Now'}).then((result) => { if(result.isConfirmed) { window.location.href = 'logincraft.php'; } }); } else { document.getElementById('sellForm').submit(); }">Sell on Craftzon</a></li>

					<li><a href="global-selling.php">craftzon Global Selling</a></li>
					<li><a href="fulfilment.php">Fulfilment by craftzon</a></li>
					<li><a href="view_ads.php">All Advertisements</a></li>
					<li><a href="merchant-commission.php">craftzon Pay on Merchants</a></li>
				</ul>
			</div>

			<div class="col-6 col-md-6 col-lg-2 mb-4">
				<h5>Get to Know Us</h5>
				<ul class="list-unstyled">
					<li><a href="abouuspage.php">About craftzon</a></li>
					<li><a href="blog.php">Craftzon Blog</a></li>
					<li><a href="craftzonscience.php">craftzon Science</a></li>
				</ul>
			</div>
			
			<div class="col-6 col-lg-4 mb-4">
				<p>🌐 INDIA &nbsp;&nbsp; <strong>&#8377;</strong> Rupee</p>
				<p><strong style="color:white;">Already have an account?</strong> <a href='logincraft.php'>Sign in</a></p>
				<ul class="list-inline">
					<li class="list-inline-item"><a href="condition.php">Conditions of Use</a></li>
					<li class="list-inline-item"><a href="privacypolicy.php">Privacy Notice</a></li>
					<li class="list-inline-item"><a href="cookiesnoties.php">Cookies Notice</a></li>
				</ul>
				<p>&copy; 2005 - 2025 <strong>craftzon.com</strong></p>
			</div>			
			<div class="col-6 col-md-6 col-lg-2 mb-4">
    <h5>Connect with Us</h5>
    <ul class="list-unstyled">
        <li>
            <a href="https://facebook.com" target="_blank" class="fs-4 text-decoration-none">
                <i class="fab fa-facebook" style="color:#3b5998;"></i> Facebook
            </a>
        </li>
        <li>
            <a href="https://twitter.com" target="_blank" class="fs-4 text-decoration-none">
                <i class="fab fa-twitter" style="color:#1da1f2;"></i> Twitter
            </a>
        </li>
        <li>
            <a href="https://www.instagram.com/craftzonshop?igsh=MW5nbDNsYTQ1ZmN3aA==" target="_blank" class="fs-4 text-decoration-none">
                <i class="fab fa-instagram" style="color:#e4405f;"></i> Instagram
            </a>
        </li>
    </ul>
</div>
		
			<div class="col-6 col-md-6 col-lg-2 mb-4">
				<h5>Let Us Help You</h5>
				<ul class="list-unstyled">
					<!-- Hidden form -->
<form id="editProfileForm" action="update.php" method="POST" style="display:none;">  <input type="hidden" name="userid" value="<?= $usid ?>">
</form>

<li><a href="#" onclick="if(<?php echo isset($_SESSION['users_id']) ? $_SESSION['users_id'] : 0; ?> == 0) { Swal.fire({title: 'Login Required', text: 'Please login first!', icon: 'warning', showCancelButton: true, confirmButtonText: 'Login Now'}).then((result) => { if(result.isConfirmed) { window.location.href = 'logincraft.php'; } }); } else { document.getElementById('editProfileForm').submit(); }">edit profile</a></li>

					<li><a href="#" onclick="if(<?= $usid ?> == 0) { event.preventDefault(); Swal.fire({title: 'Login Required', text: 'Please login first!', icon: 'warning', showCancelButton: true, confirmButtonText: 'Login Now'}).then((result) => { if(result.isConfirmed) { window.location.href = 'logincraft.php'; } }); } else { window.location.href = 'returncenter.php'; }">Returns Centre</a></li>
					<li><a href="installcrafyzonstep.php">Craft App Installation Guide</a></li>
					<li><a href="100perpurchaseprotectionpage.php">100% Purchase Protection</a></li>
					<li><a href="helpcraftzon.php">Help</a></li>
				</ul>
			</div>
		
		</div>
		
	</div>
<script>
const tabs = document.querySelectorAll('.tabs button');
const searchInput = document.getElementById('searchInput');

tabs.forEach(tab => {
  tab.addEventListener('click', () => {
    tabs.forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
    const status = tab.getAttribute('data-status');
    filterOrders(status, searchInput.value.trim().toLowerCase());
  });
});

function triggerSearch() {
  const activeTab = document.querySelector('.tabs button.active').getAttribute('data-status');
  filterOrders(activeTab, searchInput.value.trim().toLowerCase());
}

function filterOrders(status, query) {
  const orders = document.querySelectorAll('.order-card');
  orders.forEach(order => {
    const orderStatus = order.getAttribute('data-status');
    const orderID = order.querySelector('.order-header b').textContent.toLowerCase();
    const customer = order.querySelector('.order-header b:nth-of-type(2)')?.textContent.toLowerCase() || '';
    const productName = order.querySelector('.product-info b')?.textContent.toLowerCase() || '';
    const matchesStatus = status === 'all' || orderStatus === status;
    const matchesQuery = orderID.includes(query) || customer.includes(query) || productName.includes(query) || query === '';
    order.style.display = matchesStatus && matchesQuery ? 'block' : 'none';
  });
}

function toggleFeedbackForm(btn) {
  const form = btn.nextElementSibling;
  form.style.display = form.style.display === "none" ? "block" : "none";

  // Only attach star click events if we are in "Give Feedback" mode
  if (btn.textContent.trim() === "Give Feedback") {
      const stars = form.querySelectorAll(".feedback-star");
      stars.forEach(star => {
        star.onclick = () => {
          const val = star.getAttribute("data-value");
          stars.forEach(s => s.classList.toggle("active", s.getAttribute("data-value") <= val));
        };
      });
  }
}


function submitFeedback(btn, orderId, uid) {
  const form = btn.closest(".feedback-form");
  const rating = form.querySelectorAll(".feedback-star.active").length;
  const comment = form.querySelector(".feedback-text").value.trim();

  if (rating === 0) {
    showPopup("Please select a rating!", 2000, "#ff4d4d");
    return;
  }

  const xhr = new XMLHttpRequest();
  xhr.open("POST", "submit_feedback.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.onload = function() {
    if (this.status === 200) {
      const res = JSON.parse(this.responseText);
      if (res.status === "success") {
        showPopup("Feedback submitted successfully!", 2000, "#4CAF50");
        form.style.display = "none";
        
        // Update to "View Feedback" state
        const toggleBtn = form.previousElementSibling;
        if (toggleBtn && toggleBtn.classList.contains("feedback-toggle-btn")) {
            toggleBtn.textContent = "View Feedback";
        }
        
        const textArea = form.querySelector(".feedback-text");
        if (textArea) {
            textArea.readOnly = true;
        }
        
        // Remove the submit button since it's already submitted
        const submitBtn = form.querySelector(".feedback-submit-btn");
        if (submitBtn) {
            submitBtn.style.display = "none";
        }
        
        // Disable star clicking by removing the onclick in toggleFeedbackForm
        // Just visually it remains as they selected it.
        form.classList.add("submitted"); // Optional flag
      } else {
        showPopup("Error: " + res.msg, 3000, "#ff4d4d");
      }
    } else {
      showPopup("Server error", 3000, "#ff4d4d");
    }
  };
  xhr.send("order_id=" + orderId + "&uid=" + uid + "&rating=" + rating + "&comment=" + encodeURIComponent(comment));
}
function showPopup(message, duration = 2000, color = "#4CAF50") {
    const popup = document.getElementById("feedbackPopup");
    popup.textContent = message;
    popup.style.backgroundColor = color;
    popup.style.display = "block";
    setTimeout(() => {
        popup.style.display = "none";
    }, duration);
}

</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
		$(document).ready(function (){
		$("#option1").click(function()
		{
			$("#opdiv").toggle();
		});
		});
	</script>
<?php include 'chatbot.php'; ?>
</body>
</html>
