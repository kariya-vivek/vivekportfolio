import re

with open('seller/create_craftzonstore.php', 'r', encoding='utf-8', errors='ignore') as f:
    text = f.read()

bad = '''$defaultImage = "craftzonstroreimage/default.jpg";'''
good = '''$defaultImage = "sellerlogo/default.jpg";'''

if bad in text:
    text = text.replace(bad, good)
    with open('seller/create_craftzonstore.php', 'w', encoding='utf-8') as f:
        f.write(text)
    print("Fixed default image path to sellerlogo")
else:
    print("Not found in create_craftzonstore.php")
