import os
path = r'C:\xampp\htdocs\craftzon\user\crafthome.php'
with open(path, 'r', encoding='utf-8') as f:
    content = f.read()

content = content.replace('$us_profile = $_SESSION["users_id"];', '')
content = content.replace('$usid = $us_profile;', '')

with open(path, 'w', encoding='utf-8') as f:
    f.write(content)
