import os
import glob
import re

user_dir = r"C:\xampp\htdocs\craftzon\user"
php_files = glob.glob(os.path.join(user_dir, "*.php"))

js_login_check = r"""if(<?php echo isset($_SESSION['users_id']) ? $_SESSION['users_id'] : 0; ?> == 0) { Swal.fire({title: 'Login Required', text: 'Please login first!', icon: 'warning', showCancelButton: true, confirmButtonText: 'Login Now'}).then((result) => { if(result.isConfirmed) { document.getElementById('popupLink').click(); } }); } else { """

for file in php_files:
    with open(file, 'r', encoding='utf-8', errors='ignore') as f:
        content = f.read()
        
    modified = False

    # 1. Edit Profile
    if "onclick=\"document.getElementById('editProfileForm').submit();\"" in content:
        content = content.replace(
            "onclick=\"document.getElementById('editProfileForm').submit();\"",
            f"onclick=\"{js_login_check}document.getElementById('editProfileForm').submit(); }}\""
        )
        modified = True
        
    # 2. My Orders
    if "onclick=\"document.getElementById('myOrdersForm').submit();\"" in content:
        content = content.replace(
            "onclick=\"document.getElementById('myOrdersForm').submit();\"",
            f"onclick=\"{js_login_check}document.getElementById('myOrdersForm').submit(); }}\""
        )
        modified = True

    # 3. Contact Us
    if "onclick=\"document.getElementById('contactFormHidden').submit();\"" in content:
        content = content.replace(
            "onclick=\"document.getElementById('contactFormHidden').submit();\"",
            f"onclick=\"{js_login_check}document.getElementById('contactFormHidden').submit(); }}\""
        )
        modified = True
        
    # 4. Become Supplier
    # Need regex because id has $usid
    pattern_supplier = r"onclick=\"document\.getElementById\('becomeSupplierForm[^']*'\)\.submit\(\);\""
    if re.search(pattern_supplier, content):
        def repl_supp(m):
            return f"onclick=\"{js_login_check}{m.group(0)[9:-1]} }}\""
        content = re.sub(pattern_supplier, repl_supp, content)
        modified = True

    # 5. View Shop
    pattern_shop = r"onclick=\"document\.getElementById\('goStoreForm[^']*'\)\.submit\(\);\""
    if re.search(pattern_shop, content):
        def repl_shop(m):
            return f"onclick=\"{js_login_check}{m.group(0)[9:-1]} }}\""
        content = re.sub(pattern_shop, repl_shop, content)
        modified = True

    if modified:
        with open(file, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Updated sidebar login checks in {os.path.basename(file)}")
