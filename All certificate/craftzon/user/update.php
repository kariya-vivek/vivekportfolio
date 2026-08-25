<?php

session_start();



$con = mysqli_connect("localhost", "root", "", "craftzon");

$updid = $_POST['userid'] ?? 0;

	if (isset($_POST['userid']))

	{

		

		$que="select * from craftus_reg where u_id='$updid'";

		$sel=mysqli_query($con,$que);

		$res=mysqli_fetch_array($sel);

		if(isset($_POST['updatenb']))

		{

			$updnm=$_POST['upnm'];

			$updem=$_POST['upemid'];

			$updmb=$_POST['upmono'];

			$currentImage = $res['profile_img']; // assuming 'profile_img' is the DB column



			// Check if a new image was uploaded

			if (isset($_FILES['profileimg']) && $_FILES['profileimg']['error'] == 0) {

				$imgName = $_FILES['profileimg']['name'];

				$tmpName = $_FILES['profileimg']['tmp_name'];

				$ext = pathinfo($imgName, PATHINFO_EXTENSION);



				$newImageName = uniqid('user_') . '.' . $ext;

				$uploadPath = '../userprofileimage/' . $newImageName;



				if (move_uploaded_file($tmpName, $uploadPath)) {

					$currentImage = 'userprofileimage/' . $newImageName; // store db relative path

				}

			}



			$up = "UPDATE craftus_reg SET uname='$updnm', email='$updem', mobile_no='$updmb', profile_img='$currentImage' WHERE u_id='$updid'";

			$upd = mysqli_query($con, $up);

		

			if($upd)

			{

				$sellerUpdate = "UPDATE seller SET selleremailid='$updem' WHERE sellernm='$updnm'";

				$sellerUpd = mysqli_query($con, $sellerUpdate);

				echo "update succesesfull";

				header("Location: crafthome.php");

				exit();

			}

			else

			{

				echo "error";

			}

		}

	}

	else

	{

		echo "User ID not provided.";

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

			width: 360px;

			height: auto;

			background-color: #f5deb3;

			border-radius: 24px;

			display: flex;

			flex-direction: column;

			align-items: center;

			justify-content: center;

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

	



    .current-image img {

        width: 120px;

        height: 120px;

        border-radius: 50%;

        object-fit: cover;

        border: 2px solid #581845;

    }

	input[type="file"] {

    font-size: 15px;

    border-radius: 6px;

    padding: 10px;

    border: 1px solid #ccc;

    background-color: #fff;

    width: 85%;

    box-sizing: border-box;

    font-family: Arial, sans-serif;

    color: #333;

    height: 40px;

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

				<h1 style="color:#581845">update profile</h1>

				<br>

				<form action="" method="post" enctype="multipart/form-data">

				<input type="hidden" name="userid" value="<?php echo $res['u_id']; ?>">



					

					<input type="text" name="upnm" placeholder="enter your name" value="<?php echo $res['uname'] ?>" readonly>

					<br><br>

					<input type="text" name="upemid" placeholder="enter your email id" value="<?php echo $res['email']?>">

					<br><br>

					<input type="text" name="upmono" placeholder="enter your mobile no" value="<?php echo $res['mobile_no']?>" maxlength="10">

					<br><br>

					<input type="file" name="profileimg" id="profileimg" accept="image/*">



<div class="current-image">

	<strong>Current Image:</strong><br>

	<img src="../<?php echo htmlspecialchars($res['profile_img']); ?>" alt="Current Profile Image">

</div>

					<input type="submit" value="update" name="updatenb" style="background-color:#b08d57;color:white; padding: 4px 114px;border-radius:6px;" >

					<br><br>

				</form>

			</div>

		</center>

	</body>

</html>