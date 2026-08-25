import os

user_dir = r"C:\xampp\htdocs\craftzon\user"
crafthome_path = os.path.join(user_dir, 'crafthome.php')
with open(crafthome_path, 'r', encoding='utf-8', errors='ignore') as f:
    content = f.read()

# Replace session login redirect
content = content.replace(
    'if ($us_profile == false) {\n    header(\'location:logincraft.php\');\n    exit();\n}',
    '$usid = isset($_SESSION["users_id"]) ? $_SESSION["users_id"] : 0;'
)

# For the AJAX wishlist toggle to force redirect if not logged in
content = content.replace(
"""if (isset($_POST['wishlist_product_id'])) {""",
"""if (isset($_POST['wishlist_product_id'])) {
    if ($usid == 0) { echo 'not_logged_in'; exit(); }"""
)

# In JS for wishlist toggle:
content = content.replace(
"""if(response === 'added') icon.css("color", "red");""",
"""if(response === 'not_logged_in') { window.location.href = 'logincraft.php'; return; }
            if(response === 'added') icon.css("color", "red");"""
)

with open(crafthome_path, 'w', encoding='utf-8', errors='ignore') as f:
    f.write(content)

# cart.php
cart_path = os.path.join(user_dir, 'cart.php')
with open(cart_path, 'r', encoding='utf-8') as f:
    cart_content = f.read()
if "header('Location: logincraft.php')" not in cart_content:
    cart_content = cart_content.replace("$uid = $_POST['uid'];", "$uid = $_POST['uid'] ?? 0;\nif (empty($uid) || $uid == 0) { header('Location: logincraft.php'); exit; }")
    with open(cart_path, 'w', encoding='utf-8') as f:
        f.write(cart_content)

# wishlist.php
wishlist_path = os.path.join(user_dir, 'wishlist.php')
with open(wishlist_path, 'r', encoding='utf-8') as f:
    wishlist_content = f.read()
if "header('Location: logincraft.php')" not in wishlist_content:
    wishlist_content = wishlist_content.replace("$usid = $_SESSION['users_id'] ?? 0;", "$usid = $_SESSION['users_id'] ?? 0;\nif ($usid == 0) { header('Location: logincraft.php'); exit; }")
    with open(wishlist_path, 'w', encoding='utf-8') as f:
        f.write(wishlist_content)

print("Updates applied.")
