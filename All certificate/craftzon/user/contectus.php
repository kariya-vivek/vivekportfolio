<?php
$con = mysqli_connect("localhost", "root", "", "craftzon");

$responseMessage = "";
$cuid = $_POST['uid'];

// Fetch user info
$sel = "SELECT * FROM craftus_reg WHERE u_id=$cuid";
$res = mysqli_query($con, $sel);
$row = mysqli_fetch_array($res);

if (isset($_POST['sm'])) {
    $name = trim($_POST['name']);
    $userEmail = trim($row['email']); // Logged-in user email
    $recipientEmail = trim($_POST['recipient']); // Recipient email
    $messageContent = trim($_POST['message']);

    // Insert into contactus table
    $insert = "INSERT INTO contactus (user_id, name, uemailid, email, message) 
               VALUES ('$cuid', '$name', '$userEmail', '$recipientEmail', '$messageContent')";
    mysqli_query($con, $insert);

    // Send email
    $subject = "New Message from $name via Craftzon Contact Form";
    $message = "Name: $name\nUser Email: $userEmail\nMessage:\n$messageContent";
    $headers = "From: $userEmail";

    if (filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) { // validate recipient email
        if (mail($recipientEmail, $subject, $message, $headers)) {
            $responseMessage = "Mail sent successfully to $recipientEmail!";

            // Redirect after 2 seconds
            echo "<script>
                    setTimeout(function() {
                        window.location.href = 'crafthome.php';
                    }, 2000);
                  </script>";
        } else {
            $responseMessage = "Mail failed. Please check your server's mail configuration.";
        }
    } else {
        $responseMessage = "Invalid recipient email!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact Us - Craftzon</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f0f2f5;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    form {
      background: #fff;
      padding: 20px 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 500px;
    }

    h2 {
      margin-bottom: 20px;
      color: #333;
    }

    label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
    }

    input, textarea {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 14px;
    }

    button {
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #5c67f2;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }

    button:hover {
      background-color: #4a54e1;
    }

    #response {
      margin-top: 15px;
      font-size: 14px;
      color: green;
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
  <form id="contactForm" action="" method="post">
  <input type="hidden" name="uid" value="<?php echo $row['u_id']; ?>">
    <h2>Contact Us</h2>
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?php echo $row['uname'];?>" readonly required />

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo $row['email'];?>" readonly required />

	<label for="recipient">Send To (Recipient Email):</label>
	<input type="email" id="recipient" name="recipient" placeholder="Enter recipient email" required />

   
   <label for="message">Message:</label>
    <textarea id="message" name="message" rows="5" required></textarea>

    <button type="submit" name="sm">Send Message</button>
    <p id="response"><?php echo $responseMessage; ?></p>
  </form>
</body>
</html>
