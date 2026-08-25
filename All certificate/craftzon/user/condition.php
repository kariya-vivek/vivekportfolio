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
<title>CraftZon Conditions of Use</title>
<style>
/* Basic Reset & Font */
body {
    margin: 0;
    padding: 0;
    font-family: 'Inter', sans-serif;
    line-height: 1.6;
    background-color: #F8F8F8;
    color: #333333;
    font-size: 16px;
}

/* Header & Footer */
.header {
   
    padding: 20px 0;
    text-align: center;
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

.header h1 {
    margin: 0;
    font-size: 2.2rem;
}
.header p {
    margin: 5px 0 0 0;
    font-size: 1rem;
}

.footer {
    background-color: #581845;
    color: #fff;
    padding: 30px 20px;
    text-align: center;
}
.footer a {
    color: #ffcc00;
    text-decoration: none;
}
.footer a:hover {
    text-decoration: underline;
}

/* Container */
.container {
    max-width: 900px;
    margin: 20px auto;
    padding: 1.5em 5%;
    background-color: #FFFFFF;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border-radius: 8px;
}

/* Headers */
h1, h2, h3 {
    color: #A0522D;
    margin-top: 1.5em;
    margin-bottom: 0.8em;
    line-height: 1.2;
}

h1 {
    font-size: 2.2em;
    text-align: center;
    padding-bottom: 0.5em;
    border-bottom: 2px solid #D2B48C;
}

h2 {
    font-size: 1.8em;
    border-bottom: 1px solid #EEEEEE;
    padding-bottom: 0.3em;
}
.sfdiv ul li::before {
    content: none !important;
}

h3 {
    font-size: 1.4em;
    margin-top: 1.2em;
}

/* Paragraphs and Lists */
p {
    margin-bottom: 1em;
}

ul {
    list-style: none;
    padding-left: 0;
    margin-bottom: 1em;
}

ul li {
    position: relative;
    padding-left: 1.8em;
    margin-bottom: 0.6em;
}

ul li::before {
    content: "\2022";
    color: #D2B48C;
    font-weight: bold;
    display: inline-block;
    position: absolute;
    left: 0;
    top: 0;
}

a {
    color: #4682B4;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

strong {
    color: #555555;
}

.last-updated {
    text-align: center;
    font-style: italic;
    color: #777777;
    margin-bottom: 2em;
}

.contact-info {
    background-color: #FAFAFA;
    border: 1px solid #D2B48C;
    padding: 1.5em;
    margin-top: 2em;
    border-radius: 8px;
}

.contact-info p {
    margin-bottom: 0.5em;
}

.contact-info strong {
    display: block;
    margin-bottom: 0.5em;
    font-size: 1.1em;
    color: #A0522D;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .container {
        margin: 10px auto;
        padding: 1em 4%;
    }

    h1 { font-size: 1.8em; }
    h2 { font-size: 1.5em; }
    h3 { font-size: 1.2em; }
    body { font-size: 15px; }
}

@media (max-width: 480px) {
    .container {
        margin: 5px auto;
        padding: 0.8em 3%;
        box-shadow: none;
        border-radius: 0;
    }

    h1 { font-size: 1.5em; padding-bottom: 0.3em; }
    h2 { font-size: 1.3em; }
    h3 { font-size: 1.1em; }
    body { font-size: 14px; }

    ul li { padding-left: 1.5em; }
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

<!-- Header -->
<div class="header">
    <h1>CraftZon Conditions of Use</h1>
    <p>Connecting Artisans and Buyers Since 2025</p>
</div>

<!-- Main Content -->
<div class="container">
    <p class="last-updated"><strong>Last Updated:</strong> July 23, 2025</p>

    <p>Welcome to CraftZon! These Conditions of Use ("Terms") govern your access to and use of the CraftZon website, mobile applications, and services (collectively, the "Service")...</p>

    <h2>1. Acceptance of Terms</h2>
    <p>By creating an account, accessing, or using CraftZon, you affirm that you are at least 18 years old...</p>

    <h2>2. Definitions</h2>
    <ul>
        <li><strong>"Service"</strong> refers to the CraftZon website, mobile applications, and all related services provided by CraftZon.</li>
        <li><strong>"User," "You," "Your"</strong> refers to any individual or entity accessing or using the Service.</li>
        <li><strong>"Seller"</strong> refers to a User who creates listings and sells handmade products through the Service.</li>
        <li><strong>"Buyer"</strong> refers to a User who purchases handmade products through the Service.</li>
        <li><strong>"Content"</strong> refers to any text, graphics, images, audio, video, or other material uploaded, posted, or displayed on the Service.</li>
    </ul>
	
	<h2>3. Account Registration and Security</h2>
        <ul>
            <li>You must register for an account to access certain features of the Service. You agree to provide accurate, current, and complete information during the registration process and to update such information to keep it accurate, current, and complete.</li>
            <li>You are responsible for safeguarding your password and for all activities that occur under your account. You agree to notify CraftZon immediately of any unauthorized use of your account.</li>
            <li>CraftZon reserves the right to suspend or terminate your account and refuse any and all current or future use of the Service if any information provided proves to be inaccurate, not current, or incomplete.</li>
        </ul>

        <h2>4. User Conduct</h2>
        <p>You agree not to:</p>
        <ul>
            <li>Use the Service for any illegal purpose or in violation of any local, state, national, or international law.</li>
            <li>Post, upload, or transmit any Content that is unlawful, harmful, threatening, abusive, harassing, defamatory, vulgar, obscene, libelous, invasive of another's privacy, hateful, or racially, ethnically, or otherwise objectionable.</li>
            <li>Impersonate any person or entity, or falsely state or otherwise misrepresent your affiliation with a person or entity.</li>
            <li>Interfere with or disrupt the Service or servers or networks connected to the Service.</li>
            <li>Attempt to gain unauthorized access to any portion of the Service, other accounts, computer systems, or networks connected to the Service, whether through hacking, password mining, or any other means.</li>
            <li>Engage in any form of "spamming," "phishing," or other similar activities.</li>
            <li>Use the Service to sell mass-produced items or items not genuinely handmade by you or your team.</li>
        </ul>

        <h2>5. Content Submission and Intellectual Property</h2>
        <h3>5.1. Your Content</h3>
        <ul>
            <li>You retain all rights in, and are solely responsible for, the Content you submit to the Service.</li>
            <li>By submitting Content, you grant CraftZon a worldwide, non-exclusive, royalty-free, transferable, and sublicensable license to use, reproduce, distribute, prepare derivative works of, display, and perform the Content in connection with the Service and CraftZon's (and its successors' and affiliates') business, including without limitation for promoting and redistributing part or all of the Service (and derivative works thereof) in any media formats and through any media channels.</li>
            <li>You warrant that your Content does not infringe any third-party intellectual property rights, privacy rights, or publicity rights.</li>
        </ul>
        <h3>5.2. CraftZon's Intellectual Property</h3>
        <p>All intellectual property rights in the Service, including but not limited to copyrights, trademarks, service marks, logos, and designs, are owned by CraftZon or its licensors. You may not use any of CraftZon's intellectual property without our prior written consent.</p>

        <h2>6. Purchases and Sales</h2>
        <ul>
            <li>CraftZon provides a platform for Sellers and Buyers to connect and conduct transactions. We are not a party to any actual contract between Buyers and Sellers.</li>
            <li><strong>For Buyers:</strong> You agree to pay for items purchased and acknowledge that all sales are final unless otherwise stated by the Seller or required by law.</li>
            <li><strong>For Sellers:</strong> You are responsible for accurately describing your items, setting prices, fulfilling orders, and handling shipping. You agree to comply with all applicable laws regarding the sale of your products.</li>
            <li>CraftZon is not responsible for the quality, safety, or legality of items listed, the truth or accuracy of listings, or the ability of Sellers to sell items or Buyers to pay for items.</li>
        </ul>

        <h2>7. Disclaimers</h2>
        <p>THE SERVICE IS PROVIDED "AS IS" AND "AS AVAILABLE," WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESS OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, AND NON-INFRINGEMENT. CRAFTZON DOES NOT WARRANT THAT THE SERVICE WILL BE UNINTERRUPTED, SECURE, OR ERROR-FREE, OR THAT DEFECTS WILL BE CORRECTED.</p>

        <h2>8. Limitation of Liability</h2>
        <p>TO THE MAXIMUM EXTENT PERMITTED BY APPLICABLE LAW, IN NO EVENT SHALL CRAFTZON, ITS AFFILIATES, DIRECTORS, EMPLOYEES, OR LICENSORS BE LIABLE FOR ANY INDIRECT, INCIDENTAL, SPECIAL, CONSEQUENTIAL, OR PUNITIVE DAMAGES, OR ANY LOSS OF PROFITS OR REVENUES, WHETHER INCURRED DIRECTLY OR INDIRECTLY, OR ANY LOSS OF DATA, USE, GOODWILL, OR OTHER INTANGIBLE LOSSES, RESULTING FROM (A) YOUR ACCESS TO OR USE OF OR INABILITY TO ACCESS OR USE THE SERVICE; (B) ANY CONDUCT OR CONTENT OF ANY THIRD PARTY ON THE SERVICE; (C) ANY CONTENT OBTAINED FROM THE SERVICE; OR (D) UNAUTHORIZED ACCESS, USE, OR ALTERATION OF YOUR TRANSMISSIONS OR CONTENT.</p>

        <h2>9. Indemnification</h2>
        <p>You agree to defend, indemnify, and hold harmless CraftZon, its affiliates, licensors, and service providers, and its and their respective officers, directors, employees, contractors, agents, licensors, suppliers, successors, and assigns from and against any claims, liabilities, damages, judgments, awards, losses, costs, expenses, or fees (including reasonable attorneys' fees) arising out of or relating to your violation of these Conditions of Use or your use of the Service, including, but not limited to, your Content, any use of the Service's content, services, and products other than as expressly authorized in these Conditions of Use, or your use of any information obtained from the Service.</p>

        <h2>10. Governing Law and Jurisdiction</h2>
        <p>These Terms shall be governed and construed in accordance with the laws of [Your Country/State], without regard to its conflict of law provisions. Any dispute arising from or relating to the subject matter of these Terms shall be subject to the exclusive jurisdiction of the courts located in [Your City, Your Country/State].</p>

        <h2>11. Changes to the Conditions of Use</h2>
        <p>CraftZon reserves the right, at its sole discretion, to modify or replace these Terms at any time. If a revision is material, we will provide at least 30 days' notice prior to any new terms taking effect. What constitutes a material change will be determined at our sole discretion. By continuing to access or use our Service after those revisions become effective, you agree to be bound by the revised terms. If you do not agree to the new terms, please stop using the Service.</p>

    <!-- Rest of your conditions content goes here (as in your original code) -->

    <h2>12. Contact Us</h2>
    <div class="contact-info">
        <strong>CraftZon Support Team</strong>
        <p><strong>Email:</strong> <a href="mailto:craftzon25@gmail.com">craftzon25@gmail.com</a></p>
        <p><strong>Address:</strong> [amreli]</p>
        <p><strong>Website:</strong> <a href="https://www.craftzon.com" target="_blank">www.craftzon.com</a></p>
    </div>
	<br>
	<div class="section text-center">
       <a href="crafthome.php?category=home" class="btn-home">Back to Home</a>
    </div><br>

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
