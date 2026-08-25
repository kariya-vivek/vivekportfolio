import os

path = r"C:\xampp\htdocs\craftzon\admin\adminlogin.php"

with open(path, 'r', encoding='utf-8') as f:
    content = f.read()

sql_old = """	$admineld = $_POST['emailid'];
	$adminpwd = $_POST['adminpwd'];
	
	$ses = "SELECT admin_id, name, password FROM admin_table WHERE emailid='$admineld'";
	$sel = mysqli_query($con, $ses);
	
	$total = mysqli_num_rows($sel);"""

sql_new = """	$admineld = $_POST['emailid'];
	$adminpwd = $_POST['adminpwd'];
	
	$stmt = mysqli_prepare($con, "SELECT admin_id, name, password FROM admin_table WHERE emailid=?");
	mysqli_stmt_bind_param($stmt, "s", $admineld);
	mysqli_stmt_execute($stmt);
	$sel = mysqli_stmt_get_result($stmt);
	
	$total = mysqli_num_rows($sel);"""

content = content.replace(sql_old, sql_new)

with open(path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Fixed adminlogin.php")
