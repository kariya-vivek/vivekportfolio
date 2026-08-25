<?php
session_start();




include 'low_stock_alert.php';

$con = mysqli_connect("localhost", "root", "", "craftzon");

$uid = isset($_REQUEST['uid']) ? intval($_REQUEST['uid']) : 0;
$pid = isset($_REQUEST['pid']) ? intval($_REQUEST['pid']) : 0;
$pnm = isset($_REQUEST['pnm']) ? $_REQUEST['pnm'] : '';
$price = isset($_REQUEST['prc']) ? $_REQUEST['prc'] : 0;
$qty = isset($_REQUEST['qty']) ? intval($_REQUEST['qty']) : 1;
$cart_id = isset($_REQUEST['cart_id']) ? intval($_REQUEST['cart_id']) : 0;

$sel = "SELECT uname, email FROM craftus_reg WHERE u_id = $uid";
$selectu = mysqli_query($con, $sel);
$rowu = mysqli_fetch_assoc($selectu);
$fnm = $rowu['uname'];
$eid = $rowu['email'];

$orderMessage = null; // Initialize the message variable

if (isset($_POST['orderbtn'])) {
    $fnmo = $_POST['name'];
    $eido = $_POST['email'];
    $pnmo = $_POST['pnmo'];
    $qty = $_POST['quantity'];
    $add = $_POST['address'];
    $pymeth = $_POST['payment_method'];

    // Simulate payment success for UPI and Card (90% success rate)
    $paymentSuccess = true;
    if ($pymeth === "upi" || $pymeth === "card") {
        $rand = rand(1, 100);
        if ($rand <= 10) {
            $paymentSuccess = false;
        }
    }

    // Check stock before proceeding
    $checkStock = "SELECT stock_quantity FROM product_table WHERE product_id = $pid";
    $stockRes = mysqli_query($con, $checkStock);
    $stockRow = mysqli_fetch_assoc($stockRes);

    if ($stockRow['stock_quantity'] < $qty) {
        $orderMessage = [
            'title' => 'Stock Issue',
            'body' => 'Sorry, only ' . $stockRow['stock_quantity'] . ' items are left in stock.',
            'redirect' => null
        ];
    } elseif (!$paymentSuccess) {
        $orderMessage = [
            'title' => 'Payment Failed',
            'body' => 'Unfortunately, your payment did not go through. Please try again.',
            'redirect' => null
        ];
    } else {
        // This is the successful payment block
       $sellerRes = mysqli_query($con, "
        SELECT s.sellerid 
        FROM product_table p
        JOIN seller s ON p.crafted_by = s.sellernm
        WHERE p.product_id = $pid
    ");
    $sellerRow = mysqli_fetch_assoc($sellerRes);
    $seller_id = $sellerRow['sellerid']; // seller id for this product

    // This is the successful payment block
    $ins = "INSERT INTO craftorder 
            (uid, productid, seller_id, fullname, email, productnm, quantity, price, address, paymentmethod)
            VALUES 
            ('$uid', '$pid', '$seller_id', '$fnmo', '$eido', '$pnmo', '$qty', '$price', '$add', '$pymeth')";
    $res = mysqli_query($con, $ins);

        if ($res) {
           
			// --- Insert into payments table ---
$inserted_order_id = mysqli_insert_id($con); // get the last inserted order id

if ($pymeth === "cod") {
    $transaction_id = 'COD-' . time();
    $payment_status = 'Pending';
} else {
    $transaction_id = 'TXN-' . time() . rand(100,999);
    $payment_status = 'Completed';
}

$ins_payment = "INSERT INTO payments
    (order_id, user_id, payment_method, payment_status, amount, transaction_id)
    VALUES
    ('$inserted_order_id', '$uid', '$pymeth', '$payment_status', '".($price*$qty)."', '$transaction_id')";

mysqli_query($con, $ins_payment);


		   // Update stock only after successful order insert
            $newStock = $stockRow['stock_quantity'] - $qty;
            $newStatus = ($newStock <= 0) ? 'out of stock' : 'in stock';
            $updateStock = "UPDATE product_table SET stock_quantity = $newStock, stock_status = '$newStatus' WHERE product_id = $pid";
            mysqli_query($con, $updateStock);
		
// === Send low stock alert to seller if stock < 5 ===
if ($newStock <= 5) {
    echo "<script>
        fetch('low_stock_alert.php?seller_id={$seller_id}&product_name=" . urlencode($pnmo) . "&new_stock={$newStock}')
        .then(res => res.text())
        .then(data => console.log('Low stock mail response:', data))
        .catch(err => console.error('Error:', err));
    </script>";
}



            // Send email only on successful payment
            $to = $eido;
            $subject = "Order Confirmation from Craftzon";
            $message = "Hello, {$fnmo}! Your order for {$qty} of {$pnmo} has been successfully placed. Thank you for shopping with us!";
            $headers = "From: webmaster@craftzon.com\r\n";
            $headers .= "Content-type: text/html\r\n";
            
            mail($to, $subject, $message, $headers);
			// Remove ordered item from cart if cart_id exists
			if($cart_id > 0){
			mysqli_query($con, "DELETE FROM user_cart WHERE cart_id = $cart_id AND user_id = $uid");
		}

           if ($pymeth === "cod") {
    $orderMessage = [
        'title' => 'Order Placed',
        'body' => 'Your order has been placed. Payment will be collected on delivery.',
        'redirect' => 'myorders.php',
        'postData' => ['uid' => $uid]
    ];
} else {
    $orderMessage = [
        'title' => 'Payment Successful',
        'body' => 'Your order has been placed successfully!',
        'redirect' => 'myorders.php',
        'postData' => ['uid' => $uid]
    ];
}

        } else {
            $orderMessage = [
                'title' => 'Order Error',
                'body' => 'Error placing order: ' . mysqli_error($con),
                'redirect' => null
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
  <title>Place Order - Craftzon</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f6f8;
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

    input, select, textarea {
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
      background-color: #28a745;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }

    button:hover {
      background-color: #218838;
    }

    #response {
      margin-top: 15px;
      font-size: 14px;
      color: green;
    }
	/* Overlay */
.modal {
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.6); /* Darker overlay */
  display: none;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

/* Modal box */
.modal-content {
  background: linear-gradient(135deg, #fff7e6, #ffe6f0); /* soft gradient */
  padding: 25px;
  border-radius: 12px;
  width: 90%;
  max-width: 420px;
  text-align: center;
  box-shadow: 0 10px 25px rgba(0,0,0,0.35);
  position: relative;
  animation: popIn 0.3s ease forwards;
  border-top: 6px solid #ff6f61; /* top color accent */
  font-family: 'Arial', sans-serif;
}

/* Modal title */
.modal-content h2 {
  margin-bottom: 15px;
  font-size: 22px;
  color: #ff4d6d; /* bright color for title */
}

/* Modal message */
.modal-content p {
  font-size: 16px;
  color: #333;
  line-height: 1.5;
}

/* Close button */
.close-btn {
  position: absolute;
  top: 10px; right: 15px;
  font-size: 22px;
  cursor: pointer;
  color: #ff4d6d; /* same as title accent */
  font-weight: bold;
  transition: transform 0.2s;
}
.close-btn:hover {
  transform: rotate(90deg);
}

/* Pop-in animation */
@keyframes popIn {
  from { transform: scale(0.8); opacity: 0; }
  to { transform: scale(1); opacity: 1; }
}


  </style>
  <script>
	function checkStock() 
	{
		let qty = document.getElementById("quantity").value;
		let pid = "<?php


 echo $pid; ?>";

		if (qty > 0) 
		{
			let xhr = new XMLHttpRequest();
			xhr.open("GET", "check_stock.php?pid=" + pid + "&qty=" + qty, true);
			xhr.onreadystatechange = function() {
				if (xhr.readyState == 4 && xhr.status == 200) 
				{
					let response = xhr.responseText;
					let responseBox = document.getElementById("response");

					if (response.includes("Only")) 
					{
						responseBox.style.color = "red";
					} else if (response.includes("Stock available")) {
						responseBox.style.color = "green";
					} 
					else 
					{
						responseBox.style.color = "orange";
					}

					responseBox.innerHTML = response;

					if (response.includes("Only"))
					{
						let available = response.match(/\d+/); 
						if (available) 
						{
							document.getElementById("quantity").max = available[0];
						}
					}
				}
			};
			xhr.send();
		}
	}
</script>

<script>
window.onload = function () {
  const paymentSelect = document.getElementById("payment");
  const upiBox = document.getElementById("upiBox");
  const cardBox = document.getElementById("cardBox");

  // Show/hide payment fields based on selection
  paymentSelect.addEventListener("change", function () {
    const method = paymentSelect.value;
    upiBox.style.display = method === "upi" ? "block" : "none";
    cardBox.style.display = method === "card" ? "block" : "none";
  });

  // Validate payment details on form submit
  document.getElementById("orderForm").addEventListener("submit", function (e) {
    const method = paymentSelect.value;

    if (method === "upi") {
      const upiId = document.getElementById("upiId").value.trim();
      if (upiId === "") {
        alert("UPI ID is required.");
        e.preventDefault();
        return;
      }
      const upiPattern = /^[a-zA-Z0-9.\-_]{2,}@[\w]{2,}$/;
      if (!upiPattern.test(upiId)) {
        alert("Please enter a valid UPI ID (e.g. name@bank).");
        e.preventDefault();
        return;
      }
    }

    if (method === "card") {
      const cardNum = document.getElementById("cardNumber").value.trim();
      const cvv = document.getElementById("cvv").value.trim();
      const expiry = document.getElementById("expiry").value.trim();

      if (cardNum === "" || cvv === "" || expiry === "") {
        alert("All card fields are required.");
        e.preventDefault();
        return;
      }

      const cardPattern = /^\d{16}$/;
      const cvvPattern = /^\d{3}$/;
      const expiryPattern = /^(0[1-9]|1[0-2])\/\d{2}$/;

      if (!cardPattern.test(cardNum)) {
        alert("Card number must be exactly 16 digits.");
        e.preventDefault();
        return;
      }
      if (!cvvPattern.test(cvv)) {
        alert("CVV must be exactly 3 digits.");
        e.preventDefault();
        return;
      }
      if (!expiryPattern.test(expiry)) {
        alert("Expiry date must be in MM/YY format.");
        e.preventDefault();
        return;
      }

      const [month, year] = expiry.split('/');
      const now = new Date();
      const currentYear = now.getFullYear() % 100;
      const currentMonth = now.getMonth() + 1;

      if (parseInt(year) < currentYear || (parseInt(year) === currentYear && parseInt(month) < currentMonth)) {
        alert("Card has expired. Please use a valid card.");
        e.preventDefault();
        return;
      }
    }
  });
};

function calculateTotal() {
  let qty = parseInt(document.getElementById("quantity").value);
  let price = parseFloat(document.getElementById("price").value);
  let totalField = document.getElementById("total");

  if (!isNaN(qty) && !isNaN(price)) {
    totalField.value = qty * price;
  } else {
    totalField.value = "";
  }
}
</script>

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
    <h2>Place Your Order</h2>
<input type="hidden" name="uid" value="<?php


 echo $uid; ?>">
    <input type="hidden" name="pid" value="<?php


 echo $pid; ?>">
    <input type="hidden" name="pnm" value="<?php


 echo htmlspecialchars($pnm); ?>">
    <input type="hidden" name="prc" value="<?php


 echo $price; ?>">
    <label for="name">Full Name:</label>
    <input type="text" id="name" name="name" value="<?php


 echo $fnm; ?>" required />

    <label for="email">Email Address:</label>
    <input type="email" id="email" name="email" value="<?php


 echo $eid; ?>" required />
	
	<label for="pnmo">product name</label>
	<input type="text" name="pnmo" value="<?php


 echo $pnm; ?>" readonly>
	
	<label for="quantity">Quantity:</label>
	<input type="number" id="quantity" name="quantity" value="<?php


 echo $qty; ?>"min="1" required onkeyup="checkStock(); calculateTotal();" onchange="checkStock(); calculateTotal();" />

	
	
	<label for="price">price:</label>
    <input type="number" id="price" name="price" value="<?php


 echo $price; ?>" readonly required />
	
    <label for="total">Total:</label>
	<input type="number" id="total" name="total" readonly />
	
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
		  <input type="text" id="cardNumber" name="cardNumber" placeholder="1234567890123456" maxlength="16" inputmode="numeric" />
		  <label for="cvv">CVV:</label>
		  <input type="password" id="cvv" name="cvv" placeholder="•••" maxlength="3" />
		  <label for="expiry">Expiry Date:</label>
		  <input type="text" id="expiry" name="expiry" placeholder="MM/YY" />
		</div>



    <button type="submit" name="orderbtn">Submit Order</button>
    <p id="response"></p>
  </form>
  <div id="customModal" class="modal" style="display:none;">
  <div class="modal-content">
    <span class="close-btn" onclick="closeModal()">&times;</span>
    <h2 id="modalTitle"></h2>
    <p id="modalMessage"></p>
  </div>
</div>

<script>
    // This is the single, combined function to run on page load.
    window.onload = function() {
        // --- Logic for showing/hiding payment fields (from your original code) ---
        const paymentSelect = document.getElementById("payment");
        const upiBox = document.getElementById("upiBox");
        const cardBox = document.getElementById("cardBox");

        // Show/hide payment fields based on selection
        paymentSelect.addEventListener("change", function () {
            const method = paymentSelect.value;
            upiBox.style.display = method === "upi" ? "block" : "none";
            cardBox.style.display = method === "card" ? "block" : "none";
        });

        // Validate payment details on form submit (from your original code)
        document.getElementById("orderForm").addEventListener("submit", function (e) {
            const method = paymentSelect.value;

            if (method === "upi") {
                const upiId = document.getElementById("upiId").value.trim();
                if (upiId === "") {
                    alert("UPI ID is required.");
                    e.preventDefault();
                    return;
                }
                const upiPattern = /^[a-zA-Z0-9.\-_]{2,}@[\w]{2,}$/;
                if (!upiPattern.test(upiId)) {
                    alert("Please enter a valid UPI ID (e.g. name@bank).");
                    e.preventDefault();
                    return;
                }
            }

            if (method === "card") {
                const cardNum = document.getElementById("cardNumber").value.trim();
                const cvv = document.getElementById("cvv").value.trim();
                const expiry = document.getElementById("expiry").value.trim();

                if (cardNum === "" || cvv === "" || expiry === "") {
                    alert("All card fields are required.");
                    e.preventDefault();
                    return;
                }

                const cardPattern = /^\d{16}$/;
                const cvvPattern = /^\d{3}$/;
                const expiryPattern = /^(0[1-9]|1[0-2])\/\d{2}$/;

                if (!cardPattern.test(cardNum)) {
                    alert("Card number must be exactly 16 digits.");
                    e.preventDefault();
                    return;
                }
                if (!cvvPattern.test(cvv)) {
                    alert("CVV must be exactly 3 digits.");
                    e.preventDefault();
                    return;
                }
                if (!expiryPattern.test(expiry)) {
                    alert("Expiry date must be in MM/YY format.");
                    e.preventDefault();
                    return;
                }

                const [month, year] = expiry.split('/');
                const now = new Date();
                const currentYear = now.getFullYear() % 100;
                const currentMonth = now.getMonth() + 1;

                if (parseInt(year) < currentYear || (parseInt(year) === currentYear && parseInt(month) < currentMonth)) {
                    alert("Card has expired. Please use a valid card.");
                    e.preventDefault();
                    return;
                }
            }
        });

        // --- Logic for displaying modal (from your corrected code) ---
        function showModal(title, message) {
            document.getElementById("modalTitle").innerText = title;
            document.getElementById("modalMessage").innerText = message;
            document.getElementById("customModal").style.display = "flex";
        }

        function closeModal() {
            document.getElementById("customModal").style.display = "none";
        }
			
        // Check if a message was set by the PHP script
        <?php


 if ($orderMessage): ?>
    var orderMessage = <?php


 echo json_encode($orderMessage); ?>;
    showModal(orderMessage.title, orderMessage.body);

    if (orderMessage.redirect) {
        setTimeout(function () {
            if (orderMessage.postData) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = orderMessage.redirect;

                for (const key in orderMessage.postData) {
                    if (orderMessage.postData.hasOwnProperty(key)) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = key;
                        input.value = orderMessage.postData[key];
                        form.appendChild(input);
                    }
                }

                document.body.appendChild(form);
                form.submit();
            } else {
                window.location.href = orderMessage.redirect;
            }
        }, 2000);
    }
<?php


 endif; ?>

    };

    // The functions below need to be outside the onload block to be globally accessible
    function checkStock() {
        let qty = document.getElementById("quantity").value;
        let pid = "<?php


 echo $pid; ?>";

        if (qty > 0) {
            let xhr = new XMLHttpRequest();
            xhr.open("GET", "check_stock.php?pid=" + pid + "&qty=" + qty, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    let response = xhr.responseText;
                    let responseBox = document.getElementById("response");

                    if (response.includes("Only")) {
                        responseBox.style.color = "red";
                    } else if (response.includes("Stock available")) {
                        responseBox.style.color = "green";
                    } else {
                        responseBox.style.color = "orange";
                    }

                    responseBox.innerHTML = response;

                    if (response.includes("Only")) {
                        let available = response.match(/\d+/);
                        if (available) {
                            document.getElementById("quantity").max = available[0];
                        }
                    }
                }
            };
            xhr.send();
        }
    }

    function calculateTotal() {
        let qty = parseInt(document.getElementById("quantity").value);
        let price = parseFloat(document.getElementById("price").value);
        let totalField = document.getElementById("total");

        if (!isNaN(qty) && !isNaN(price)) {
            totalField.value = qty * price;
        } else {
            totalField.value = "";
        }
    }
	window.addEventListener('DOMContentLoaded', function() {
    calculateTotal(); // calculate total based on qty from URL
});
</script>
<script>
    // ADD THIS BLOCK AT THE END OF YOUR HTML, RIGHT BEFORE </body>
    // This is a separate script tag for clarity, but you can also
    // add it to your main script block outside of window.onload.

    // Modal functions must be global to work correctly.
    function showModal(title, message) {
        document.getElementById("modalTitle").innerText = title;
        document.getElementById("modalMessage").innerText = message;
        document.getElementById("customModal").style.display = "flex";
    }

    function closeModal() {
        document.getElementById("customModal").style.display = "none";
    }

    // This block triggers the modal on page load if a message exists.
    window.addEventListener('DOMContentLoaded', function() {
        <?php


 if ($orderMessage): ?>
            var orderMessage = <?php


 echo json_encode($orderMessage); ?>;
            showModal(orderMessage.title, orderMessage.body);
            
            setTimeout(function() {
                closeModal();
                if (orderMessage.redirect) {
                    window.location.href = orderMessage.redirect;
                }
            }, 2000); // The modal closes after 2 seconds for all messages.
        <?php


 endif; ?>
    });
</script>
</body>
</html>
