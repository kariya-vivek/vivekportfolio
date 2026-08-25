import os
import glob
import re

user_dir = r"C:\xampp\htdocs\craftzon\user"
php_files = glob.glob(os.path.join(user_dir, "*.php"))

for file in php_files:
    with open(file, 'r', encoding='utf-8', errors='ignore') as f:
        content = f.read()
        
    modified = False

    # I accidentally removed `">` from the form tag. Let's find forms that end abruptly
    # Examples from log: 
    # style="display:none;    <input type="hidden" name="userid"
    # style="display:none;  <input type="hidden" name="userid"
    
    pattern = r'style="display:none;(\s*)<input type="hidden" name="userid"'
    if re.search(pattern, content):
        content = re.sub(pattern, r'style="display:none;">\1<input type="hidden" name="userid"', content)
        modified = True

    if modified:
        with open(file, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Fixed broken editProfileForm in {os.path.basename(file)}")

