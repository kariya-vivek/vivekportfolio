import re

with open('user/returnorder.php', 'r', encoding='utf-8', errors='ignore') as f:
    text = f.read()

bad_form = '<form id="returnForm" action="#" method="post" enctype="multipart/form-data">'
good_form = '<form id="returnForm" action="#" method="post" enctype="multipart/form-data" onsubmit="this.insertAdjacentHTML(\'beforeend\', \'<input type=\\\'hidden\\\' name=\\\'sretbtn\\\' value=\\\'1\\\'>\'); var btn = this.querySelector(\'button[type=submit]\'); btn.disabled=true; btn.innerText=\'Processing...\'; return true;">'

if bad_form in text:
    text = text.replace(bad_form, good_form)
    with open('user/returnorder.php', 'w', encoding='utf-8') as f:
        f.write(text)
    print("Fixed returnorder.php double submit")
else:
    print("Not found in returnorder.php")
