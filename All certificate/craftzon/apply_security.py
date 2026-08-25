import os
import re

root_dir = r"C:\xampp\htdocs\craftzon"

files_to_fix = [
    (r"user\orderform.php", [
        (r"\$uid = isset\(\$_REQUEST\['uid'\]\) \? \$_REQUEST\['uid'\] : 0;", r"$uid = isset($_REQUEST['uid']) ? intval($_REQUEST['uid']) : 0;"),
        (r"\$pid = isset\(\$_REQUEST\['pid'\]\) \? \$_REQUEST\['pid'\] : 0;", r"$pid = isset($_REQUEST['pid']) ? intval($_REQUEST['pid']) : 0;")
    ]),
    (r"user\online_view.php", [
        (r"\$product_id = \$_GET\['product_id'\];", r"$product_id = intval($_GET['product_id']);"),
        (r"\$userid = \$_GET\['userid'\];", r"$userid = intval($_GET['userid']);")
    ]),
    (r"user\usviwshop.php", [
        (r"\$seller_id\s*=\s*\$_GET\['sellerid'\];", r"$seller_id = intval($_GET['sellerid']);")
    ]),
    (r"seller\selleradminpanel.php", [
        # Escaping string parameters
        (r"\$sellernm = \$_GET\['sellernm'\];", r"$sellernm = mysqli_real_escape_string($con, $_GET['sellernm']);"),
        (r"\$sellernm = \$_SESSION\['seller_id'\];", r"$sellernm = mysqli_real_escape_string($con, $_SESSION['seller_id']);")
    ]),
    (r"seller\getselDashboardData.php", [
        (r"\$sellernm = \$_GET\['sellernm'\];", r"$sellernm = mysqli_real_escape_string($con, $_GET['sellernm']);")
    ]),
    (r"user\low_stock_alert.php", [
        (r"\$seller_id = \$_GET\['seller_id'\];", r"$seller_id = intval($_GET['seller_id']);")
    ])
]

for rel_path, replacements in files_to_fix:
    path = os.path.join(root_dir, rel_path)
    if os.path.exists(path):
        with open(path, 'r', encoding='utf-8') as f:
            content = f.read()
        
        for search, replace in replacements:
            content = re.sub(search, replace, content)
            
        with open(path, 'w', encoding='utf-8') as f:
            f.write(content)

print("Security fixes applied.")
