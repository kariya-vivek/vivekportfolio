<?php
include 'low_stock_alert.php';

$con = mysqli_connect("localhost", "root", "", "craftzon");

$uid = isset($_REQUEST['uid']) ? intval($_REQUEST['uid']) : 0;

if ($uid == 0) {
    die("User ID is missing.");
}

$sel = "SELECT uname, email FROM craftus_reg WHERE u_id = $uid";
$selectu = mysqli_query($con, $sel);
$rowu = mysqli_fetch_assoc($selectu);
$fnm = $rowu['uname'];
$eid = $rowu['email'];

$orderMessage = null; 

// Fetch cart items and calculate total
$cartRes = mysqli_query($con, "SELECT c.quantity, p.price, p.product_name, p.product_id, p.stock_quantity FROM user_cart c JOIN product_table p ON c.product_id = p.product_id WHERE c.user_id = $uid");
$totalCartValue = 0;
$cartItems = [];
$stockError = "";

while ($row = mysqli_fetch_assoc($cartRes)) {
    if ($row['quantity'] > $row['stock_quantity']) {
        $stockError = "Sorry, " . $row['product_name'] . " does not have enough stock (Only " . $row['stock_quantity'] . " left).";
    }
    $totalCartValue += ($row['quantity'] * $row['price']);
    $cartItems[] = $row;
}

