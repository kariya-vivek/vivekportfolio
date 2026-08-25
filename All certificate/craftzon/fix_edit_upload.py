import re

with open('seller/editstroe.php', 'r', encoding='utf-8', errors='ignore') as f:
    text = f.read()

bad = '''$db_file = 'craftzonstroreimage/' . $safe_filename;
              $uploadPath = '../craftzonstroreimage/' . $safe_filename;'''

good = '''$db_file = 'sellerlogo/' . $safe_filename;
              $uploadPath = '../sellerlogo/' . $safe_filename;'''

if bad in text:
    text = text.replace(bad, good)
    with open('seller/editstroe.php', 'w', encoding='utf-8') as f:
        f.write(text)
    print("Fixed editstroe.php upload path to sellerlogo")
else:
    print("Not found in editstroe.php")
