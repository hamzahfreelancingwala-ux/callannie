<?php
require 'db.php';
session_start();

// --- BACKEND LOGIC ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_message'])) {
    header('Content-Type: application/json');
    
    $message = $_POST['ajax_message'];
    // NOTE: Keep your API Key secure. For production, use environment variables.
    $apiKey = "AIzaSyBW7wa25G6UVlJ-nijrfZJYOKCrqeXJycA";

    // UPDATED MODEL: Using gemini-2.5-flash which is the current stable free-tier model
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . $apiKey;

    $data = [
        "contents" => [
            [
                "parts" => [
                    ["text" => $message]
                ]
            ]
        ]
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $result = json_decode($response, true);
    curl_close($ch);

    // Precise parsing for the Gemini response structure
    if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
        $aiReply = $result['candidates'][0]['content']['parts'][0]['text'];
        
        // Save to DB
        try {
            $stmt = $pdo->prepare("INSERT INTO conversations (message, response) VALUES (?, ?)");
            $stmt->execute([$message, $aiReply]);
        } catch (Exception $e) {
            // Log error silently or handle as needed
        }

        echo json_encode(['reply' => $aiReply]);
    } else {
        // Handle API errors (like rate limits or invalid keys)
        $errorInfo = $result['error']['message'] ?? "Unknown API Error. Check your API key or rate limits.";
        echo json_encode(['reply' => "Annie Error: " . $errorInfo]);
    }
    exit; 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Annie AI | Live</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: #0a0a0a; color: white; height: 100vh; display: flex; flex-direction: column; }
        
        .header { padding: 15px 25px; background: #111; border-bottom: 1px solid #222; display: flex; justify-content: space-between; align-items: center; }
        .online-status { color: #00ff88; font-size: 0.8rem; display: flex; align-items: center; gap: 5px; }
        .dot { width: 8px; height: 8px; background: #00ff88; border-radius: 50%; box-shadow: 0 0 8px #00ff88; }

        #chat-window { flex: 1; overflow-y: auto; padding: 20px; display: flex; flex-direction: column; gap: 15px; }
        .msg { max-width: 80%; padding: 12px 18px; border-radius: 18px; line-height: 1.4; font-size: 0.95rem; }
        .user { align-self: flex-end; background: #3a7bd5; border-bottom-right-radius: 2px; }
        .ai { align-self: flex-start; background: #1e1e1e; border: 1px solid #333; border-bottom-left-radius: 2px; }

        .typing { display: none; color: #666; font-size: 0.8rem; margin-left: 20px; margin-bottom: 10px; }

        .input-area { padding: 20px; background: #111; display: flex; gap: 10px; align-items: center; }
        input { flex: 1; padding: 12px 20px; border-radius: 25px; border: 1px solid #333; background: #000; color: white; outline: none; }
        .btn { background: #333; border: none; width: 45px; height: 45px; border-radius: 50%; color: white; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: 0.2s; }
        .btn:hover { background: #444; }
        .btn-send { background: #3a7bd5; }
        .active-mic { background: #ff4b2b !important; animation: pulse 1s infinite; }
        @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }
    </style>
</head>
<body>

    <div class="header">
        <div><strong>Annie AI</strong></div>
        <div class="online-status"><div class="dot"></div> System Active</div>
        <button onclick="window.location.href='index.php'" style="background:none; border:none; color:#666; cursor:pointer;">Exit</button>
    </div>

    <div id="chat-window">
        <div class="msg ai">Hi! I'm Annie. I've updated to the latest 2026 engine. How can I help you today?</div>
    </div>
    
    <div class="typing" id="typing">Annie is thinking...</div>

    <div class="input-area">
        <button id="mic-btn" class="btn">🎤</button>
        <input type="text" id="user-input" placeholder="Say something...">
        <button id="send-btn" class="btn btn-send">➔</button>
    </div>

    <script>
        const chatWindow = document.getElementById('chat-window');
        const userInput = document.getElementById('user-input');
        const typing = document.getElementById('typing');

        function addMessage(role, text) {
            const div = document.createElement('div');
            div.className = `msg ${role}`;
            div.innerText = text;
            chatWindow.appendChild(div);
            chatWindow.scrollTop = chatWindow.scrollHeight;
        }

        async function handleChat() {
            const text = userInput.value.trim();
            if(!text) return;

            addMessage('user', text);
            userInput.value = '';
            typing.style.display = 'block';

            try {
                // Fetching from THIS same file
                const res = await fetch('chat.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `ajax_message=${encodeURIComponent(text)}`
                });
                const data = await res.json();
                typing.style.display = 'none';
                addMessage('ai', data.reply);

                // Voice Read-out
                const speech = new SpeechSynthesisUtterance(data.reply);
                window.speechSynthesis.speak(speech);

            } catch (e) {
                typing.style.display = 'none';
                addMessage('ai', "Connection error. Please check your internet or API limits.");
            }
        }

        document.getElementById('send-btn').onclick = handleChat;
        userInput.onkeypress = (e) => { if(e.key === 'Enter') handleChat(); };

        // Voice Support
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        if(SpeechRecognition) {
            const recognition = new SpeechRecognition();
            const micBtn = document.getElementById('mic-btn');

            micBtn.onclick = () => {
                recognition.start();
                micBtn.classList.add('active-mic');
            };

            recognition.onresult = (e) => {
                userInput.value = e.results[0][0].transcript;
                micBtn.classList.remove('active-mic');
                handleChat();
            };
            recognition.onerror = () => micBtn.classList.remove('active-mic');
        }
    </script>
</body>
</html>
