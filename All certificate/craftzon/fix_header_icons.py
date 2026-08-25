import os
import glob
import re

user_dir = r"C:\xampp\htdocs\craftzon\user"
php_files = glob.glob(os.path.join(user_dir, "*.php"))

js_check_wishlist = "if(<?php echo isset($_SESSION['users_id']) ? $_SESSION['users_id'] : 0; ?> == 0) { event.preventDefault(); Swal.fire({title: 'Login Required', text: 'Please login first!', icon: 'warning', showCancelButton: true, confirmButtonText: 'Login Now'}).then((result) => { if(result.isConfirmed) { window.location.href = 'logincraft.php'; } }); return false; }"
js_check_cart = "if(<?php echo isset($_SESSION['users_id']) ? $_SESSION['users_id'] : 0; ?> == 0) { event.preventDefault(); Swal.fire({title: 'Login Required', text: 'Please login first!', icon: 'warning', showCancelButton: true, confirmButtonText: 'Login Now'}).then((result) => { if(result.isConfirmed) { window.location.href = 'logincraft.php'; } }); return false; } else { document.getElementById('cartForm').submit(); }"

for file in php_files:
    with open(file, 'r', encoding='utf-8', errors='ignore') as f:
        content = f.read()
        
    modified = False

    # Fix rogue ">" symbols in inline forms
    bad_inline = 'style="display:inline;">\n">\n'
    bad_inline2 = 'style="display:inline;">\r\n">\r\n'
    if bad_inline in content:
        content = content.replace(bad_inline, 'style="display:inline;">\n')
        modified = True
    if bad_inline2 in content:
        content = content.replace(bad_inline2, 'style="display:inline;">\r\n')
        modified = True

    # Add popup to wishlist
    # Current button: <button type="submit" style="background:none; border:none; padding:0; margin:0; cursor:pointer;">
    wishlist_btn = '<button type="submit" style="background:none; border:none; padding:0; margin:0; cursor:pointer;">'
    new_wishlist_btn = f'<button type="submit" onclick="{js_check_wishlist}" style="background:none; border:none; padding:0; margin:0; cursor:pointer;">'
    
    if wishlist_btn in content:
        content = content.replace(wishlist_btn, new_wishlist_btn)
        modified = True

    # Check cart button
    # It might have `onclick="checkCartLogin()"`
    cart_btn1 = '<button type="button" onclick="checkCartLogin()" style="all:unset; cursor:pointer; display:inline-block;">'
    new_cart_btn1 = f'<button type="button" onclick="{js_check_cart}" style="all:unset; cursor:pointer; display:inline-block;">'
    if cart_btn1 in content:
        content = content.replace(cart_btn1, new_cart_btn1)
        modified = True

    if modified:
        with open(file, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Fixed header icons and rogue symbols in {os.path.basename(file)}")

