import os
import re

user_dir = r"C:\xampp\htdocs\craftzon\user"

user_php_files = [f for f in os.listdir(user_dir) if f.endswith('.php')]

for f in user_php_files:
    file_path = os.path.join(user_dir, f)
    with open(file_path, 'r', encoding='utf-8', errors='ignore') as file:
        content = file.read()
        
    original = content
    
    # Prepend ../ to dynamic database image paths
    # Match src="<?= $row['image'] ?>" or src="<?php echo $row['image']; ?>"
    # Or echo '<img src="' . $row['image'] . '"'
    
    # 1. echo '<img src="' . $row['image'] . '"' -> echo '<img src="../' . $row['image'] . '"'
    content = re.sub(r'src=["\']"\s*\.\s*\$row\[[\'"]image[\'"]\]', r'src="../" . $row[\'image\']', content)
    content = re.sub(r'src=["\']"\s*\.\s*\$rowp\[[\'"]image[\'"]\]', r'src="../" . $rowp[\'image\']', content)
    
    # 2. <img src="<?= $row['image'] ?>"> -> <img src="../<?= $row['image'] ?>">
    content = re.sub(r'src=["\']<\?=\s*\$row\[[\'"]image[\'"]\]\s*\?>["\']', r'src="../<?= $row[\'image\'] ?>"', content)
    content = re.sub(r'src=["\']<\?=\s*\$rowp\[[\'"]image[\'"]\]\s*\?>["\']', r'src="../<?= $rowp[\'image\'] ?>"', content)
    
    # 3. For profile images and shop images
    content = re.sub(r'src=["\']<\?=\s*\$profileImg\s*\?>["\']', r'src="../<?= $profileImg ?>"', content)
    
    # 4. In case of any other variables like $row['photo'], $row['shopimage']
    content = re.sub(r'src=["\']"\s*\.\s*\$row\[[\'"](photo|shopimage|profile_img)[\'"]\]', r'src="../" . $row[\'\1\']', content)
    
    # Let's also check background-image: url(...)
    # if it uses $row['image']
    content = re.sub(r'url\([\'"]?<\?=\s*\$row\[[\'"]image[\'"]\]\s*\?>[\'"]?\)', r'url(../<?= $row[\'image\'] ?>)', content)

    # Some images might already have ../../ so we should be careful, but we only ran this once.
    
    if content != original:
        with open(file_path, 'w', encoding='utf-8') as file:
            file.write(content)

print("Dynamic image paths in user panel updated.")
