import os

user_dir = r"C:\xampp\htdocs\craftzon\user"
ov_path = os.path.join(user_dir, 'online_view.php')
with open(ov_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Fix checkLogin logic
bad_js = """function checkLogin(e, redirectUrl = null) {
    if (typeof e !== 'undefined' && e) e.preventDefault();
    var uid = <?php echo $userid; ?>;"""

good_js = """function checkLogin(e, redirectUrl = null) {
    var uid = <?php echo $userid; ?>;
    if (uid == 0) {
        if (typeof e !== 'undefined' && e) e.preventDefault();"""

content = content.replace(bad_js, good_js)

# Fix the inline onsubmit so they don't break
content = content.replace(
    'onsubmit="return checkLogin(event) ? this.submit() : false;"',
    'onsubmit="return checkLogin(event);"'
)

content = content.replace(
    'onsubmit="return checkLogin(event) ? true : false;"',
    'onsubmit="return checkLogin(event);"'
)

with open(ov_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Fixed checkLogin JS.")
