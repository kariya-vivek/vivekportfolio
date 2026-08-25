import os
import glob

user_dir = r"C:\xampp\htdocs\craftzon\user"
php_files = glob.glob(os.path.join(user_dir, "*.php"))

script_tag = '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>'

for file in php_files:
    with open(file, 'r', encoding='utf-8', errors='ignore') as f:
        content = f.read()
        
    if script_tag not in content:
        # Add it before </head>
        if '</head>' in content:
            content = content.replace('</head>', f'    {script_tag}\n</head>', 1)
            with open(file, 'w', encoding='utf-8') as f2:
                f2.write(content)
            print(f"Added SweetAlert script to {os.path.basename(file)}")

