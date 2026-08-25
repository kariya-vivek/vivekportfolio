<?php
$con = mysqli_connect("localhost", "root", "", "craftzon");
if(!$con){
    die("Database connection failed: " . mysqli_connect_error());
}

$seid = (int)$_POST['sellerid'];  // seller id from URL
$message = "";

// Fetch commission record (latest one, paid or unpaid)
$sql = "SELECT * FROM seller_commission WHERE seller_id=$seid ORDER BY id DESC LIMIT 1";
$result = mysqli_query($con, $sql);
$commission = mysqli_fetch_assoc($result);

if(isset($_POST['pay'])){
    $upi_id = trim($_POST['upi_id']);

    // Basic validation for UPI ID (example: name@bank)
    if(empty($upi_id)){
        $message = "❌ Please enter your UPI ID.";
    } elseif(!preg_match("/^[\w.\-]{2,50}@[a-zA-Z]{2,50}$/", $upi_id)){
        $message = "❌ Invalid UPI ID format. Example: name@upi";
    } else {
        if($commission && $commission['status'] == 'unpaid'){
            $cid = (int)$commission['id'];
            $update = "UPDATE seller_commission 
                       SET status='paid', payment_method='upi', upi_id='" . mysqli_real_escape_string($con, $upi_id) . "' 
                       WHERE id=$cid";
            if(mysqli_query($con, $update)){
                $message = "✅ Commission marked as Paid successfully using UPI!";
                // Refresh commission variable
                $commission['status'] = 'paid';
                $commission['upi_id'] = $upi_id;
                $commission['payment_method'] = 'upi';
            } else {
                $message = "❌ Failed to update commission status.";
            }
        } else {
            $message = "No unpaid commission found.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pay Commission - Craftzon</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background:#f8f9fa; font-family: Arial, sans-serif; }
        .container { max-width: 600px; margin: 60px auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        h2 { color:#581845; }
        .btn-pay { background:#581845; color:white; border:none; padding:10px 20px; border-radius:5px; }
        .btn-pay:hover { background:#450c34; }
        .msg { margin-top:15px; font-weight:bold; }
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
<div class="container">
    <h2>Pay Commission</h2>
    <hr>
    <?php if($commission){ ?>
        <p><strong>Month/Year:</strong> <?= htmlspecialchars($commission['month_year']); ?></p>
        <p><strong>Delivered Sales:</strong> ₹<?= htmlspecialchars($commission['delivered_sales']); ?></p>
        <p><strong>Commission:</strong> ₹<?= htmlspecialchars($commission['commission']); ?></p>

        <?php if($commission['status'] == 'unpaid'){ ?>
            <form method="post">
			<input type="hidden" name="sellerid" value="<?= $seid ?>">

                <div class="mb-3">
                    <label for="upi_id" class="form-label">Enter UPI ID</label>
                    <input type="text" 
                           id="upi_id" 
                           name="upi_id" 
                           class="form-control" 
                           value="<?= htmlspecialchars($commission['upi_id'] ?? '') ?>" 
                           placeholder="example@upi" 
                           required>
                </div>
                <button type="submit" name="pay" class="btn-pay">Pay via UPI</button>
            </form>
        <?php } else { ?>
            <p><strong>Status:</strong> ✅ Paid</p>
            <p><strong>Payment Method:</strong> <?= htmlspecialchars($commission['payment_method']); ?></p>
            <p><strong>UPI ID Used:</strong> <?= htmlspecialchars($commission['upi_id']); ?></p>
        <?php } ?>
    <?php } else { ?>
        <p><em>No commission record found for this seller.</em></p>
    <?php } ?>

    <?php if($message){ ?>
        <p class="msg"><?= $message; ?></p>
    <?php } ?>

   <form id="backForm" action="store.php" method="post" style="display:inline;">
    <input type="hidden" name="sellerid" value="<?= $seid ?>">
    <button type="submit" class="btn btn-secondary mt-3">← Back to Store</button>
</form>

</div>
</body>
</html>
