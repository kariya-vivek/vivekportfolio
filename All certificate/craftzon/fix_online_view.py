import os
import re

user_dir = r"C:\xampp\htdocs\craftzon\user"

# 1. Update online_view.php images and accept POST
f = os.path.join(user_dir, 'online_view.php')
with open(f, 'r', encoding='utf-8') as file:
    content = file.read()

# Fix image paths
content = content.replace('src="<?php echo $product_data[\'image\']; ?>"','src="../<?php echo $product_data[\'image\']; ?>"')
content = content.replace('src="<?php echo $rs[\'shopimage\'];?>"','src="../<?php echo $rs[\'shopimage\'];?>"')
content = content.replace('src="<?php echo $review[\'profile_img\']; ?>"','src="../<?php echo $review[\'profile_img\']; ?>"')

# Fix GET to REQUEST
content = content.replace("$pid = intval($_GET['product_id']);", "$pid = isset($_REQUEST['product_id']) ? intval($_REQUEST['product_id']) : 0;")
# We'll use session for userid instead of REQUEST if possible, but let's just allow it from session directly
content = content.replace("$userid = isset($_GET['userid']) ? intval($_GET['userid']) : 0;", "$userid = isset($_SESSION['users_id']) ? $_SESSION['users_id'] : (isset($_REQUEST['userid']) ? intval($_REQUEST['userid']) : 0);")

with open(f, 'w', encoding='utf-8') as file:
    file.write(content)

print("online_view.php fixed.")
