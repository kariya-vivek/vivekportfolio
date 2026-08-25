<?php

session_start();









$con = mysqli_connect("localhost", "root", "", "craftzon");

$uid = $_SESSION['users_id'] ?? 0;

if (empty($uid) || $uid == 0) { header('Location: logincraft.php'); exit; }



// Handle AJAX quantity update with stock adjustment

if (isset($_POST['update_qty'])) {

    $cart_id = intval($_POST['cart_id']);

    $new_quantity = intval($_POST['quantity']);



    $get_cart = mysqli_query($con, "SELECT product_id, quantity FROM user_cart WHERE cart_id = $cart_id AND user_id = $uid");

    $cart_row = mysqli_fetch_assoc($get_cart);

    $pid = $cart_row['product_id'];

    $old_quantity = intval($cart_row['quantity']);



    $get_stock = mysqli_query($con, "SELECT stock_quantity FROM product_table WHERE product_id = $pid");

    $stock_row = mysqli_fetch_assoc($get_stock);

    $current_stock = intval($stock_row['stock_quantity']);



    // Check if the user is trying to increase the quantity

    if ($new_quantity > $old_quantity) {

        // If they are increasing it, check if it exceeds the stock

        if ($new_quantity > $current_stock) {

            echo "out_of_stock";

            exit();

        }

    }



    if ($new_quantity == $old_quantity) {

        echo "success";

        exit();

    }



    // Proceed to update cart quantity

    $update_cart = mysqli_query($con, "UPDATE user_cart SET quantity = $new_quantity WHERE cart_id = $cart_id AND user_id = $uid");

    echo ($update_cart) ? "success" : "error";

    exit();

    $update_cart = mysqli_query($con, "UPDATE user_cart SET quantity = $new_quantity WHERE cart_id = $cart_id AND user_id = $uid");

    

    echo ($update_cart) ? "success" : "error";

    exit();

}



// Handle removal and restore stock

if (isset($_POST['remove'])) {

    $cart_id = intval($_POST['remove_pid']);

    $get_cart = mysqli_query($con, "SELECT product_id, quantity FROM user_cart WHERE cart_id = $cart_id AND user_id = $uid");

    $cart_row = mysqli_fetch_assoc($get_cart);

    $pid = $cart_row['product_id'];

    $qty = intval($cart_row['quantity']);



    mysqli_query($con, "DELETE FROM user_cart WHERE cart_id = $cart_id AND user_id = $uid");



  echo "

<form id='redirectForm' action='cart.php' method='post'>

    <input type='hidden' name='uid' value='{$uid}'>

</form>

<script>

    document.getElementById('redirectForm').submit();

</script>";





    exit();

}



$sel = "SELECT * FROM user_cart WHERE user_id = $uid";

$res = mysqli_query($con, $sel);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">

<link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css' rel='stylesheet'>

<title>Your CraftZon Cart</title>

<style>

