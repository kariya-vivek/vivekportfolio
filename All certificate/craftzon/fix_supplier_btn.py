import os
import glob
import re

user_dir = r"C:\xampp\htdocs\craftzon\user"
php_files = glob.glob(os.path.join(user_dir, "*.php"))

js_check = "if({$usid} == 0) { Swal.fire({title: 'Login Required', text: 'Please login first!', icon: 'warning', showCancelButton: true, confirmButtonText: 'Login Now'}).then((result) => { if(result.isConfirmed) { document.getElementById('popupLink').click(); } }); } else { "

for file in php_files:
    with open(file, 'r', encoding='utf-8', errors='ignore') as f:
        content = f.read()
        
    modified = False

    # 1. Become Supplier
    pattern_supp = r"onclick=\\\"document\.getElementById\('becomeSupplierForm\{\$usid\}'\)\.submit\(\);\\\""
    if re.search(pattern_supp, content):
        def repl_supp(m):
            return f"onclick=\\\"{js_check}document.getElementById('becomeSupplierForm{{$usid}}').submit(); }}\\\""
        content = re.sub(pattern_supp, repl_supp, content)
        modified = True

    # 2. View Shop
    pattern_shop = r"onclick=\\\"document\.getElementById\('goStoreForm\{\$row\['sellerid'\]\}'\)\.submit\(\);\\\""
    if re.search(pattern_shop, content):
        def repl_shop(m):
            return f"onclick=\\\"{js_check}document.getElementById('goStoreForm{{$row['sellerid']}}').submit(); }}\\\""
        content = re.sub(pattern_shop, repl_shop, content)
        modified = True

    if modified:
        with open(file, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Fixed supplier/shop buttons in {os.path.basename(file)}")

