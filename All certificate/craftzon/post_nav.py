import os
import re

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

files_to_update = ["crafthome.php", "wishlist.php"]

for fname in files_to_update:
    path = os.path.join(user_dir, fname)
    if os.path.exists(path):
        with open(path, 'r', encoding='utf-8') as f:
            content = f.read()
            
        # Replace the onclick
        content = content.replace("onclick=\"window.location.href=\\'online_view.php?product_id=' . $productId . '&userid=' . $usid . '\\'\">",
                                  "onclick=\"viewProductPOST(' . $productId . ')\" style=\"cursor:pointer;\">")
        
        # Inject the script if not there
        if "viewProductPOST" not in content:
            content = content.replace("</body>", js_func)
            
        with open(path, 'w', encoding='utf-8') as f:
            f.write(content)

print("Navigation converted to POST.")
