import os

path = r"C:\xampp\htdocs\craftzon\user\view_ads.php"
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

if bad_block in content:
    content = content.replace(bad_block, good_block)
    
with open(path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Fixed view_ads.php accessibility")
