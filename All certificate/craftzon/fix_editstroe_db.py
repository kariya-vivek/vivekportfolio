import re

with open('seller/editstroe.php', 'r', encoding='utf-8', errors='ignore') as f:
    text = f.read()

bad = '''$target_file = 'craftzonstroreimage/' . $safe_filename;
            
            if (move_uploaded_file($_FILES['shop_image']['tmp_name'], $target_file)) {
                $shop_image = $target_file;
            }'''

good = '''$db_file = 'craftzonstroreimage/' . $safe_filename;
            $uploadPath = '../craftzonstroreimage/' . $safe_filename;
            
            if (move_uploaded_file($_FILES['shop_image']['tmp_name'], $uploadPath)) {
                $shop_image = $db_file;
            }'''

if bad in text:
    text = text.replace(bad, good)
    with open('seller/editstroe.php', 'w', encoding='utf-8') as f:
        f.write(text)
    print("Fixed editstroe.php upload path")
else:
    print("Not found in editstroe.php")
