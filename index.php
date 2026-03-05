<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Annie AI | Your Virtual Companion</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: #0f0f0f; color: white; overflow-x: hidden; }
        .hero { height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; background: radial-gradient(circle at center, #1a1a2e 0%, #0f0f0f 100%); }
        h1 { font-size: 4rem; margin-bottom: 20px; background: linear-gradient(90deg, #00d2ff, #3a7bd5); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        p { font-size: 1.2rem; color: #aaa; max-width: 600px; margin-bottom: 30px; line-height: 1.6; }
        .btn-group { display: flex; gap: 20px; }
        .btn { padding: 15px 35px; border-radius: 50px; border: none; font-weight: bold; cursor: pointer; transition: 0.3s; text-decoration: none; font-size: 1rem; }
        .btn-primary { background: #3a7bd5; color: white; box-shadow: 0 4px 15px rgba(58, 123, 213, 0.4); }
        .btn-primary:hover { background: #00d2ff; transform: translateY(-3px); }
        .btn-outline { background: transparent; color: white; border: 2px solid #3a7bd5; }
        .btn-outline:hover { background: rgba(58, 123, 213, 0.1); }
        .circles { position: absolute; width: 100%; height: 100%; overflow: hidden; z-index: -1; }
        .circle { position: absolute; border-radius: 50%; background: rgba(58, 123, 213, 0.1); animation: float 20s infinite linear; }
        @keyframes float { 0% { transform: translateY(0); } 100% { transform: translateY(-100vh); } }
    </style>
</head>
<body>
    <div class="circles">
        <div class="circle" style="width: 80px; height: 80px; left: 10%; bottom: -100px;"></div>
        <div class="circle" style="width: 120px; height: 120px; left: 80%; bottom: -150px; animation-delay: 5s;"></div>
    </div>
    <div class="hero">
        <h1>Meet Annie AI</h1>
        <p>Experience the next generation of conversational AI. Talk, chat, and explore ideas with a companion that truly understands you.</p>
        <div class="btn-group">
            <button class="btn btn-primary" onclick="window.location.href='chat.php'">Start Chatting</button>
            <button class="btn btn-outline" onclick="window.location.href='login.php'">Login / Sign Up</button>
        </div>
    </div>
</body>
</html>
