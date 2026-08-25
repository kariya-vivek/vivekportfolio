<?php
	session_start();

	$con = mysqli_connect("localhost", "root", "", "craftzon");

	if (isset($_POST['login'])) 
	{
		$eid = $_GET['emailid'];
		$rupwd = $_POST['pwd'];
		$rcupwd = $_POST['cpwd'];
		if ($rupwd == $rcupwd)
		{
			$uid = $_GET['uid'];
			echo $uid;
			$hashed_pwd = password_hash($rupwd, PASSWORD_DEFAULT);
			$upd = "UPDATE craftus_reg SET password='$hashed_pwd' WHERE email='$eid'";
			$update = mysqli_query($con, $upd);
			if ($update)
			{
				setcookie('pwd', $rupwd, time() + (60*60*24));
				header("Location: logincraft.php");
				exit;
			}
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
		}
		.c
		{
			width:360px;
			height:450px;
			background-color:#f5deb3;
			border-radius: 24px;
		    flex-direction: column;
		    text-align:center;
		}
		input
		{
			font-size: 22px;
			border-radius: 6px;
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
	<body bgcolor="#581845">
			<div class="c">
			<br><br>
				<center>
					<h1 style="color:#581845">New Password </h1>
					<div style="height:48px;background-color:#d8e4bc;width:260px;">
						<p>Please create new password that you do not use any other site.</p>
					</div>
				</center>
				<br>
				<form action="#" method="post">
					<input type="password" placeholder="Create new password" style="font-size:24px;" name="pwd">
					<br><br>
					<input type="password" placeholder="Confirm your password" style="font-size:24px;" name="cpwd">
					<br>
					<br>
					<input type="submit" value="continue" name="login" style="background-color:#b08d57;color:white; padding: 4px 107px;border-radius:6px;">
					<br>
					<br>
					
					<input type="button" value="cancel" name="Cancel" style="background-color:white;color:black; padding: 4px 116px;border-radius:6px;" onclick="window.location.href='forgot.php'">
					<br>
				</form>
			</div>
	</body>
</html>