if (isset($_POST['orderbtn'])) {
    $fnmo = $_POST['name'];
    $eido = $_POST['email'];
    $add = $_POST['address'];
    $pymeth = $_POST['payment_method'];

    if ($stockError != "") {
        $orderMessage = ['title' => 'Stock Issue', 'body' => $stockError, 'redirect' => null];
    } else {
        $paymentSuccess = true;
        if ($pymeth === "upi" || $pymeth === "card") {
            if (rand(1, 100) <= 10) { // 10% failure rate
                $paymentSuccess = false;
            }
        }

        if (!$paymentSuccess) {
            $orderMessage = ['title' => 'Payment Failed', 'body' => 'Unfortunately, your payment did not go through. Please try again.', 'redirect' => null];
        } else if (count($cartItems) > 0) {
            $main_order_id = 0;

            foreach ($cartItems as $item) {
                $pid = $item['product_id'];
                $qty = $item['quantity'];
                $price = $item['price'];
                $pnmo = $item['product_name'];
                
                $sellerRes = mysqli_query($con, "SELECT s.sellerid FROM product_table p JOIN seller s ON p.crafted_by = s.sellernm WHERE p.product_id = $pid");
                $sellerRow = mysqli_fetch_assoc($sellerRes);
                $seller_id = $sellerRow['sellerid'];

                $ins = "INSERT INTO craftorder (uid, productid, seller_id, fullname, email, productnm, quantity, price, address, paymentmethod) VALUES ('$uid', '$pid', '$seller_id', '$fnmo', '$eido', '$pnmo', '$qty', '$price', '$add', '$pymeth')";
                if (mysqli_query($con, $ins)) {
                    if ($main_order_id == 0) {
                        $main_order_id = mysqli_insert_id($con);
                    }
                    $newStock = $item['stock_quantity'] - $qty;
                    $status = ($newStock <= 0) ? 'out of stock' : 'in stock';
                    mysqli_query($con, "UPDATE product_table SET stock_quantity = $newStock, stock_status = '$status' WHERE product_id = $pid");
                    
                    if ($newStock <= 5) {
                        echo "<script>fetch('low_stock_alert.php?seller_id={$seller_id}&product_name=" . urlencode($pnmo) . "&new_stock={$newStock}');</script>";
                    }
                }
            }

            if ($pymeth === "cod") {
                $transaction_id = 'COD-' . time();
                $payment_status = 'Pending';
            } else {
                $transaction_id = 'TXN-' . time() . rand(100,999);
                $payment_status = 'Completed';
            }

            mysqli_query($con, "INSERT INTO payments (order_id, user_id, payment_method, payment_status, amount, transaction_id) VALUES ('$main_order_id', '$uid', '$pymeth', '$payment_status', '$totalCartValue', '$transaction_id')");
            mysqli_query($con, "DELETE FROM user_cart WHERE user_id = $uid");

            $orderMessage = [
                'title' => ($pymeth === "cod") ? 'Order Placed' : 'Payment Successful',
                'body' => 'Your order for all items has been placed successfully!',
                'redirect' => 'myorders.php',
                'postData' => ['uid' => $uid]
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Place Order (All Cart Items) - Craftzon</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f4f6f8; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
    form { background: #fff; padding: 25px 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); width: 100%; max-width: 500px; max-height: 90vh; overflow-y: auto;}
    h2 { margin-bottom: 20px; color: #333; }
    label { display: block; margin-top: 15px; font-weight: bold; }
    input, select, textarea { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px; box-sizing: border-box; }
    button { margin-top: 20px; padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; width: 100%; }
    button:hover { background-color: #218838; }
    
    .cart-summary { background: #f9f9f9; padding: 10px; border-radius: 5px; margin-top: 10px; font-size: 14px;}
    .cart-summary ul { list-style: none; padding: 0; margin: 0; }
    .cart-summary li { display: flex; justify-content: space-between; margin-bottom: 5px; padding-bottom: 5px; border-bottom: 1px solid #eee; }
    
    .modal { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); display: none; justify-content: center; align-items: center; z-index: 1000; }
    .modal-content { background: linear-gradient(135deg, #fff7e6, #ffe6f0); padding: 25px; border-radius: 12px; width: 90%; max-width: 420px; text-align: center; position: relative; border-top: 6px solid #ff6f61; }
    .modal-content h2 { margin-bottom: 15px; color: #ff4d6d; }
    .close-btn { position: absolute; top: 10px; right: 15px; font-size: 22px; cursor: pointer; color: #ff4d6d; }
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
  <form id="orderForm" action="" method="post">
    <h2>Checkout (<?php echo count($cartItems); ?> Items)</h2>
    
    <div class="cart-summary">
        <ul>
            <?php foreach($cartItems as $item): ?>
            <li>
                <span><?php echo $item['product_name']; ?> (x<?php echo $item['quantity']; ?>)</span>
                <span>₹<?php echo $item['price'] * $item['quantity']; ?></span>
            </li>
            <?php endforeach; ?>
        </ul>
        <div style="text-align: right; font-weight: bold; margin-top: 10px;">Total: ₹<?php echo $totalCartValue; ?></div>
    </div>

    <input type="hidden" name="uid" value="<?php echo $uid; ?>">
    
    <label for="name">Full Name:</label>
    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($fnm); ?>" required />

    <label for="email">Email Address:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($eid); ?>" required />
	
	<label for="address">Shipping Address:</label>
    <textarea id="address" name="address" rows="3" required></textarea>
	
    <label for="payment">Payment Method:</label>
	<select id="payment" name="payment_method" required>
		<option value="">-- Select Payment Method --</option>
		<option value="cod">Cash on Delivery</option>
		<option value="upi">UPI</option>
		<option value="card">Credit/Debit Card</option>
	</select>
	
	<div id="upiBox" style="display:none; margin-top:15px;">
        <label for="upiId">UPI ID:</label>
        <input type="text" id="upiId" name="upiId" placeholder="example@bank" />
	</div>

    <div id="cardBox" style="display:none; margin-top:15px;">
        <input type="text" id="cardNumber" name="cardNumber" placeholder="1234567890123456" maxlength="16" />
        <label for="cvv">CVV:</label>
        <input type="password" id="cvv" name="cvv" placeholder="•••" maxlength="3" />
        <label for="expiry">Expiry Date:</label>
        <input type="text" id="expiry" name="expiry" placeholder="MM/YY" />
    </div>

    <button type="submit" name="orderbtn">Pay ₹<?php echo $totalCartValue; ?></button>
  </form>

  <div id="customModal" class="modal" style="display:none;">
      <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h2 id="modalTitle"></h2>
        <p id="modalMessage"></p>
      </div>
  </div>

<script>
    window.onload = function () {
      const paymentSelect = document.getElementById("payment");
      const upiBox = document.getElementById("upiBox");
      const cardBox = document.getElementById("cardBox");

      paymentSelect.addEventListener("change", function () {
        const method = paymentSelect.value;
        upiBox.style.display = method === "upi" ? "block" : "none";
        cardBox.style.display = method === "card" ? "block" : "none";
      });

      document.getElementById("orderForm").addEventListener("submit", function (e) {
          const method = paymentSelect.value;
          if (method === "upi") {
              const upiId = document.getElementById("upiId").value.trim();
              if (!/^[a-zA-Z0-9.\-_]{2,}@[\w]{2,}$/.test(upiId)) {
                  alert("Please enter a valid UPI ID (e.g. name@bank).");
                  e.preventDefault();
              }
          } else if (method === "card") {
              const cardNum = document.getElementById("cardNumber").value.trim();
              const cvv = document.getElementById("cvv").value.trim();
              const expiry = document.getElementById("expiry").value.trim();
              
              if (!/^\d{16}$/.test(cardNum) || !/^\d{3}$/.test(cvv) || !/^(0[1-9]|1[0-2])\/\d{2}$/.test(expiry)) {
                  alert("Please enter valid card details.");
                  e.preventDefault();
              }
          }
      });
    };

    function showModal(title, message) {
        document.getElementById("modalTitle").innerText = title;
        document.getElementById("modalMessage").innerText = message;
        document.getElementById("customModal").style.display = "flex";
    }

    function closeModal() {
        document.getElementById("customModal").style.display = "none";
    }

    window.addEventListener('DOMContentLoaded', function() {
        <?php if ($orderMessage): ?>
            var orderMessage = <?php echo json_encode($orderMessage); ?>;
            showModal(orderMessage.title, orderMessage.body);
            
            setTimeout(function() {
                closeModal();
                if (orderMessage.redirect) {
                    if (orderMessage.postData) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = orderMessage.redirect;
                        for (const key in orderMessage.postData) {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = key;
                            input.value = orderMessage.postData[key];
                            form.appendChild(input);
                        }
                        document.body.appendChild(form);
                        form.submit();
                    } else {
                        window.location.href = orderMessage.redirect;
                    }
                }
            }, 2000);
        <?php endif; ?>
    });
</script>
</body>
</html>
