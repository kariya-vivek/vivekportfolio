<?php
session_start();
$usid = isset($_SESSION["users_id"]) ? $_SESSION["users_id"] : 0;
$us_profile = $usid;
$con = mysqli_connect("localhost", "root", "", "craftzon");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
<link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css' rel='stylesheet'>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Craftzon App User Guide</title>
<style>
body {
    font-family: 'Roboto', sans-serif;
    background-color: #fdf6f0;
    color: #333;
}
.header {
   
    padding: 20px 0;
    text-align: center;
}
.header h1 {
    margin: 0;
    font-size: 2.2rem;
}
.container-content {
    max-width: 900px;
    margin: 40px auto;
    padding: 0 20px;
}
.blog-post {
    background-color: #fff;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
}
.blog-post h2 {
    color: #581845;
    margin-bottom: 10px;
    font-size: 1.8rem;
}
.blog-post p {
    line-height: 1.6;
    font-size: 1rem;
}
.btn-home {
    display: inline-block;
    background-color: #581845;
    color: #fff;
    padding: 12px 25px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 1rem;
    transition: background-color 0.3s ease;
    margin-top: 20px;
}
.btn-home:hover {
    background-color: #450c34;
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

.container {
    max-width: 900px;
    margin: 40px auto;
    padding: 20px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
}
h1 {
    color: white;
    text-align: center;
    margin-bottom: 20px;
}
h2 {
    color: #581845;
    margin-top: 20px;
}
p, li {
    line-height: 1.6;
    margin-bottom: 10px;
    color: white;
}
ul {
    margin-left: 20px;
}
.step-img {
    width: 100%;
    max-width: 400px;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin-bottom: 15px;
}
.back-home {
    display: inline-block;
    margin-top: 20px;
    padding: 12px 25px;
    background: #581845;
    color: #fff;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}
.back-home:hover {
    background: #450c34;
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
    <h1 style="color:#581845">In Future Craftzon App User Guide</h1>
	
</div>

<div class="container blog-post">

    <h2>Step 1: Install from Play Store</h2>
    <p>Open the <strong>Google Play Store</strong> on your Android device, search for <strong>"Craftzon – Handmade Things"</strong>, select the app, and tap <strong>Install</strong>.</p>
    <img src='../step1image.png' alt="Install Craftzon App from Play Store" class="step-img">

    <h2>Step 2: Open the App</h2>
    <p>Once installed, tap <strong>Open</strong> from the Play Store or the app icon on your home screen to launch Craftzon.</p>
    <img src='../step2image.png' alt="Craftzon Home Screen" class="step-img">

    <h2>Step 3: Register or Login</h2>
    <p>If you are a new user, tap <strong>Register</strong> to create a new account using your email. If you already have an account, tap <strong>Login</strong> and enter your credentials.</p>
    <img src='../step3image.png' alt="Register or Login Screen" class="step-img">

    <h2>Step 4: Explore the Home Page</h2>
    <p>After logging in, you will land on the Craftzon home page where you can:</p>
    <ul>
        <li>Browse handmade products by category</li>
        <li>Advertise your own products</li>
        <li>Access your profile and wishlist</li>
        <li>Check purchase protection and help sections</li>
    </ul>
    <img src='../step4image.png' alt="Craftzon Home Page" class="step-img">

    <h2>Tips for Using the App</h2>
    <ul>
        <li>Always keep the app updated from Play Store.</li>
        <li>Use a valid email for registration to receive notifications.</li>
        <li>Explore categories and use search for faster navigation.</li>
        <li>Check “100% Purchase Protection” for safe shopping.</li>
    </ul>
    <a href="crafthome.php?category=home" class="btn-home">← Back to Home</a>
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

</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
		$(document).ready(function (){
		$("#option1").click(function()
		{
			$("#opdiv").toggle();
		});
		});
	</script>
</html>