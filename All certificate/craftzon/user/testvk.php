<?php
$plain_password = "vivek@123";  // your static password
$hashed = password_hash($plain_password, PASSWORD_DEFAULT);
echo $hashed;
echo "welcome admin"
?>
