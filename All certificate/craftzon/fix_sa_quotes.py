import os

bad_cdn = '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>'
good_cdn = "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>"

for directory in ['user', 'seller']:
    if not os.path.exists(directory):
        continue
    for file in os.listdir(directory):
        if file.endswith('.php'):
            filepath = os.path.join(directory, file)
            with open(filepath, 'r', encoding='utf-8', errors='ignore') as f:
                content = f.read()
            
            if bad_cdn in content:
                content = content.replace(bad_cdn, good_cdn)
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(content)
                print(f"Fixed sweetalert quotes in {filepath}")

