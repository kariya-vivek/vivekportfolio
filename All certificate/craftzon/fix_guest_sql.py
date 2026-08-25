import os
user_dir = r"C:\xampp\htdocs\craftzon\user"
crafthome_path = os.path.join(user_dir, 'crafthome.php')
with open(crafthome_path, 'r', encoding='utf-8', errors='ignore') as f:
    content = f.read()

# Fix the sidenav SQL fetch
content = content.replace(
"""$sel1 = "SELECT uname, profile_img FROM craftus_reg WHERE u_id = $usid";
						$select1 = mysqli_query($con, $sel1);
						$row1 = mysqli_fetch_array($select1);
						$profileImg = $row1['profile_img'];""",
"""$profileImg = 'default.png';
						if ($usid > 0) {
							$sel1 = "SELECT uname, profile_img FROM craftus_reg WHERE u_id = $usid";
							$select1 = mysqli_query($con, $sel1);
							if ($row1 = mysqli_fetch_array($select1)) {
								$profileImg = $row1['profile_img'];
							}
						}"""
)

# And the other fetch for the store button
content = content.replace(
"""$sel1="select uname  from craftus_reg where u_id=$usid";
							$select1=mysqli_query($con,$sel1);
							$row1=mysqli_fetch_array($select1);
							
							$sel = "SELECT * FROM seller WHERE sellernm = '" . $row1['uname'] . "'";""",
"""$row1 = ['uname' => 'Guest'];
							if ($usid > 0) {
								$sel1="select uname from craftus_reg where u_id=$usid";
								$select1=mysqli_query($con,$sel1);
								$row1=mysqli_fetch_array($select1);
							}
							$sel = "SELECT * FROM seller WHERE sellernm = '" . $row1['uname'] . "'";"""
)

with open(crafthome_path, 'w', encoding='utf-8', errors='ignore') as f:
    f.write(content)
