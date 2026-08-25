import os
import glob
import re

user_dir = r"C:\xampp\htdocs\craftzon\user"
php_files = glob.glob(os.path.join(user_dir, "*.php"))

# 1. Fix abouuspage.html link -> abouuspage.php
for file in php_files:
    with open(file, 'r', encoding='utf-8', errors='ignore') as f:
        content = f.read()
    if 'abouuspage.html' in content:
        content = content.replace('abouuspage.html', 'abouuspage.php')
        with open(file, 'w', encoding='utf-8') as f:
            f.write(content)

# 2. Fix the login redirect in informational pages
info_pages = ['abouuspage.php', 'viewcraftstory.php']

for page in info_pages:
    path = os.path.join(user_dir, page)
    if os.path.exists(path):
        with open(path, 'r', encoding='utf-8') as f:
            content = f.read()
        
        bad_block = """$us_profile = $_SESSION["users_id"];
$usid = $us_profile;
if ($us_profile == false) {
    header('location:logincraft.php');
    exit();
}"""
        good_block = """$usid = isset($_SESSION["users_id"]) ? $_SESSION["users_id"] : 0;
$us_profile = $usid;"""
        
        # Another variation
        bad_block_2 = """$us_profile = $_SESSION["users_id"] ?? '';
$usid = $us_profile;
if ($us_profile == false) {
    header('location:logincraft.php');
    exit();
}"""
        if bad_block in content:
            content = content.replace(bad_block, good_block)
        elif bad_block_2 in content:
            content = content.replace(bad_block_2, good_block)
            
        with open(path, 'w', encoding='utf-8') as f:
            f.write(content)

print("Fixed About Us and Craft Story accessibility")
