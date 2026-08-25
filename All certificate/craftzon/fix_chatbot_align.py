import re

with open('user/chatbot.php', 'r', encoding='utf-8', errors='ignore') as f:
    text = f.read()

bad = '''    width: 60px;
    height: 60px;
    font-size: 24px;'''

good = '''    width: 60px;
    height: 60px;
    font-size: 24px;
    display: flex;
    align-items: center;
    justify-content: center;'''

if bad in text:
    text = text.replace(bad, good)
    with open('user/chatbot.php', 'w', encoding='utf-8') as f:
        f.write(text)
    print("Fixed chatbot.php alignment")
else:
    print("Not found")
