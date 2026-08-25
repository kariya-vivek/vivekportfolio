with open('seller/editstroe.php', 'r', encoding='utf-8', errors='ignore') as f:
    text = f.read()

bad = '<img src="<?php echo $seller[\'shopimage\']; ?>" alt="Store Image" width="100"><br>'
good = '<img src="../<?php echo $seller[\'shopimage\']; ?>" alt="Store Image" width="100"><br>'

if bad in text:
    text = text.replace(bad, good)
    with open('seller/editstroe.php', 'w', encoding='utf-8') as f:
        f.write(text)
    print("Fixed editstroe.php image path")
else:
    print("Not found")
