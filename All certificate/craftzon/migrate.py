import os
import re

root_dir = r"C:\xampp\htdocs\craftzon"
admin_dir = os.path.join(root_dir, "admin")
seller_dir = os.path.join(root_dir, "seller")

# Admin files
admin_files = [
    "adminlogin.php",
    "adminlogout.php",
    "adminpanel.php",
    "getDashboardData.php"
]

# Seller files
seller_files = [
    "selleradminpanel.php",
    "seller_advertise.php",
    "getselDashboardData.php",
    "addproduct.php",
    "update_product.php",
    "delete_product.php",
    "editstroe.php",
    "create_craftzonstore.php",
    "logincraft.php"
]

def update_paths(content):
    # This will try to prepend '../' to database image paths and known files
    # E.g., src='{$row['image']}' -> src='../{$row['image']}'
    
    # We must be careful not to rewrite already absolute URLs or http
    # Specifically replacing src='{$row['image']}' -> src='../{$row['image']}'
    # and src='" . htmlspecialchars($row['media_path']) . "' -> src='../" . htmlspecialchars(...) . "'
    # and src='{$row['shopimage']}' -> src='../{$row['shopimage']}'
    # and src='{$row['profile_img']}' -> src='../{$row['profile_img']}'
    # and src='{$row['photo']}' -> src='../{$row['photo']}'
    
    content = re.sub(r"src='\{\$row\['(image|profile_img|shopimage|photo)'\]\}'", r"src='../{$row['\1']}'", content)
    content = re.sub(r"src='\" \. htmlspecialchars\(\$row\['(media_path|image)'\]\) \. \"'", r"src='../\" . htmlspecialchars($row['\1']) . \"'", content)
    
    # Also fix header locations to root if they point to something in root? 
    # E.g., header('location:crafthome.php') -> header('location:../crafthome.php')
    
    # Fix form action='addproduct.php' -> if it's within seller, no change.
    
    # Fix includes if there are any, like include 'low_stock_alert.php' -> include '../low_stock_alert.php'
    # Actually wait, `include 'db.php'` if any, but they just use mysqli_connect directly mostly.
    
    return content

for f in admin_files:
    src_path = os.path.join(root_dir, f)
    dst_path = os.path.join(admin_dir, f)
    if os.path.exists(src_path):
        with open(src_path, 'r', encoding='utf-8', errors='ignore') as file:
            content = file.read()
        
        content = update_paths(content)
        # Any specific admin adjustments?
        
        with open(dst_path, 'w', encoding='utf-8') as file:
            file.write(content)
        os.remove(src_path)

for f in seller_files:
    src_path = os.path.join(root_dir, f)
    dst_path = os.path.join(seller_dir, f)
    if os.path.exists(src_path):
        with open(src_path, 'r', encoding='utf-8', errors='ignore') as file:
            content = file.read()
        
        content = update_paths(content)
        # Fix includes in seller files
        # create_craftzonstore.php might upload images to 'craftzonstroreimage/' -> needs to be '../craftzonstroreimage/'
        # Let's fix move_uploaded_file destinations
        content = re.sub(r"move_uploaded_file\(([^,]+),\s*['\"](craftzonstroreimage/|uploads/|advrtisephoto/)['\"]\s*\.\s*([^)]+)\)", 
                         r"move_uploaded_file(\1, '../\2' . \3)", content)
                         
        content = re.sub(r"['\"](craftzonstroreimage|uploads|advrtisephoto)/['\"]\s*\.", r"'../\1/' .", content)
        
        # In update_product.php, addproduct.php, delete_product.php
        
        with open(dst_path, 'w', encoding='utf-8') as file:
            file.write(content)
        os.remove(src_path)

print("Moved files successfully.")
