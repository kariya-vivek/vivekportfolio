<?php
// update_product.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $con = mysqli_connect("localhost", "root", "", "craftzon");
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Helper function for image upload
    function uploadImage($fileInputName, $currentImagePath) {
        if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileTmpPath = $_FILES[$fileInputName]['tmp_name'];
            $fileName = basename($_FILES[$fileInputName]['name']);
            $fileSize = $_FILES[$fileInputName]['size'];
            $fileType = mime_content_type($fileTmpPath);

            // Validate file type
            if (!in_array($fileType, $allowedTypes)) {
                return ['error' => 'Invalid image type. Allowed: JPG, PNG, GIF.'];
            }

            // Optional: validate size (max 5MB)
            if ($fileSize > 5 * 1024 * 1024) {
                return ['error' => 'Image size exceeds 5MB limit.'];
            }

            // Set upload directory (make sure this exists and writable)
            $uploadDir = 'uploads/products/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Generate unique file name to avoid collisions
            $newFileName = uniqid('prod_') . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
            $destPath = $uploadDir . $newFileName;

            // Move uploaded file
            if (move_uploaded_file($fileTmpPath, $destPath)) {
                // Optionally delete old image file if exists and different
                if ($currentImagePath && file_exists($currentImagePath) && $currentImagePath !== $destPath) {
                    @unlink($currentImagePath);
                }
                return ['path' => $destPath];
            } else {
                return ['error' => 'Failed to move uploaded file.'];
            }
        }
        // No new file uploaded, keep old image
        return ['path' => $currentImagePath];
    }

    // On submit (update)
    if (isset($_POST['update_product'])) {
        $productId = (int)$_POST['product_id'];
        $craftedBy = mysqli_real_escape_string($con, $_POST['crafted_by']);
         $section   = $_POST['section'] ?? 'default';
		$productName = mysqli_real_escape_string($con, $_POST['product_name']);
        $price = (float)$_POST['price'];
        $description = mysqli_real_escape_string($con, $_POST['description']);
        $quantity = (int)$_POST['quantity'];
        $category = mysqli_real_escape_string($con, $_POST['category']);
        $currentImagePath = mysqli_real_escape_string($con, $_POST['current_image']);

        // Fetch current stock_status
        $checkSql = "SELECT stock_status FROM product_table WHERE product_id = $productId";
        $result = mysqli_query($con, $checkSql);
        $currentStatus = 'in stock';
        if ($row = mysqli_fetch_assoc($result)) {
            $currentStatus = $row['stock_status'];
        }

        // Handle image upload
        $uploadResult = uploadImage('image', $currentImagePath);
        if (isset($uploadResult['error'])) {
            die("Image upload error: " . $uploadResult['error']);
        }
        $newImagePath = $uploadResult['path'];

        // Update status if it was "out of stock" and quantity is now > 0
        $newStatus = ($currentStatus === 'out of stock' && $quantity > 0) ? 'in stock' : $currentStatus;

        // Perform update
        $updateSql = "UPDATE product_table 
                      SET product_name = ?, price = ?, product_description = ?, stock_quantity = ?, stock_status = ?, image = ?, category = ?
                      WHERE product_id = ?";

        $stmt = mysqli_prepare($con, $updateSql);
        mysqli_stmt_bind_param($stmt, "sdsisssi", $productName, $price, $description, $quantity, $newStatus, $newImagePath, $category, $productId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        mysqli_close($con);

        // Redirect back to seller panel
       // Redirect back to seller panel, keeping section
header("Location: selleradminpanel.php?sellernm=" . urlencode($craftedBy) . "&section=" . urlencode($section));
exit;


    }

    // On initial load (show form)
    if (isset($_POST['product_id']) && isset($_POST['crafted_by'])) {
        $productId = (int)$_POST['product_id'];
        $craftedBy = $_POST['crafted_by'];

        $sql = "SELECT * FROM product_table WHERE product_id = $productId AND crafted_by = '$craftedBy'";
        $result = mysqli_query($con, $sql);

        if ($row = mysqli_fetch_assoc($result)) {
            $productName = htmlspecialchars($row['product_name']);
            $price = htmlspecialchars($row['price']);
            $description = htmlspecialchars($row['product_description']);
            $quantity = htmlspecialchars($row['stock_quantity']);
            $currentImagePath = htmlspecialchars($row['image']);
            $category = htmlspecialchars($row['category']);
        } else {
            echo "Product not found.";
            exit;
        }

        mysqli_close($con);
    } else {
        // Redirect back to seller panel, keeping section
header("Location: selleradminpanel.php?sellernm=" . urlencode($craftedBy) . "&section=" . urlencode($section));
exit;

    }
} else {
    // Redirect back to seller panel, keeping section
header("Location: selleradminpanel.php?sellernm=" . urlencode($craftedBy) . "&section=" . urlencode($section));
exit;

}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Product</title>
    <style>
        body {
            font-family: Arial;
            padding: 20px;
        }
        form {
            max-width: 600px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }
        input[type='text'], input[type='number'], textarea, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            box-sizing: border-box;
        }
        button {
            padding: 10px 20px;
            background-color: green;
            border: none;
            color: white;
            cursor: pointer;
            margin-top: 20px;
        }
        button:hover {
            opacity: 0.9;
        }
        img.current-image {
            margin-top: 10px;
            max-width: 150px;
            max-height: 150px;
            object-fit: contain;
            border: 1px solid #ccc;
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

<h2>Update Product</h2>

<form method="post" action="update_product.php" enctype="multipart/form-data">
    <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
    <input type="hidden" name="crafted_by" value="<?php echo htmlspecialchars($craftedBy); ?>">
    <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($currentImagePath); ?>">

    <label>Product Name:</label>
    <input type="text" name="product_name" value="<?php echo $productName; ?>" required>

    <label>Category:</label>
    <select name="category" required>
        <?php
        $categories = [
            "auction" => "Auction",
            "home_decor" => "Home Decor",
            "pottery" => "Pottery",
            "clayart" => "Clay Art",
            "brassart" => "Brass Art",
            "woodenart" => "Wooden Art",
            "bambooart" => "Bamboo Art",
            "leatherart" => "Leather Art",
            "patola_slik_sarees" => "Patola Silk Sarees",
            "bandhani" => "Bandhani",
            "kutch_embroidery" => "Kutch Embroidery",
            "tangaliya_shawl" => "Tangaliya Shawl",
            "surat_zari_craft" => "Surat Zari Craft"
        ];

        foreach ($categories as $value => $label) {
            $selected = ($category === $value) ? 'selected' : '';
            echo "<option value=\"$value\" $selected>$label</option>";
        }
        ?>
    </select>

    <label>Price:</label>
    <input type="number" step="0.01" name="price" value="<?php echo $price; ?>" required>

    <label>Description:</label>
    <textarea name="description" required><?php echo $description; ?></textarea>

    <label>Quantity:</label>
    <input type="number" name="quantity" min="0" value="<?php echo $quantity; ?>" required>
	<input type="hidden" name="section" value="products">

    <label>Current Image:</label>
    <?php if ($currentImagePath && file_exists($currentImagePath)) : ?>
        <img src="../<?php echo $currentImagePath; ?>" alt="Current Image" class="current-image">
    <?php else: ?>
        <p>No image available.</p>
    <?php endif; ?>

    <label>Upload New Image (optional):</label>
    <input type="file" name="image" accept="image/*">

    <button type="submit" name="update_product">Update Product</button>
</form>

</body>
</html>
