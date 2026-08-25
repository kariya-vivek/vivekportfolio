import re

with open('user/update.php', 'r', encoding='utf-8', errors='ignore') as f:
    text = f.read()

bad = '''if (move_uploaded_file($tmpName, $uploadPath)) {

					$currentImage = $uploadPath; // store full path if needed

				}'''

good = '''if (move_uploaded_file($tmpName, $uploadPath)) {

					$currentImage = 'userprofileimage/' . $newImageName; // store db relative path

				}'''

if bad in text:
    text = text.replace(bad, good)
    with open('user/update.php', 'w', encoding='utf-8') as f:
        f.write(text)
    print("Fixed update.php image path")
else:
    print("Not found in update.php")
