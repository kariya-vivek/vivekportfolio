<?php


session_start();
$con = mysqli_connect("localhost", "root", "", "craftzon");
$pid = isset($_REQUEST['product_id']) ? intval($_REQUEST['product_id']) : 0;
$userid = isset($_SESSION['users_id']) ? $_SESSION['users_id'] : (isset($_REQUEST['userid']) ? intval($_REQUEST['userid']) : 0);

if (!$con) {
    die('Connection failed: ' . mysqli_connect_error());
}

// Fetch product
$query = "SELECT * FROM product_table WHERE product_id = $pid";
$result = mysqli_query($con, $query);
$product_data = mysqli_fetch_assoc($result);

// Redirect to auction page if category = auction
if ($product_data['category'] == 'auction') {
    $auctionQ = mysqli_query($con, "SELECT auction_id FROM auction_table WHERE product_id = $pid");
    $auctionRow = mysqli_fetch_assoc($auctionQ);
    if ($auctionRow) {
       $auction_id = $auctionRow['auction_id'];
        ?>
        <form id="redirectForm" action="auctionpage.php" method="POST">
            <input type="hidden" name="auction_id" value="<?php

 echo htmlspecialchars($auction_id); ?>">
            <input type="hidden" name="uid" value="<?php

 echo htmlspecialchars($userid); ?>">
        </form>
        <script>
            document.getElementById('redirectForm').submit();
        </script>
        <?php


        exit();
    }
}

// Seller info
$sellernm = $product_data['crafted_by'];
$query = "SELECT * FROM seller WHERE sellernm = '$sellernm'";
$result = mysqli_query($con, $query);
$rs = mysqli_fetch_assoc($result);
$sellerId = intval($rs['sellerid']);

