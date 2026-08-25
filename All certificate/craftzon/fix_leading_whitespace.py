import os
import glob
import re

user_dir = r"C:\xampp\htdocs\craftzon\user"
php_files = glob.glob(os.path.join(user_dir, "*.php"))

for file in php_files:
    with open(file, 'rb') as f:
        content = f.read()
    
    # Check if starts with <?php but has whitespace before it
    # We can just decode, strip leading whitespace before <?php
    text = content.decode('utf-8', errors='ignore')
    
    # Find first <?php
    idx = text.find('<?php')
    if idx > 0:
        # Check if everything before <?php is just whitespace
        if text[:idx].strip() == '':
            text = text[idx:]
            with open(file, 'w', encoding='utf-8') as f:
                f.write(text)
            print(f"Fixed leading whitespace in {os.path.basename(file)}")
            
    # Also fix update.php specifically to avoid warning on line 5
    if os.path.basename(file) == 'update.php':
        if "$updid=$_POST['userid'];" in text:
            text = text.replace("$updid=$_POST['userid'];", "$updid = $_POST['userid'] ?? 0;")
            with open(file, 'w', encoding='utf-8') as f:
                f.write(text)
            print("Fixed update.php POST warning")

