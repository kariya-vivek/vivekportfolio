import os
import re

user_dir = 'user'
for file in os.listdir(user_dir):
    if not file.endswith('.php'):
        continue
        
    filepath = os.path.join(user_dir, file)
    with open(filepath, 'r', encoding='utf-8', errors='ignore') as f:
        content = f.read()
    
    changed = False

    # 1. Sell on Craftzon
    bad_sell = '''<form id="sellForm" action='../seller/create_craftzonstore.php' method="POST" style="display:none;">  <input type="hidden" name="userid" value="<?= $usid ?>">
</form>

<li><a href="#" onclick="document.getElementById('sellForm').submit();">Sell on Craftzon</a></li>'''

    good_sell = '''<form id="sellForm" action='../seller/create_craftzonstore.php' method="POST" style="display:none;">  <input type="hidden" name="userid" value="<?= $usid ?>">
</form>

<li><a href="#" onclick="if(<?= $usid ?> == 0) { event.preventDefault(); Swal.fire({title: 'Login Required', text: 'Please login first!', icon: 'warning', showCancelButton: true, confirmButtonText: 'Login Now'}).then((result) => { if(result.isConfirmed) { window.location.href = 'logincraft.php'; } }); } else { document.getElementById('sellForm').submit(); }">Sell on Craftzon</a></li>'''

    # Sometimes $usid is 0 statically in previous replaced files, wait let me use regex just in case
    # Actually the files were generated using template replacement, so it should match. Let's use regex for safety on the a tag.

    sell_pattern = r'<li><a href="[^"]*" onclick="document\.getElementById\(\'sellForm\'\)\.submit\(\);\s*">Sell on Craftzon</a></li>'
    if re.search(sell_pattern, content):
        content = re.sub(
            sell_pattern, 
            r'<li><a href="#" onclick="if(<?= $usid ?> == 0) { event.preventDefault(); Swal.fire({title: \'Login Required\', text: \'Please login first!\', icon: \'warning\', showCancelButton: true, confirmButtonText: \'Login Now\'}).then((result) => { if(result.isConfirmed) { window.location.href = \'logincraft.php\'; } }); } else { document.getElementById(\'sellForm\').submit(); }">Sell on Craftzon</a></li>',
            content
        )
        changed = True

    # 2. Edit Profile
    edit_pattern = r'<li><a href="[^"]*" onclick="if\([^)]+\)\s*\{\s*Swal\.fire\([^)]+\)\.then\(\(result\)\s*=>\s*\{\s*if\(result\.isConfirmed\)\s*\{\s*window\.location\.href\s*=\s*\'logincraft\.php\';\s*\}\s*\}\);\s*\}\s*else\s*\{\s*document\.getElementById\(\'editProfileForm\'\)\.submit\(\);\s*\}">edit profile</a></li>'
    if re.search(edit_pattern, content):
        content = re.sub(
            edit_pattern,
            r'<li><a href="#" onclick="if(<?= $usid ?> == 0) { event.preventDefault(); Swal.fire({title: \'Login Required\', text: \'Please login first!\', icon: \'warning\', showCancelButton: true, confirmButtonText: \'Login Now\'}).then((result) => { if(result.isConfirmed) { window.location.href = \'logincraft.php\'; } }); } else { document.getElementById(\'editProfileForm\').submit(); }">edit profile</a></li>',
            content
        )
        changed = True

    # 3. Returns Centre
    return_pattern = r'<li><a href="returncenter\.php">Returns Centre</a></li>'
    if re.search(return_pattern, content):
        content = re.sub(
            return_pattern,
            r'<li><a href="#" onclick="if(<?= $usid ?> == 0) { event.preventDefault(); Swal.fire({title: \'Login Required\', text: \'Please login first!\', icon: \'warning\', showCancelButton: true, confirmButtonText: \'Login Now\'}).then((result) => { if(result.isConfirmed) { window.location.href = \'logincraft.php\'; } }); } else { window.location.href = \'returncenter.php\'; }">Returns Centre</a></li>',
            content
        )
        changed = True

    if changed:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Fixed {file}")

