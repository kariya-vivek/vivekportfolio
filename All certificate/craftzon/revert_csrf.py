import os
import re

# Remove csrf.php
csrf_file = r'C:\xampp\htdocs\craftzon\user\csrf.php'
if os.path.exists(csrf_file):
    os.remove(csrf_file)

# Remove CSRF injections from all PHP files in user, seller, admin
for root, dirs, files in os.walk(r'C:\xampp\htdocs\craftzon'):
    for file in files:
        if file.endswith('.php'):
            path = os.path.join(root, file)
            with open(path, 'r', encoding='utf-8', errors='ignore') as f:
                content = f.read()
            
            modified = False
            if 'require_once "../user/csrf.php";' in content:
                content = content.replace('require_once "../user/csrf.php";', '')
                modified = True
            if "require_once '../user/csrf.php';" in content:
                content = content.replace("require_once '../user/csrf.php';", '')
                modified = True
            if "require_once 'csrf.php';" in content:
                content = content.replace("require_once 'csrf.php';", '')
                modified = True
            if 'verify_csrf();' in content:
                content = content.replace('verify_csrf();', '')
                modified = True
            if 'name="csrf_token"' in content:
                content = re.sub(r'<input type="hidden" name="csrf_token".*?>\n?', '', content)
                modified = True
                
            if modified:
                with open(path, 'w', encoding='utf-8') as f:
                    f.write(content)
                print(f'Reverted CSRF in {path}')
