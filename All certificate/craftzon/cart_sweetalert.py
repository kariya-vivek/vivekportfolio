import os

user_dir = r"C:\xampp\htdocs\craftzon\user"

crafthome_path = os.path.join(user_dir, 'crafthome.php')
with open(crafthome_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Update Cart button to intercept if $usid == 0
content = content.replace(
    '<form action="cart.php" method="POST" style="display:inline;">',
    '<form id="cartForm" action="cart.php" method="POST" style="display:inline;">'
)
content = content.replace(
    '<button type="submit" style="all:unset; cursor:pointer; display:inline-block;">',
    '<button type="button" onclick="checkCartLogin()" style="all:unset; cursor:pointer; display:inline-block;">'
)

# Inject checkCartLogin JS function
cart_js = """
<script>
function checkCartLogin() {
    var usid = <?php echo $usid; ?>;
    if (usid == 0) {
        Swal.fire({
            title: 'Login Required',
            text: 'Please login to view your cart!',
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
    } else {
        document.getElementById('cartForm').submit();
    }
}
</script>
</body>
"""
content = content.replace("</body>", cart_js)

with open(crafthome_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Cart button updated to use SweetAlert.")
