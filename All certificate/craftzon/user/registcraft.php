<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Craftzon Sign Up</title>
	<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
	<style>
		body {
			display: flex;
			align-items: center;
			justify-content: center;
			height: 100vh;
			margin: 0;
		}
		.c {
			width: 360px;
			height: auto;
			background-color: #f5deb3;
			border-radius: 24px;
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			text-align:center;
			padding: 20px;
		}
		.a {
			text-decoration:none;
		}
		input {
			font-size: 20px;
			border-radius: 6px;
			padding: 8px;
			width: 90%;
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
<body bgcolor="#581845">
	<div class="c">
		<h1 style="color:#581845">Sign Up</h1>
		<h5>sign up to continue</h5>
		<form action="#" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
			<input type="text" placeholder="user name" name="unm" id="unm">
			<br><br>
			<input type="text" placeholder="email address" name="emid" id="emid">
			<br><br>
			<input type="text" placeholder="mobile number" name="mono" id="mono" maxlength="10">
			<br><br>
			<input type="password" placeholder="password" name="pwd" id="pwd">
			<br><br>
			<input type="password" placeholder="confirm password" name="cpwd" id="cpwd">
			<br><br>
			<input type="file" name="profile_img" id="profile_img" accept="image/*">
			<br><br>
			<input type="submit" value="Sign up" name="signinb" style="background-color:#b08d57;color:white; padding: 4px 114px;border-radius:6px;">
			<br><br>
			<p style="color:#581845">Already have an account ? 
			<a href='logincraft.php' class="a">sign in</a></p>
		</form>
	</div>

<script>
function validateForm() {
	let name = document.getElementById("unm").value.trim();
	let email = document.getElementById("emid").value.trim();
	let mobile = document.getElementById("mono").value.trim();
	let pwd = document.getElementById("pwd").value;
	let cpwd = document.getElementById("cpwd").value;

	// Name
	if (name === "") {
		Swal.fire("Error!", "Please enter your name.", "error");
		return false;
	}

	// Email
	let emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
	if (!email.match(emailPattern)) {
		Swal.fire("Invalid Email!", "Please enter a valid email address.", "warning");
		return false;
	}

	// Mobile
	let mobilePattern = /^[1-9][0-9]{9}$/;
	if (!mobile.match(mobilePattern)) {
		Swal.fire("Invalid Mobile!", "Please enter a valid 10-digit mobile number.", "warning");
		return false;
	}

	// Password length
	if (pwd.length < 7) {
		Swal.fire("Weak Password!", "Password must be greater than 6 characters.", "warning");
		return false;
	}

	// Password match
	if (pwd !== cpwd) {
		Swal.fire("Mismatch!", "Passwords do not match.", "error");
		return false;
	}

	return true;
}
</script>
</body>
</html>

<?php
$con=mysqli_connect("localhost","root","","craftzon");
if(isset($_POST['signinb'])) {
	$un=$_POST['unm'];
	$ueid=$_POST['emid'];
	$umono=$_POST['mono'];
	$upwd=$_POST['pwd'];
	$cupwd=$_POST['cpwd'];

	// Server-side validation
	if($upwd !== $cupwd){
		echo "<script>
			Swal.fire('Error!', 'Both passwords must match!', 'error');
		</script>";
		exit;
	}
	if(strlen($upwd) < 7){
		echo "<script>
			Swal.fire('Weak Password!', 'Password must be greater than 6 characters.', 'warning');
		</script>";
		exit;
	}
	if(!preg_match("/^[1-9][0-9]{9}$/",$umono)){
		echo "<script>
			Swal.fire('Invalid Mobile!', 'Please enter a valid 10-digit mobile number.', 'warning');
		</script>";
		exit;
	}
	if(!filter_var($ueid, FILTER_VALIDATE_EMAIL)){
		echo "<script>
			Swal.fire('Invalid Email!', 'Please enter a valid email address.', 'warning');
		</script>";
		exit;
	}

	// Handle profile image
	$profileImg = 'userprofileimage/default.png'; // <-- default image path
    $uploadPath = '../userprofileimage/default.png';
	if(!empty($_FILES['profile_img']['name'])){
		$profileImg = 'userprofileimage/' . $_FILES['profile_img']['name'];
        $uploadPath = '../userprofileimage/' . $_FILES['profile_img']['name'];
		move_uploaded_file($_FILES['profile_img']['tmp_name'], $uploadPath);
	}

	$plain_pwd = $upwd;
	
	$hashed_pwd = password_hash($upwd, PASSWORD_DEFAULT);
	$in="insert into craftus_reg (uname,email,mobile_no,password,profile_img) values('$un','$ueid','$umono','$hashed_pwd','$profileImg')";
	$ins=mysqli_query($con,$in);

	if($ins){
		// ✅ Simple welcome email (plain text)
$to = $ueid;
$subject = "Welcome to Craftzon!";
$message = "Hi $un,\n\n".
           "Thank you for registering with Craftzon 🎉\n\n".
           "Your login details are:\n".
           "name: $un\n".
           "Password: $plain_pwd\n\n".
           "You can now login here: http://localhost/craftzon/logincraft.php\n\n".
           "- The Craftzon Team";

$headers = "From: Craftzon <no-reply@craftzon.com>";

mail($to, $subject, $message, $headers);

		echo "<script>
			Swal.fire('Success!', 'Registration successful! Check your email for login details.', 'success')
			.then(()=>{ window.location.href='logincraft.php'; });
		</script>";
	}
}
?>
