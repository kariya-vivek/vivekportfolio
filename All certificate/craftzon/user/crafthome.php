<?php


session_start();


$usid = isset($_SESSION["users_id"]) ? $_SESSION["users_id"] : 0;

// Single DB connection
$con = mysqli_connect("localhost", "root", "", "craftzon");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// AJAX request to get cart count
if (isset($_GET['action']) && $_GET['action'] === 'get_cart_count') {
    $res = mysqli_query($con, "SELECT COUNT(*) AS total FROM user_cart WHERE user_id=$usid");
    $row = mysqli_fetch_assoc($res);
    echo $row['total'] ?? 0;
    exit(); // Stop further HTML output
}

// AJAX request to get wishlist count
if (isset($_GET['action']) && $_GET['action'] === 'get_wishlist_count') {
    $res = mysqli_query($con, "SELECT COUNT(*) AS total FROM wishlist WHERE user_id='$usid'");
    $row = mysqli_fetch_assoc($res);
    echo $row['total'] ?? 0;
    exit(); // Stop further HTML output
}

// Toggle wishlist
if (isset($_POST['wishlist_product_id'])) {
    if ($usid == 0) { echo 'not_logged_in'; exit(); }
    $pid = $_POST['wishlist_product_id'];
    $check = mysqli_query($con, "SELECT 1 FROM wishlist WHERE user_id='$usid' AND product_id='$pid'");

    if (mysqli_num_rows($check) > 0) {
        // Remove from wishlist
        mysqli_query($con, "DELETE FROM wishlist WHERE user_id='$usid' AND product_id='$pid'");
        echo 'removed';
    } else {
        // Add to wishlist
        mysqli_query($con, "INSERT INTO wishlist(user_id, product_id, created_at) VALUES('$usid', '$pid', NOW())");
        echo 'added';
    }
    exit();
}

// Function to get cart count
function getCartCount($con, $userId)
{
    $sql = "SELECT COUNT(*) AS total FROM user_cart WHERE user_id = '$userId'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['total'] ?? 0;
}

$cartCount = getCartCount($con, $usid);

// Redirect if category not set
if (!isset($_GET['category'])) {
    header("Location: crafthome.php?category=home");
    exit();
}
$uname = '';
$resUser = mysqli_query($con, "SELECT uname FROM craftus_reg WHERE u_id = '$usid' LIMIT 1");
if ($rowUser = mysqli_fetch_assoc($resUser)) {
    $uname = $rowUser['uname'];
}

