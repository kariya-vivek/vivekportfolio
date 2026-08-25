import os

user_dir = r"C:\xampp\htdocs\craftzon\user"

# Key pages to inject the chatbot
pages_to_inject = [
    "crafthome.php",
    "cart.php",
    "myorders.php",
    "wishlist.php",
    "trackorder.php",
    "returncenter.php",
    "usviwshop.php"
]

for f in pages_to_inject:
    path = os.path.join(user_dir, f)
    if os.path.exists(path):
        with open(path, 'r', encoding='utf-8', errors='ignore') as file:
            content = file.read()
            
        if "chatbot.php" not in content:
            # inject before </body>
            content = content.replace("</body>", "<?php include 'chatbot.php'; ?>\n</body>")
            
            with open(path, 'w', encoding='utf-8') as file:
                file.write(content)

print("Chatbot injected.")