// Check if user follows this seller
$isFollowing = false;
if ($userid > 0 && $sellerId > 0) {
    $checkFollow = mysqli_query($con, "SELECT * FROM follow WHERE sellerid=$sellerId AND userid=$userid");
    $isFollowing = mysqli_num_rows($checkFollow) > 0;
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add to Cart
    if (isset($_POST['ajax_cart'])) {
        $qty = 1;
        $check = mysqli_query($con, "SELECT * FROM user_cart WHERE user_id=$userid AND product_id=$pid");
        if (mysqli_num_rows($check) == 0) {
            mysqli_query($con, "INSERT INTO user_cart (user_id, product_id, quantity) VALUES ($userid, $pid, $qty)");
        }
        $res = mysqli_query($con, "SELECT COUNT(*) AS total FROM user_cart WHERE user_id=$userid");
        $row = mysqli_fetch_assoc($res);
        echo $row['total'] ?? 0;
        exit();
    }

    // Wishlist toggle
    if (isset($_POST['ajax_wishlist'])) {
        $checkWish = mysqli_query($con, "SELECT * FROM wishlist WHERE user_id=$userid AND product_id=$pid");
        if(mysqli_num_rows($checkWish) > 0){
            mysqli_query($con, "DELETE FROM wishlist WHERE user_id=$userid AND product_id=$pid");
            echo "removed";
        } else {
            mysqli_query($con, "INSERT INTO wishlist (user_id, product_id) VALUES ($userid, $pid)");
            echo "added";
        }
        exit();
    }

    // Follow/Unfollow toggle
    if (isset($_POST['ajax_follow'])) {
        if ($userid > 0 && $sellerId > 0) {
            $checkFollow = mysqli_query($con, "SELECT * FROM follow WHERE sellerid=$sellerId AND userid=$userid");
            if (mysqli_num_rows($checkFollow) > 0) {
                mysqli_query($con, "DELETE FROM follow WHERE sellerid=$sellerId AND userid=$userid");
                echo "unfollowed";
            } else {
                mysqli_query($con, "INSERT INTO follow (sellerid, userid) VALUES ($sellerId, $userid)");
                echo "followed";
            }
        }
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?php

 echo $product_data['product_name']; ?> | Craftzon</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
<style>
    body {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        background: #eef1f6;
        color: #333;
    }
    .product-wrapper {
        max-width: 1200px;
        margin: 60px auto;
        padding: 30px;
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 15px 50px rgba(0,0,0,0.08);
        display: flex; /* Changed from grid to flex for better control on smaller screens */
        flex-direction: column;
    }
    .main-content {
        display: flex;
        gap: 60px;
        margin-bottom: 40px;
    }
    @media (max-width: 992px) {
        .main-content {
            flex-direction: column;
            gap: 30px;
        }
    }
    .product-image-container {
        flex: 1.2; /* Distribute space */
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }
    .product-image {
        width: 100%;
		height:100%;
        object-fit: cover;
        aspect-ratio: 4/3;
        display: block;
    }
    .product-details {
        flex: 1; /* Distribute space */
        padding: 20px;
    }
    .product-details h1 {
        font-size: 40px;
        font-weight: 700;
        margin-bottom: 10px;
        color: #1a237e;
    }
    .price {
        font-size: 24px;
        color: #ff5722;
        font-weight: 800;
        margin-bottom: 15px;
    }
    .ratings {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }
    .stars {
        color: #ffc107;
        font-size: 20px;
        letter-spacing: 2px;
    }
    .stock-info {
        font-size: 16px;
        color: #6c757d;
        margin-bottom: 30px;
        padding-top: 20px;
    }
    .stock-info span {
        font-weight: 600;
        color: #333;
    }
    .btn-group {
        display: flex;
        gap: 20px;
        margin: 30px 0;
    }
    .btn {
        flex: 1;
        padding: 18px;
        border: none;
        font-size: 18px;
        font-weight: bold;
        border-radius: 50px;
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(0,0,0,0.15);
    }
    .btn-cart {
        background: #28a745;
        color: #fff;
    }
    .btn-buy {
        background: #ff5722;
        color: #fff;
    }
    .maker-box {
        margin-top: 40px;
        padding: 25px;
        border-radius: 16px;
        background: #f9fbfd;
        border: 1px solid #eceff1;
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .maker-box img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #fff;
    }
    .maker-info {
        flex-grow: 1;
    }
    .maker-info h3 {
        margin: 0;
        font-size: 20px;
        color: #1a237e;
    }
    .maker-info p {
        margin: 5px 0 0;
        font-size: 14px;
        color: #6c757d;
    }
    .follow-btn {
        padding: 10px 25px;
        border: none;
        border-radius: 30px;
        background: #007bff;
        color: #fff;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0,123,255,0.2);
    }
    .follow-btn:hover {
        background: #0056b3;
    }
    .follow-btn.following {
        background: #6c757d;
        box-shadow: 0 5px 15px rgba(108,117,125,0.2);
    }
    .follow-btn.following:hover {
        background: #5a6268;
    }
    #wishlist-icon {
        position: absolute;
        top: 20px;
        right: 20px;
        font-size: 28px;
        cursor: pointer;
        text-shadow: 0 0 5px #000;
        transition: transform 0.3s, filter 0.3s;
    }
    #wishlist-icon:hover {
        transform: scale(1.2);
    }
    .section-box {
        background: #f9fbfd;
        border-radius: 16px;
        padding: 25px;
        margin-top: 30px;
        border: 1px solid #eceff1;
    }
    .section-box h2 {
        font-size: 24px;
        color: #1a237e;
        margin-top: 0;
        border-bottom: 2px solid #e0e0e0;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }
    .section-box p {
        line-height: 1.6;
        color: #555;
    }
    .review-item {
        border-bottom: 1px solid #e0e0e0;
        padding-bottom: 15px;
        margin-bottom: 15px;
    }
    .review-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }
    .review-item strong {
        font-size: 16px;
        color: #333;
    }
    .review-item span {
        font-size: 14px;
        color: #888;
        margin-left: 10px;
    }
	.btn-home {
    display: inline-block;
    background-color: #f0f0f0;  /* softer, neutral */
    color: #555;                /* subtle text */
    padding: 8px 18px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.9rem;           /* slightly smaller */
    opacity: 0.7;               /* less visible */
    transition: all 0.3s ease;
}

.btn-home:hover {
    background-color: #e0e0e0;
    color: #333;
    opacity: 1;                 /* becomes clearer only on hover */
}

</style>
<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
<link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css' rel='stylesheet'>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
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

<div class="container-fluid" style="background-color:#581845; padding:10px 20px;">
    <div class="row align-items-center">
        <div class="col-4">
            <a href="crafthome.php"><img src="../craftzonlogo.jpeg" class="img-fluid rounded-circle" style="max-width: 80px; border: 2px solid white; background-color: #581845;"></a>
        </div>
        <div class="col-4 text-center">
            <h2 style="color:white; margin:0; font-weight:bold; letter-spacing:2px;">CraftZon</h2>
        </div>
        <div class="col-4 text-end">
            <a href="crafthome.php" style="color:white; text-decoration:none; margin-right:20px; font-size:18px;"><i class="fa-solid fa-house"></i> Home</a>
            <a href="#" onclick="checkLogin(event, 'cart.php')" style="color:white; text-decoration:none; font-size:18px;"><i class="fa-solid fa-cart-shopping"></i> Cart</a>
        </div>
    </div>
