with open('seller/selleradminpanel.php', 'r', encoding='utf-8', errors='ignore') as f:
    text = f.read()

bad1 = "? \"<img src='{$imagePath}'"
good1 = "? \"<img src='../{$imagePath}'"

bad2 = "$preview = \"<img src='{$mediaPath}'"
good2 = "$preview = \"<img src='../{$mediaPath}'"

bad3 = "$imagePreview = \"<img src='{$imagePath}'"
good3 = "$imagePreview = \"<img src='../{$imagePath}'"

bad4 = "$preview = \"<video src='{$mediaPath}'"
good4 = "$preview = \"<video src='../{$mediaPath}'"

if bad1 in text: text = text.replace(bad1, good1)
if bad2 in text: text = text.replace(bad2, good2)
if bad3 in text: text = text.replace(bad3, good3)
if bad4 in text: text = text.replace(bad4, good4)

with open('seller/selleradminpanel.php', 'w', encoding='utf-8') as f:
    f.write(text)
print("Fixed selleradminpanel.php image paths")
