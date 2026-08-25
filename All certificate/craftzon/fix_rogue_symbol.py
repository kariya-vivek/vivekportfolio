import os
import glob

user_dir = r"C:\xampp\htdocs\craftzon\user"
php_files = glob.glob(os.path.join(user_dir, "*.php"))

for file in php_files:
    with open(file, 'r', encoding='utf-8', errors='ignore') as f:
        content = f.read()
        
    bad_part = 'style="display:none;">\n">\n'
    bad_part2 = 'style="display:none;">\r\n">\r\n'
    bad_part3 = '">\n  <input type="hidden" name="userid"'
    bad_part4 = '">\r\n  <input type="hidden" name="userid"'
    bad_part5 = '">\n    <input type="hidden" name="userid"'
    bad_part6 = '">\r\n    <input type="hidden" name="userid"'

    content = content.replace(bad_part, 'style="display:none;">\n')
    content = content.replace(bad_part2, 'style="display:none;">\r\n')
    content = content.replace(bad_part3, '  <input type="hidden" name="userid"')
    content = content.replace(bad_part4, '  <input type="hidden" name="userid"')
    content = content.replace(bad_part5, '    <input type="hidden" name="userid"')
    content = content.replace(bad_part6, '    <input type="hidden" name="userid"')

    # Also clean up any other standalone `">\n` right after a form tag
    content = content.replace('">\n    <input type="hidden" name="userid" value="<?= $usid ?>">', '    <input type="hidden" name="userid" value="<?= $usid ?>">')
    content = content.replace('">\r\n    <input type="hidden" name="userid" value="<?= $usid ?>">', '    <input type="hidden" name="userid" value="<?= $usid ?>">')
    content = content.replace('">\n  <input type="hidden" name="userid" value="<?= $usid ?>">', '  <input type="hidden" name="userid" value="<?= $usid ?>">')
    content = content.replace('">\r\n  <input type="hidden" name="userid" value="<?= $usid ?>">', '  <input type="hidden" name="userid" value="<?= $usid ?>">')

    with open(file, 'w', encoding='utf-8') as f:
        f.write(content)

print("Cleaned up rogue '> symbols")
