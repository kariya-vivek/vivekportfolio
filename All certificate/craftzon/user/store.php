<?php
	$con = mysqli_connect("localhost", "root", "", "craftzon");
	$seid = (int)$_POST['sellerid'];  // cast to int for safety
	// Check if seller has unpaid commission
$hasUnpaidCommission = false;
$checkCommission = "SELECT * FROM seller_commission WHERE seller_id=$seid AND status='unpaid' LIMIT 1";
$commissionResult = mysqli_query($con, $checkCommission);
if ($commissionResult && mysqli_num_rows($commissionResult) > 0) {
    $hasUnpaidCommission = true;
}

	$sel = "SELECT * FROM seller WHERE sellerid=$seid";
	$select=mysqli_query($con,$sel);
	$row=mysqli_fetch_array($select);
	$suspended = false;
if ($row['status'] == 'suspend') {
    $suspended = true;
}
$followerCount = 0;
$followerQuery = "SELECT COUNT(*) AS total FROM follow WHERE sellerid = $seid";
$followerResult = mysqli_query($con, $followerQuery);
if ($followerResult) {
    $followerRow = mysqli_fetch_assoc($followerResult);
    $followerCount = $followerRow['total'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Store Administrator Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
            color: #333;
        }
       .container {
  max-width: 1200px;
  margin: 40px auto;
  padding: 40px;
  background: #ffffff;
  border-radius: 16px;
  box-shadow: 0 12px 40px rgba(0,0,0,0.08);
}
.container {
  max-width: 1200px;
  margin: 40px auto;
  padding: 40px;
  background: linear-gradient(145deg, #ffffff, #f9f9f9);
  border-radius: 16px;
  box-shadow: 0 12px 40px rgba(0,0,0,0.08);
}

.header {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  gap: 30px;
  border-bottom: 2px solid #f0f0f0;
  padding-bottom: 30px;
}

.store-banner {
  display: flex;
  align-items: center;
  gap: 30px;
}

.store-logo {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  object-fit: cover;
  border: 4px solid #f9f9f9;
  box-shadow: 0 5px 20px rgba(0,0,0,0.1);
  transition: transform 0.3s ease;
}
.store-logo:hover {
  transform: scale(1.05);
}

.store-info {
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.store-name {
  font-size: 2.2rem;
  font-weight: 700;
  margin: 0;
  letter-spacing: 0.5px;
  background: linear-gradient(90deg, #1a237e, #3949ab);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.store-info p {
  margin: 6px 0;
  font-size: 1rem;
  color: #555;
}

.store-info p strong {
  color: #222;
}

.followers {
  margin-top: 10px;
  font-size: 1.05rem;
  font-weight: 600;
  color: #ff5722;
}

/* Responsive */
@media (max-width: 768px) {
  .header, .store-banner {
    flex-direction: column;
    text-align: center;
  }
  .store-logo {
    margin-bottom: 15px;
  }
}


.followers {
  margin-top: 10px;
  font-size: 1.05rem;
  font-weight: 600;
  color: #ff5722;
}
 .buttons-section {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }
        .admin-button {
            background-color: #007bff;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
            text-decoration: none; /* For anchor tags if used for navigation */
            display: inline-block; /* Ensure padding works correctly */
        }
        .admin-button:hover {
            background-color: #0056b3;
        }
        @media (max-width: 768px) {
  .header, .store-banner {
    flex-direction: column;
    text-align: center;
  }
  .store-logo {
    margin-bottom: 15px;
  }
}
.container {
  background: linear-gradient(145deg, #ffffff, #f9f9f9);
}
.btn-home { display: inline-block; background-color: #581845; color: #fff; padding: 12px 25px; border-radius: 6px; text-decoration: none; font-size: 1rem; transition: background-color 0.3s ease; }
.btn-home:hover { background-color: #450c34; }	
.headermain { background-color: #581845; color: #fff; padding: 25px 0; text-align: center; }
.headermain h1 { margin: 0; font-size: 2rem; }
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
    </style>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
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
<div class="headermain">
    <h1>Welcome to <?php echo htmlspecialchars($row['storenm']); ?> – Your Handmade Crafts Store on Craftzon</h1>
    <p>Discover unique, handcrafted products and support talented artisans worldwide</p>
</div>


   <div class="container">
  <div class="header">
    <div class="store-banner">
      <img src="../<?php echo $row['shopimage']?>" alt="Store Logo" class="store-logo">
      <div class="store-info">
        <h1 class="store-name"><?php echo $row['storenm']?></h1>
        <p class="owner"><strong>👤 Owner:</strong> <?php echo $row['sellernm']?></p>
        <p class="email"><strong>📧 Email:</strong> <?php echo $row['selleremailid']?></p>
        <p class="followers"><strong>⭐ Followers:</strong> <?php echo $followerCount; ?></p>
      </div>
    </div>
  </div>
  <br><br>




        <div class="buttons-section">

    <!-- Add New Product -->
    <form action="../seller/addproduct.php" method="post" style="display:inline;">
        <input type="hidden" name="sellernm" value="<?php echo htmlspecialchars($row['sellernm']); ?>">
        <button type="submit" class="admin-button">Add New Product</button>
    </form>

    <!-- Dashboard -->
   <button class="admin-button" onclick="location.href='../seller/selleradminpanel.php?sellernm=<?php echo $row['sellernm']; ?>'">Dashboard</button>

    <!-- Edit Store Details -->
    <form action="../seller/editstroe.php" method="post" style="display:inline;">
        <input type="hidden" name="sellerid" value="<?php echo (int)$row['sellerid']; ?>">
        <button type="submit" class="admin-button">Edit Store Details</button>
    </form>

    <!-- Advertise Product -->
    <form action="../seller/seller_advertise.php" method="post" style="display:inline;">
        <input type="hidden" name="sellerid" value="<?php echo (int)$row['sellerid']; ?>">
        <button type="submit" class="admin-button">Advertise Product</button>
    </form>

    <!-- Add Crafter Story -->
    <form action="add_story.php" method="post" style="display:inline;">
        <input type="hidden" name="sellerid" value="<?php echo (int)$row['sellerid']; ?>">
        <button type="submit" class="admin-button">Add Crafter Story</button>
    </form>

    

		<?php
// Check if today is the last day of the month
$isMonthEnd = (date('t') == date('j'));

// Get the latest unpaid commission record for this seller
$commissionSql = "SELECT * FROM seller_commission WHERE seller_id=" . (int)$row['sellerid'] . " AND status='unpaid' ORDER BY id DESC LIMIT 1";
$commissionRes = mysqli_query($con, $commissionSql);
$unpaidCommission = mysqli_fetch_assoc($commissionRes);
?>

<?php if ($unpaidCommission): ?>
    <?php if ($isMonthEnd || $unpaidCommission): ?>
        <form action="pay_commission.php" method="post" style="display:inline;">
            <input type="hidden" name="sellerid" value="<?php echo (int)$row['sellerid']; ?>">
            <button type="submit" class="admin-button">Pay Commission</button>
        </form>
    <?php endif; ?>
<?php endif; ?>


			
	 </div>

        <hr style="border: 0; border-top: 1px solid #eee; margin: 40px 0;">

        <div class="container-fluid text-center mt-4">
    <div class="row mt-4 g-3">
        <?php
            $sql = "SELECT * FROM product_table WHERE crafted_by='" . $row['sellernm'] . "'";
            $s = mysqli_query($con, $sql);

            if (mysqli_num_rows($s) > 0) {
                while ($row1 = mysqli_fetch_assoc($s)) {
                    echo '<div class="col-12 col-sm-6 col-md-4 col-lg-3">';
                    echo '  <div class="card h-100">';
                    
                    echo '      <img src="../' . htmlspecialchars($row1['image']) . '" class="card-img-top" alt="' . htmlspecialchars($row1['product_name']) . '">';
                    
                    echo '    <div class="card-body d-flex flex-column">';
                    echo '      <h5 class="card-title">' . htmlspecialchars($row1['product_name']) . '</h5>';
                    echo '      <p class="card-text mb-1"><strong>Crafted by:</strong> ' . htmlspecialchars($row1['crafted_by']) . '</p>';
                    echo '      <p class="card-text mb-1"><strong>Price:</strong> ₹' . htmlspecialchars($row1['price']) . '</p>';
                    echo '      <p class="card-text text-truncate" title="' . htmlspecialchars($row1['product_description']) . '">' . htmlspecialchars($row1['product_description']) . '</p>';
                    //echo '      <a href="online_view.php?product_id=' . $row1['product_id'] . '" class="btn btn-primary mt-auto">View Details</a>';
                    echo '    </div>';
                    echo '  </div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No products found for this seller.</p>';
            }
        ?>
    </div>
</div>

            <p style="text-align: center; margin-top: 30px; color: #777;">(More products would be listed here dynamically)</p>
		<br>	<div class="text-center">
        <a href="crafthome.php?category=home" class="btn-home">Back to Home</a>
    </div>
        </div>
		<br>
	<div class="footer">
    <p>&copy; 2025 Craftzon. All Rights Reserved.</p>
    <p><a href="condition.html">Terms & Conditions</a> | <a href="privacypolicy.html">Privacy Policy</a></p>
</div>

    </div>
	<div id="suspendModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999;">
    <div style="background:#fff; padding:30px; border-radius:8px; max-width:500px; margin:100px auto; text-align:center;">
        <h2>Account Suspended</h2>
        <p>Your store is currently suspended. Redirecting to home page... </p><br>
		<p>Please resolve any related issues and contact your designated representative for further assistance.</p>
    </div>
	
</div>

</body>
<script>
    window.onload = function() {
        <?php if ($suspended): ?>
            const modal = document.getElementById('suspendModal');
            modal.style.display = 'block';
            setTimeout(() => {
                window.location.href = "crafthome.php";
            }, 10000); // 10 seconds
        <?php endif; ?>
    };
</script>

</html>