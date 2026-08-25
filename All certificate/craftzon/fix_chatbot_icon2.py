import re

with open('user/chatbot.php', 'r', encoding='utf-8', errors='ignore') as f:
    text = f.read()

text = re.sub(r'<span style="cursor:pointer" onclick="toggleChatbot\(\)">.*?</span>', '<span style="cursor:pointer" onclick="toggleChatbot()">&times;</span>', text)

with open('user/chatbot.php', 'w', encoding='utf-8') as f:
    f.write(text)

print("Fixed chatbot.php close icon")
