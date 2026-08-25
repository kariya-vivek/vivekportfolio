import re

with open('user/cancelorder.php', 'r', encoding='utf-8', errors='ignore') as f:
    text = f.read()

bad_form = '<form id="cancelOrderForm" action="#" method="post">'
good_form = '<form id="cancelOrderForm" action="#" method="post" onsubmit="this.insertAdjacentHTML(\'beforeend\', \'<input type=\\\'hidden\\\' name=\\\'sucancelbtn\\\' value=\\\'1\\\'>\'); var btn = this.querySelector(\'button[type=submit]\'); btn.disabled=true; btn.innerText=\'Processing...\'; return true;">'

if bad_form in text:
    text = text.replace(bad_form, good_form)
    with open('user/cancelorder.php', 'w', encoding='utf-8') as f:
        f.write(text)
    print("Fixed cancelorder.php double submit")
else:
    print("Not found in cancelorder.php")
