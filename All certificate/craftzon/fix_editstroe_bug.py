import re

with open('seller/editstroe.php', 'r', encoding='utf-8', errors='ignore') as f:
    text = f.read()

bad = '''if (isset($_FILES['shop_image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {'''
good = '''if (isset($_FILES['shop_image']) && $_FILES['shop_image']['error'] === UPLOAD_ERR_OK) {'''

if bad in text:
    text = text.replace(bad, good)
    with open('seller/editstroe.php', 'w', encoding='utf-8') as f:
        f.write(text)
    print("Fixed editstroe.php FILES bug")
else:
    print("Not found in editstroe.php")
