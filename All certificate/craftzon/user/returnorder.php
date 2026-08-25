<?php
	$con=mysqli_connect("localhost","root","","craftzon");
	$oid=$_POST['orderid'];
	$urid=$_POST['uid'];
	if(isset($_POST['sretbtn']))
	{
		$res=$_POST['reason'];
		$eid=$_POST['email'];
		$filenm=$_FILES['photo']["name"];
		$tempnm=$_FILES['photo']['tmp_name'];
		$uploadPath='../retundbimage/' . $filenm;
		$db_folder='retundbimage/' . $filenm;
		move_uploaded_file($tempnm,$uploadPath);
		$addcomm=$_POST['comments'];
		$in = "insert into return_requests (order_id,emailid,reason,comments,photo,uretunid) values ('$oid','$eid', '$res', '$addcomm', '$db_folder','$urid')";
		$ins=mysqli_query($con,$in);
		if($ins)
{
    echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Return request submitted!',
            text: 'Redirecting to your orders...',
            timer: 2000,
            showConfirmButton: false
        }).then(() => {
            // Create and submit a hidden POST form
            var form = document.createElement('form');
            form.method = 'post';
            form.action = 'myorders.php';

            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'uid';
            input.value = '$urid';
            form.appendChild(input);

            document.body.appendChild(form);
            form.submit();
        });
    });
    </script>";
}

		else
		{
			echo "<script>
  document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
      icon: 'error',
      title: 'Submission Failed',
      text: 'Please try again later.'
    });
  });
</script>";


		}
	}
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Craftzon - Return Order</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f9f9f9;
      margin: 0;
      padding: 0;
    }
    .return-container {
      max-width: 600px;
      margin: 40px auto;
      background: #fff;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 0 12px rgba(0,0,0,0.15);
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }
    label {
      font-weight: bold;
      display: block;
      margin: 12px 0 6px;
      color: #444;
    }
    input, select, textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #bbb;
      border-radius: 6px;
      margin-bottom: 15px;
      font-size: 15px;
    }
    textarea {
      resize: vertical;
      min-height: 80px;
    }
    button {
      width: 100%;
      padding: 12px;
      background: #007bff;
      color: white;
      font-size: 16px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 10px;
    }
    button:hover {
      background: #0056b3;
    }
    .cancel-btn {
      background: #dc3545;
    }
    .cancel-btn:hover {
      background: #b52a38;
    }
	.popup {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background: #fff;
  color: #333;
  padding: 25px 30px;
  border-radius: 10px;
  box-shadow: 0 0 15px rgba(0,0,0,0.2);
  z-index: 9999;
  text-align: center;
  font-size: 1em;
  border: 2px solid #d63384;
}

.popup.success {
  border-color: #28a745;
}

.popup.error {
  border-color: #dc3545;
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

  <div class="return-container">
    <h2>Return Your Product</h2>
    <form id="returnForm" action="#" method="post" enctype="multipart/form-data" onsubmit="this.insertAdjacentHTML('beforeend', '<input type=\'hidden\' name=\'sretbtn\' value=\'1\'>'); var btn = this.querySelector('button[type=submit]'); btn.disabled=true; btn.innerText='Processing...'; return true;">
	<input type="hidden" name="orderid" value="<?php echo $oid; ?>">
<input type="hidden" name="uid" value="<?php echo $urid; ?>">


      <label for="order_id">Order ID</label>
      <input type="text" id="order_id" name="order_id" value="<?php echo $oid; ?>" readonly required>

      <label for="reason">Reason for Return</label>
      <select id="reason" name="reason" required>
        <option value="">-- Select Reason --</option>
        <option value="damaged">Damaged Product</option>
        <option value="wrong_item">Wrong Item Delivered</option>
        <option value="defective">Defective Product</option>
        <option value="not_expected">Not as Expected</option>
        <option value="other">Other</option>
      </select>

      <label for="email">Email Address:</label>
      <input type="email" id="email" name="email" required />
	  
	  <label for="photo">Upload Product Photo</label>
      <input type="file" id="photo" name="photo" accept="image/*" required>

      <label for="comments">Additional Comments</label>
      <textarea id="comments" name="comments" placeholder="Write any additional details..."></textarea>

      <button type="submit" name="sretbtn">Submit Return Request</button>
      <button type="button" class="cancel-btn" onclick="window.location.href='myorders.php'">Cancel</button>
    </form>
  </div>

</body>
</html>
