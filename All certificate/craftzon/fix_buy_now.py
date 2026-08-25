import os
import re

ov_path = r"C:\xampp\htdocs\craftzon\user\online_view.php"
with open(ov_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Fix broken form tag
content = content.replace(
    '<form method="POST" action="orderform.php" id="buyForm"\n"> onsubmit="return checkLogin(event);">',
    '<form method="POST" action="orderform.php" id="buyForm" onsubmit="return checkLogin(event);">'
)
# Just in case there is no newline
content = content.replace(
    '<form method="POST" action="orderform.php" id="buyForm""> onsubmit="return checkLogin(event);">',
    '<form method="POST" action="orderform.php" id="buyForm" onsubmit="return checkLogin(event);">'
)

with open(ov_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Fixed online_view.php Buy Now button")
