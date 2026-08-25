import os

seller_dir = r"C:\xampp\htdocs\craftzon\seller"

# 1. create_craftzonstore.php (IDOR and SQLi Fix)
cc_path = os.path.join(seller_dir, 'create_craftzonstore.php')
if os.path.exists(cc_path):
    with open(cc_path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Enforce Session Check
    session_check = """<?php
	session_start();
	if(empty($_SESSION['users_id'])) { die('Unauthorized'); }
	$seller_id = $_SESSION['users_id'];
	$con = mysqli_connect("localhost", "root", "", "craftzon");
"""
    # Replace the top logic
    old_top = """<?php
	$con = mysqli_connect("localhost", "root", "", "craftzon");
	$seller_id=$_POST['userid'];
	if (isset($_POST['userid']))
	{"""
    content = content.replace(old_top, session_check + "\n\t{")
    
    # Prepared statement for insert
    old_sql = """$inque="insert into seller (sellerid,storenm,sellernm,sellemail,gstin,storedesc,sellerimage) values('$seller_id','$stornm','$selnm','$sellemid','$storegstno','$storedesc','$defaultImage')";
			if(mysqli_query($con,$inque))"""
    new_sql = """$stmt = mysqli_prepare($con, "INSERT INTO seller (sellerid,storenm,sellernm,sellemail,gstin,storedesc,sellerimage) VALUES (?, ?, ?, ?, ?, ?, ?)");
			mysqli_stmt_bind_param($stmt, "issssss", $seller_id, $stornm, $selnm, $sellemid, $storegstno, $storedesc, $defaultImage);
			if(mysqli_stmt_execute($stmt))"""
    content = content.replace(old_sql, new_sql)
    
    with open(cc_path, 'w', encoding='utf-8') as f:
        f.write(content)

# 2. editstroe.php (IDOR and SQLi Fix)
es_path = os.path.join(seller_dir, 'editstroe.php')
if os.path.exists(es_path):
    with open(es_path, 'r', encoding='utf-8') as f:
        content = f.read()

    # Enforce Session Check
    old_seller_check = """$seller_id = $_POST['sellerid'] ?? '';

if (!$seller_id) {
    die("Seller ID not provided.");
}"""
    new_seller_check = """session_start();
if(empty($_SESSION['users_id'])) { die('Unauthorized'); }
$seller_id = $_SESSION['users_id'];"""
    content = content.replace(old_seller_check, new_seller_check)

    # Prepared statement for UPDATE
    old_update = """    $query = "UPDATE seller 
              SET storenm = '$store_name', sellernm = '$owner_name', sellemail = '$owner_email', gstin = '$gstin', storedesc = '$store_desc', sellerimage = '$image_path'
              WHERE sellerid = '$seller_id'";

    if (mysqli_query($con, $query)) {"""
    new_update = """    $stmt = mysqli_prepare($con, "UPDATE seller SET storenm = ?, sellernm = ?, sellemail = ?, gstin = ?, storedesc = ?, sellerimage = ? WHERE sellerid = ?");
    mysqli_stmt_bind_param($stmt, "ssssssi", $store_name, $owner_name, $owner_email, $gstin, $store_desc, $image_path, $seller_id);
    if (mysqli_stmt_execute($stmt)) {"""
    content = content.replace(old_update, new_update)

    with open(es_path, 'w', encoding='utf-8') as f:
        f.write(content)

print("Seller IDOR and SQLi fixes applied.")
