import os
import re

user_dir = r"C:\xampp\htdocs\craftzon\user"

# Inject SweetAlert into crafthome.php
crafthome_path = os.path.join(user_dir, 'crafthome.php')
with open(crafthome_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Add SweetAlert CDN if not present
if "sweetalert2" not in content:
    content = content.replace("</head>", '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>\n</head>')

# Update wishlist JS
swal_js = """Swal.fire({
                title: 'Login Required',
                text: 'Please login to manage your wishlist!',
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
            return;"""

content = content.replace(
    "if(response === 'not_logged_in') { window.location.href = 'logincraft.php'; return; }",
    f"if(response === 'not_logged_in') {{ {swal_js} }}"
)

with open(crafthome_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("SweetAlert added to crafthome.")
