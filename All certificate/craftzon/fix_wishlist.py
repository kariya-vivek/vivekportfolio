import os
path = r'C:\xampp\htdocs\craftzon\user\wishlist.php'
with open(path, 'r', encoding='utf-8') as f:
    content = f.read()

content = content.replace("onclick=\"window.location.href='online_view.php?product_id=' . $productId . '&userid=' . $usid . ''\">", 
                          "onclick=\"window.location.href=\\'online_view.php?product_id=' . $productId . '&userid=' . $usid . '\\'\">")

with open(path, 'w', encoding='utf-8') as f:
    f.write(content)
print("wishlist fixed.")
