import os

# 1. Update seller/addproduct.php filename generation
addprod = r'C:\xampp\htdocs\craftzon\seller\addproduct.php'
if os.path.exists(addprod):
    with open(addprod, 'r', encoding='utf-8') as f:
        content = f.read()
    content = content.replace("uniqid('prod_')", "bin2hex(random_bytes(16))")
    with open(addprod, 'w', encoding='utf-8') as f:
        f.write(content)

# 2. Inject CSRF into seller/delete_product.php
delprod = r'C:\xampp\htdocs\craftzon\seller\delete_product.php'
if os.path.exists(delprod):
    with open(delprod, 'r', encoding='utf-8') as f:
        content = f.read()
    if 'csrf.php' not in content:
        content = content.replace('<?php', '<?php\nrequire_once "../user/csrf.php";\nverify_csrf();\n')
    with open(delprod, 'w', encoding='utf-8') as f:
        f.write(content)

# 3. Inject CSRF into admin/adminpanel.php POST actions
adminpanel = r'C:\xampp\htdocs\craftzon\admin\adminpanel.php'
if os.path.exists(adminpanel):
    with open(adminpanel, 'r', encoding='utf-8') as f:
        content = f.read()
    if 'csrf.php' not in content:
        content = content.replace('<?php', '<?php\nrequire_once "../user/csrf.php";\nverify_csrf();\n')
    with open(adminpanel, 'w', encoding='utf-8') as f:
        f.write(content)

print('CSRF and Filename fixes applied.')
