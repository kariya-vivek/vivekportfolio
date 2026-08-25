import os

path = r"C:\xampp\htdocs\craftzon\admin\adminpanel.php"
with open(path, 'r', encoding='utf-8') as f:
    content = f.read()

bad = """                                <img src='../\\" . htmlspecialchars($row['image']) . \\"' 
                                     alt='Ad Image' 
                                     width='80' 
                                     height='80' 
                                     style='object-fit:cover; border-radius:6px;'>"""
good = """                                <img src='../" . htmlspecialchars($row['image']) . "' 
                                     alt='Ad Image' 
                                     width='80' 
                                     height='80' 
                                     style='object-fit:cover; border-radius:6px;'>"""

content = content.replace(bad, good)

with open(path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Fixed syntax errors at 1385")
