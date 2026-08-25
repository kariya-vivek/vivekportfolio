<?php
	
	$con=mysqli_connect("localhost","root","","craftzon");
	$keyword=$_POST['keyword'];
	$del="delete from email_otp where emailid='$keyword'";
	
	mysqli_query($con,$del);
	
	$otp=rand(1000,9999);
	$to = $keyword;
	$subject="otp verification";
	$message="your otp is".$otp;
	$headers="From:your_email@gmail.com";
	
	if(mail($to,$subject,$message,$headers))
	{
		echo "mail sent successfully";
	}
	else
	{
		echo "mail faild";
	}
	$ins="insert into email_otp (emailid,otp) values('$keyword','$otp')";
	mysqli_query($con,$ins);
?>