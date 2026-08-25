import os

user_dir = 'user'
for file in os.listdir(user_dir):
    if not file.endswith('.php'):
        continue
        
    filepath = os.path.join(user_dir, file)
    with open(filepath, 'r', encoding='utf-8', errors='ignore') as f:
        content = f.read()
    
    if "\\'" in content:
        # Only replace \' with ' inside the Swal.fire calls we injected
        content = content.replace("\\'Login Required\\'", "'Login Required'")
        content = content.replace("\\'Please login first!\\'", "'Please login first!'")
        content = content.replace("\\'warning\\'", "'warning'")
        content = content.replace("\\'Login Now\\'", "'Login Now'")
        content = content.replace("\\'logincraft.php\\'", "'logincraft.php'")
        content = content.replace("\\'sellForm\\'", "'sellForm'")
        content = content.replace("\\'editProfileForm\\'", "'editProfileForm'")
        content = content.replace("\\'returncenter.php\\'", "'returncenter.php'")
        
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Fixed backslashes in {file}")
