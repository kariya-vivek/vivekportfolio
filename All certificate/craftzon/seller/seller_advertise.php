<?php
$conn = mysqli_connect("localhost","root","","craftzon");
if(!$conn){ die("DB connection failed: ".mysqli_connect_error()); }

// Get sellerid from URL
$seller_id = isset($_POST['sellerid']) ? (int)$_POST['sellerid'] : 0;
if($seller_id <= 0){ die("❌ Invalid Seller ID."); }

// Fetch seller info
$sellerRes = mysqli_query($conn, "SELECT * FROM seller WHERE sellerid='$seller_id' LIMIT 1");
if(!$sellerRes || mysqli_num_rows($sellerRes) == 0){ die("❌ Seller not found."); }
$seller = mysqli_fetch_assoc($sellerRes);
$seller_name = $seller['sellernm'];
$seller_email = $seller['selleremailid'];

// Fetch all products by this seller
$products = [];
$productRes = mysqli_query($conn, "SELECT * FROM product_table WHERE crafted_by='".mysqli_real_escape_string($conn, $seller_name)."' AND status='active'");
if($productRes && mysqli_num_rows($productRes) > 0){
    while($p = mysqli_fetch_assoc($productRes)){
        $products[] = $p;
    }
}

// Handle form submission
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action']=="add_ad"){
    $product_id = $_POST['pid'];
    $product_name = mysqli_real_escape_string($conn,$_POST['product_name']);
    $category = mysqli_real_escape_string($conn,$_POST['category']);
    $price = $_POST['price'];
    $description = mysqli_real_escape_string($conn,$_POST['description']);
    $user_email = $seller_email;

    // Image upload
    // Image upload
$image_path = isset($_POST['existing_image']) && !empty($_POST['existing_image']) ? $_POST['existing_image'] : NULL;
if (!empty($_FILES['image']['name'])) {
    $image_name = time() . '_' . basename($_FILES['image']['name']);
    $target_dir = "../advrtisephoto/";
    $target_path = $target_dir . $image_name;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
        $image_path = "advrtisephoto/" . $image_name;
    }
}


    $sql = "INSERT INTO advertisements 
        (seller_id, productid, product_name, category, price, description, user_email, image)
        VALUES ('$seller_id','$product_id','$product_name','$category','$price','$description','$user_email','$image_path')";
    if(mysqli_query($conn,$sql)){
        echo json_encode(["status"=>"success","message"=>"Product advertised successfully!"]);
    } else {
        echo json_encode(["status"=>"error","message"=>"Something went wrong: ".mysqli_error($conn)]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Advertise Product | Craftzon</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
<style>
body{font-family:'Roboto',sans-serif;background:#fdf6f0;}
.container-box{max-width:700px;margin:40px auto;background:#fff;padding:25px;border-radius:10px;
    box-shadow:0 0 10px rgba(0,0,0,0.1);}
h1{color:#581845;text-align:center;margin-bottom:25px;}
form input,form select,form textarea{margin-bottom:15px;}
button{background:#ff6600;color:#fff;border:none;padding:10px;border-radius:6px;width:100%;cursor:pointer;}
button:hover{background:#e65c00;}
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
<div class="container-box">
    <h1>Advertise Your Product</h1>
    <form id="adForm" enctype="multipart/form-data" method="post">
        <input type="hidden" name="sellerid" value="<?= $seller_id ?>">
        <input type="hidden" name="pid" id="pid" value="">
        <input type="hidden" name="action" value="add_ad">
        <input type="hidden" name="existing_image" id="existing_image" value="">

        <label>Product Name</label>
        <select name="product_name" id="product_name" class="form-control" required>
            <option value="">Select Product</option>
            <?php foreach($products as $p): ?>
                <option value="<?= htmlspecialchars($p['product_name']) ?>" 
                        data-price="<?= $p['price'] ?>" 
                        data-description="<?= htmlspecialchars($p['product_description']); ?>"
                        data-category="<?= htmlspecialchars($p['category']); ?>"
                        data-pid="<?= $p['product_id'] ?>"
                        data-image="<?= htmlspecialchars($p['image']) ?>">
                    <?= htmlspecialchars($p['product_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Price (INR)</label>
        <input type="number" name="price" id="price" class="form-control" placeholder="Price" readonly required>

        <label>Category</label>
        <input type="text" name="category" id="category" class="form-control" placeholder="Category" readonly required>

        <label>Description</label>
        <textarea name="description" rows="4" class="form-control" placeholder="Product Description" id="description" readonly></textarea>

        <label>Seller Email</label>
        <input type="email" name="user_email" class="form-control" 
               value="<?= htmlspecialchars($seller_email) ?>" readonly>

        <label>Product Image</label>
        <div id="image_preview_container" style="display:none; margin-bottom: 15px;">
            <img id="image_preview" src="" alt="Product Image" style="max-width: 200px; border-radius: 8px; border: 2px solid #ccc;">
            <p style="font-size: 12px; color: gray;">You can upload a new image below to override this.</p>
        </div>
        <label>Upload New Image (Optional)</label>
        <input type="file" name="image" class="form-control" accept="image/*">

        <button type="submit" id="submitBtn">Advertise Product</button>
    </form>
    <a href="view_ads.php?sellerid=<?= $seller_id ?>" class="btn btn-secondary w-100 mt-3">View My Advertisements</a>
</div>

<script>
$(document).ready(function(){
    // Auto-fill price, category, description, and pid when product selected
    $("#product_name").change(function(){
        var selected = $(this).find(':selected');
        $("#price").val(selected.data('price') || '');
        $("#description").val(selected.data('description') || '');
        $("#category").val(selected.data('category') || '');
        $("#pid").val(selected.data('pid') || '');
        var imgPath = selected.data('image') || '';
        if(imgPath) {
            $("#image_preview").attr("src", "../" + imgPath);
            $("#image_preview_container").show();
            $("#existing_image").val(imgPath);
        } else {
            $("#image_preview_container").hide();
            $("#existing_image").val("");
        }
    });

    $("#adForm").on('submit', function(e){
        e.preventDefault();

        var formData = new FormData(this);
        var sellerId = $("input[name='sellerid']").val();

        $.ajax({
            url: "", // same page
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response){
                try {
                    var res = JSON.parse(response);
                    if(res.status == "success"){
                        Swal.fire({
                            icon: "success",
                            title: res.message,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
    // Create hidden form to POST sellerid
    var form = document.createElement('form');
    form.method = 'POST';
    form.action = '../user/store.php';

    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'sellerid';
    input.value = sellerId;

    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
});

                    } else {
                        Swal.fire({ icon: "error", title: "Oops!", text: res.message });
                    }
                } catch(err){
                    Swal.fire({ icon: "error", title: "Error!", text: response });
                }
            },
            error: function(xhr, status, error){
                Swal.fire({ icon: "error", title: "Oops!", text: error });
            }
        });
    });
});
</script>
</body>
</html>
