import os

path = r"C:\xampp\htdocs\craftzon\admin\adminpanel.php"
with open(path, 'r', encoding='utf-8') as f:
    content = f.read()

# Fix image tag
bad_img = """                            echo "<img src='../\\" . htmlspecialchars($row['media_path']) . \\"' 
                                      alt='Craft Media' 
                                      width='100' 
                                      height='80' 
                                      style='object-fit:cover; border-radius:6px;'>";"""
good_img = """                            echo "<img src='../" . htmlspecialchars($row['media_path']) . "' 
                                      alt='Craft Media' 
                                      width='100' 
                                      height='80' 
                                      style='object-fit:cover; border-radius:6px;'>";"""
content = content.replace(bad_img, good_img)

# Fix video tag
bad_vid = """                            echo "<video width='150' height='80' controls>
                                      <source src='../\\" . htmlspecialchars($row['media_path']) . \\"' type='video/mp4'>
                                      Your browser does not support the video tag.
                                  </video>";"""
good_vid = """                            echo "<video width='150' height='80' controls>
                                      <source src='../" . htmlspecialchars($row['media_path']) . "' type='video/mp4'>
                                      Your browser does not support the video tag.
                                  </video>";"""
content = content.replace(bad_vid, good_vid)

with open(path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Fixed syntax errors in adminpanel.php")
