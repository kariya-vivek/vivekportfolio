<?php
	$con=mysqli_connect("localhost","root","","craftzon");
	$oid=$_POST['orderid'];
	$urid=$_POST['uid'];
	$pri=$_POST['prc'];
	if(isset($_POST['sucancelbtn']))
	{
		$res=$_POST['reason'];
		$prevStatusQuery = "SELECT order_status,quantity FROM craftorder WHERE orderid = $oid";
		$prevStatusResult = mysqli_query($con, $prevStatusQuery);
		$prevStatusRow = mysqli_fetch_assoc($prevStatusResult);
		$currentStatus = $prevStatusRow['order_status'];
		$qty=$prevStatusRow['quantity'];
		$amount=$pri * $qty;
		$texta=$amount* 0.12;
		$maina=$amount+$texta;
		if ($currentStatus === 'shipped') {
			$refund_amount = $maina * 0.95  ;
		} else {
			$refund_amount = $maina ;
		}

		$eid = $_POST['email'];
		$addcomm = $_POST['comments'];
		$in = "insert into cancel_orders (order_id,ucancelid,user_email,reason,comments,refund_amount) values ('$oid','$urid','$eid','$res','$addcomm','$refund_amount')";
		$ins=mysqli_query($con,$in);
		if($ins)
		{
				$up = "UPDATE craftorder SET previous_status = '$currentStatus', order_status = 'cancel' WHERE orderid = $oid";

				$upd=mysqli_query($con,$up);
				if($upd)
				{
					$getOrder = "SELECT productid, quantity FROM craftorder WHERE orderid = $oid";
					$orderRes = mysqli_query($con, $getOrder);
					$orderRow = mysqli_fetch_assoc($orderRes);
					$pid = $orderRow['productid'];
					$qty = $orderRow['quantity'];

					$getStock = "SELECT stock_quantity FROM product_table WHERE product_id = $pid";
					$stockRes = mysqli_query($con, $getStock);
					$stockRow = mysqli_fetch_assoc($stockRes);
					$newStock = $stockRow['stock_quantity'] + $qty;

					$newStatus = ($newStock > 0) ? 'in stock' : 'out of stock';
					$updateStock = "UPDATE product_table SET stock_quantity = $newStock, stock_status = '$newStatus' WHERE product_id = $pid";
					mysqli_query($con, $updateStock);

					$to = $eid;
					$subject = "Craftzon Order Cancellation Confirmation";
					$formatted_refund = number_format($refund_amount, 2); // 2 decimal places
$message = "Dear Customer,\n\nYour order (Order ID: $oid) has been successfully cancelled.\nRefund Amount: ₹$formatted_refund\n\nThank you for shopping with Craftzon.\n\nRegards,\nCraftzon Team";

					$headers = "From: support@craftzon.com";

					mail($to, $subject, $message, $headers);

unset($_POST['sucancelbtn']);

// Redirect with POST instead of GET
echo "
<!DOCTYPE html>
<html>
<head>
  <meta charset='UTF-8'>
  <title>Redirecting...</title>
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
<form id='redirectForm' action='myorders.php' method='post'>
    <input type='hidden' name='uid' value='$urid'>
</form>
<script>
    document.getElementById('redirectForm').submit();
</script>
</body>
</html>
";
exit; // ✅ ensures no further HTML or PHP is executed

				}
		}
		else
		{
			echo '<script>alert("no");</script>';
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cancel Order - Craftzon</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f0f2f5;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    form {
      background: #fff;
      padding: 25px 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 500px;
    }

    h2 {
      margin-bottom: 20px;
      color: #333;
    }

    label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
    }

    input, textarea, select {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 14px;
    }

    button {
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #dc3545;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }

    button:hover {
      background-color: #c82333;
    }

    #response {
      margin-top: 15px;
      font-size: 14px;
      color: green;
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
  <form id="cancelOrderForm" action="#" method="post" onsubmit="this.insertAdjacentHTML('beforeend', '<input type=\'hidden\' name=\'sucancelbtn\' value=\'1\'>'); var btn = this.querySelector('button[type=submit]'); btn.disabled=true; btn.innerText='Processing...'; return true;">
    <h2>Cancel Your Order</h2>
<input type="hidden" name="orderid" value="<?php echo $oid; ?>">
<input type="hidden" name="uid" value="<?php echo $urid; ?>">
<input type="hidden" name="prc" value="<?php echo $pri; ?>">

    <label for="orderId">Order ID:</label>
    <input type="text" id="orderId" name="orderId" value="<?php echo $oid; ?>" readonly required />

    <label for="email">Email Address:</label>
    <input type="email" id="email" name="email" required />

    <label for="reason">Reason for Cancellation:</label>
    <select id="reason" name="reason" required>
      <option value="">-- Select Reason --</option>
      <option value="delayed">Delivery is delayed</option>
      
      <option value="changed-mind">Changed my mind</option>
      <option value="duplicate">Duplicate order</option>
      <option value="other">Other</option>
    </select>

    <label for="comments">Additional Comments (optional):</label>
    <textarea id="comments" name="comments" rows="4"></textarea>

    <button type="submit" name="sucancelbtn">Submit Cancellation</button>
    <p id="response"></p>
  </form>

</body>
</html>