// Get seller_id for footer link
$seller_id = 0;
if ($uname) {
    $resSeller = mysqli_query($con, "SELECT sellerid FROM seller WHERE sellernm = '$uname' LIMIT 1");
    if ($rowSeller = mysqli_fetch_assoc($resSeller)) {
        $seller_id = $rowSeller['sellerid'];
    }
}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>craftzon home</title>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
			<link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css' rel='stylesheet'>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
		<style>
			html
			{
				scroll-behavior: smooth;
			}
			.shdiv
			{
				background-color:#581845;
				height:8%;
				align-items:center;
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
			.custom-select
			{
				background-color: #f5deb3;
				outline: none;
				border: none;
				width: 100%;
				max-width: 300px; /* Adjust as needed */
				padding: 10px;
				font-size: 16px;
				border-radius: 5px;
				box-sizing: border-box;
				appearance: none; /* Removes default dropdown arrow in some browsers */
				-webkit-appearance: none;
				-moz-appearance: none;
			}
			.custom-select option 
			{
				background-color: #f5deb3;
			}
			@media (max-width: 1024px) 
			{
				.menu-row 
				{
		           display: none;   
				}
				.img-fluid 
				{
					max-width: 58px !important;
				}
				.uicon
				{
					max-width: 35px !important;
				}
			}

			@media (max-width: 600px)
			{
				.custom-select
				{
					font-size: 14px;
				}
			}
			.product
			{
				height:auto;
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
			.slider-container
			{
				width: 100%;
				overflow-x:auto;
				overflow-y: hidden;
				white-space: nowrap;
				-webkit-overflow-scrolling: touch;
				scroll-behavior: smooth;
				padding: 20px 0;
				display: flex;
				cursor: grab;
			}
	
			.slider-container::-webkit-scrollbar 
			{
				display: none;
			}

			.slider-container
			{
				-ms-overflow-style: none;
				scrollbar-width: none;
			}

			.slider-container.active 
			{
				cursor: grabbing;
			}

			.slide 
			{
				flex: 0 0 auto;
				width: 150px;
				text-align: center;
				margin: 0 15px;
			}

			.slide img 
			{
				width: 150px;
				height: 150px;
				border-radius: 50%;
				object-fit: cover;
				border: 3px solid #ddd;
				background: #f5f5f5;
			}
	
			.slide p
			{
				margin-top: 10px;
				font-size: 16px;
				font-weight: bold;
				color: #333;
			}
			.slider-container, .slide img,.slide p
			{
				user-select: none;          /* Prevent text selection */
				-webkit-user-drag: none;    /* Prevent image dragging */
				-webkit-touch-callout: none;
			}


			.active-category
			{
				background: #ffcc00;
				font-weight: bold;
				border-radius: 5px;
				height:auto;
				padding: 2px 6px;
				text-align:center;
			}
			.product
			{
				background-color: #fff;
				border: 1px solid #ddd;
				border-radius: 10px;
				padding: 16px;
				margin-bottom: 24px;
				box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
				transition: box-shadow 0.3s ease, transform 0.3s ease;
			}

			.product:hover
			{
				box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
				transform: translateY(-4px);
			}

			.product-img 
			{
				object-fit: contain;
				width: 100%;
				height: auto;
				background-color: #f4f4f4;
				border-radius: 8px;
				margin-bottom: 12px;
			}

			.product h5
			{
				font-size: 1.2rem;
				font-weight: 600;
				color: #2c3e50;
				margin-bottom: 6px;
			}

			.product p 
			{
				font-size: 0.95rem;
				color: #555;
				margin-bottom: 6px;
			}

			.product p strong 
			{
				color: #333;
			}

			.product input[type="button"],
			.product input[type="submit"]
			{
				  display: block;
				  width: 100%;
				  background-color: #ff6f61;
				  color: #fff;
				  border: none;
				  padding: 10px 0;
				  border-radius: 6px;
				  cursor: pointer;
				  font-size: 0.95rem;
				  margin-top: 8px;
				  transition: background-color 0.3s ease;
			}

		.product input[type="button"]:hover,
		.product input[type="submit"]:hover
		{
		  background-color: #e65c50;
		}
		.star { font-size: 20px; margin-right: 2px; display: inline-block; }
		.star.filled { color: #ffc107; }
		.star.half {
		  color: #ffc107;
		  background: linear-gradient(to right, #ffc107 50%, #c0c0c0 50%);
		  -webkit-background-clip: text;
		  -webkit-text-fill-color: transparent;
		}
		.star.empty { color: #c0c0c0; text-shadow: 0 0 1px #999; }
		.product-card.out-of-stock {
			background-color: #f0f0f0;  /* Light grey background */
			box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Grey shadow */
			position: relative;
			opacity: 0.7;  /* Slightly faded to indicate unavailable */
		}
.product-card {
    border: 2px solid #b08d57;  /* gold-like border */
    border-radius: 12px;        /* rounded corners */
    background: #fff;           /* white card background */
    box-shadow: 0 4px 10px rgba(0,0,0,0.1); /* soft shadow */
    padding: 15px;
height:100%;
    transition: all 0.3s ease;  /* smooth hover effect */
}

/* Hover effect */
.product-card:hover {
    border-color: #581845;      /* deep purple border on hover */
    box-shadow: 0 6px 15px rgba(0,0,0,0.2);
    transform: translateY(-5px); /* slight lift */
}
		.overlay-notify {
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			background-color: rgba(88,24,69, 0.8); /* Red semi-transparent overlay */
			color: white;
			font-weight: bold;
			padding: 8px 12px;
			border-radius: 5px;
			font-size: 16px;
			text-align: center;
		}
		.rating-badge {
			display: inline-block;
			background-color: #28a745; /* green */
			color: white;
			font-weight: bold;
			padding: 2px 6px;
			border-radius: 8px;
			font-size: 0.85rem;
		}
		.enhanced-menu {
  background-color: #581845;
  padding: 8px 12px;
  border-radius: 8px;
  gap: 15px;
  flex-wrap: wrap;
  justify-content: center;
}

.enhanced-menu .menu-item {
  color: white;
  padding: 8px 14px;
  border-radius: 5px;
  cursor: pointer;
  font-weight: 500;
  transition: background 0.3s, color 0.3s;
}

.enhanced-menu .menu-item:hover {
  background-color: #ffcc00;
  color: #581845;
}

.enhanced-menu .active-category {
  background-color: #ffcc00;
  color: #581845;
  font-weight: bold;
}

/* Dropdown */
.enhanced-menu .dropdown {
  position: relative;
  display: inline-block;
}

.enhanced-menu .dropdown-content {
  display: none;
  position: absolute;
  background-color: #f5deb3;
  min-width: 180px;
  box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
  border-radius: 6px;
  z-index: 1000;
}

.enhanced-menu .dropdown-content .dropdown-item {
  color: #581845;
  padding: 10px 12px;
  display: block;
  cursor: pointer;
}

.enhanced-menu .dropdown-content .dropdown-item:hover {
  background-color: #b08d57;
  color: white;
}

.enhanced-menu .dropdown:hover .dropdown-content {
  display: block;
}

/* Responsive */
@media (max-width: 768px) {
  .enhanced-menu {
    flex-direction: column;
    align-items: center;
  }
  .enhanced-menu .dropdown-content {
    position: static;
    box-shadow: none;
  }
}/* Hide menu on screens smaller than 1024px (tablet & mobile) */
@media (max-width: 1024px) {
    .pc-menu {
        display: none !important;
    }
}





		
@media (max-width: 400px) {
    .slider-container {
        flex-wrap: wrap !important;
        justify-content: center !important;
        overflow-x: hidden !important;
    }
    .slide {
        min-width: 30% !important;
        margin: 5px !important;
    }
}
</style>

		<script>
		window.onload = function() {
    const params = new URLSearchParams(window.location.search);
    const category = params.get("category");

    if (category) {
        // Highlight normal menu items
        document.querySelectorAll(".mc").forEach(el => {
            if (el.getAttribute("name") === category) {
                el.classList.add("active-category");
            }
        });

        // Set dropdown value
        const dropdown = document.querySelector(".custom-select");
        if (dropdown) {
            for (let i = 0; i < dropdown.options.length; i++) {
                if (dropdown.options[i].value.toLowerCase() === category.toLowerCase()) {
                    dropdown.selectedIndex = i;
                    dropdown.classList.add("active-category"); // optional styling
                    break;
                }
            }
        }
    }
};

		function fun(element) 
		{
			let value = "";
			if (element.tagName === "SELECT")
			{
				value = element.value;
			} 
			else 		
			{
				value = element.getAttribute('name');
			}
			if (value) 
			{
				window.location.href = 'crafthome.php?category=' + encodeURIComponent(value);
			}
		}
		
		</script>



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
<body id="top1">
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
      						    $profileImg = !empty($row1['profile_img']) ? $row1['profile_img'] : 'userprofileimage/default.png';
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
							$row1 = ['uname' => 'Guest'];
							if ($usid > 0) {
								$sel1="select uname from craftus_reg where u_id=$usid";
								$select1=mysqli_query($con,$sel1);
								$row1=mysqli_fetch_array($select1);
							}
							$sel = "SELECT * FROM seller WHERE sellernm = '" . $row1['uname'] . "'";
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
           
		   <div class="menu-item mc" name="home" onclick="fun(this)" style="cursor:pointer; color:white; font-weight:400;">Home</div>
           <form id="ordersForm" method="POST" action="myorders.php" style="display:none;">
  <input type="hidden" name="uid" value="<?= $usid ?>">
</form>

<div class="mc" onclick="if(<?php echo isset($_SESSION['users_id']) ? $_SESSION['users_id'] : 0; ?> == 0) { Swal.fire({title: 'Login Required', text: 'Please login first!', icon: 'warning', showCancelButton: true, confirmButtonText: 'Login Now'}).then((result) => { if(result.isConfirmed) { window.location.href = 'logincraft.php'; } }); } else { document.getElementById('ordersForm').submit(); }" 
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

<script>
    // Dropdown toggle
    const dropdown = document.querySelector('.dropdown');
    const dropdownContent = dropdown.querySelector('.dropdown-content');

    dropdown.addEventListener('mouseenter', () => {
        dropdownContent.style.display = 'block';
    });
    dropdown.addEventListener('mouseleave', () => {
        dropdownContent.style.display = 'none';
    });
</script>

  <script>
  function f1() {
    const inputValue = document.getElementById('searchInput').value.trim();
    if (inputValue) {
      window.location.href = 'crafthome.php?category=' + encodeURIComponent(inputValue);
    } else {
      alert("Please enter a craft to search.");
    }
  }
</script>
	
	<div class="col-6 col-sm-3 col-md-3 text-end" style="margin-top:-20px; display: flex; justify-content: flex-end; gap: 15px;">
    <!-- Wishlist -->
   <form action="wishlist.php" method="POST" style="display:inline;">
    <input type="hidden" name="uid" value="<?php

 echo $usid; ?>">
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
    <form id="cartForm" action="cart.php" method="POST" style="display:inline;">
    <input type="hidden" name="uid" value="<?php

 echo $usid; ?>">
    <button type="button" onclick="if(<?php echo isset($_SESSION['users_id']) ? $_SESSION['users_id'] : 0; ?> == 0) { event.preventDefault(); Swal.fire({title: 'Login Required', text: 'Please login first!', icon: 'warning', showCancelButton: true, confirmButtonText: 'Login Now'}).then((result) => { if(result.isConfirmed) { window.location.href = 'logincraft.php'; } }); return false; } else { document.getElementById('cartForm').submit(); }" style="all:unset; cursor:pointer; display:inline-block;">
        <i class="fa-solid fa-cart-plus fa-xl" style="position: relative;font-size:27px;color:white;">
            <span id="cart-count" class="img-fluid rounded-circle" 
                  style="position: absolute; top: -13px; right: -12px; color: red; font-size: 12px; padding: 2px 5px; border-radius: 50%;">
                <?php

 echo $cartCount; ?>
            </span>
        </i>
    </button>
</form>
</div>
		</div>
		
		<!--<h2 style="color:red;text-align:center">Craft Categories</h2>-->
		<div class="slider-container" id="slider">
			<div class="slide"><img src='../home2.png' alt="home" name="home" onclick="fun(this);"><p>all Categories</p></div>
			<div class="slide"><img src='../new.jpg' alt="new" name="new" onclick="fun(this);"><p>new</p></div>
			<div class="slide"><img src='../trend.jpg' alt="trend" name="trend" onclick="fun(this);"><p>trend</p></div>
			<div class="slide"><img src='../auction.jpg' alt="auction" name="auction" onclick="fun(this);"><p>auction</p></div>
			<div class="slide"><img src='../decor.jpg' alt="home_decor" name="home_decor" onclick="fun(this);"><p>home_decor</p></div>
			<div class="slide"><img src='../pottery.jpg' alt="pottery" name="pottery" onclick="fun(this);"><p>pottery</p></div>
			<div class="slide"><img src='../clay.jpg' alt="Clay Art" name="clayart" onclick="fun(this);"><p>Clay Art</p></div>
			<div class="slide"><img src='../brass.jpg' alt="Brass Art" name="brassart" onclick="fun(this);"><p>Brass Art</p></div>
			<div class="slide"><img src='../wood.jpg' alt="Wooden Art" name="woodenart" onclick="fun(this);"><p>Wooden Art</p></div>
			<div class="slide"><img src='../bamboo.jpg' alt="Bamboo Art" name="bambooart" onclick="fun(this);"><p>Bamboo Art</p></div>
			<div class="slide"><img src='../leather.jpg' alt="Leather Art" name="leatherart" onclick="fun(this);"><p>Leather Art</p></div>
			<div class="slide"><img src='../patola_slik_sarees.jpg' alt="patola_slik_sarees" name="patola_slik_sarees" onclick="fun(this);"><p>patola_slik_sarees</p></div>
			<div class="slide"><img src='../bandhani.jpg' alt="bandhani" name="bandhani" onclick="fun(this);"><p>bandhani</p></div>
			<div class="slide"><img src='../kutchembroidery.jpg' alt="kutch_embroidery" name="kutch_embroidery" onclick="fun(this);"><p>kutch_embroidery</p></div>
			<div class="slide"><img src='../tangaliyashawl.jpg' alt="tangaliya_shawl" name="tangaliya_shawl" onclick="fun(this);"><p>tangaliya_shawl</p></div>
			<div class="slide"><img src='../surat_zari_craft.jpg' alt="surat_zari_craft" name="surat_zari_craft" onclick="fun(this);"><p>surat_zari_craft</p></div>
		</div>
	<script>
const slider = document.getElementById("slider");
let isDown = false;
let startX;
let scrollLeft;

slider.addEventListener('mousedown', (e) => {
    isDown = true;
    slider.classList.add('active');
    startX = e.pageX - slider.offsetLeft;
    scrollLeft = slider.scrollLeft;
});
slider.addEventListener('mouseleave', () => {
    isDown = false;
    slider.classList.remove('active');
});
slider.addEventListener('mouseup', () => {
    isDown = false;
    slider.classList.remove('active');
});
slider.addEventListener('mousemove', (e) => {
    if(!isDown) return;
    e.preventDefault();
    const x = e.pageX - slider.offsetLeft;
    const walk = (x - startX); // adjust scroll speed here
    slider.scrollLeft = scrollLeft - walk;
});

// Touch support
slider.addEventListener('touchstart', (e) => {
    isDown = true;
    startX = e.touches[0].pageX - slider.offsetLeft;
    scrollLeft = slider.scrollLeft;
});
slider.addEventListener('touchend', () => {
    isDown = false;
});
slider.addEventListener('touchmove', (e) => {
    if(!isDown) return;
    const x = e.touches[0].pageX - slider.offsetLeft;
    const walk = (x - startX);
    slider.scrollLeft = scrollLeft - walk;
});
 </script>
	<div class="container-fluid text-center mt-4">
  <div class="row mt-4 g-3">
    <?php


    $host = 'localhost';
    $db   = 'craftzon';
    $user = 'root';
    $pwd  = '';

    $con = mysqli_connect($host, $user, $pwd, $db);
    if (!$con) {
        die('Connection failed: ' . mysqli_connect_error());
    }
	// Fetch all product IDs in user's wishlist


   function displayProduct($row, $category = '') {
    global $usid, $con;

    // Fetch wishlist product IDs for current user
    $wishlistProducts = [];
    $resWishlist = mysqli_query($con, "SELECT product_id FROM wishlist WHERE user_id='$usid'");
    while ($rowW = mysqli_fetch_assoc($resWishlist)) {
        $wishlistProducts[] = $rowW['product_id'];
    }

    $productId = $row['product_id'];

    // Ratings
    $ratingQuery = "SELECT AVG(rating) AS avg_rating, COUNT(*) AS total_reviews 
                    FROM feedbacks 
                    WHERE order_id IN (SELECT orderid FROM craftorder WHERE productid = '$productId')";
    $ratingResult = mysqli_query($con, $ratingQuery);
    $ratingRow = mysqli_fetch_assoc($ratingResult);
    $avgRating = $ratingRow['avg_rating'] ? round($ratingRow['avg_rating'], 1) : 0;
    $totalReviews = $ratingRow['total_reviews'] ?? 0;

    $fullStars = floor($avgRating);
    $hasHalfStar = ($avgRating - $fullStars) >= 0.25 && ($avgRating - $fullStars) <= 0.75;
    $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);

    // Card class for normal styling (no highlight for out-of-stock)
    $cardClass = 'product-card';

    echo '<div class="col-6 col-sm-4 col-md-3">';
    echo '<div class="' . $cardClass . '">';

    // Determine if product is in stock
    $inStock = strtolower($row['stock_status']) != 'out of stock';

    // Image container
    echo '<div class="product-img-container" style="position:relative;">';

    // Product image clickable only if in stock
    if ($inStock) {
        echo '<img src="../' . $row['image'] . '" class="product-img" alt="' . $row['product_name'] . '" 
              onclick="viewProductPOST(' . $productId . ')" style="cursor:pointer;">';
    } else {
        echo '<img src="../' . $row['image'] . '" class="product-img" alt="' . $row['product_name'] . '">';
        echo '<div class="overlay-notify">Out of Stock</div>';
    }

    // Wishlist heart
        // Wishlist heart (show only if NOT auction category)
    if (strtolower($category) !== 'auction') {
        $inWishlist = in_array($productId, $wishlistProducts);
        echo '<div class="wishlist-icon" data-product-id="' . $productId . '" 
             style="position:absolute; top:10px; right:10px; font-size:22px; color:' . ($inWishlist ? 'red' : 'gray') . '; cursor:pointer;">
            <i class="fa-solid fa-heart"></i>
        </div>';
    }


    echo '</div>'; // end product-img-container

    // Product details
    echo '<h5 class="product-title">' . $row['product_name'] . '</h5>';
    echo '<p class="product-maker"><strong>Crafted by:</strong> ' . $row['crafted_by'] . '</p>';
    echo '<p class="product-price"><strong>Price:</strong> ₹' . $row['price'] . '</p>';
    echo '<p class="product-desc">' . substr($row['product_description'], 0, 60) . '...</p>';

    // Rating (skip for auction category)
    if (strtolower($category) != 'auction') {
        echo '<div class="product-rating">';
        for ($i = 0; $i < $fullStars; $i++) echo '<span class="star filled">★</span>';
        if ($hasHalfStar) echo '<span class="star half">★</span>';
        for ($i = 0; $i < $emptyStars; $i++) echo '<span class="star empty">★</span>';
        if ($avgRating > 0) echo '<span class="rating-badge">' . $avgRating . '★</span>';
        echo '</div>';
    }

    echo '</div>'; // end product-card
    echo '</div>'; // end col
}

    if (isset($_GET['category'])) {
        $category = trim($_GET['category']);

        if ($category == "home") {
            $query = "SELECT * FROM product_table WHERE status='active' AND crafted_by IN (SELECT sellernm FROM seller WHERE status='active')";
            $result = mysqli_query($con, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                displayProduct($row, $row['category']);
            }

        } elseif ($category == "new") {
            $query = "SELECT * FROM product_table WHERE created_at >= CURRENT_TIMESTAMP - INTERVAL 2 DAY AND status='active' AND crafted_by IN (SELECT sellernm FROM seller WHERE status='active')";
            $result = mysqli_query($con, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                displayProduct($row, $row['category']);
            }

        } elseif ($category == "trend") {
            $query = "SELECT productid FROM craftorder WHERE ordertime >= NOW() - INTERVAL 30 DAY GROUP BY productid ORDER BY SUM(quantity) DESC LIMIT 10";
            $result = mysqli_query($con, $query);
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $productid = $row['productid'];
                    $query11 = "SELECT * FROM product_table WHERE product_id = '$productid' AND status='active' AND crafted_by IN (SELECT sellernm FROM seller WHERE status='active')";
                    $result11 = mysqli_query($con, $query11);
                    while ($row11 = mysqli_fetch_assoc($result11)) {
                        displayProduct($row11, $row11['category']);
                    }
                }
            }

        } else {
            // Search logic with deduplication
            $safe_val = mysqli_real_escape_string($con, $category);
            $query1 = "SELECT * FROM product_table WHERE crafted_by LIKE '%$safe_val%' AND status='active'";
            $query2 = "SELECT * FROM product_table WHERE product_name LIKE '%$safe_val%' AND status='active'";
            $query3 = "SELECT * FROM product_table WHERE category LIKE '%$safe_val%' AND status='active'";
            $queries = [$query1, $query2, $query3];
            $displayed = [];

            foreach ($queries as $sql) {
                $result = mysqli_query($con, $sql);
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        if (!in_array($row['product_id'], $displayed)) {
                            displayProduct($row, $row['category']);
                            $displayed[] = $row['product_id'];
                        }
                    }
                }
            }
        }
    } else {
        echo "<p style='text-align:center;'>No search term provided.</p>";
    }

    mysqli_close($con);
    ?>
  </div>
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
		$(document).ready(function (){
		$("#option1").click(function()
		{
			$("#opdiv").toggle();
		});
		});
	</script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function updateCartCount() {
    $.get(window.location.href, { action: 'get_cart_count' }, function(data) {
        $("#cart-count").text(data);
    });
}

