<?php
include 'db.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Annie AI | Access Your Account</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { 
            background: #0a0a0a; 
            height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            color: white;
            overflow: hidden;
        }

        /* Animated Background Glow */
        .glow {
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(58, 123, 213, 0.2);
            filter: blur(100px);
            border-radius: 50%;
            z-index: -1;
            animation: pulse 8s infinite alternate;
        }

        @keyframes pulse {
            from { transform: translate(-50%, -50%); }
            to { transform: translate(50%, 50%); }
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 24px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            text-align: center;
        }

        h2 { margin-bottom: 10px; font-size: 1.8rem; letter-spacing: -1px; }
        p.subtitle { color: #888; font-size: 0.9rem; margin-bottom: 30px; }

        .input-group { margin-bottom: 20px; text-align: left; }
        label { display: block; font-size: 0.8rem; color: #aaa; margin-bottom: 8px; margin-left: 5px; }
        
        input {
            width: 100%;
            padding: 14px 20px;
            background: #151515;
            border: 1px solid #333;
            border-radius: 12px;
            color: white;
            outline: none;
            transition: 0.3s;
        }

        input:focus { border-color: #3a7bd5; box-shadow: 0 0 10px rgba(58, 123, 213, 0.2); }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(90deg, #3a7bd5, #00d2ff);
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
            transition: 0.3s;
        }

        .btn-submit:hover { opacity: 0.9; transform: translateY(-2px); }

        .toggle-text { margin-top: 20px; font-size: 0.85rem; color: #888; }
        .toggle-text span { color: #3a7bd5; cursor: pointer; font-weight: 600; }

        /* Hide elements for toggle */
        .hidden { display: none; }
    </style>
</head>
<body>
    <div class="glow"></div>

    <div class="auth-card">
        <div id="login-header">
            <h2>Welcome Back</h2>
            <p class="subtitle">Enter your details to chat with Annie.</p>
        </div>

        <div id="signup-header" class="hidden">
            <h2>Create Account</h2>
            <p class="subtitle">Join Annie AI and start talking today.</p>
        </div>

        <form id="auth-form" action="auth_process.php" method="POST">
            <input type="hidden" name="action" id="auth-action" value="login">

            <div class="input-group hidden" id="username-group">
                <label>Full Name</label>
                <input type="text" name="username" placeholder="John Doe">
            </div>

            <div class="input-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="name@example.com">
            </div>

            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="••••••••">
            </div>

            <button type="submit" class="btn-submit" id="submit-btn">Login</button>
        </form>

        <p class="toggle-text" id="toggle-wrapper">
            Don't have an account? <span onclick="toggleAuth()">Sign Up</span>
        </p>
    </div>

    <script>
        function toggleAuth() {
            const action = document.getElementById('auth-action');
            const userGroup = document.getElementById('username-group');
            const loginHeader = document.getElementById('login-header');
            const signupHeader = document.getElementById('signup-header');
            const submitBtn = document.getElementById('submit-btn');
            const toggleWrapper = document.getElementById('toggle-wrapper');

            if (action.value === 'login') {
                // Switch to Signup
                action.value = 'signup';
                userGroup.classList.remove('hidden');
                loginHeader.classList.add('hidden');
                signupHeader.classList.remove('hidden');
                submitBtn.innerText = 'Create Account';
                toggleWrapper.innerHTML = 'Already have an account? <span onclick="toggleAuth()">Login</span>';
            } else {
                // Switch to Login
                action.value = 'login';
                userGroup.classList.add('hidden');
                loginHeader.classList.remove('hidden');
                signupHeader.classList.add('hidden');
                submitBtn.innerText = 'Login';
                toggleWrapper.innerHTML = 'Don\'t have an account? <span onclick="toggleAuth()">Sign Up</span>';
            }
        }
    </script>
</body>
</html>
