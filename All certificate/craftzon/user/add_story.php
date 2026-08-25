<?php
// Connect DB
$con = new mysqli("localhost", "root", "", "craftzon");
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Get seller_id from URL
if (!isset($_POST['sellerid'])) {
    die("Seller ID missing in URL.");
}
$sellerid = intval($_POST['sellerid']);

// Handle form submission
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title       = isset($_POST['title']) ? trim($_POST['title']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $mediaType   = isset($_POST['media_type']) ? $_POST['media_type'] : '';

    if (isset($_FILES['media']) && $_FILES['media']['error'] == 0) {
        $uploadDir = '../crafter_storie/';
        $fileName = time() . "_" . basename($_FILES["media"]["name"]);
        $targetPath = $uploadDir . $fileName;
        $dbPath = 'crafter_storie/' . $fileName;

        $allowedImage = ['jpg','jpeg','png','gif'];
        $allowedVideo = ['mp4','mov','avi','mkv'];
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (($mediaType == "image" && in_array($ext, $allowedImage)) ||
            ($mediaType == "video" && in_array($ext, $allowedVideo))) {
            
            if (move_uploaded_file($_FILES["media"]["tmp_name"], $targetPath)) {
                $stmt = $con->prepare("INSERT INTO crafter_story (seller_id, title, description, media_type, media_path) VALUES (?,?,?,?,?)");
                $stmt->bind_param("issss", $sellerid, $title, $description, $mediaType, $dbPath);
                if ($stmt->execute()) {
    // Success → SweetAlert2 + redirect with POST
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Story Uploaded!',
                text: 'Your crafter story has been added successfully.',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                // Create form dynamically for POST
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = 'store.php';

                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'sellerid';
                input.value = '{$sellerid}';

                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            });
        });
    </script>";
    exit;
}
 
 else {
                    $message = "<div class='alert alert-danger'>Database error: " . $stmt->error . "</div>";
                }
            } else {
                $message = "<div class='alert alert-danger'>Failed to upload file.</div>";
            }
        } else {
            $message = "<div class='alert alert-warning'>Invalid file type for selected media.</div>";
        }
    } else {
        $message = "<div class='alert alert-warning'>Please upload a file.</div>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Crafter Story</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; }
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        .form-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #444;
            margin-bottom: 20px;
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

<div class="container">
    <div class="form-container">
        <h3 class="form-title text-center">✨ Add Your Crafter Story</h3>
        <?= $message; ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="sellerid" value="<?= $sellerid; ?>">

            <div class="mb-3">
                <label class="form-label">Story Title</label>
                <input type="text" name="title" class="form-control" required maxlength="255">
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Select Media Type</label>
                <select name="media_type" class="form-select" required>
                    <option value="">-- Choose Type --</option>
                    <option value="image">Image</option>
                    <option value="video">Video</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Upload File</label>
                <input type="file" name="media" class="form-control" accept="image/*,video/*" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Upload Story</button>
        </form>
    </div>
</div>

</body>
</html>
