<?php
$con = mysqli_connect("localhost", "root", "", "craftzon");
session_start();
if(isset($_COOKIE['unm']) && isset($_COOKIE['pwd']))
{
	$ck_unm = $_COOKIE['unm'];
	$ck_pwd = $_COOKIE['pwd'];
}
else
{
	$ck_unm='';
	$ck_pwd='';
}
if (isset($_POST['login'])) 
{
	$uln = $_POST['ulnm'];
	$upwd = $_POST['ulpwd'];
	
	$ses = "SELECT u_id, uname,password,status FROM craftus_reg WHERE uname='$uln'";
	$sel = mysqli_query($con, $ses);
	
	$total = mysqli_num_rows($sel);
	if ($total == 1)
	{
		$row = mysqli_fetch_assoc($sel);
		$hashed_password = $row['password'];

		
		if($row['status']=='suspend')
		{
			$uid = $row['u_id']; // get user id
			echo "<script>
				  window.onload = function() {
					showPopup('Your account is suspended. Please contact admin.', true, 'contectus.php?uid={$uid}');
				  };
				</script>";    
		}


		else if (password_verify($upwd, $hashed_password))
		{
			$_SESSION['users_id'] =$row['u_id'];
			if(isset($_POST['remember']))
			{	
				setcookie('unm', $uln, time() + (60*60*24));
				setcookie('pwd', $upwd, time() + (60*60*24));
			}
			echo "<script>
				  window.onload = function() {
					showPopup('Login successful. Redirecting to home...', false, 'crafthome.php');
				  };
				</script>";
		}
		else
		{
			echo "<script>
				  window.onload = function() {
					showPopup('Incorrect password. Please try again.');
				  };
				</script>";
		}
	} 
	else 
	{
			echo "<script>
				  window.onload = function() {
					showPopup('Invalid username or password');
				  };
				</script>";
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<style>
	body
	{
		display: flex;
		align-items: center;
		justify-content: center;
		height: 100vh;
		margin: 0;
		background-color:#581845;
	}

	.c	
	{
		width: 360px;
		height: 420px;
		background-color: #f5deb3;
		border-radius: 24px;
		flex-direction: column;
		text-align:center;
	}

	.a
	{
		text-decoration:none;
	}
	input
	{
		font-size: 20px;
		border-radius: 6px;
	}
	#popupBox 
	{
	  opacity: 1;
	  transition: opacity 1s ease;
	}
	#popupBox.fade-out 
	{
	  opacity: 0;
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
			<div class="c">
			<br><br>
				<h1 style="color:#581845">LOGIN </h1>
				<br>
				<form action="" method="post">
					<input type="text" placeholder="user name" name="ulnm" value="<?php echo $ck_unm; ?>">
					<br><br>
					<input type="password" placeholder="password" name="ulpwd" value="<?php echo $ck_pwd; ?>">
					<br>
					
					<div style="text-align:left; width:80%; margin:0 auto;">
						<input type="checkbox" name="remember"> Remember me
						<a href="forgot.php" class="a" style="float:right;">Forgot password?</a>
					</div>

					<br>
					<input type="submit" value="login" name="login" style="background-color:#b08d57;color:white; padding: 4px 114px;border-radius:6px;" >
					<p style="color:#581845">don't have an account ?</p><a class="a" href="registcraft.php">register</a>
				</form>
			</div>
			<div id="popupBox" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5); z-index:999;">
  <div style="background:#fff; padding:20px; border-radius:10px; width:300px; margin:100px auto; text-align:center; position:relative;">
    <span id="popupMessage" style="font-size:18px;"></span><br><br>
    <a id="popupLink" href="#" style="display:none; color:#581845; text-decoration:underline;">Contact Us</a><br><br>
    <button onclick="closePopup()" style="padding:6px 20px; background:#581845; color:white; border:none; border-radius:6px;">Close</button>
  </div>
</div>
<script>
function showPopup(message, showLink = false, redirectUrl = null) {
  document.getElementById('popupMessage').innerText = message;
  const link = document.getElementById('popupLink');

  if (showLink && redirectUrl) {
    link.href = redirectUrl;   // ✅ now dynamic (with uid)
    link.style.display = 'inline-block';
  } else {
    link.style.display = 'none';
  }

  const popup = document.getElementById('popupBox');
  popup.style.display = 'block';

  // Auto-redirect only if URL given AND not contact link
  if (redirectUrl && !showLink) {
    setTimeout(() => {
      window.location.href = redirectUrl;
    }, 3000);
  }
}


function closePopup() {
  document.getElementById('popupBox').style.display = 'none';
}
</script>

	</body>
</html>