body { margin:0; padding:0; font-family: 'Inter', sans-serif; background-color:#F8F8F8; color:#333; font-size:16px; display:flex; flex-direction:column; align-items:center; min-height:100vh; }

.cart-list { display:flex; flex-direction:column; align-items:center; gap:1.5em; padding:2em; width:100%; max-width:800px; }

.cart-card { display:flex; justify-content:space-between; align-items:center; background-color:#fff; padding:1em; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.08); width:100%; }

.cart-image { width:80px; height:80px; border-radius:8px; object-fit:cover; }

.cart-details { flex:1; margin-left:1em; }

.cart-details h5 { margin:0; color:#A0522D; font-size:1.2em; }

.cart-details p { margin:0.3em 0; color:#555; }

.cart-actions { display:flex; flex-direction:column; gap:0.5em; }

.cart-actions input[type="button"], .cart-actions input[type="submit"], .continue-shopping-button { background-color:#A0522D; color:#fff; border:none; padding:0.5em 1em; border-radius:25px; cursor:pointer; font-weight:bold; transition:background-color 0.3s ease; }

.cart-actions input[type="button"]:hover, .cart-actions input[type="submit"]:hover, .continue-shopping-button:hover { background-color:#8B4513; }

.continue-shopping-button { margin:2em auto; font-size:1.2em; padding:1em 2em; box-shadow:0 4px 8px rgba(0,0,0,0.1); display:inline-block; }

.cart-container { max-width:500px; width:90%; padding:2em; background-color:#fff; box-shadow:0 4px 12px rgba(0,0,0,0.08); border-radius:12px; display:flex; flex-direction:column; align-items:center; gap:1.5em; text-align:center; }

.empty-cart-icon { width:80px; height:80px; color:#D2B48C; }

h1 { color:#A0522D; font-size:1.8em; margin:0; }

p { font-size:1.1em; color:#555; margin:0; }

.deals-link { color:#4682B4; text-decoration:none; font-weight:bold; font-size:1.1em; padding:0.5em 1em; border-radius:25px; border:1px solid #4682B4; transition:background-color 0.3s ease, color 0.3s ease; }

.deals-link:hover { background-color:#4682B4; color:#fff; }

.quantity-selector { display:flex; align-items:center; gap:5px; margin-top:5px; }

.qty-btn { background-color:#A0522D; color:#fff; border:none; padding:5px 10px; font-size:16px; border-radius:5px; cursor:pointer; font-weight:bold; transition:background-color 0.3s ease; }

.qty-btn:hover { background-color:#8B4513; }

.quantity-selector input[type="text"] { width:40px; text-align:center; font-size:16px; font-weight:bold; border:1px solid #ccc; border-radius:5px; padding:3px 0; }

.cart-summary { margin-top:20px; background-color:#fff; padding:1em 2em; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.08); width:90%; max-width:800px; display:flex; justify-content:space-between; align-items:center; font-size:1.2em; font-weight:bold; }

.order-now-btn { background-color: #A0522D; color: #fff; border: none; padding:0.5em 1em; height:40px; border-radius:25px; cursor:pointer; font-weight:bold; transition: background-color 0.3s ease; }

.order-now-btn:hover { background-color:#8B4513; }

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

   background-color:#581845;

color:#fff;

    padding: 20px 0;

	width:100%;

    text-align: center;

}

.header h1 {

	color:white;

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

	width:100%;

}

.footer a {

    color: #ffcc00;

    text-decoration: none;

}

.footer a:hover {

    text-decoration: underline;

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

<div class="header">

    <h1>Your Shopping Cart</h1>

	<p style="margin-top: 10px; font-size: 1.2rem; color: #f8d7da;">

        Review your selected items before proceeding to checkout.

    </p>

</div>





<br><br>

<?php





 if (mysqli_num_rows($res) > 0): ?>

    <div class="cart-list">

    <?php





 

    while ($row = mysqli_fetch_array($res)): 

        $pid = $row['product_id'];

        $selpr = "SELECT * FROM product_table WHERE product_id = '$pid'";

        $respr = mysqli_query($con, $selpr);

        $rowp = mysqli_fetch_array($respr);

    ?>

        <div class="cart-card" data-cartid="<?= $row['cart_id'] ?>" data-price="<?= $rowp['price'] ?>" data-pid="<?= $pid ?>" data-stock="<?= $rowp['stock_quantity'] ?>">

            <img src="../<?= $rowp['image'] ?>" class="cart-image">

            <div class="cart-details">

                <h5><?= $rowp['product_name'] ?></h5>

                <p>Price: ₹<?= $rowp['price'] ?></p>

                <?php





 if ($rowp['stock_quantity'] <= 10): ?>

            <p style="color:#B22222; font-weight:bold;">Only <?= $rowp['stock_quantity'] ?> left in stock!</p>

            <?php





 endif; ?>

                <div class="quantity-selector">

                    <button type="button" class="qty-btn" onclick="changeQty(<?= $row['cart_id'] ?>, -1)">-</button>

                    <input type="text" id="qty-<?= $row['cart_id'] ?>" value="<?= $row['quantity'] ?>" readonly>

                    <button type="button" class="qty-btn" onclick="changeQty(<?= $row['cart_id'] ?>, 1)">+</button>

                </div>

                <p>Total: ₹<span id="total-<?= $row['cart_id'] ?>"><?= $rowp['price'] * $row['quantity'] ?></span></p>

            </div>

            <div class="cart-actions">

                <form id="buyForm_<?php





 echo $row['cart_id']; ?>" method="POST" action="orderform.php" style="display:none;">

    <input type="hidden" name="uid" value="<?php





 echo $uid; ?>">

    <input type="hidden" name="pid" value="<?php





 echo $pid; ?>">

    <input type="hidden" name="pnm" value="<?php





 echo htmlspecialchars($rowp['product_name']); ?>">

    <input type="hidden" name="prc" value="<?php





 echo $rowp['price']; ?>">

    <input type="hidden" name="cart_id" value="<?php





 echo $row['cart_id']; ?>">

    <input type="hidden" name="qty" id="qty_hidden_<?php





 echo $row['cart_id']; ?>">

</form>



<input type="button" value="Buy" 

       onclick="

           document.getElementById('qty_hidden_<?php





 echo $row['cart_id']; ?>').value =

               document.getElementById('qty-<?php





 echo $row['cart_id']; ?>').value;

           document.getElementById('buyForm_<?php





 echo $row['cart_id']; ?>').submit();

       ">



                <form method="post">

				<input type="hidden" name="uid" value="<?= $uid ?>">

                    <input type="hidden" name="remove_pid" value="<?= $row['cart_id'] ?>">

                    <input type="submit" name="remove" value="Remove">

                </form>

            </div>

        </div>

    <?php





 endwhile; ?>

    </div>



    <button class="continue-shopping-button" onclick="window.location.href='crafthome.php'">Continue Shopping</button>



    <div class="cart-summary">

        <span>Total: ₹<span id="cart-total">0</span></span>

        <button class="order-now-btn" onclick="window.location.href='orderform_all.php?uid=<?= $uid ?>'">Buy All</button>

    </div>



<?php





 else: ?>

    <div class="cart-container">

        <div class="empty-cart-icon">

            <i class="fa-solid fa-basket-shopping" style="font-size:80px"></i><br>

        </div>

        <h1>Your CraftZon Cart is Empty</h1>

        <p>Nothing in here. Only possibilities!</p>

        <a href="#" class="deals-link">Shop Today's Deals</a>

        <button class="continue-shopping-button" onclick="window.location.href='crafthome.php'">Continue Shopping</button>

    </div>

<?php





 endif; ?>



<script>

function changeQty(cartId, change){

    var qtyInput = document.getElementById('qty-' + cartId);

    var totalSpan = document.getElementById('total-' + cartId);

    var card = document.querySelector('.cart-card[data-cartid="'+cartId+'"]');

    var price = parseInt(card.getAttribute('data-price'));

    var stock = parseInt(card.getAttribute('data-stock'));

    var plusBtn = card.querySelector('.qty-btn:nth-of-type(3)');

    var minusBtn = card.querySelector('.qty-btn:nth-of-type(1)');



    var currentQty = parseInt(qtyInput.value);

    var newQty = currentQty + change;

	// Check if the quantity is being decreased below 1.

    if (newQty < 1) {

        return;

    }

   

   var xhr = new XMLHttpRequest();

xhr.open("POST", "cart.php", true);

xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function() {

        if (xhr.responseText.includes("success")) {

            qtyInput.value = newQty;

            totalSpan.textContent = price * newQty;

            updateCartTotal();



            // Disable "+" if quantity reaches stock

            if (newQty >= stock) {

                plusBtn.disabled = true;

                plusBtn.style.opacity = "0.5";

                plusBtn.style.cursor = "not-allowed";

            } else {

                plusBtn.disabled = false;

                plusBtn.style.opacity = "1";

                plusBtn.style.cursor = "pointer";

            }



            // Disable "-" if quantity is 1

            if (newQty === 1) {

                minusBtn.disabled = true;

                minusBtn.style.opacity = "0.5";

                minusBtn.style.cursor = "not-allowed";

            } else {

                minusBtn.disabled = true;

                minusBtn.style.opacity = "1";

                minusBtn.style.cursor = "pointer";

				

            }

			

        } else if (xhr.responseText.includes("out_of_stock")) {

            plusBtn.disabled = true;

            plusBtn.style.opacity = "0.5";

            plusBtn.style.cursor = "not-allowed";

            alert("Cannot increase quantity. Only " + stock + " left in stock.");

        }

    };

    xhr.send("update_qty=1&cart_id=" + cartId + "&quantity=" + newQty + "&uid=<?= $uid ?>");

}



function updateCartTotal(){

    var cards = document.querySelectorAll('.cart-card');

    var total = 0;

    cards.forEach(function(card){

        var cartId = card.getAttribute('data-cartid');

        var totalSpan = document.getElementById('total-' + cartId);

        total += parseInt(totalSpan.textContent);

    });

    document.getElementById('cart-total').textContent = total;

}

window.onload = function() {

    updateCartTotal();



    // Initial button state check

    document.querySelectorAll('.cart-card').forEach(function(card){

        var qty = parseInt(card.querySelector('input[type="text"]').value);

        var stock = parseInt(card.getAttribute('data-stock'));

        var plusBtn = card.querySelector('.qty-btn:nth-of-type(3)');

        var minusBtn = card.querySelector('.qty-btn:nth-of-type(1)');



        if (qty >= stock) {

            plusBtn.disabled = true;

            plusBtn.style.opacity = "0.5";

            plusBtn.style.cursor = "not-allowed";

        }

        if (qty === 1)

        {

            minusBtn.disabled = true;

            minusBtn.style.opacity = "0.5";

            minusBtn.style.cursor = "not-allowed";

        }



    });

};

</script>



<div class="container-fluid text-center mt-4">

			<div class="row mt-4" style="background-color:#f5deb3;">

				<div class="col-12">

					<a href="#top1" class="btn btn-link" style="text-decoration:none;color:#581845;">Back to Top</a>

				</div>

			</div>

		<div class="footer">

    <p>&copy; 2025 CraftZon. All Rights Reserved.</p>

    <p><a href="condition.html">Terms & Conditions</a> | <a href="privacypolicy.html">Privacy Policy</a></p>

</div>

	

	</div>





<?php





 include 'chatbot.php'; ?>

</body>

</html>