<?php
$host = "localhost";
$db   = "craftzon";
$user = "root";
$pwd  = "";

$snm = isset($_POST['sellernm']) ? $_POST['sellernm'] : '';
$con = mysqli_connect($host, $user, $pwd, $db);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['work'])) {
    $product_name        = $_POST['productName'];
    $crafted_by          = $_POST['craftsmanName'];
    $category            = $_POST['productCategory'];
    $price               = $_POST['productPrice'];
    $product_description = $_POST['productDescription'];
    $product_image       = '';

    // Set stock quantity
    $stock_qty = strtolower($category) === "auction" ? 1 : intval($_POST['productStock']);

    // Handle image upload with security checks
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_tmp_path = $_FILES['image']['tmp_name'];
        $image_name     = basename($_FILES['image']['name']);
        $upload_dir     = 'craftzonstroreimage/';
        
        // Security: Validate MIME type and Extension
        $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $image_tmp_path);
        finfo_close($finfo);
        
        $ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($mime, $allowed_mimes) && in_array($ext, $allowed_exts)) {
            // Generate safe filename to prevent double-extension bypass
            $safe_filename = bin2hex(random_bytes(16)) . '.' . $ext;
            $target_file    = $upload_dir . $safe_filename;

            if (move_uploaded_file($image_tmp_path, $target_file)) {
                $product_image = $target_file;
            } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Image upload failed!'
                });
            </script>";
            }
        } else {
            // Invalid extension
            echo "<script>Swal.fire({icon:'error',title:'Invalid file',text:'Only valid images allowed!'});</script>";
        }
    }

    // Insert into product_table safely using prepared statements
    $stmt = mysqli_prepare($con, "INSERT INTO product_table (product_name, crafted_by, category, price, stock_quantity, product_description, image, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, 'active', NOW())");
    mysqli_stmt_bind_param($stmt, "sssdiss", $product_name, $crafted_by, $category, $price, $stock_qty, $product_description, $product_image);
    
    if (mysqli_stmt_execute($stmt)) {
        $product_id = mysqli_insert_id($con);

        // If auction, insert into auction_table
        if (strtolower($category) === "auction") {
            $start_price = $price;
            $start_time  = date("Y-m-d H:i:s");
            $end_time    = date("Y-m-d H:i:s", strtotime("+1 day"));
            $auction_fee = isset($_POST['auctionFee']) ? $_POST['auctionFee'] : 0;

            $auction_sql = "INSERT INTO auction_table 
                (product_id, start_price, current_price, start_time, end_time, status, auction_fee) 
                VALUES 
                ($product_id, $start_price, $start_price, '$start_time', '$end_time', 'active', '$auction_fee')";

            if (!mysqli_query($con, $auction_sql)) {
                $error = mysqli_error($con);
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Auction Error',
                        text: 'Auction insert failed: $error'
                    });
                </script>";
            }
        }

        // Get sellerid
        $seller_query = "SELECT sellerid FROM seller WHERE sellernm = '$crafted_by'";
        $seller_result = mysqli_query($con, $seller_query);

        if (mysqli_num_rows($seller_result) > 0) {
            $seller_row = mysqli_fetch_assoc($seller_result);
            $sellerid = $seller_row['sellerid'];

            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Product Added!',
                    text: 'Your product was successfully inserted.',
                    confirmButtonText: 'Go to Store'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../user/store.php?sellerid=$sellerid';
                    }
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Seller Not Found',
                    text: 'Could not find seller ID for crafted_by: $crafted_by'
                });
            </script>";
        }
    } else {
        $error = mysqli_error($con);
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Database Error',
                text: '$error'
            });
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Product</title>
<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
<style>
/* Your original CSS here (unchanged) */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
    background-color: #f0f8ff;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-height: 100vh;
    box-sizing: border-box;
}
.container {
    background-color: #ffffff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    max-width: 600px;
    width: 100%;
    box-sizing: border-box;
}
h2 { text-align: center; color: #000080; margin-bottom: 25px; font-size: 2em; }
.form-group { margin-bottom: 20px; }
label { display: block; margin-bottom: 8px; color: #000080; font-weight: bold; font-size: 1.1em; }
input[type="text"], input[type="number"], textarea, select {
    width: calc(100% - 24px);
    padding: 12px;
    border: 1px solid #add8e6;
    border-radius: 5px;
    box-sizing: border-box;
    font-size: 1em;
    color: #333;
}
input[type="file"] { padding: 8px; border: 1px solid #add8e6; border-radius: 5px; background-color: #e0f2f7; }
textarea { resize: vertical; min-height: 100px; }
.image-upload-block {
    border: 2px dashed #4169e1;
    background-color: #f0f8ff;
    padding: 25px;
    text-align: center;
    cursor: pointer;
    margin-bottom: 20px;
    border-radius: 8px;
    transition: border-color 0.3s ease, background-color 0.3s ease;
}
.image-upload-block:hover { border-color: #000080; background-color: #e6f2ff; }
.image-upload-block img {
    max-width: 120px;
    max-height: 120px;
    display: block;
    margin: 0 auto 15px auto;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
.image-upload-block p { color: #000080; font-weight: bold; }
.date-time {
    font-size: 0.9em;
    color: #4682b4;
    text-align: right;
    margin-top: 25px;
    padding-top: 15px;
    border-top: 1px solid #eee;
}
.add-product-button {
    display: block;
    width: 100%;
    padding: 15px;
    background-color: #000080;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 1.2em;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-top: 30px;
}
.add-product-button:hover { background-color: #4169e1; }
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
<div class="container">
<form method="POST" enctype="multipart/form-data">
<h2>Add New Product</h2>

<div class="form-group">
    <label for="productImage">Product Image:</label>
    <div class="image-upload-block" onclick="document.getElementById('productImage').click()">
        <img id="imagePreview" src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%234169e1' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><rect x='3' y='3' width='18' height='18' rx='2' ry='2'></rect><circle cx='8.5' cy='8.5' r='1.5'></circle><polyline points='21 15 16 10 5 21'></polyline></svg>" alt="Upload Image Icon"  required>
        <p>Click to Upload Image</p>
    </div>
    <input type="file" id="productImage" name="image" accept="image/*" style="display:none;" onchange="previewImage(event)">
</div>

<div class="form-group">
    <label for="productName">Product Name:</label>
    <input type="text" id="productName" name="productName" placeholder="Enter product name" required>
</div>

<div class="form-group">
    <label for="craftsmanName">Crafted By:</label>
    <input type="text" id="craftsmanName" name="craftsmanName" value="<?php echo $snm; ?>" readonly>
</div>

<div class="form-group">
    <label for="productCategory">Category:</label>
    <select id="productCategory" name="productCategory" required>
        <option value="">Select a category</option>
        <option value="auction">Auction</option>
        <option value="home_decor">Home Decor</option>
        <option value="pottery">Pottery</option>
        <option value="clayart">Clay Art</option>
        <option value="brassart">Brass Art</option>
        <option value="woodenart">Wooden Art</option>
        <option value="bambooart">Bamboo Art</option>
        <option value="leatherart">Leather Art</option>
        <option value="patola_slik_sarees">Patola Silk Sarees</option>
        <option value="bandhani">Bandhani</option>
        <option value="kutch_embroidery">Kutch Embroidery</option>
        <option value="tangaliya_shawl">Tangaliya Shawl</option>
        <option value="surat_zari_craft">Surat Zari Craft</option>
    </select>
</div>

<div class="form-group" id="auctionFeeDiv" style="display:none;">
    <label for="auctionFee">Auction Fee (₹):</label>
    <input type="number" id="auctionFee" name="auctionFee" placeholder="Auction fee" min="0" readonly>
</div>

<div class="form-group">
    <label for="productPrice">Price (₹):</label>
    <input type="number" id="productPrice" name="productPrice" placeholder="Enter price" step="0.01" min="0" required>
</div>

<div class="form-group">
    <label for="productStock">Stock Quantity:</label>
    <input type="number" id="productStock" name="productStock" placeholder="Enter stock quantity" min="0" required>
</div>

<div class="form-group">
    <label for="productDescription">Product Description:</label>
    <textarea id="productDescription" name="productDescription" placeholder="Provide a detailed description of the product"></textarea>
</div>

<div class="date-time">
    Product Added On: <span id="currentDateTime"></span>
</div>

<button type="submit" class="add-product-button" name="work">Add Product</button>
</form>
</div>

<script>
function displayDateTime() {
    const now = new Date();
    const options = { year:'numeric', month:'long', day:'numeric', hour:'2-digit', minute:'2-digit', second:'2-digit', hour12:true };
    document.getElementById('currentDateTime').textContent = now.toLocaleString('en-US', options);
}
displayDateTime();
setInterval(displayDateTime, 1000);

function previewImage(event) {
    const [file] = event.target.files;
    if(file) document.getElementById('imagePreview').src = URL.createObjectURL(file);
}

const categorySelect = document.getElementById('productCategory');
const stockInput = document.getElementById('productStock');
const auctionDiv = document.getElementById('auctionFeeDiv');
const priceInput = document.getElementById('productPrice');
const auctionInput = document.getElementById('auctionFee');

// Function to update auction fee (10% of price)
function updateAuctionFee() {
    const price = parseFloat(priceInput.value) || 0;
    auctionInput.value = (price * 0.1).toFixed(2); // 10% fee, 2 decimals
}


// Category change
categorySelect.addEventListener('change', function() {
    if(this.value.toLowerCase() === 'auction') {
        auctionDiv.style.display = 'block';
        updateAuctionFee();
        stockInput.value = 1;
        stockInput.readOnly = true;
        stockInput.min = 1;
        stockInput.max = 1;
    } else {
        auctionDiv.style.display = 'none';
        stockInput.readOnly = false;
        stockInput.min = 0;
        stockInput.max = 2000;
    }
});

priceInput.addEventListener('input', function() {
    if(categorySelect.value.toLowerCase() === 'auction') updateAuctionFee();
});
</script>

<?php if(isset($sql)): ?>
<script>
<?php if(isset($sellerid)): ?>
Swal.fire({icon:'success', title:'Product Added!', text:'Your product was successfully inserted.', confirmButtonText:'Go to Store'}).then((result)=>{
   if (result.isConfirmed) {
    let form = document.createElement("form");
    form.method = "POST";
    form.action = "../user/store.php";

    let input = document.createElement("input");
    input.type = "hidden";
    input.name = "sellerid";
    input.value = "<?php echo $sellerid; ?>";

    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}

});
<?php elseif(!empty($error)): ?>
Swal.fire({icon:'error', title:'Database Error', text:'<?php echo $error; ?>'});
<?php else: ?>
Swal.fire({icon:'warning', title:'Seller Not Found', text:'Could not find seller ID for crafted_by: <?php echo $crafted_by; ?>'});
<?php endif; ?>
</script>
<?php endif; ?>

</body>
</html>
