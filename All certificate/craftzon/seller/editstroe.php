<?php
$con = mysqli_connect("localhost", "root", "", "craftzon");

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

session_start();
$seller_id = isset($_POST['sellerid']) ? $_POST['sellerid'] : 0;
if (empty($seller_id) && isset($_GET['sellerid'])) {
    $seller_id = $_GET['sellerid'];
}

// Fetch current seller data
$query = "SELECT * FROM seller WHERE sellerid = '$seller_id'";
$result = mysqli_query($con, $query);
$seller = mysqli_fetch_assoc($result);

if (!$seller) {
    die("Seller not found.");
}

// Handle form submission
if (isset($_POST['update_store'])) {
    $store_name = mysqli_real_escape_string($con, $_POST['store_name']);
    $seller_name = mysqli_real_escape_string($con, $_POST['seller_name']);
    $gst_no = mysqli_real_escape_string($con, $_POST['gst_no']);
    $description = mysqli_real_escape_string($con, $_POST['description']);

    // Handle Image Upload securely
    $shop_image = $seller['shopimage']; // Default to old image
    
    if (isset($_FILES['shop_image']) && $_FILES['shop_image']['error'] === UPLOAD_ERR_OK) {
        $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['shop_image']['tmp_name']);
        finfo_close($finfo);
        
        $ext = strtolower(pathinfo($_FILES['shop_image']['name'], PATHINFO_EXTENSION));
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($mime, $allowed_mimes) && in_array($ext, $allowed_exts)) {
            $safe_filename = bin2hex(random_bytes(16)) . '.' . $ext;
            $db_file = 'sellerlogo/' . $safe_filename;
            $uploadPath = '../sellerlogo/' . $safe_filename;
            
            if (move_uploaded_file($_FILES['shop_image']['tmp_name'], $uploadPath)) {
                $shop_image = $db_file;
            }
        }
    }

    $update_query = "UPDATE seller SET 
                     storenm = '$store_name', 
                     sellernm = '$seller_name', 
                     gstinno = '$gst_no', 
                     description = '$description', 
                     shopimage = '$shop_image' 
                     WHERE sellerid = '$seller_id'";

    if (mysqli_query($con, $update_query)) {
        echo "<script>alert('Store updated successfully!'); window.location.href='../user/store.php';</script>";
    } else {
        echo "<script>alert('Error updating store.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Store</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #581845; color: white; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .container { background-color: #f5deb3; padding: 30px; border-radius: 10px; width: 400px; color: #581845; text-align: center; }
        input, textarea { width: 90%; padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid #ccc; font-size: 16px; }
        button { background-color: #b08d57; color: white; border: none; padding: 10px 20px; font-size: 18px; border-radius: 5px; cursor: pointer; }
        button:hover { background-color: #a07c46; }
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
    <h2>Edit Store Details</h2>
    <form action="editstroe.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="sellerid" value="<?php echo $seller_id; ?>">
        
        <input type="text" name="store_name" value="<?php echo htmlspecialchars($seller['storenm']); ?>" required>
        <input type="text" name="seller_name" value="<?php echo htmlspecialchars($seller['sellernm']); ?>" required>
        <input type="text" name="gst_no" value="<?php echo htmlspecialchars($seller['gstinno']); ?>" required>
        
        <textarea name="description" rows="4" required><?php echo htmlspecialchars($seller['description']); ?></textarea>
        
        <p>Current Image:</p>
        <img src="../<?php echo $seller['shopimage']; ?>" alt="Store Image" width="100"><br>
        
        <input type="file" name="shop_image" accept="image/*">
        
        <button type="submit" name="update_store">Update Store</button>
    </form>
</div>

</body>
</html>
