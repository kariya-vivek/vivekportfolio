import os

store_path = r"C:\xampp\htdocs\craftzon\user\store.php"

with open(store_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Fix the action links
content = content.replace('action="addproduct.php"', 'action="../seller/addproduct.php"')
content = content.replace("location.href='selleradminpanel.php", "location.href='../seller/selleradminpanel.php")
content = content.replace('action="editstroe.php"', 'action="../seller/editstroe.php"')
content = content.replace('action="seller_advertise.php"', 'action="../seller/seller_advertise.php"')
content = content.replace('action="add_story.php"', 'action="../seller/add_story.php"')

with open(store_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Fixed links in store.php")
