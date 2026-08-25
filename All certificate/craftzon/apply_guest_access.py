import os

user_dir = r"C:\xampp\htdocs\craftzon\user"

# Modify crafthome.php
crafthome_path = os.path.join(user_dir, 'crafthome.php')
with open(crafthome_path, 'r', encoding='utf-8', errors='ignore') as f:
    content = f.read()

# Replace session login redirect
content = content.replace(
"""session_start();
$us_profile = $_SESSION["users_id"];
$usid = $us_profile;
if ($us_profile == false) {
    header('location:logincraft.php');
    exit();
}""",
"""session_start();
$usid = isset($_SESSION["users_id"]) ? $_SESSION["users_id"] : 0;"""
)

# Fix Sidenav profile section
old_profile_section = """					<?php
						$con = mysqli_connect("localhost", "root", "", "craftzon");
						$sel1 = "SELECT uname, profile_img FROM craftus_reg WHERE u_id = $usid";
						$select1 = mysqli_query($con, $sel1);
						$row1 = mysqli_fetch_array($select1);
						$profileImg = $row1['profile_img'];
					?>
					
					<!-- dY` Display profile image in circular shape -->
					<img src="../<?= $profileImg ?>" alt="Profile Image" style="width:120px; height:120px; border-radius:50%; object-fit:cover; border:3px solid #581845;">
					<br><br>


					<form id="editProfileForm" action="update.php" method="POST" style="display:none;">
    <input type="hidden" name="userid" value="<?= $usid ?>">
</form>
<h4 style="cursor:pointer;color:#581845" onclick="document.getElementById('editProfileForm').submit();">
    Edit Profile
</h4>"""

new_profile_section = """					<?php
						$con = mysqli_connect("localhost", "root", "", "craftzon");
						if ($usid > 0) {
							$sel1 = "SELECT uname, profile_img FROM craftus_reg WHERE u_id = $usid";
							$select1 = mysqli_query($con, $sel1);
							$row1 = mysqli_fetch_array($select1);
							$profileImg = $row1['profile_img'];
					?>
					<img src="../<?= $profileImg ?>" alt="Profile Image" style="width:120px; height:120px; border-radius:50%; object-fit:cover; border:3px solid #581845;">
					<br><br>
					<form id="editProfileForm" action="update.php" method="POST" style="display:none;">
						<input type="hidden" name="userid" value="<?= $usid ?>">
					</form>
					<h4 style="cursor:pointer;color:#581845" onclick="document.getElementById('editProfileForm').submit();">Edit Profile</h4>
					<?php } else { ?>
					<img src="../userprofileimage/default.png" alt="Guest" style="width:120px; height:120px; border-radius:50%; object-fit:cover; border:3px solid #581845;">
					<br><br>
					<h4 style="cursor:pointer;color:#581845" onclick="window.location.href='logincraft.php';">Login / Register</h4>
					<?php } ?>"""

content = content.replace(old_profile_section, new_profile_section)

with open(crafthome_path, 'w', encoding='utf-8') as f:
    f.write(content)

# Modify online_view.php similarly
online_view_path = os.path.join(user_dir, 'online_view.php')
with open(online_view_path, 'r', encoding='utf-8') as f:
    ov_content = f.read()

ov_content = ov_content.replace(
"""session_start();
$us_profile = $_SESSION["users_id"];
$usid = $us_profile;
if ($us_profile == false) {
    header('location:logincraft.php');
    exit();
}""",
"""session_start();
$usid = isset($_SESSION["users_id"]) ? $_SESSION["users_id"] : 0;"""
)
# Note: online_view didn't have that strict block, it just had session_start(). Wait!
# Actually, I checked online_view.php earlier, it didn't redirect! It only had `session_start();` at the top.
# So online_view is already accessible, it just expects $userid.

# Ensure cart.php and wishlist.php enforce login securely.
cart_path = os.path.join(user_dir, 'cart.php')
with open(cart_path, 'r', encoding='utf-8') as f:
    cart_content = f.read()
if "if (empty($uid) || $uid == 0)" not in cart_content:
    cart_content = cart_content.replace("$uid = $_POST['uid'];", "$uid = $_POST['uid'] ?? 0;\nif (empty($uid) || $uid == 0) { header('Location: logincraft.php'); exit; }")
    with open(cart_path, 'w', encoding='utf-8') as f:
        f.write(cart_content)

wishlist_path = os.path.join(user_dir, 'wishlist.php')
with open(wishlist_path, 'r', encoding='utf-8') as f:
    wishlist_content = f.read()
if "if ($usid == 0) { header('Location: logincraft.php'); exit; }" not in wishlist_content:
    wishlist_content = wishlist_content.replace("$usid = $_SESSION['users_id'] ?? 0;", "$usid = $_SESSION['users_id'] ?? 0;\nif ($usid == 0) { header('Location: logincraft.php'); exit; }")
    with open(wishlist_path, 'w', encoding='utf-8') as f:
        f.write(wishlist_content)

print("Applied guest access properly.")
