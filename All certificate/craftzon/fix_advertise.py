with open('seller/seller_advertise.php', 'r', encoding='utf-8', errors='ignore') as f:
    text = f.read()

bad = '$target_dir = "advrtisephoto/";'
good = '$target_dir = "../advrtisephoto/";'

if bad in text:
    text = text.replace(bad, good)
    with open('seller/seller_advertise.php', 'w', encoding='utf-8') as f:
        f.write(text)
    print("Fixed seller_advertise.php")
else:
    print("Not found")
