<!-- Chatbot UI -->
<style>
#chatbot-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 300px;
    background: #fff;
    border: 1px solid #ccc;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    display: none;
    flex-direction: column;
    z-index: 1000;
}
#chatbot-header {
    background: #581845;
    color: white;
    padding: 10px;
    border-radius: 10px 10px 0 0;
    font-weight: bold;
    display: flex;
    justify-content: space-between;
}
#chatbot-messages {
    height: 250px;
    padding: 10px;
    overflow-y: auto;
    font-size: 14px;
}
#chatbot-input-container {
    display: flex;
    border-top: 1px solid #ddd;
}
#chatbot-input {
    flex: 1;
    border: none;
    padding: 10px;
    border-radius: 0 0 0 10px;
    outline: none;
}
#chatbot-send {
    background: #581845;
    color: white;
    border: none;
    padding: 10px 15px;
    cursor: pointer;
    border-radius: 0 0 10px 0;
}
#chatbot-toggle {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #581845;
    color: white;
    border: none;
    border-radius: 50%;
    width: 60px;
    height: 60px;
    font-size: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    z-index: 1001;
}
.chat-msg { margin-bottom: 8px; }
.chat-bot { color: #581845; font-weight: bold; }
.chat-user { color: #333; text-align: right; }
</style>

<button id="chatbot-toggle" onclick="toggleChatbot()">
    <i class="fa-solid fa-robot fa-lg"></i>
</button>

<div id="chatbot-container">
    <div id="chatbot-header">
        <span>CraftZon AI</span>
        <span style="cursor:pointer" onclick="toggleChatbot()">&times;</span>
    </div>
    <div id="chatbot-messages">
        <div class="chat-msg chat-bot">Hello! Welcome to CraftZon. How can I help you today?</div>
    </div>
    <div id="chatbot-input-container">
        <input type="text" id="chatbot-input" placeholder="Type a message..." onkeypress="handleChatEnter(event)">
        <button id="chatbot-send" onclick="sendChatMessage()">Send</button>
    </div>
</div>

<script>
function toggleChatbot() {
    var chat = document.getElementById("chatbot-container");
    var btn = document.getElementById("chatbot-toggle");
    if (chat.style.display === "none" || chat.style.display === "") {
        chat.style.display = "flex";
        btn.style.display = "none";
    } else {
        chat.style.display = "none";
        btn.style.display = "block";
    }
}

function handleChatEnter(e) {
    if (e.key === 'Enter') sendChatMessage();
}

function sendChatMessage() {
    var input = document.getElementById("chatbot-input");
    var text = input.value.trim();
    if (!text) return;
    
    var msgDiv = document.getElementById("chatbot-messages");
    
    // Display user message
    var userMsg = document.createElement("div");
    userMsg.className = "chat-msg chat-user";
    userMsg.innerText = text;
    msgDiv.appendChild(userMsg);
    
    input.value = "";
    msgDiv.scrollTop = msgDiv.scrollHeight;
    
    // Add a small loading indicator
    var typingMsg = document.createElement("div");
    typingMsg.className = "chat-msg chat-bot";
    typingMsg.innerText = "Typing...";
    typingMsg.id = "bot-typing";
    msgDiv.appendChild(typingMsg);
    msgDiv.scrollTop = msgDiv.scrollHeight;

    // Fetch dynamic response from backend
    fetch('chatbot_api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ message: text })
    })
    .then(response => response.json())
    .then(data => {
        // Remove typing indicator
        document.getElementById("bot-typing").remove();
        
        // Display bot response
        var botMsg = document.createElement("div");
        botMsg.className = "chat-msg chat-bot";
        botMsg.innerText = data.reply;
        msgDiv.appendChild(botMsg);
        msgDiv.scrollTop = msgDiv.scrollHeight;
    })
    .catch(error => {
        document.getElementById("bot-typing").remove();
        var errorMsg = document.createElement("div");
        errorMsg.className = "chat-msg chat-bot";
        errorMsg.innerText = "Sorry, I am facing a connection error.";
        msgDiv.appendChild(errorMsg);
        msgDiv.scrollTop = msgDiv.scrollHeight;
    });
}
</script>
