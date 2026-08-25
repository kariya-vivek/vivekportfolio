import os

def fix_file(rel_path, replacements):
    path = os.path.join(r"C:\xampp\htdocs\craftzon\user", rel_path)
    if os.path.exists(path):
        with open(path, 'r', encoding='utf-8') as f:
            content = f.read()
        for s, r in replacements:
            content = content.replace(s, r)
        with open(path, 'w', encoding='utf-8') as f:
            f.write(content)

fix_file("registcraft.php", [
    ("'../userprofileimage/default.png\";", "'../userprofileimage/default.png';"),
    ("'../userprofileimage/\".$_FILES", "'../userprofileimage/' . $_FILES")
])

fix_file("returnorder.php", [
    ("'../retundbimage/\".$filenm;", "'../retundbimage/' . $filenm;")
])

fix_file("update.php", [
    ("'../userprofileimage/\" . $newImageName;", "'../userprofileimage/' . $newImageName;")
])

fix_file("wishlist.php", [
    ("onclick=\"window.location.href=\\'online_view.php?product_id='.$productId.'&userid='.$usid.''\">", "onclick=\"window.location.href='online_view.php?product_id=' . $productId . '&userid=' . $usid . ''\">")
])

print("Fixed syntax errors.")
