import os
import glob
import re

user_dir = r"C:\xampp\htdocs\craftzon\user"
php_files = glob.glob(os.path.join(user_dir, "*.php"))

js_check = "if(<?php echo isset($_SESSION['users_id']) ? $_SESSION['users_id'] : 0; ?> == 0) { Swal.fire({title: 'Login Required', text: 'Please login first!', icon: 'warning', showCancelButton: true, confirmButtonText: 'Login Now'}).then((result) => { if(result.isConfirmed) { window.location.href = 'logincraft.php'; } }); } else { "

for file in php_files:
    with open(file, 'r', encoding='utf-8', errors='ignore') as f:
        original_content = f.read()
        
    content = original_content

    # Fix the missing popup on the top menu "My Orders" and "Contact Us"
    if "onclick=\"document.getElementById('ordersForm').submit();\"" in content:
        content = content.replace(
            "onclick=\"document.getElementById('ordersForm').submit();\"",
            f"onclick=\"{js_check}document.getElementById('ordersForm').submit(); }}\""
        )

    if "onclick=\"document.getElementById('contactForm').submit();\"" in content:
        content = content.replace(
            "onclick=\"document.getElementById('contactForm').submit();\"",
            f"onclick=\"{js_check}document.getElementById('contactForm').submit(); }}\""
        )
        
    # Fix the Triple Line menu crashing for Guest users
    good_img_fetch = """$con = mysqli_connect("localhost", "root", "", "craftzon");
                        $profileImg = 'userprofileimage/default.png';
                        if ($usid > 0) {
      						$sel1 = "SELECT uname, profile_img FROM craftus_reg WHERE u_id = $usid";
      						$select1 = mysqli_query($con, $sel1);
      						if($row1 = mysqli_fetch_array($select1)) {
        						$profileImg = $row1['profile_img'] ? $row1['profile_img'] : 'userprofileimage/default.png';
                            }
                        }"""
    
    content = re.sub(r'\$con = mysqli_connect\("localhost", "root", "", "craftzon"\);\s*\$sel1 = "SELECT uname, profile_img FROM craftus_reg WHERE u_id = \$usid";\s*\$select1 = mysqli_query\(\$con, \$sel1\);\s*\$row1 = mysqli_fetch_array\(\$select1\);\s*\$profileImg = \$row1\[\'profile_img\'\];', good_img_fetch, content)
    
    good_seller_fetch = """$con = mysqli_connect("localhost","root","","craftzon");
                            $uname = 'Guest';
                            if ($usid > 0) {
      							$sel1="select uname from craftus_reg where u_id=$usid";
      							$select1=mysqli_query($con,$sel1);
      							if($row1=mysqli_fetch_array($select1)) {
                                    $uname = $row1['uname'];
                                }
                            }
							$sel = "SELECT * FROM seller WHERE sellernm = '" . mysqli_real_escape_string($con, $uname) . "'";"""
                            
    content = re.sub(r'\$con = mysqli_connect\("localhost","root","","craftzon"\);\s*\$sel1="select uname\s*from craftus_reg where u_id=\$usid";\s*\$select1=mysqli_query\(\$con,\$sel1\);\s*\$row1=mysqli_fetch_array\(\$select1\);\s*\$sel = "SELECT \* FROM seller WHERE sellernm = \'" \. \$row1\[\'uname\'\] \. "\'";', good_seller_fetch, content)

    # Some files use different spacing for the seller fetch
    content = re.sub(r'\$con = mysqli_connect\("localhost","root","","craftzon"\);\s*\$sel1="select uname  from craftus_reg where u_id=\$usid";\s*\$select1=mysqli_query\(\$con,\$sel1\);\s*\$row1=mysqli_fetch_array\(\$select1\);\s*\$sel = "SELECT \* FROM seller WHERE sellernm = \'" \. \$row1\[\'uname\'\] \. "\'";', good_seller_fetch, content)

    if content != original_content:
        with open(file, 'w', encoding='utf-8') as f2:
            f2.write(content)
        print(f"Fixed sidebar logic in {os.path.basename(file)}")
