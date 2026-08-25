import os
import glob

user_dir = r"C:\xampp\htdocs\craftzon\user"
php_files = glob.glob(os.path.join(user_dir, "*.php"))

bad_block = """$us_profile = $_SESSION["users_id"];
$usid = $us_profile;
if ($us_profile == false) {
    header('location:logincraft.php');
    exit();
}"""
good_block = """$usid = isset($_SESSION["users_id"]) ? $_SESSION["users_id"] : 0;
$us_profile = $usid;"""

bad_block2 = """$us_profile = $_SESSION["users_id"] ?? '';
$usid = $us_profile;
if ($us_profile == false) {
    header('location:logincraft.php');
    exit();
}"""

for path in php_files:
    # Do NOT modify account-specific pages!
    if os.path.basename(path) in ['cart.php', 'orderform.php', 'myorders.php', 'update.php', 'store.php']:
        continue
        
    with open(path, 'r', encoding='utf-8', errors='ignore') as f:
        content = f.read()
        
    modified = False
    if bad_block in content:
        content = content.replace(bad_block, good_block)
        modified = True
    if bad_block2 in content:
        content = content.replace(bad_block2, good_block)
        modified = True
        
    if modified:
        with open(path, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Removed forced login from {os.path.basename(path)}")
