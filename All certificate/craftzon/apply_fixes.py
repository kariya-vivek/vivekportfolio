import os
import re

user_dir = r"C:\xampp\htdocs\craftzon\user"

# 1. cart.php
cart_path = os.path.join(user_dir, 'cart.php')
with open(cart_path, 'r', encoding='utf-8') as f:
    content = f.read()
content = content.replace("$uid = $_POST['uid'] ?? 0;", "$uid = isset($_POST['uid']) ? intval($_POST['uid']) : 0;")
with open(cart_path, 'w', encoding='utf-8') as f:
    f.write(content)

# 2. orderform.php
order_path = os.path.join(user_dir, 'orderform.php')
with open(order_path, 'r', encoding='utf-8') as f:
    content = f.read()
# Let's cast values to safe int/float where possible
content = content.replace("$uid = $_POST['uid'];", "$uid = isset($_POST['uid']) ? intval($_POST['uid']) : 0;")
content = content.replace("$pid = $_POST['pid'];", "$pid = isset($_POST['pid']) ? intval($_POST['pid']) : 0;")
with open(order_path, 'w', encoding='utf-8') as f:
    f.write(content)

# 3. crafthome.php responsive fix
crafthome_path = os.path.join(user_dir, 'crafthome.php')
with open(crafthome_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Fix the CSS for slider container to wrap properly on mobile
css_fix = """
@media (max-width: 400px) {
    .slider-container {
        flex-wrap: wrap !important;
        justify-content: center !important;
        overflow-x: hidden !important;
    }
    .slide {
        min-width: 30% !important;
        margin: 5px !important;
    }
}
</style>
"""
content = content.replace("</style>", css_fix)
with open(crafthome_path, 'w', encoding='utf-8') as f:
    f.write(content)

# 4. admin/adminpanel.php delete check
admin_path = r"C:\xampp\htdocs\craftzon\admin\adminpanel.php"
if os.path.exists(admin_path):
    with open(admin_path, 'r', encoding='utf-8') as f:
        content = f.read()
    # Let's cast delete_id to intval to prevent SQLi.
    content = content.replace("$_GET['delete_id']", "intval($_GET['delete_id'])")
    with open(admin_path, 'w', encoding='utf-8') as f:
        f.write(content)

print("Fixes applied successfully.")
