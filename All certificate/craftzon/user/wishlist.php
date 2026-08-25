<?php
session_start();
$usid = $_SESSION['users_id'] ?? 0;
if ($usid == 0) { header('Location: logincraft.php'); exit; }
if (!$usid) {
    header('location:logincraft.php');
    exit();
}

$con = mysqli_connect("localhost", "root", "", "craftzon");
if (!$con) die("Connection failed: " . mysqli_connect_error());

// AJAX remove request
if (isset($_POST['wishlist_product_id'])) {
    $pid = intval($_POST['wishlist_product_id']);
    $query = "DELETE FROM wishlist WHERE user_id=$usid AND product_id=$pid";
    if (mysqli_query($con, $query)) echo 'removed';
    else echo 'error';
    exit();
}

// Fetch wishlist products
$query = "SELECT p.* 
          FROM product_table p
          JOIN wishlist w ON p.product_id = w.product_id
          WHERE w.user_id = $usid AND p.status='active'";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Wishlist | Craftzon</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
<link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css' rel='stylesheet'>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<style>
.product-card {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 16px;
    margin-bottom: 24px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    transition: box-shadow 0.3s ease, transform 0.3s ease;
    position: relative;
}
.product-card:hover { box-shadow: 0 6px 16px rgba(0,0,0,0.1); transform: translateY(-4px);}
.product-img { width:100%; height:220px; object-fit:cover; border-radius:8px; background:#f4f4f4; margin-bottom:12px; cursor:pointer; }
.product-title { font-size:1.2rem; font-weight:600; color:#2c3e50; margin-bottom:6px; }
.product-price { font-size:0.95rem; color:#555; margin-bottom:6px; }
.product-desc { font-size:0.9rem; color:#666; margin-bottom:6px; }
.product-rating { margin-bottom:8px; }
.star { font-size:18px; margin-right:2px; display:inline-block; }
.star.filled { color:#ffc107; }
.star.empty { color:#c0c0c0; }
.rating-badge { background-color:#28a745; color:#fff; font-weight:bold; padding:2px 6px; border-radius:8px; font-size:0.8rem; }
.wishlist-icon { position:absolute; top:10px; right:10px; font-size:22px; cursor:pointer; z-index:10; }
.product-card.out-of-stock { opacity:0.7; }
.overlay-notify { position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); background:rgba(255,0,0,0.8); color:white; font-weight:bold; padding:8px 12px; border-radius:5px; font-size:16px; }
.btn-home {
    display: inline-block;
    background-color: #581845;
    color: #fff;
    padding: 12px 25px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 1rem;
    transition: background-color 0.3s ease;
}

.btn-home:hover {
    background-color: #450c34;
}
.header {
    color: #581845;
    padding: 20px 0;
    text-align: center;
}
.header h1 {
    margin: 0;
    font-size: 2.2rem;
}
.header p {
    margin: 5px 0 0 0;
    font-size: 1rem;
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

<div class="header">
    <h1>CraftZon Wishlist</h1>
    <p>Connecting Artisans and Buyers Since 2025</p>
</div>

<div class="container my-4">

    <div class="row">
    <?php
    $wishlistProducts = [];
    while ($row = mysqli_fetch_assoc($result)) $wishlistProducts[] = $row;

    if (count($wishlistProducts) == 0) {
        echo "<p style='grid-column:1/-1; text-align:center; font-size:18px;'>Your wishlist is empty.</p>";
    } else {
        foreach ($wishlistProducts as $row) {
            $productId = $row['product_id'];

            // Ratings
            $ratingQuery = "SELECT AVG(rating) AS avg_rating FROM feedbacks WHERE order_id IN (SELECT orderid FROM craftorder WHERE productid='$productId')";
            $ratingResult = mysqli_query($con, $ratingQuery);
            $ratingRow = mysqli_fetch_assoc($ratingResult);
            $avgRating = $ratingRow['avg_rating'] ? round($ratingRow['avg_rating'],1) : 0;
            $fullStars = floor($avgRating);
            $emptyStars = 5 - $fullStars;

            $outOfStock = ($row['stock_status']=='out of stock') ? 'out-of-stock' : '';

            echo '<div class="col-6 col-md-4 col-lg-3">';
            echo '<div class="product-card '.$outOfStock.'">';
            
            echo '<div style="position:relative;">';
            echo '<img src="../'.$row['image'].'" class="product-img" onclick="viewProductPOST(' . $productId . ')" style="cursor:pointer;">';
            if ($outOfStock) echo '<div class="overlay-notify">Out of Stock</div>';
            echo '<div class="wishlist-icon" data-product-id="'.$productId.'"><i class="fa-solid fa-heart" style="color:red;"></i></div>';
            echo '</div>';

            echo '<h5 class="product-title">'.$row['product_name'].'</h5>';
            echo '<p class="product-price"><strong>Price:</strong> ₹'.$row['price'].'</p>';
            echo '<p class="product-desc">'.substr($row['product_description'],0,60).'...</p>';
            echo '<div class="product-rating">';
            for ($i=0;$i<$fullStars;$i++) echo '<span class="star filled">★</span>';
            for ($i=0;$i<$emptyStars;$i++) echo '<span class="star empty">★</span>';
            if ($avgRating>0) echo '<span class="rating-badge">'.$avgRating.'★</span>';
            echo '</div>';
            echo '</div></div>';
        }
    }
    ?>
    </div>
	
</div>
<br>
	<div class="section text-center">
       <a href="crafthome.php?category=home" class="btn-home">Back to Home</a>
    </div><br>

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
$(document).ready(function(){
    $(".wishlist-icon").click(function(e){
        e.stopPropagation();
        var icon = $(this).find("i");
        var card = $(this).closest('.product-card');
        var pid = $(this).data("product-id");

        $.post("wishlist.php", { wishlist_product_id: pid }, function(response){
            if(response === 'removed'){
                card.fadeOut(300, function(){ $(this).remove(); });
            } else {
                alert("Failed to remove item. Please try again.");
            }
        });
    });
});
</script>

<script>
$(document).ready(function(){
    // Toggle side menu on triple bar click
    $("#option1").click(function(){
        $("#opdivt").fadeToggle(); // smoother animation
    });
});
</script>

	

<script>
$(document).ready(function(){

    // 📌 Toggle side menu
    $("#option1").click(function(){
        $("#opdiv").toggle();
    });

    // 📌 Remove product from wishlist (without refresh)
    $(".wishlist-icon").click(function(e){
        e.stopPropagation();
        var icon = $(this).find("i");
        var card = $(this).closest('.product-card');
        var pid = $(this).data("product-id");

        $.post("wishlist.php", { wishlist_product_id: pid }, function(response){
            if(response === 'removed'){
                // Remove the card visually
                card.fadeOut(300, function(){ 
                    $(this).remove(); 
                });

                // ✅ Update wishlist count dynamically
                var countEl = $("#wishlist-count");
                var current = parseInt(countEl.text()) || 0;
                if(current > 0){
                    countEl.text(current - 1);
                }

                // ✅ If no items left, update everywhere
                if($(".product-card").length === 1){ 
                    // Product Grid
                    $(".row").html("<p style='grid-column:1/-1; text-align:center; font-size:18px;'>Your wishlist is empty.</p>");
                    
                    // Header
                    $(".header h1").text("Your Wishlist is Empty");
                    $(".header p").text("Start adding products to save them here!");
                    
                    // Footer (you can choose where to display)
                    $(".sfdiv").prepend("<p style='text-align:center; width:100%; font-size:16px; color:white;'>Your wishlist is empty.</p>");
                }

            } else {
                alert("Failed to remove item. Please try again.");
            }
        });
    });

});

</script>

<?php include 'chatbot.php'; ?>

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
</body>

</html>
