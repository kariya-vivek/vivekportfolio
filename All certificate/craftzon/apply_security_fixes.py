import os
import re

user_dir = r"C:\xampp\htdocs\craftzon\user"

# 1. Create CSRF Helper
csrf_code = """<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
function verify_csrf() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            die('CSRF token validation failed. Unauthorized request.');
        }
    }
}
?>"""
with open(os.path.join(user_dir, 'csrf.php'), 'w', encoding='utf-8') as f:
    f.write(csrf_code)

# 2. Fix cart.php (IDOR & CSRF)
cart_path = os.path.join(user_dir, 'cart.php')
if os.path.exists(cart_path):
    with open(cart_path, 'r', encoding='utf-8') as f:
        content = f.read()
    if "csrf.php" not in content:
        content = content.replace("<?php", "<?php\nrequire_once 'csrf.php';\nverify_csrf();\n")
    # Fix IDOR: Don't trust POST uid
    content = re.sub(r'\$uid\s*=\s*isset\(\$_POST\[\'uid\'\]\)\s*\?\s*intval\(\$_POST\[\'uid\'\]\)\s*:\s*0;', '$uid = $_SESSION[\'users_id\'] ?? 0;', content)
    with open(cart_path, 'w', encoding='utf-8') as f:
        f.write(content)

# 3. Fix orderform.php (IDOR & CSRF)
order_path = os.path.join(user_dir, 'orderform.php')
if os.path.exists(order_path):
    with open(order_path, 'r', encoding='utf-8') as f:
        content = f.read()
    if "csrf.php" not in content:
        content = content.replace("<?php", "<?php\nrequire_once 'csrf.php';\nverify_csrf();\n")
    content = re.sub(r'\$uid\s*=\s*isset\(\$_POST\[\'uid\'\]\)\s*\?\s*intval\(\$_POST\[\'uid\'\]\)\s*:\s*0;', '$uid = $_SESSION[\'users_id\'] ?? 0;', content)
    with open(order_path, 'w', encoding='utf-8') as f:
        f.write(content)

# 4. Inject CSRF Token into forms in crafthome.php
ch_path = os.path.join(user_dir, 'crafthome.php')
if os.path.exists(ch_path):
    with open(ch_path, 'r', encoding='utf-8') as f:
        content = f.read()
    # Add csrf.php include at the top
    if "csrf.php" not in content:
        content = content.replace("<?php", "<?php\nrequire_once 'csrf.php';\n")
    
    # Inject hidden token into forms
    token_input = '<input type="hidden" name="csrf_token" value="<?php echo $_SESSION[\'csrf_token\'] ?? \'\'; ?>">'
    content = content.replace('<form action="wishlist.php" method="POST" style="display:inline;">', f'<form action="wishlist.php" method="POST" style="display:inline;">\n{token_input}')
    content = content.replace('<form id="cartForm" action="cart.php" method="POST" style="display:inline;">', f'<form id="cartForm" action="cart.php" method="POST" style="display:inline;">\n{token_input}')
    content = content.replace('<form id="editProfileForm" action="update.php" method="POST" style="display:none;">', f'<form id="editProfileForm" action="update.php" method="POST" style="display:none;">\n{token_input}')
    
    with open(ch_path, 'w', encoding='utf-8') as f:
        f.write(content)

# 5. Inject CSRF Token into forms in online_view.php
ov_path = os.path.join(user_dir, 'online_view.php')
if os.path.exists(ov_path):
    with open(ov_path, 'r', encoding='utf-8') as f:
        content = f.read()
    if "csrf.php" not in content:
        content = content.replace("<?php", "<?php\nrequire_once 'csrf.php';\n")
    
    token_input = '<input type="hidden" name="csrf_token" value="<?php echo $_SESSION[\'csrf_token\'] ?? \'\'; ?>">'
    content = content.replace('<form method="POST" action="orderform.php" id="buyForm"', f'<form method="POST" action="orderform.php" id="buyForm"\n{token_input}')
    content = content.replace('<form action="cart.php" method="POST" style="display:inline;">', f'<form action="cart.php" method="POST" style="display:inline;">\n{token_input}')
    
    with open(ov_path, 'w', encoding='utf-8') as f:
        f.write(content)

print("IDOR and CSRF fixes applied to User Panel.")
