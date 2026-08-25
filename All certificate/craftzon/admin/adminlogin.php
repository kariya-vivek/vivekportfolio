<?php
$con = mysqli_connect("localhost", "root", "", "craftzon");
session_start();

if(isset($_COOKIE['adunm']) && isset($_COOKIE['adpwd']))
{
	$ck_unm = $_COOKIE['adunm'];
	$ck_pwd = $_COOKIE['adpwd'];
}
else
{
	$ck_unm = '';
	$ck_pwd = '';
}

if (isset($_POST['login'])) 
{
	$admineld = $_POST['emailid'];
	$adminpwd = $_POST['adminpwd'];
	
	$stmt = mysqli_prepare($con, "SELECT admin_id, name, password FROM admin_table WHERE emailid=?");
	mysqli_stmt_bind_param($stmt, "s", $admineld);
	mysqli_stmt_execute($stmt);
	$sel = mysqli_stmt_get_result($stmt);
	
	$total = mysqli_num_rows($sel);

	if ($total == 1)
	{
		$row = mysqli_fetch_assoc($sel);
		$hashed_password = $row['password'];

		if (password_verify($adminpwd, $hashed_password))
		{
			$_SESSION['admin_id'] = $row['admin_id'];

			if(isset($_POST['remember']))
			{	
				setcookie('adunm', $admineld, time() + (60*60*24));
				setcookie('adpwd', $adminpwd, time() + (60*60*24));
			}

			header('location:adminpanel.php');
			exit();
		}
		else
		{
			
			echo "<script>alert('Incorrect password. Please try again.');</script>";
		}
	} 
	else 
	{
		echo "<script>alert('Invalid email or password');</script>";
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
	</style>
	</head>
	<body>
			<div class="c">
			<br><br>
				<h1 style="color:#581845">LOGIN </h1>
				<br>
				<form action="#" method="post">
					<input type="email" placeholder="enter email" name="emailid" value="<?php echo $ck_unm; ?>">
					<br><br>
					<input type="password" placeholder="password" name="adminpwd" value="<?php echo $ck_pwd; ?>">
					<br>
					
					<div style="text-align:left; width:80%; margin:0 auto;">
						<input type="checkbox" name="remember"> Remember me
						
					</div>

					<br>
					<input type="submit" value="login" name="login" style="background-color:#b08d57;color:white; padding: 4px 114px;border-radius:6px;" >
					
				</form>
			</div>
	</body>
</html>


