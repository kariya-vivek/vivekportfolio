import re

with open('seller/create_craftzonstore.php', 'r', encoding='utf-8', errors='ignore') as f:
    text = f.read()

bad = '''$filenm = $_FILES['store_image']['name'];
				$tempnm = $_FILES['store_image']['tmp_name'];
				$uploadPath = '../craftzonstroreimage/' . $filenm;
				$folder = 'craftzonstroreimage/' . $filenm;'''

good = '''$filenm = time() . '_' . $_FILES['store_image']['name'];
                $filenm = str_replace(' ', '_', $filenm);
				$tempnm = $_FILES['store_image']['tmp_name'];
				$uploadPath = '../sellerlogo/' . $filenm;
				$folder = 'sellerlogo/' . $filenm;'''

if bad in text:
    text = text.replace(bad, good)
    with open('seller/create_craftzonstore.php', 'w', encoding='utf-8') as f:
        f.write(text)
    print("Fixed create_craftzonstore.php upload path to sellerlogo")
else:
    print("Not found in create_craftzonstore.php")
