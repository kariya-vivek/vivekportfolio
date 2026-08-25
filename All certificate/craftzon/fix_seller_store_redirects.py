import os
import glob

seller_dir = r"C:\xampp\htdocs\craftzon\seller"
for file in glob.glob(os.path.join(seller_dir, "*.php")):
    with open(file, 'r', encoding='utf-8') as f:
        content = f.read()
    
    modified = False
    
    # Fix JS redirects
    if "'store.php" in content:
        content = content.replace("'store.php", "'../user/store.php")
        modified = True
    if '"store.php"' in content:
        content = content.replace('"store.php"', '"../user/store.php"')
        modified = True
    
    if modified:
        with open(file, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Fixed store.php redirect in {os.path.basename(file)}")
