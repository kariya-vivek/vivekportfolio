import os

user_dir = 'user'
for file in os.listdir(user_dir):
    if file.endswith('.php'):
        filepath = os.path.join(user_dir, file)
        with open(filepath, 'r', encoding='utf-8', errors='ignore') as f:
            content = f.read()
        
        # Replace the bad block
        bad_block1 = '''$profileImg = 'default.png';
						if ($usid > 0) {
							$sel1 = "SELECT uname, profile_img FROM craftus_reg WHERE u_id = $usid";
							$select1 = mysqli_query($con, $sel1);
							if ($row1 = mysqli_fetch_array($select1)) {
								$profileImg = $row1['profile_img'];
							}
						}'''
        good_block = '''$profileImg = 'userprofileimage/default.png';
                        if ($usid > 0) {
      						$sel1 = "SELECT uname, profile_img FROM craftus_reg WHERE u_id = $usid";
      						$select1 = mysqli_query($con, $sel1);
      						if($row1 = mysqli_fetch_array($select1)) {
      						    $profileImg = !empty($row1['profile_img']) ? $row1['profile_img'] : 'userprofileimage/default.png';
                            }
                        }'''

        bad_block2 = '''$profileImg = 'userprofileimage/default.png';
                        if ($usid > 0) {
      						$sel1 = "SELECT uname, profile_img FROM craftus_reg WHERE u_id = $usid";
      						$select1 = mysqli_query($con, $sel1);
      						if($row1 = mysqli_fetch_array($select1)) {
      						$profileImg = $row1['profile_img'] ? $row1['profile_img'] : 'userprofileimage/default.png';
                            }
                        }'''

        changed = False
        if bad_block1 in content:
            content = content.replace(bad_block1, good_block)
            changed = True
        elif bad_block2 in content:
            content = content.replace(bad_block2, good_block)
            changed = True
            
        if changed:
            with open(filepath, 'w', encoding='utf-8') as f:
                f.write(content)
            print(f"Fixed {file}")

