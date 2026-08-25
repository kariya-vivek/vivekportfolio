import re

with open('seller/seller_advertise.php', 'r', encoding='utf-8', errors='ignore') as f:
    text = f.read()

# I need to use regex to replace the block to avoid exact whitespace/encoding issues
pattern = r'\$image_path\s*=\s*NULL;[\s\n]*if\s*\(\!empty\(\$_FILES\[\'image\'\]\[\'name\'\]\)\)\s*\{.*?\n\}'

replacement = '''$image_path = isset($_POST['existing_image']) && !empty($_POST['existing_image']) ? $_POST['existing_image'] : NULL;
if (!empty($_FILES['image']['name'])) {
    $image_name = time() . '_' . basename($_FILES['image']['name']);
    $target_dir = "../advrtisephoto/";
    $target_path = $target_dir . $image_name;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
        $image_path = "advrtisephoto/" . $image_name;
    }
}'''

if re.search(pattern, text, re.DOTALL):
    text = re.sub(pattern, replacement, text, flags=re.DOTALL)
    with open('seller/seller_advertise.php', 'w', encoding='utf-8') as f:
        f.write(text)
    print("Fixed seller_advertise.php PHP logic")
else:
    print("Pattern not found!")

