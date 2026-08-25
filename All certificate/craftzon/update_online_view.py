import os
import re

user_dir = r"C:\xampp\htdocs\craftzon\user"
ov_path = os.path.join(user_dir, 'online_view.php')
with open(ov_path, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Add SweetAlert CDN if missing
if "sweetalert2" not in content:
    content = content.replace("</head>", '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>\n<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">\n<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">\n</head>')

# 2. Add Top Header Bar just after <body>
header_html = """
<div class="container-fluid" style="background-color:#581845; padding:10px 20px;">
    <div class="row align-items-center">
        <div class="col-4">
            <a href="crafthome.php"><img src="../craftzonlogo.jpeg" class="img-fluid rounded-circle" style="max-width: 80px; border: 2px solid white; background-color: #581845;"></a>
        </div>
        <div class="col-4 text-center">
            <h2 style="color:white; margin:0; font-weight:bold; letter-spacing:2px;">CraftZon</h2>
        </div>
        <div class="col-4 text-end">
            <a href="crafthome.php" style="color:white; text-decoration:none; margin-right:20px; font-size:18px;"><i class="fa-solid fa-house"></i> Home</a>
            <a href="#" onclick="checkLogin(event, 'cart.php')" style="color:white; text-decoration:none; font-size:18px;"><i class="fa-solid fa-cart-shopping"></i> Cart</a>
        </div>
    </div>
</div>
"""
if "CraftZon</h2>" not in content:
    content = content.replace("<body>", f"<body>\n{header_html}")

# 3. Add JS function checkLogin
js_func = """
<script>
function checkLogin(e, redirectUrl = null) {
    if (typeof e !== 'undefined' && e) e.preventDefault();
    var uid = <?php echo $userid; ?>;
    if (uid == 0) {
        Swal.fire({
            title: 'Login Required',
            text: 'Please login to use this feature!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#581845',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Login Now'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'logincraft.php';
            }
        });
        return false;
    }
    if (redirectUrl) window.location.href = redirectUrl;
    return true;
}
</script>
"""
if "function checkLogin" not in content:
    content = content.replace("</body>", f"{js_func}\n</body>")

# 4. Update Wishlist click
# Find: if(userid === 0) { alert("Please log in to manage wishlist!"); return; }
content = re.sub(
    r'if\s*\(\s*userid\s*===\s*0\s*\)\s*\{\s*alert\([^)]+\);\s*return;\s*\}',
    'if(userid === 0) { checkLogin(); return; }',
    content
)

# 5. Update Follow click
content = re.sub(
    r'if\s*\(\s*userid\s*===\s*0\s*\)\s*\{\s*alert\([^)]+\);\s*return;\s*\}',
    'if(userid === 0) { checkLogin(); return; }',
    content
)

# 6. Update Buy form
content = content.replace(
    '<form method="POST" action="orderform.php" id="buyForm">',
    '<form method="POST" action="orderform.php" id="buyForm" onsubmit="return checkLogin(event) ? this.submit() : false;">'
)

# 7. Update Add to Cart form
content = content.replace(
    '<form method="post" class="add-to-cart-form"',
    '<form method="post" class="add-to-cart-form" onsubmit="return checkLogin(event) ? true : false;"'
)

with open(ov_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("online_view updated successfully.")
