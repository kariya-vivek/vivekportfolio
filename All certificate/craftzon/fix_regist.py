import re

with open('user/registcraft.php', 'r', encoding='utf-8', errors='ignore') as f:
    text = f.read()

bad = '''	$profileImg = '../userprofileimage/default.png'; // <-- default image path
	if(!empty($_FILES['profile_img']['name'])){
		$profileImg = '../userprofileimage/' . $_FILES['profile_img']['name'];
		move_uploaded_file($_FILES['profile_img']['tmp_name'], $profileImg);
	}'''
good = '''	$profileImg = 'userprofileimage/default.png'; // <-- default image path
    $uploadPath = '../userprofileimage/default.png';
	if(!empty($_FILES['profile_img']['name'])){
		$profileImg = 'userprofileimage/' . $_FILES['profile_img']['name'];
        $uploadPath = '../userprofileimage/' . $_FILES['profile_img']['name'];
		move_uploaded_file($_FILES['profile_img']['tmp_name'], $uploadPath);
	}'''

if bad in text:
    text = text.replace(bad, good)
    with open('user/registcraft.php', 'w', encoding='utf-8') as f:
        f.write(text)
    print("Fixed registcraft.php")
else:
    print("Not found in registcraft.php")