</div>


<div class="product-wrapper">
    <div class="main-content">
        <div class="product-image-container">
            <img src="../<?php

 echo $product_data['image']; ?>" alt="Product Image" class="product-image">
            <div id="wishlist-icon">
                <i class="fa-solid fa-heart" style="color:<?php


                    $checkWish = mysqli_query($con, "SELECT * FROM wishlist WHERE user_id=$userid AND product_id=$pid");
                    echo (mysqli_num_rows($checkWish) > 0) ? 'red' : 'gray';
                ?>;"></i>
            </div>
        </div>

        <div class="product-details">
            <h1><?php

 echo $product_data['product_name']; ?></h1>

            <?php


            // Ratings
            $avgQuery = "SELECT AVG(rating) AS avg_rating, COUNT(*) AS total_reviews
                         FROM feedbacks
                         WHERE order_id IN (SELECT orderid FROM craftorder WHERE productid=$pid)";
            $avgResult = mysqli_query($con, $avgQuery);
            $avgData = mysqli_fetch_assoc($avgResult);
            $avgRating = round($avgData['avg_rating'], 1);
            $totalReviews = $avgData['total_reviews'];
            ?>
            <div class="ratings">
                <p class="price">
                    ₹<?php

 echo $product_data['price']; ?>
                </p>
                <div class="stars">
                    <?php


                    $filledStars = floor($avgRating);
                    $hasHalfStar = ($avgRating - $filledStars) >= 0.25 && ($avgRating - $filledStars) <= 0.75;
                    $emptyStars = 5 - $filledStars - ($hasHalfStar ? 1 : 0);
                    for($i = 0; $i < $filledStars; $i++){ echo '<span>★</span>'; }
                    if($hasHalfStar) echo '<span>★</span>';
                    for($i = 0; $i < $emptyStars; $i++){ echo '<span style="color: #e0e0e0;">★</span>'; }
                    ?>
                </div>
                <span style="font-size: 16px; color: #555;">
                    (<?php

 echo $avgRating; ?>/5 based on <?php

 echo $totalReviews;?> reviews)
                </span>
            </div>

            <p class="stock-info">
                Stock: <span><?php

 echo $product_data['stock_quantity']; ?></span> | Sold: <span>
                <?php


                    $query = "SELECT SUM(quantity) AS total_sold FROM craftorder WHERE productid=$pid AND ordertime >= CURDATE() - INTERVAL 30 DAY";
                    $result = mysqli_query($con, $query);
                    $row = mysqli_fetch_assoc($result);
                    echo $row['total_sold'] ?? 0;
                ?>
                </span>
            </p>

            <div class="btn-group">
                <?php

 if($product_data['stock_quantity'] > 0): ?>
                  <form method="POST" action="orderform.php" id="buyForm" onsubmit="return checkLogin(event);">
    <input type="hidden" name="uid" value="<?php

 echo $userid; ?>">
    <input type="hidden" name="pid" value="<?php

 echo $pid; ?>">
    <input type="hidden" name="pnm" value="<?php

 echo htmlspecialchars($product_data['product_name']); ?>">
    <input type="hidden" name="prc" value="<?php

 echo $product_data['price']; ?>">

    <input type="submit" class="btn btn-buy" value="⚡ Buy Now">
