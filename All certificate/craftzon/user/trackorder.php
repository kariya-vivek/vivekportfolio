<?php
$con = mysqli_connect("localhost", "root", "", "craftzon");
$orderid = $_POST['orderid']; 

$sql = "SELECT ordertime, excepdelivdate, order_request_status, order_status, processed_date, previous_status 
        FROM craftorder WHERE orderid=$orderid";
$res = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($res);

$order_date = $row['ordertime'];
$expected_date = $row['excepdelivdate'];
$order_request_status = $row['order_request_status'];
$order_status = $row['order_status'];
$processed_date = $row['processed_date'];
$previous_status = $row['previous_status'];

// ✅ Set processed_date when first moved to processed
if ($order_request_status === 'processed' && (empty($processed_date) || $processed_date == "0000-00-00")) {
    $processed_date = date("Y-m-d H:i:s");
    $updateProcessedDateSql = "UPDATE craftorder SET processed_date='$processed_date' WHERE orderid=$orderid";
    mysqli_query($con, $updateProcessedDateSql);	
}

// ✅ Ensure expected delivery date exists
if (empty($expected_date) || $expected_date == "0000-00-00") {
    $expected_date = date('Y-m-d', strtotime($processed_date . ' +7 days'));
    $updateExpectedSql = "UPDATE craftorder SET excepdelivdate='$expected_date' WHERE orderid=$orderid";
    mysqli_query($con, $updateExpectedSql);
}

$elapsedDays = 0;
if ($order_request_status === "processed" && !empty($processed_date) && $processed_date != "0000-00-00") {
    $elapsedDays = floor((time() - strtotime($processed_date)) / (60 * 60 * 24));
}

// ✅ Auto status update (sequential, no skipping directly to Delivered)
if ($elapsedDays >= 1 && $elapsedDays < 3 && $order_status !== 'Shipped') {
    $update = "UPDATE craftorder SET previous_status='$order_status', order_status='Shipped' WHERE orderid=$orderid";
    mysqli_query($con, $update);
    $previous_status = $order_status;
    $order_status = 'Shipped';
} elseif ($elapsedDays >= 3 && $elapsedDays < 5 && $order_status !== 'Out for Delivery') {
    $update = "UPDATE craftorder SET previous_status='$order_status', order_status='Out for Delivery' WHERE orderid=$orderid";
    mysqli_query($con, $update);
    $previous_status = $order_status;
    $order_status = 'Out for Delivery';
} elseif ($elapsedDays >= 5 && $order_status !== 'Delivered') {
    $update = "UPDATE craftorder SET previous_status='$order_status', order_status='Delivered' WHERE orderid=$orderid";
    mysqli_query($con, $update);
    $previous_status = $order_status;
    $order_status = 'Delivered';

    $updatePayment = "UPDATE payments 
                      SET payment_status='Completed', payment_date=NOW() 
                      WHERE order_id=$orderid AND payment_method='COD' 
                      AND payment_status='Pending'";
    mysqli_query($con, $updatePayment);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Track Order Progress | Craftzon</title>
  <style>
    body {font-family:'Segoe UI',sans-serif;background:#f4f6f8;margin:0;padding:0;}
    .container {max-width:700px;margin:60px auto;background:#fff;padding:30px;border-radius:12px;box-shadow:0 8px 20px rgba(0,0,0,0.1);}
    h2 {text-align:center;color:#333;margin-bottom:30px;}
    
    .progress-tracker {display:flex;justify-content:space-between;position:relative;margin:40px 20px;}
    .progress-tracker::before {content:"";position:absolute;top:20px;left:0;width:100%;height:6px;background:#e0e0e0;z-index:0;border-radius:4px;}
    .progress-fill {content:"";position:absolute;top:20px;left:0;height:6px;background:#00b894;z-index:1;border-radius:4px;transition:width .6s ease;}
    
    .step {text-align:center;width:25%;position:relative;z-index:2;}
    .circle {width:40px;height:40px;border-radius:50%;background:#e0e0e0;margin:0 auto;line-height:40px;color:#fff;font-weight:bold;display:flex;align-items:center;justify-content:center;transition:background .5s;}
    .circle img {width:22px;height:22px;}
    .circle.active {background:#0078d4;}
    .circle.completed {background:#00b894;}
    .circle.previous {background:#f39c12;}
    
    .label {margin-top:10px;font-size:14px;font-weight:600;color:#555;}
    .edd {text-align:center;margin-top:20px;font-size:15px;font-weight:600;color:#333;}
    .status-info {text-align:center;margin-top:20px;font-size:15px;font-weight:600;color:#444;}
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
    <h2>Order Delivery Progress</h2>
    <div class="progress-tracker">
      <div class="progress-fill" id="progressFill"></div>
      <div class="step"><div class="circle" id="step1"><img src="https://cdn-icons-png.flaticon.com/512/992/992700.png" alt="Processing"/></div><div class="label">Processing</div></div>
      <div class="step"><div class="circle" id="step2"><img src="https://cdn-icons-png.flaticon.com/512/9641/9641159.png" alt="Shipped"/></div><div class="label">Shipped</div></div>
      <div class="step"><div class="circle" id="step3"><img src="https://cdn-icons-png.flaticon.com/512/1067/1067566.png" alt="Out for Delivery"/></div><div class="label">Out for Delivery</div></div>
      <div class="step"><div class="circle" id="step4"><img src="https://cdn-icons-png.flaticon.com/512/5290/5290058.png" alt="Delivered"/></div><div class="label">Delivered</div></div>
    </div>

    <div class="edd" id="eddLabel"></div>

    <div class="status-info">
      Current Status: <b><?php echo $order_status; ?></b><br/>
      Previous Status: <b><?php echo $previous_status ? $previous_status : 'N/A'; ?></b>
    </div>
  </div>

<script>
const orderRequestStatus = "<?php echo $order_request_status; ?>";
const expectedDateRaw = "<?php echo $expected_date; ?>";
const currentStatus = "<?php echo $order_status; ?>";
const previousStatus = "<?php echo $previous_status; ?>";

const steps = ["Processing", "Shipped", "Out for Delivery", "Delivered"];
let currentStep = steps.indexOf(currentStatus) + 1;

for (let i = 1; i <= 4; i++) {
    const circle = document.getElementById("step" + i);
    if (i < currentStep) {
        circle.classList.add("completed");
    } else if (i === currentStep) {
        circle.classList.add("active");
    }
    if (steps[i-1] === previousStatus && steps[i-1] !== currentStatus) {
        circle.classList.add("previous");
    }
}

// ✅ Progress bar fill width
const fill = document.getElementById("progressFill");
fill.style.width = ((currentStep - 1) / (steps.length - 1)) * 100 + "%";

// ✅ Expected Delivery Date
const eddLabel = document.getElementById("eddLabel");
if (orderRequestStatus === "processed" && expectedDateRaw && expectedDateRaw !== "0000-00-00") {
    eddLabel.textContent = "Expected Delivery Date: " + new Date(expectedDateRaw).toDateString();
} else if (orderRequestStatus === "pending") {
    eddLabel.textContent = "Order Request Status: Pending (Delivery not started)";
} else {
    eddLabel.textContent = "Expected Delivery Date: Pending";
}
</script>
<?php include 'chatbot.php'; ?>
</body>
</html>
