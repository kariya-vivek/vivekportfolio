import os
import glob
import re

user_dir = r"C:\xampp\htdocs\craftzon\user"
php_files = glob.glob(os.path.join(user_dir, "*.php"))

js_check_cart = "if(<?php echo isset($_SESSION['users_id']) ? $_SESSION['users_id'] : 0; ?> == 0) { event.preventDefault(); Swal.fire({title: 'Login Required', text: 'Please login first!', icon: 'warning', showCancelButton: true, confirmButtonText: 'Login Now'}).then((result) => { if(result.isConfirmed) { window.location.href = 'logincraft.php'; } }); return false; }"

for file in php_files:
    with open(file, 'r', encoding='utf-8', errors='ignore') as f:
        content = f.read()
        
    modified = False

    # Check cart button
    cart_btn2 = '<button type="submit" style="all:unset; cursor:pointer; display:inline-block; position:relative;">'
    new_cart_btn2 = f'<button type="submit" onclick="{js_check_cart}" style="all:unset; cursor:pointer; display:inline-block; position:relative;">'
    
    if cart_btn2 in content:
        content = content.replace(cart_btn2, new_cart_btn2)
        modified = True

    if modified:
        with open(file, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Fixed Cart icon popup in {os.path.basename(file)}")

