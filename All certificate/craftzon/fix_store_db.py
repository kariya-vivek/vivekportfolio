import re

with open('seller/create_craftzonstore.php', 'r', encoding='utf-8', errors='ignore') as f:
    text = f.read()

bad = '''$folder = '../craftzonstroreimage/' . $filenm;
				move_uploaded_file($tempnm, $folder);
			} else {
				$folder = $defaultImage;
			}

			$in="insert into seller (storenm,sellernm,selleremailid,gstinno,description,shopimage) 
values('$stornm','$selnm','$sellemid','$storegstno','$storedesc','$folder')"; '''

good = '''$uploadPath = '../craftzonstroreimage/' . $filenm;
				$folder = 'craftzonstroreimage/' . $filenm;
				move_uploaded_file($tempnm, $uploadPath);
			} else {
				$folder = $defaultImage;
			}

			$in="insert into seller (storenm,sellernm,selleremailid,gstinno,description,shopimage) 
values('$stornm','$selnm','$sellemid','$storegstno','$storedesc','$folder')"; '''

if bad in text:
    text = text.replace(bad, good)
    with open('seller/create_craftzonstore.php', 'w', encoding='utf-8') as f:
        f.write(text)
    print("Fixed store image path logic in create_craftzonstore.php")
else:
    print("Not found in create_craftzonstore.php")
