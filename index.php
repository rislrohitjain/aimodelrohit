
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Rohit AI's Model | Next-Gen Intelligence</title>
    <meta name="title" content="Rohit AI | Next-Gen Intelligence">
    <meta name="description" content="Advanced AI search and discovery model by Rohit. Explore smart results with a premium glassmorphism interface.">
    <meta name="author" content="Rohit">

    <link rel="icon" type="image/png" href="https://img.icons8.com/fluency/48/artificial-intelligence.png">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 50%, #ec4899 100%);
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --sidebar-bg: rgba(15, 23, 42, 0.95);
            --text-main: #f8fafc;
            --text-dim: #94a3b8;
            --sidebar-width: 280px;
        }

        body, html {
            margin: 0; padding: 0; height: 100%;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: #0f172a; color: var(--text-main);
            overflow: hidden;
        }

        .bg-glow {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at 50% -20%, #312e81, transparent),
                        radial-gradient(circle at 0% 100%, #1e1b4b, transparent);
            z-index: -1;
        }

        .app-container { display: flex; height: 100vh; width: 100vw; position: relative; }

        /* --- SIDEBAR LOGIC --- */
        #sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-width);
            height: 100%;
            background: var(--sidebar-bg);
            backdrop-filter: blur(20px);
            border-right: 1px solid var(--glass-border);
            display: flex;
            flex-direction: column;
            padding: 20px;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 2000;
            transform: translateX(0); /* Visible by default */
        }

        #sidebar.collapsed {
            transform: translateX(-100%);
        }

        /* Overlay for mobile when sidebar is open */
        #sidebar-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.6);
            z-index: 1500;
            display: none;
        }

        /* --- MAIN CONTENT AREA --- */
        #main-chat { 
            flex: 1; 
            margin-left: var(--sidebar-width); 
            display: flex; 
            flex-direction: column; 
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            min-width: 0;
        }

        #main-chat.full-width {
            margin-left: 0;
        }

        /* --- TOGGLE BUTTON --- */
        .menu-toggle {
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 2100;
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid var(--glass-border);
            color: white;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }
        .menu-toggle:hover { background: var(--glass-bg); transform: scale(1.05); }

        .brand {
            font-size: 1.5rem; font-weight: 800;
            background: var(--primary-gradient); -webkit-background-clip: text;
            -webkit-text-fill-color: transparent; 
            margin: 50px 0 30px 0;
            display: flex; align-items: center; gap: 10px;
        }

        .nav-link {
            padding: 12px 16px; border-radius: 12px; color: var(--text-dim);
            text-decoration: none; display: flex; align-items: center; gap: 12px;
            transition: 0.3s; margin-bottom: 8px; font-weight: 500;
        }
        .nav-link:hover, .nav-link.active { background: var(--glass-bg); color: white; }

        /* --- CHAT COMPONENTS --- */
        #chat-window { flex: 1; overflow-y: auto; padding: 80px 20px 20px 20px; scroll-behavior: smooth; }
        .chat-content { max-width: 850px; margin: 0 auto; width: 100%; }

        .hero-section { text-align: center; margin-top: 12vh; animation: fadeIn 0.8s ease; }
        .hero-section h1 { font-size: 3.2rem; font-weight: 700; margin-bottom: 10px; letter-spacing: -1.5px; }
        .hero-section p { color: var(--text-dim); font-size: 1.1rem; }

        .bottom-container { padding: 20px; background: linear-gradient(transparent, #0f172a 70%); }
        .input-box {
            max-width: 850px; margin: 0 auto;
            background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border); border-radius: 28px;
            padding: 8px 15px; display: flex; align-items: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.4);
        }
        .input-box input {
            flex: 1; background: transparent; border: none;
            color: white; padding: 12px; font-size: 1rem; outline: none;
        }

        .action-btn {
            width: 45px; height: 45px; border-radius: 50%; border: none;
            background: transparent; color: var(--text-dim); cursor: pointer;
            transition: 0.3s; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;
        }
        .action-btn:hover { background: var(--glass-bg); color: white; }
        .send-btn { background: var(--primary-gradient); color: white; }

        /* --- MESSAGE STYLES --- */
        .message-row { margin-bottom: 40px; animation: slideUp 0.5s ease-out; }
        .user-label { font-weight: 600; margin-bottom: 10px; display: flex; align-items: center; gap: 10px; }
        .ai-card { background: var(--glass-bg); border: 1px solid var(--glass-border); border-radius: 24px; padding: 25px; margin-top: 15px; }
        
        .spinner-container { display: flex; align-items: center; gap: 15px; color: var(--text-dim); }
        .loading-icon { color: #a855f7; font-size: 1.4rem; }
        
        .result-item { border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 20px; margin-bottom: 20px; }
        .result-item:last-child { border-bottom: none; }
        .badge-score { background: rgba(99, 102, 241, 0.2); color: #818cf8; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
        .map-link { color: #38bdf8; text-decoration: none; font-size: 0.9rem; display: flex; align-items: center; gap: 5px; margin-top: 10px; }

        /* --- ANIMATIONS --- */
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .listening-active { color: #ff4d4d !important; animation: pulse 1.5s infinite; }
        @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.4; } 100% { opacity: 1; } }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: var(--glass-border); border-radius: 10px; }

        /* --- RESPONSIVE --- */
        @media (max-width: 768px) {
            #main-chat { margin-left: 0 !important; }
            #sidebar { transform: translateX(-100%); }
            #sidebar.active-mobile { transform: translateX(0); }
            .hero-section h1 { font-size: 2.2rem; }
        }
    </style>
</head>
<body>

    <div class="bg-glow"></div>
    <div id="sidebar-overlay"></div>

    <div class="app-container">
        <button class="menu-toggle" id="menuToggle">
            <i class="fas fa-bars"></i>
        </button>

        <aside id="sidebar">
            <div class="brand">
                <i class="fas fa-bolt"></i> 
                <span>Rohit AI's Model</span>
            </div>
            
            <a href="javascript:void(0);" onclick="window.location.reload();" class="nav-link active">
                <i class="fas fa-plus-circle"></i> New Chat
            </a>
            <a href="javascript:void(0);" onclick="alert('Exploring...');" class="nav-link">
                <i class="fas fa-compass"></i> Explore Model
            </a>
            <a href="javascript:void(0);" onclick="alert('Exploring...');" class="nav-link"><i class="fas fa-history"></i> Chat History</a>
            
            <div style="margin-top: auto;margin-bottom:5%;">
                <a href="javascript:void(0);" onclick="alert('Exploring...');" class="nav-link"><i class="fas fa-cog"></i> Settings</a>
            </div>
        </aside>

        <main id="main-chat">
            <div id="chat-window">
                <div class="chat-content">
                    <div class="hero-section" id="hero">
                        <h1>How can I assist you? <br>By Rohit's AI Model</h1>
                        <p>AI-Powered Semantic Location Discovery Model</p>
                        <p>Search and discover with Rohit's AI Model intelligence.</p>
                    </div>
                    <div id="messages-list"></div>
                </div>
            </div>

            <div class="bottom-container">
                <div class="input-box">
                    <button class="action-btn" id="voiceBtn" title="Voice Input"><i class="fas fa-microphone"></i></button>
                    <input type="text" id="userInput" placeholder="Ask anything..." autocomplete="off">
                    <button class="action-btn send-btn" id="sendBtn"><i class="fas fa-arrow-up"></i></button>
                </div>
            </div>
        </main>
    </div>

    <script>
        $(document).ready(function() {
            const userInput = $('#userInput');
            const messagesList = $('#messages-list');
            const chatWindow = $('#chat-window');
            const voiceBtn = $('#voiceBtn');
            const sidebar = $('#sidebar');
            const mainChat = $('#main-chat');
            const overlay = $('#sidebar-overlay');

            // --- SIDEBAR TOGGLE LOGIC ---
            $('#menuToggle').click(function() {
                const isMobile = window.innerWidth <= 768;
                const icon = $(this).find('i');

                if (isMobile) {
                    sidebar.toggleClass('active-mobile');
                    overlay.fadeToggle();
                } else {
                    sidebar.toggleClass('collapsed');
                    mainChat.toggleClass('full-width');
                }

                // Switch Icon
                if (sidebar.hasClass('collapsed') || (isMobile && !sidebar.hasClass('active-mobile'))) {
                    icon.removeClass('fa-times').addClass('fa-bars');
                } else {
                    icon.removeClass('fa-bars').addClass('fa-times');
                }
            });

            // Close mobile sidebar on overlay click
            overlay.click(function() {
                sidebar.removeClass('active-mobile');
                overlay.fadeOut();
                $('#menuToggle').find('i').addClass('fa-bars').removeClass('fa-times');
            });

            // --- VOICE INPUT ---
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            if (SpeechRecognition) {
                const recognition = new SpeechRecognition();
                recognition.onstart = () => voiceBtn.find('i').addClass('listening-active');
                recognition.onresult = (e) => {
                    const text = e.results[0][0].transcript;
                    userInput.val(text);
                    sendMessage(text);
                };
                recognition.onend = () => voiceBtn.find('i').removeClass('listening-active');
                voiceBtn.click(() => recognition.start());
            }

            // --- SEND MESSAGE LOGIC ---
            function sendMessage(query) {
                if (!query.trim()) return;
                $('#hero').fadeOut();
                userInput.val('');

                const messageId = 'msg-' + Date.now();
                const userHtml = `
                    <div class="message-row">
                        <div class="user-label"><i class="fas fa-user-circle"></i> Rohit</div>
                        <div style="font-size: 1.1rem; margin-left: 32px; color: #cbd5e1;">${query}</div>
                        <div class="ai-card">
                            <div class="user-label" style="color:#a855f7"><i class="fas fa-wand-magic-sparkles"></i> Rohit AI</div>
                            <div id="${messageId}" class="ai-content">
                                <div class="spinner-container">
                                    <i class="fas fa-circle-notch fa-spin loading-icon"></i>
                                    <span>Searching...</span>
                                </div>
                            </div>
                        </div>
                    </div>`;
                
                messagesList.append(userHtml);
                chatWindow.animate({ scrollTop: chatWindow[0].scrollHeight }, 500);

                $.ajax({
                    url: 'search.php',
                    type: 'POST',
                    dataType: 'json',
                    data: { query: query },
                    success: function(data) {
                        let htmlContent = "";
                        if (data && data.length > 0) {
                            data.forEach(item => {
                                // 1. Check if coordinates exist and are valid
								const hasCoordinates = item.latitude != null && item.longitude != null;
								// console.log(hasCoordinates);return false;

								// 2. Generate the map link HTML only if coords are present
								const mapLinkHtml = hasCoordinates 
									? `<a href="https://www.google.com/maps?q=${item.latitude},${item.longitude}" target="_blank" class="map-link">
										<i class="fas fa-map-marker-alt"></i> View on Map
									   </a>` 
									: '';

								// 3. Append to your htmlContent
								htmlContent += `
									<div class="result-item">
										<div style="display:flex; justify-content:space-between; align-items:center;">
											<h3 style="margin:0; color:white; font-size:1.1rem;">${item.place_name}</h3>
											<span class="badge-score">${item.score}% Match</span>
										</div>
										<p style="color:var(--text-dim); margin:10px 0; line-height:1.5;">${item.desc}</p>
										${mapLinkHtml}
									</div>`;
									
									
                            });
                        } else {
                            htmlContent = "<p style='color:var(--text-dim);'>No results found for your query.</p>";
                        }
                        $(`#${messageId}`).hide().html(htmlContent).fadeIn(400);
                    },
                    error: () => {
                        $(`#${messageId}`).html("<span style='color:#ef4444;'>Connection error.</span>");
                    }
                });
            }

            $('#sendBtn').click(() => sendMessage(userInput.val()));
            userInput.keypress(e => { if (e.which == 13) sendMessage(userInput.val()); });
        });
    </script>
</body>
</html>