import os
import glob
import re

user_dir = r"C:\xampp\htdocs\craftzon\user"
php_files = glob.glob(os.path.join(user_dir, "*.php"))

for file in php_files:
    with open(file, 'r', encoding='utf-8', errors='ignore') as f:
        content = f.read()
        
    modified = False

    logout_html = """<h4 style="cursor:pointer;color:#581845" onclick="window.location.href='logout.php'">Logout</h4>"""
    logout_php = """<?php if(isset($usid) && $usid > 0) { ?>
<h4 style="cursor:pointer;color:#581845" onclick="window.location.href='logout.php'">Logout</h4>
<?php } else { ?>
<h4 style="cursor:pointer;color:#581845" onclick="window.location.href='logincraft.php'">Login</h4>
<?php } ?>"""

    if logout_html in content:
        content = content.replace(logout_html, logout_php)
        modified = True

    if modified:
        with open(file, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Fixed Logout button in {os.path.basename(file)}")