</form>


                    <?php


                    $check = mysqli_query($con,"SELECT * FROM user_cart WHERE user_id=$userid AND product_id=$pid");
                    if(mysqli_num_rows($check) > 0){
                       echo "
<form action='cart.php' method='POST' style='display:inline;'>
    <input type='hidden' name='uid' value='$userid'>
    <button type='submit' class='btn btn-cart'>Go to Cart</button>
</form>
";

                    } else {
                        echo '<form method="post" class="add-to-cart-form" onsubmit="return checkLogin(event);" style="flex: 1;"><input type="hidden" name="product_id" value="'.$pid.'"><input type="submit" class="btn btn-cart" value="🛒 Add to Cart"></form>';
                    }
                    ?>
                <?php

 else: ?>
                    <button class="btn btn-cart" style="background:#999; cursor:not-allowed;" disabled>❌ Out of Stock</button>
                <?php

 endif; ?>
            </div>

            <div class="maker-box">
                <img src="../<?php

 echo $rs['shopimage'];?>" alt="Shop Logo">
                <div class="maker-info">
                    <h3><?php

 echo $product_data['crafted_by'];?></h3>
                    <p><?php

 echo $rs['selleremailid'];?></p>
                </div>
                <button id="follow-btn" class="follow-btn <?php

 echo $isFollowing ? 'following' : ''; ?>">
                    <?php

 echo $isFollowing ? 'Unfollow' : '+ Follow'; ?>
                </button>
            </div>

        </div>
    </div>

    <div class="section-container">
        <div class="section-box">
            <h2>📝 Product Description</h2>
            <p><?php

 echo nl2br($product_data['product_description']);?></p>
        </div>

        <div class="section-box">
            <h2>⭐ Customer Reviews</h2>
            <div class="review-scroll" id="reviewContainer">
                <?php


                $query = "SELECT * FROM feedbacks WHERE order_id IN (SELECT orderid FROM craftorder WHERE productid=$pid) ORDER BY created_at DESC";
                $result = mysqli_query($con, $query);
                $count = 0;
                if (mysqli_num_rows($result) > 0) {
                    while ($review = mysqli_fetch_assoc($result)) {
                        $isHidden = $count >= 5 ? 'hidden-review' : '';
                        echo "<div class='review-item $isHidden'>";
                        echo "<strong>{$review['user_name']}</strong> <span style='font-weight: bold;'>({$review['rating']}/5)</span>";
                        echo "<p>{$review['comment']}</p>";
                        echo "</div>";
                        $count++;
                    }
                } else {
                    echo "<p>No reviews yet.</p>";
                }
                ?>
            </div>
            <?php

 if ($count > 5): ?>
                <button class="see-more-btn" onclick="showMoreReviews()">See More</button>
            <?php

 endif; ?>
        </div>
    </div>
</div>
<div class="section text-center">
        <a href="crafthome.php?category=home" class="btn-home">Back to Home</a>
    </div>
<script>
$(document).ready(function(){

    var userid = <?php

 echo $userid; ?>;
    var pid = <?php

 echo $pid; ?>;

    // Wishlist toggle
    $("#wishlist-icon").click(function(){
        if(userid === 0) { checkLogin(); return; }
        $.post("", {ajax_wishlist:1, userid:userid, product_id:pid}, function(res){
            if(res.trim() == "added"){
                $("#wishlist-icon i").css("color", "red");
            } else if(res.trim() == "removed"){
                $("#wishlist-icon i").css("color", "gray");
            }
        });
    });

    // Follow/Unfollow toggle
    $("#follow-btn").click(function(){
        if(userid === 0) { checkLogin(); return; }
        var btn = $(this);
        $.post("", {ajax_follow:1, userid:userid}, function(res){
            if(res.trim() == "followed"){
                btn.text('Unfollow').addClass('following');
            } else if(res.trim() == "unfollowed"){
                btn.text('+ Follow').removeClass('following');
            }
        });
    });

    // Add to Cart AJAX
    $(".add-to-cart-form").on("submit", function(e){
        e.preventDefault();
        if(userid === 0) { checkLogin(); return; }
        let pid=$(this).find("input[name='product_id']").val();
        $.post("", {ajax_cart:1, userid:userid, product_id:pid}, function(newCount){
            $(".add-to-cart-form").replaceWith(`
    <form action="cart.php" method="POST" style="display:inline;">
        <input type="hidden" name="uid" value="`+userid+`">
        <button type="submit" class="btn btn-cart">Go to Cart</button>
    </form>
`);

        });
    });
    
    // Function to show all reviews (if applicable)
    function showMoreReviews() {
        $('.hidden-review').removeClass('hidden-review');
        $('.see-more-btn').hide();
    }
});
</script>


<script>
function checkLogin(e, redirectUrl = null) {
    var uid = <?php

 echo $userid; ?>;
    if (uid == 0) {
        if (typeof e !== 'undefined' && e) e.preventDefault();
        Swal.fire({
            title: 'Login Required',
            text: 'Please login to use this feature!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#581845',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Login Now'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'logincraft.php';
            }
        });
        return false;
    }
    if (redirectUrl) window.location.href = redirectUrl;
    return true;
}
</script>

</body>
</html>