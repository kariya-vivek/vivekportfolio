import re

with open('seller/create_craftzonstore.php', 'r', encoding='utf-8', errors='ignore') as f:
    text = f.read()

bad = '''<input type="text" id="store_gstno" name="gstin_no" placeholder="E.g., 29ABCDE1234F1Z5" maxlength="15" required pattern="\\d{2}[A-Z]{5}\\d{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}">'''
good = '''<input type="text" id="store_gstno" name="gstin_no" placeholder="E.g., 29ABCDE1234F1Z5" maxlength="15">'''

if bad in text:
    text = text.replace(bad, good)
    with open('seller/create_craftzonstore.php', 'w', encoding='utf-8') as f:
        f.write(text)
    print("Fixed GST input")
else:
    print("Not found")
