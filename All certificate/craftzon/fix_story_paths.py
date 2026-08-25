with open('user/viewcraftstory.php', 'r', encoding='utf-8', errors='ignore') as f:
    text = f.read()

# Current: <img src="../<?= htmlspecialchars($row['media_path']); ?>"
# Fix: <img src="<?= htmlspecialchars($row['media_path']); ?>"
# Same for video: <source src="../<?= htmlspecialchars($row['media_path']); ?>"

bad_img = '<img src="../<?= htmlspecialchars($row[\'media_path\']); ?>"'
good_img = '<img src="<?= htmlspecialchars($row[\'media_path\']); ?>"'

bad_vid = '<source src="../<?= htmlspecialchars($row[\'media_path\']); ?>"'
good_vid = '<source src="<?= htmlspecialchars($row[\'media_path\']); ?>"'

if bad_img in text:
    text = text.replace(bad_img, good_img)
if bad_vid in text:
    text = text.replace(bad_vid, good_vid)

with open('user/viewcraftstory.php', 'w', encoding='utf-8') as f:
    f.write(text)
print("Fixed viewcraftstory.php media paths")

# Also fix the store.php action path for add_story.php!
with open('user/store.php', 'r', encoding='utf-8', errors='ignore') as f:
    text = f.read()

bad_store = 'action="../seller/add_story.php"'
good_store = 'action="add_story.php"'
if bad_store in text:
    text = text.replace(bad_store, good_store)
    with open('user/store.php', 'w', encoding='utf-8') as f:
        f.write(text)
    print("Fixed store.php add_story.php action")

