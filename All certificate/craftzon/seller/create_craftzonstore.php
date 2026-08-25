<?php
	session_start();
	if(empty($_SESSION['users_id'])) { die('Unauthorized'); }
	$seller_id = $_SESSION['users_id'];
	$con = mysqli_connect("localhost", "root", "", "craftzon");

	{
		
		$que="select * from craftus_reg where u_id='$seller_id'";
		$sel=mysqli_query($con,$que);
		$res=mysqli_fetch_array($sel);
		if(isset($_POST['cstorebtn']))
		{
			$stornm=$_POST['store_name'];
			$selnm=$_POST['owner_name'];
			$sellemid=$_POST['owner_email'];
			$storegstno=$_POST['gstin_no'];
			$storedesc = $_POST['store_desc'];
			$defaultImage = "sellerlogo/default.jpg"; // Path to default image
			if(isset($_FILES['store_image']) && $_FILES['store_image']['error'] === UPLOAD_ERR_OK) {
				$filenm = time() . '_' . $_FILES['store_image']['name'];
                $filenm = str_replace(' ', '_', $filenm);
				$tempnm = $_FILES['store_image']['tmp_name'];
				$uploadPath = '../sellerlogo/' . $filenm;
				$folder = 'sellerlogo/' . $filenm;
				move_uploaded_file($tempnm, $uploadPath);
			} else {
				$folder = $defaultImage;
			}

			$in="insert into seller (storenm,sellernm,selleremailid,gstinno,description,shopimage) values('$stornm','$selnm','$sellemid','$storegstno','$storedesc','$folder')"; 
			$ins=mysqli_query($con,$in);
			$popup = "";
			if ($ins) {
				$new_seller_id = mysqli_insert_id($con);  // Get last inserted ID
				$popup = "<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Success!',
            text: 'Store created successfully',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        }).then(() => {
            // Create hidden form and submit with POST
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '../user/store.php';

            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'sellerid';
            input.value = '{$new_seller_id}';
            form.appendChild(input);

            document.body.appendChild(form);
            form.submit();
        });
    });
</script>";

			}
			else
			{
				$popup = "<script>
					document.addEventListener('DOMContentLoaded', function() {
						Swal.fire({
							title: 'Oops!',
							text: 'Something went wrong while creating the store.',
							icon: 'error',
							timer: 2000,
							showConfirmButton: false
						});
					});
				</script>";
			}

		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
  <title>Create Online Store</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');
    :root {
      --primary-color: #007BFF;
      --secondary-color: #6C757D;
      --bg-light: #f4f7fa;
      --bg-white: #ffffff;
      --border-color: #dee2e6;
    }
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    body {
      font-family: 'Roboto', sans-serif;
      background-color: var(--bg-light);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      color: #333;
    }
    .form-container {
      background-color: var(--bg-white);
      padding: 40px 50px;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      max-width: 600px;
      width: 100%;
    }
    h1 {
      text-align: center;
      color: var(--primary-color);
      margin-bottom: 30px;
      font-size: 28px;
    }
    .form-block {
      margin-bottom: 25px;
    }
    label {
      display: block;
      font-weight: 700;
      margin-bottom: 8px;
      color: var(--secondary-color);
    }
    input[type="text"],
    input[type="email"],
    input[type="file"] {
      width: 100%;
      padding: 12px;
      border: 1px solid var(--border-color);
      border-radius: 8px;
      font-size: 16px;
      transition: border-color 0.3s, box-shadow 0.3s;
    }
    input[type="text"]:focus,
    input[type="email"]:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
    }
    input[type="file"] {
      padding: 12px 10px;
      background-color: var(--bg-light);
      cursor: pointer;
      border-style: dashed;
    }
    .image-upload-block {
      padding: 20px;
      border: 2px dashed var(--primary-color);
      border-radius: 12px;
      text-align: center;
      background-color: #eaf5ff;
    }
    .image-upload-block label {
      margin-bottom: 15px;
      color: var(--primary-color);
      font-size: 18px;
    }
    .simple-message {
      text-align: center;
      font-style: italic;
      color: var(--secondary-color);
      margin: 35px 0;
      padding: 15px;
      background-color: #f8f9fa;
      border-radius: 8px;
      border-left: 5px solid var(--primary-color);
    }
    .simple-message p {
      margin: 0;
      font-size: 14px;
    }
    .submit-btn {
      width: 100%;
      padding: 15px;
      background-color: var(--primary-color);
      color: var(--bg-white);
      border: none;
      border-radius: 8px;
      font-size: 18px;
      font-weight: 700;
      cursor: pointer;
      transition: background-color 0.3s, transform 0.2s;
    }
    .submit-btn:hover {
      background-color: #0056b3;
      transform: translateY(-2px);
    }
    .submit-btn:active {
      transform: translateY(0);
    }
	textarea {
	  width: 100%;
	  padding: 12px;
	  border: 1px solid var(--border-color);
	  border-radius: 8px;
	  font-size: 16px;
	  transition: border-color 0.3s, box-shadow 0.3s;
	  resize: none; /* Optional: prevents manual resizing */
	}

	textarea:focus {
	  outline: none;
	  border-color: var(--primary-color);
	  box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
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
  <div class="form-container">
    <h1>Create Your Craftzon Store</h1>
	
	
    <form action="" method="POST" enctype="multipart/form-data">
      <div class="form-block image-upload-block">
	  <input type="hidden" name="userid" value="<?php echo $res['u_id'] ?? ''; ?>">

        <label for="store_image">Upload Store Logo or Banner</label>
        <input type="file" id="store_image" name="store_image" accept="image/*">
        <small>Accepted formats: JPG, PNG, GIF</small>
		
		
		
      </div>
      <div class="form-block">
        <label for="store_name">Store Name</label>
        <input type="text" id="store_name" name="store_name" placeholder="E.g., My Awesome Shop" required>
      </div>
      <div class="form-block">
        <label for="owner_name">Store Owner Name</label>
        <input type="text" id="owner_name" name="owner_name" placeholder="enter owner name" value="<?php echo $res['uname'] ?>" required>
      </div>
      <div class="form-block">
        <label for="owner_email">Owner Email ID</label>
        <input type="email" id="owner_email" name="owner_email" placeholder="contact@example.com" value="<?php echo $res['email'] ?>" readonly required>
      </div>
	  <div class="form-block">
        <label for="store_gstno">GSTIN number</label>
        <input type="text" id="store_gstno" name="gstin_no" placeholder="E.g., 29ABCDE1234F1Z5" maxlength="15">
      </div>
	  <div class="form-block">
	  <label for="store_desc">Store Description</label>
	  <textarea id="store_desc" name="store_desc" rows="4" placeholder="Brief description about your store" maxlength="500" required></textarea>
	</div>
      <div class="simple-message">
        <p>By tapping create store you agree to Craftzon Terms of Service. Changes made to your name and store profile picture are visible only on Craftzon and not other platforms.</p>
      </div>
      <button type="submit" name="cstorebtn" class="submit-btn">
		Create Store
	  </button>
    </form>
  </div>
  <?php
	if (!empty($popup)) {
		echo $popup;
	}
?>

</body>
</html>

