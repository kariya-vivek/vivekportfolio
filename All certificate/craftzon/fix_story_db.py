import re

with open('user/add_story.php', 'r', encoding='utf-8', errors='ignore') as f:
    text = f.read()

bad = '''$targetPath = $uploadDir . $fileName;'''
good = '''$targetPath = $uploadDir . $fileName;
        $dbPath = 'crafter_storie/' . $fileName;'''

if bad in text:
    text = text.replace(bad, good)
    
    # replace $targetPath in bind_param with $dbPath
    text = text.replace('$stmt->bind_param("issss", $sellerid, $title, $description, $mediaType, $targetPath);',
                        '$stmt->bind_param("issss", $sellerid, $title, $description, $mediaType, $dbPath);')
    
    with open('user/add_story.php', 'w', encoding='utf-8') as f:
        f.write(text)
    print("Fixed add_story.php dbPath")
else:
    print("Not found in add_story")

# Also let's fix user/viewcraftstory.php to put ../ back!
with open('user/viewcraftstory.php', 'r', encoding='utf-8', errors='ignore') as f:
    text = f.read()

# Since I replaced it earlier, let's reverse it.
bad1 = '<img src="<?= htmlspecialchars($row[\'media_path\']); ?>"'
good1 = '<img src="../<?= htmlspecialchars($row[\'media_path\']); ?>"'

bad2 = '<source src="<?= htmlspecialchars($row[\'media_path\']); ?>"'
good2 = '<source src="../<?= htmlspecialchars($row[\'media_path\']); ?>"'

if bad1 in text: text = text.replace(bad1, good1)
if bad2 in text: text = text.replace(bad2, good2)

with open('user/viewcraftstory.php', 'w', encoding='utf-8') as f:
    f.write(text)
print("Fixed viewcraftstory.php")

# Now let's fix any bad DB entries that have ../ in them!
