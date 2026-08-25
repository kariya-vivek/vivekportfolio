import os
import re

directories = [
    r"C:\xampp\htdocs\craftzon\user",
    r"C:\xampp\htdocs\craftzon\seller",
    r"C:\xampp\htdocs\craftzon\admin"
]

def fix_image_paths(content):
    original = content
    
    # 1. <img src="<?php echo $row['image'] ?>"> or src="<?= $row['image'] ?>"
    # Pattern: src=" <?php echo $var ?> " where $var is a variable not starting with ../
    content = re.sub(r'src=(["\'])(<\?php echo\s+|\<\?=)([^>]+)\?\>\1', r'src=\1../\2\3?>\1', content)
    
    # Clean up double ../../ if they got added
    content = content.replace('src="../../', 'src="../')
    content = content.replace("src='../../", "src='../")
    content = content.replace('src=\"../../', 'src=\"../')

    # 2. echo '<img src="' . $row['image'] . '"'
    # Pattern: src="\' \. \$var \. \'\"
    # In PHP code: src="' . $row['image'] . '"
    content = re.sub(r'src=(["\'])(["\']\s*\.\s*(?:htmlspecialchars\()?\$[a-zA-Z0-9_]+\[[\'"][a-zA-Z0-9_]+[\'"]\])', r'src=\1../\2', content)
    
    # 3. src="{$row['image']}"
    content = re.sub(r'src=(["\'])\{\$([a-zA-Z0-9_]+)\[([\'"][a-zA-Z0-9_]+[\'"])\]\}\1', r'src=\1../{${\2}[\3]}\1', content)
    
    # 4. Any direct image paths that missed ../
    # like src="craftzonstroreimage/..."
    content = re.sub(r'src=(["\'])(craftzonstroreimage|uploads|advrtisephoto|retundbimage|crafter_storie|userprofileimage)/', r'src=\1../\2/', content)
    
    # Let's also check background-image url()
    content = re.sub(r'url\((["\']?)(<\?php echo\s+|\<\?=)([^>]+)\?\>\1\)', r'url(\1../\2\3?>\1)', content)

    # Clean double ../../ again just in case
    content = content.replace('../../', '../')
    # Except if it's genuinely needed, but in our 1-level deep panels, we never need ../../
    # Wait, if we replace all ../../ with ../, we are safe because panels are only 1 level deep.
    
    return content

for d in directories:
    for root, _, files in os.walk(d):
        for f in files:
            if f.endswith('.php'):
                path = os.path.join(root, f)
                with open(path, 'r', encoding='utf-8', errors='ignore') as file:
                    content = file.read()
                
                new_content = fix_image_paths(content)
                
                if new_content != content:
                    with open(path, 'w', encoding='utf-8') as file:
                        file.write(new_content)
                    print(f"Fixed images in {path}")

print("Deep scan for images complete.")
