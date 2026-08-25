<?php
	$con=mysqli_connect("localhost","root","","craftzon");
	$keyword=$_POST['keyword'];
	$otp=$_POST['otp'];
	$sql="select * from email_otp where emailid='$keyword' and otp='$otp'";
	$qu=mysqli_query($con,$sql);
	$data=mysqli_fetch_row($qu);
	if(empty($data))
	{
		echo json_encode(0);
		exit;
	}
	else
	{
		echo json_encode(1);
		exit;
	}
?>