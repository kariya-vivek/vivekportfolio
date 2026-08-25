import os
user_dir = r"C:\xampp\htdocs\craftzon\user"
ov_path = os.path.join(user_dir, 'online_view.php')

with open(ov_path, 'r', encoding='utf-8') as f:
    content = f.read()

bad_js = """<script>
function checkLogin(e, redirectUrl = null) {
    var uid = <?php echo $userid; ?>;
    if (uid == 0) {
        if (typeof e !== 'undefined' && e) e.preventDefault();
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
</script>"""

good_js = """<script>
function checkLogin(e, redirectUrl = null) {
    var uid = <?php echo $userid; ?>;
    if (uid == 0) {
        if (typeof e !== 'undefined' && e) e.preventDefault();
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
</script>"""

content = content.replace(bad_js, good_js)
with open(ov_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("JS Fixed")
