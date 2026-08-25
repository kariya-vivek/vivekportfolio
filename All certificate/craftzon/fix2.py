import os

user_dir = r"C:\xampp\htdocs\craftzon\user"
php_files = [f for f in os.listdir(user_dir) if f.endswith('.php')]

for f in php_files:
    file_path = os.path.join(user_dir, f)
    with open(file_path, 'r', encoding='utf-8', errors='ignore') as file:
        content = file.read()
        
    original = content
    
    # fix: echo '<img src="' . $row['image'] . '"' -> echo '<img src="../' . $row['image'] . '"'
    content = content.replace('src="\' . $row[\'image\']', 'src="../\' . $row[\'image\']')
    
    # fix: <img src="<?= $rowp['image'] ?>"
    content = content.replace('src="<?= $rowp[\'image\'] ?>"','src="../<?= $rowp[\'image\'] ?>"')
    
    # fix: <img src="<?= $row['image'] ?>"
    content = content.replace('src="<?= $row[\'image\'] ?>"','src="../<?= $row[\'image\'] ?>"')
    
    if content != original:
        with open(file_path, 'w', encoding='utf-8') as file:
            file.write(content)
            
print("Fixed remaining images.")
