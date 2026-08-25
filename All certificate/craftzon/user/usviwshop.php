<?php
// --- Database Connection ---
$host = "localhost";
$user = "root";
$pass = "";
$db   = "craftzon";

$con = mysqli_connect($host, $user, $pass, $db);
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// --- Get sellerid from URL ---
if (!isset($_GET['sellerid'])) {
    die("Seller ID missing in URL");
}
$sellerid = intval($_GET['sellerid']);

// --- Fetch seller info ---
$seller_query = mysqli_query($con, "SELECT * FROM seller WHERE sellerid=$sellerid AND status='active'");
if(mysqli_num_rows($seller_query) == 0) {
    die("Seller not found or inactive");
}
$seller = mysqli_fetch_assoc($seller_query);

// --- Count followers ---
$followers_query = mysqli_query($con, "SELECT COUNT(*) AS total_followers FROM follow WHERE sellerid=$sellerid");
$followers = mysqli_fetch_assoc($followers_query)['total_followers'];

// --- Fetch products ---
$products_query = mysqli_query($con, "SELECT * FROM product_table WHERE crafted_by='{$seller['sellernm']}' AND status='active'");
$total_products = mysqli_num_rows($products_query);

// --- Calculate average rating for shop from feedbacks ---
$rating_query = mysqli_query($con, "
    SELECT AVG(f.rating) AS avg_rating 
    FROM feedbacks f
    JOIN product_table p ON f.order_id = p.product_id
    WHERE p.crafted_by='{$seller['sellernm']}'
");
$rating_row = mysqli_fetch_assoc($rating_query);
$avg_rating = $rating_row['avg_rating'] ? round($rating_row['avg_rating'], 1) : 0; // 1 decimal
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $seller['storenm']; ?> - Shop Profile</title>
<style>
body { margin:0; font-family: Arial,sans-serif; background:#f9f9f9; }

/* Full-width default banner */
.shop-header {
    background: url('default-banner.jpg') center/cover no-repeat;
    width: 100%;
    height: 180px;
    position: relative;
}

/* Circular logo on banner */
.shop-logo {
    position: absolute;
    bottom: -35px;
    left: 50%;
    transform: translateX(-50%);
    width: 70px;
    height: 70px;
    background: white;
    border-radius: 50%;
    padding: 5px;
    box-shadow: 0px 2px 6px rgba(0,0,0,0.2);
}
.shop-logo img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

/* Shop Info */
.shop-info { text-align:center; margin-top:60px; padding:10px; }
.shop-info h1 { margin:5px 0; font-size:22px; font-weight:bold; }
.shop-stats { display:flex; justify-content:center; gap:25px; margin-top:10px; flex-wrap:wrap; }
.stat-box { text-align:center; }
.stat-box span { display:block; font-weight:bold; font-size:18px; }
.stat-box small { color:#555; }

/* Products grid */
.products { display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:20px; padding:20px; }
.product-card { background:white; padding:10px; border-radius:10px; box-shadow:0 2px 5px rgba(0,0,0,0.1); text-align:center; position:relative; }
.product-card img { width:100%; height:150px; object-fit:cover; border-radius:8px; }
.product-card h3 { margin:10px 0 5px; font-size:16px; }
.product-card p { color:#333; font-size:14px; margin:0; }

/* Rating badge for product */
.product-rating {
    position: absolute;
    top:10px;
    left:10px;
    background: rgba(255,215,0,0.9);
    padding:3px 6px;
    border-radius:5px;
    font-size:14px;
    font-weight:bold;
}

/* Responsive */
@media(max-width:768px){
    .shop-stats{ gap:15px; }
    .products{ grid-template-columns:repeat(auto-fill,minmax(150px,1fr)); }
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

<!-- Banner + Circular Logo -->
<div class="shop-header">
    <div class="shop-logo">
        <img src="default-logo.png" alt="Shop Logo"> <!-- fixed circular logo -->
    </div>
</div>

<!-- Shop Info & Stats -->
<div class="shop-info">
    <h1><?php echo $seller['storenm']; ?></h1>
    <div class="shop-stats">
        <div class="stat-box">
            <span><?php echo $avg_rating; ?>★</span>
            <small>Rating</small>
        </div>
        <div class="stat-box">
            <span><?php echo $followers; ?></span>
            <small>Followers</small>
        </div>
        <div class="stat-box">
            <span><?php echo $total_products; ?></span>
            <small>Products</small>
        </div>
    </div>
</div>

<!-- Products Grid -->
<div class="products">
<?php while($product = mysqli_fetch_assoc($products_query)) { 
    // Calculate average rating for each product except "auction" category
    if(strtolower($product['category']) != 'auction'){
        $prod_rating_query = mysqli_query($con, "SELECT AVG(rating) AS avg_rating FROM feedbacks WHERE order_id={$product['product_id']}");
        $prod_rating_row = mysqli_fetch_assoc($prod_rating_query);
        $prod_rating = $prod_rating_row['avg_rating'] ? round($prod_rating_row['avg_rating'],1) : 0;
    } else {
        $prod_rating = null;
    }
?>
    <div class="product-card">
        <?php if($prod_rating !== null){ ?>
            <div class="product-rating"><?php echo $prod_rating; ?>★</div>
        <?php } ?>
        <img src="../<?php echo $product['image']; ?>" alt="<?php echo $product['product_name']; ?>">
        <h3><?php echo $product['product_name']; ?></h3>
        <p>₹<?php echo number_format($product['price'],2); ?></p>
        <p><?php echo $product['stock_status']; ?></p>
    </div>
<?php } ?>
</div>

<?php include 'chatbot.php'; ?>
</body>
</html>
