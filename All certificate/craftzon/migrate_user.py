import os
import re
import shutil

root_dir = r"C:\xampp\htdocs\craftzon"
user_dir = os.path.join(root_dir, "user")

if not os.path.exists(user_dir):
    os.makedirs(user_dir)

# Move all php files to user/ except index.php
root_php_files = [f for f in os.listdir(root_dir) if f.endswith('.php') and os.path.isfile(os.path.join(root_dir, f))]

image_folders = ['advrtisephoto', 'craftdatabase', 'crafter_storie', 'craftzonstroreimage', 'retundbimage', 'uploads', 'userprofileimage']
images = [f for f in os.listdir(root_dir) if f.endswith('.jpg') or f.endswith('.png') or f.endswith('.jpeg')]

def update_user_paths(content):
    # Update links to admin and seller folders
    content = re.sub(r"['\"]admin/([a-zA-Z0-9_]+\.php)['\"]", r"'../admin/\1'", content)
    content = re.sub(r"['\"]seller/([a-zA-Z0-9_]+\.php)['\"]", r"'../seller/\1'", content)
    
    # Update image/folder paths
    for folder in image_folders:
        content = re.sub(r"['\"](" + folder + r")/", r"'../\1/", content)
    for img in images:
        content = re.sub(r"['\"](" + img + r")['\"]", r"'../\1'", content)
        
    return content

for f in root_php_files:
    if f == 'index.php': continue
    src_path = os.path.join(root_dir, f)
    dst_path = os.path.join(user_dir, f)
    
    with open(src_path, 'r', encoding='utf-8', errors='ignore') as file:
        content = file.read()
        
    content = update_user_paths(content)
    
    with open(dst_path, 'w', encoding='utf-8') as file:
        file.write(content)
        
    os.remove(src_path)

# Create index.php redirect
with open(os.path.join(root_dir, "index.php"), 'w', encoding='utf-8') as file:
    file.write("<?php\nheader('Location: user/crafthome.php');\nexit();\n?>")

print("User files moved to user/ folder and paths updated.")