// Call on page load
updateCartCount();
</script>
<script>
$(document).ready(function() {
    // Toggle wishlist
    $(".wishlist-icon").click(function(e) {
        e.stopPropagation(); // Prevent redirect if inside <a>
        var icon = $(this).find("i");
        var productId = $(this).data("product-id");

        $.post(window.location.href, { wishlist_product_id: productId }, function(response) {
            if(response === 'not_logged_in') { Swal.fire({
                title: 'Login Required',
                text: 'Please login to manage your wishlist!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#581845',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Login Now'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'logincraft.php';
                }
            });
            return; }
            if(response === 'added') icon.css("color", "red");
            else if(response === 'removed') icon.css("color", "gray");

            // Update wishlist count
            $.get(window.location.href, { action: 'get_wishlist_count' }, function(count){
                $("#wishlist-count").text(count);
            });
        });
    });

    // Load wishlist count on page load
    $.get(window.location.href, { action: 'get_wishlist_count' }, function(count){
        $("#wishlist-count").text(count);
    });
});
</script>



<?php

 include 'chatbot.php'; ?>

<script>
function viewProductPOST(pid) {
    var form = document.createElement('form');
    form.method = 'POST';
    form.action = 'online_view.php';
    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'product_id';
    input.value = pid;
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}
</script>

<script>
function checkCartLogin() {
    var usid = <?php

 echo $usid; ?>;
    if (usid == 0) {
        Swal.fire({
            title: 'Login Required',
            text: 'Please login to view your cart!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#581845',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Login Now'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'logincraft.php';
            }
        });
    } else {
        document.getElementById('cartForm').submit();
    }
}
</script>
</body>


</html>


