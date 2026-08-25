import os

user_dir = r"C:\xampp\htdocs\craftzon\user"

js_func = """
<script>
function viewProductPOST(pid) {
    var form = document.createElement('form');
    form.method = 'POST';
    form.action = 'online_view.php';
    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'product_id';
    input.value = pid;
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}
</script>
</body>
"""

for fname in ["crafthome.php", "wishlist.php"]:
    path = os.path.join(user_dir, fname)
    if os.path.exists(path):
        with open(path, 'r', encoding='utf-8') as f:
            content = f.read()
        
        if "function viewProductPOST(pid)" not in content:
            content = content.replace("</body>", js_func)
            
        with open(path, 'w', encoding='utf-8') as f:
            f.write(content)

print("JS function injected.")
