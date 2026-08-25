import re

with open('user/returnorder.php', 'r', encoding='utf-8', errors='ignore') as f:
    text = f.read()

bad = '''		$folder='../retundbimage/' . $filenm;
		move_uploaded_file($tempnm,$folder);
		$addcomm=$_POST['comments'];
		$in = "insert into return_requests (order_id,emailid,reason,comments,photo,uretunid) values ('$oid','$eid', '$res', '$addcomm', '$folder','$urid')";'''

good = '''		$uploadPath='../retundbimage/' . $filenm;
		$db_folder='retundbimage/' . $filenm;
		move_uploaded_file($tempnm,$uploadPath);
		$addcomm=$_POST['comments'];
		$in = "insert into return_requests (order_id,emailid,reason,comments,photo,uretunid) values ('$oid','$eid', '$res', '$addcomm', '$db_folder','$urid')";'''

if bad in text:
    text = text.replace(bad, good)
    with open('user/returnorder.php', 'w', encoding='utf-8') as f:
        f.write(text)
    print("Fixed returnorder.php DB path for photo")
else:
    print("Not found in returnorder.php")
