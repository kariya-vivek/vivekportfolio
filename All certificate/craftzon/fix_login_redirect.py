import os

user_dir = 'user'
for file in os.listdir(user_dir):
    if not file.endswith('.php'):
        continue
        
    filepath = os.path.join(user_dir, file)
    with open(filepath, 'r', encoding='utf-8', errors='ignore') as f:
        content = f.read()
    
    changed = False
    
    if "window.location.href='../seller/logincraft.php';" in content:
        content = content.replace("window.location.href='../seller/logincraft.php';", "window.location.href='logincraft.php';")
        changed = True

    if "window.location.href=\"../seller/logincraft.php\";" in content:
        content = content.replace("window.location.href=\"../seller/logincraft.php\";", "window.location.href=\"logincraft.php\";")
        changed = True
        
    if "<a href='../seller/logincraft.php'" in content:
        content = content.replace("<a href='../seller/logincraft.php'", "<a href='logincraft.php'")
        changed = True
        
    if '<a href="../seller/logincraft.php"' in content:
        content = content.replace('<a href="../seller/logincraft.php"', '<a href="logincraft.php"')
        changed = True

    if changed:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Fixed {file}")
