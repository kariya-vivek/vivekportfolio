import os
import re

root_dir = r"C:\xampp\htdocs\craftzon"

# All php files in root
root_php_files = [f for f in os.listdir(root_dir) if f.endswith('.php') and os.path.isfile(os.path.join(root_dir, f))]

for f in root_php_files:
    file_path = os.path.join(root_dir, f)
    with open(file_path, 'r', encoding='utf-8', errors='ignore') as file:
        content = file.read()
        
    original = content
    # Update links to admin files
    content = re.sub(r"['\"]adminlogin\.php['\"]", r"'admin/adminlogin.php'", content)
    
    # Update links to seller files
    content = re.sub(r"['\"]logincraft\.php['\"]", r"'seller/logincraft.php'", content)
    content = re.sub(r"['\"]selleradminpanel\.php['\"]", r"'seller/selleradminpanel.php'", content)
    content = re.sub(r"['\"]create_craftzonstore\.php['\"]", r"'seller/create_craftzonstore.php'", content)
    
    if content != original:
        with open(file_path, 'w', encoding='utf-8') as file:
            file.write(content)
            
print("Root files updated.")
