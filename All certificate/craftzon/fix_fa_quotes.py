import os

bad_cdn = '<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">'
good_cdn = "<link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css' rel='stylesheet'>"

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
                print(f"Fixed quotes in {filepath}")

