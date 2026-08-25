with open('seller/seller_advertise.php', 'r', encoding='utf-8', errors='ignore') as f:
    text = f.read()

# Current:
# $target_dir = "../advrtisephoto/";
# $target_path = $target_dir . $image_name;
# if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
#     $image_path = $target_path; // save full relative path
# }

# We want $image_path to be "advrtisephoto/" . $image_name
bad = '$image_path = $target_path;'
good = '$image_path = "advrtisephoto/" . $image_name;'

if bad in text:
    text = text.replace(bad, good)
    with open('seller/seller_advertise.php', 'w', encoding='utf-8') as f:
        f.write(text)
    print("Fixed seller_advertise.php image_path")
else:
    print("Not found")
