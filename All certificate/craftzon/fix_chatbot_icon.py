import re

with open('user/chatbot.php', 'r', encoding='utf-8', errors='ignore') as f:
    text = f.read()

text = text.replace('o-', '&times;')
text = text.replace('', '') # In case there's a stray replacement char
text = text.replace('<i class="fa-solid fa-comment"></i>', '<i class="fa-solid fa-robot fa-lg"></i>')
text = text.replace('<i class="fa-solid fa-comment">', '<i class="fa-solid fa-robot fa-lg">')

with open('user/chatbot.php', 'w', encoding='utf-8') as f:
    f.write(text)

print("Fixed chatbot.php icons")
