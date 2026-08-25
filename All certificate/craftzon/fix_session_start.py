import os

files_to_fix = [
    r"C:\xampp\htdocs\craftzon\user\cart.php",
    r"C:\xampp\htdocs\craftzon\user\orderform.php",
    r"C:\xampp\htdocs\craftzon\user\myorders.php",
    r"C:\xampp\htdocs\craftzon\user\update.php"
]

for file in files_to_fix:
    if os.path.exists(file):
        with open(file, 'r', encoding='utf-8', errors='ignore') as f:
            content = f.read()
            
        if 'session_start();' not in content:
            content = content.replace('<?php', '<?php\nsession_start();\n', 1)
            
            with open(file, 'w', encoding='utf-8') as f:
                f.write(content)
            print(f"Added session_start to {os.path.basename(file)}")

