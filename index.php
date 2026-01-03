<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    
    <title>Rohit AI's Model | Next-Gen Intelligence</title>
    <meta name="title" content="Rohit AI's Model | Next-Gen Intelligence">
    <meta name="description" content="Advanced AI search and discovery model by Rohit. Explore smart results with a premium glassmorphism interface.">
    <meta name="author" content="Rohit">

    <link rel="icon" type="image/png" href="http://localhost/aimodelrohit/artificial-intelligence.png">

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
            --accent-purple: #a855f7;
            --transition-speed: 0.4s;
        }

        /* Base Styles */
        body, html {
            margin: 0; padding: 0; height: 100%;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: #0f172a; color: var(--text-main);
            overflow: hidden; touch-action: manipulation;
        }

        .bg-glow {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at 50% -20%, #312e81, transparent),
                        radial-gradient(circle at 0% 100%, #1e1b4b, transparent);
            z-index: -1;
        }

        .app-container { display: flex; height: 100vh; width: 100vw; position: relative; }

        /* Sidebar & Navigation */
        #sidebar {
            position: fixed; top: 0; left: 0; width: var(--sidebar-width); height: 100%;
            background: var(--sidebar-bg); backdrop-filter: blur(20px);
            border-right: 1px solid var(--glass-border); display: flex;
            flex-direction: column; padding: 20px; z-index: 2000;
            transition: transform var(--transition-speed) cubic-bezier(0.16, 1, 0.3, 1);
        }

        #sidebar.collapsed { transform: translateX(-100%); }

        #sidebar-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.6); z-index: 1500; display: none;
        }

        .brand {
            font-size: 1.5rem; font-weight: 800; background: var(--primary-gradient);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; 
            margin: 50px 0 30px 0; display: flex; align-items: center; gap: 10px;
        }

        .nav-link {
            padding: 12px 16px; border-radius: 12px; color: var(--text-dim);
            text-decoration: none; display: flex; align-items: center; gap: 12px;
            transition: 0.3s; margin-bottom: 8px; font-weight: 500;
        }
        .nav-link:hover, .nav-link.active { background: var(--glass-bg); color: white; }

        /* Main Chat Area */
        #main-chat { 
            flex: 1; margin-left: var(--sidebar-width); display: flex; 
            flex-direction: column; transition: margin-left var(--transition-speed) ease;
            position: relative; min-width: 0;
        }
        #main-chat.full-width { margin-left: 0; }

        .menu-toggle {
            position: fixed; top: 15px; left: 15px; z-index: 2100;
            background: rgba(30, 41, 59, 0.8); border: 1px solid var(--glass-border);
            color: white; width: 42px; height: 42px; border-radius: 12px;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            transition: 0.3s; box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }

        #chat-window { flex: 1; overflow-y: auto; padding: 80px 20px 20px 20px; scroll-behavior: smooth; }
        .chat-content { max-width: 850px; margin: 0 auto; width: 100%; }

        .hero-section { text-align: center; margin-top: 12vh; animation: fadeIn 0.8s ease; padding: 0 20px; }
        .hero-section h1 { font-size: clamp(1.8rem, 5vw, 3.2rem); font-weight: 700; margin-bottom: 10px; letter-spacing: -1.5px; }

        /* Input Styles */
        .bottom-container { padding: 20px; background: linear-gradient(transparent, #0f172a 70%); }
        .input-box {
            max-width: 850px; margin: 0 auto; background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(20px); border: 1px solid var(--glass-border);
            border-radius: 28px; padding: 8px 15px; display: flex; align-items: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.4);
        }
        .input-box input {
            flex: 1; background: transparent; border: none;
            color: white; padding: 12px; font-size: 1rem; outline: none; width: 100%;
        }

        .action-btn {
            width: 42px; height: 42px; border-radius: 50%; border: none;
            background: transparent; color: var(--text-dim); cursor: pointer;
            transition: 0.3s; display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .send-btn { background: var(--primary-gradient) !important; color: white !important; }

        /* Message Styling */
        .message-row { margin-bottom: 40px; animation: slideUp 0.5s ease-out; width: 100%; box-sizing: border-box; }
        .ai-card { 
            background: var(--glass-bg); border: 1px solid var(--glass-border); 
            border-radius: 24px; padding: clamp(15px, 4vw, 25px); margin-top: 15px; 
        }
        .result-item { border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 20px; margin-bottom: 20px; }
        .result-item:last-child { border-bottom: none; }
        
        .badge-score { background: rgba(99, 102, 241, 0.2); color: #818cf8; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; white-space: nowrap; }
        .map-link { color: #38bdf8; text-decoration: none; display: flex; align-items: center; gap: 8px; margin-top: 12px; transition: 0.2s; font-size: 0.9rem; }
        .map-link:hover { text-decoration: underline; opacity: 0.8; }

        /* Voice Controls UI - Very Responsive */
        .voice-controls { 
            display: flex; 
            flex-wrap: wrap; 
            gap: 10px; 
            margin-top: 15px; 
            border-top: 1px solid var(--glass-border); 
            padding-top: 15px; 
        }
        .v-btn { 
            flex: 1;
            min-width: 120px;
            background: rgba(255,255,255,0.1); 
            border: 1px solid var(--glass-border); 
            color: white; 
            padding: 10px 15px; 
            border-radius: 12px; 
            cursor: pointer; 
            font-size: 0.85rem; 
            transition: 0.3s; 
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .v-btn:hover { background: var(--accent-purple); border-color: transparent; }

        /* Modals */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(15, 23, 42, 0.9); backdrop-filter: blur(8px);
            display: none; align-items: center; justify-content: center; z-index: 3000; padding: 20px;
        }
        .modal-overlay.active { display: flex; animation: fadeIn 0.3s ease; }
        .modal-content {
            background: rgba(30, 41, 59, 0.95); border: 1px solid var(--glass-border);
            padding: 30px; border-radius: 28px; width: 100%; max-width: 500px;
            text-align: center; box-shadow: 0 25px 50px rgba(0,0,0,0.5);
            max-height: 90vh; overflow-y: auto;
        }

        /* Animations */
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .listening-active { color: #ef4444 !important; animation: pulse 1.5s infinite; }
        @keyframes pulse { 50% { opacity: 0.4; } }

        /* Responsive Breakpoints */
        @media (max-width: 1024px) {
            :root { --sidebar-width: 240px; }
        }

        @media (max-width: 768px) {
            #main-chat { margin-left: 0 !important; }
            #sidebar { transform: translateX(-100%); width: 80%; max-width: 300px; }
            #sidebar.active-mobile { transform: translateX(0); }
            #chat-window { padding-top: 70px; }
            .hero-section { margin-top: 8vh; }
            .v-btn { font-size: 0.8rem; padding: 12px 10px; }
            .input-box { border-radius: 20px; }
        }

        @media (max-width: 480px) {
            .brand { margin-top: 60px; font-size: 1.2rem; }
            .ai-card { padding: 15px; border-radius: 18px; }
            .hero-section h1 { font-size: 1.8rem; }
            .bottom-container { padding: 10px; }
            .v-btn { min-width: 100%; } /* Stack buttons on very small phones */
        }
    </style>
</head>
<body>

    <div id="settingsModal" class="modal-overlay">
        <div class="modal-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="margin:0"><i class="fas fa-cog"></i> Settings</h3>
                <span style="cursor:pointer; font-size: 1.5rem;" id="closeSettingsBtn">×</span>
            </div>
            <div style="text-align: left; color: var(--text-dim);">
                <p><strong>Profile:</strong> Update your AI preferences.</p>
                <p><strong>Theme:</strong> Adaptive Dark Mode is enabled.</p>
                <hr style="border: 0; border-top: 1px solid var(--glass-border); margin: 20px 0;">
                <button class="send-btn" style="width:100%; padding:12px; border:none; border-radius:10px; cursor:pointer;">Save Changes</button>
            </div>
        </div>
    </div>

    <div id="exploresModal" class="modal-overlay">
        <div class="modal-content" style="max-width: 800px;">
            <i class="fas fa-microchip" style="font-size: 2.5rem; color: var(--accent-purple); margin-bottom: 15px;"></i>
            <h2>System Architecture</h2>
            <div class="table-container" style="max-height: 350px; overflow-x: auto; margin: 20px 0;">
                <table class="feature-table" style="width: 100%; border-collapse: collapse; text-align: left; min-width: 400px;">
                    <thead>
                        <tr style="background: rgba(99,102,241,0.1);">
                            <th style="padding: 12px; color: var(--accent-purple);">Feature</th>
                            <th style="padding: 12px; color: var(--accent-purple);">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td style="padding: 10px; border-bottom: 1px solid var(--glass-border);">AI Engine</td><td style="padding: 10px; border-bottom: 1px solid var(--glass-border);"><span class="status-badge">Active</span></td></tr>
                        <tr><td style="padding: 10px; border-bottom: 1px solid var(--glass-border);">Embeddings</td><td style="padding: 10px; border-bottom: 1px solid var(--glass-border);"><span class="status-badge">Active</span></td></tr>
                        <tr><td style="padding: 10px; border-bottom: 1px solid var(--glass-border);">Search</td><td style="padding: 10px; border-bottom: 1px solid var(--glass-border);"><span class="status-badge">Active</span></td></tr>
                    </tbody>
                </table>
            </div>
            <button id="closeExplores" class="send-btn" style="width:100%; padding:12px; border:none; border-radius:10px; cursor:pointer;">Back to Chat</button>
        </div>
    </div>

    <div id="welcomeModal" class="modal-overlay">
        <div class="modal-content">
            <i class="fas fa-robot" style="font-size: 3rem; color: var(--accent-purple); margin-bottom: 15px;"></i>
            <h2>Welcome to Rohit AI</h2>
            <p style="color: var(--text-dim); margin-bottom: 25px;">Experience next-gen semantic location discovery.</p>
            <button id="closeModal" class="send-btn" style="width:100%; padding:12px; border:none; border-radius:10px; cursor:pointer;">Get Started</button>
        </div>
    </div>

    <div class="bg-glow"></div>
    <div id="sidebar-overlay"></div>

    <div class="app-container">
        <button class="menu-toggle" id="menuToggle"><i class="fas fa-bars"></i></button>

        <aside id="sidebar">
            <div class="brand"><i class="fas fa-bolt"></i><span>Rohit AI's Model</span></div>
            <a href="javascript:void(0);" onclick="window.location.reload();" class="nav-link active"><i class="fas fa-plus-circle"></i> New Chat</a>
            <a href="javascript:void(0);" id="openExploresBtn" class="nav-link"><i class="fas fa-compass"></i> Explore Model</a>
            <div style="margin-top: auto; padding-bottom: 20px;">
                <a href="javascript:void(0);" id="openSettingsBtn" class="nav-link"><i class="fas fa-cog"></i> Settings</a>
            </div>
        </aside>

        <main id="main-chat">
            <div id="chat-window">
                <div class="chat-content">
                    <div class="hero-section" id="hero">
                        <h1>How can I assist you?</h1>
                        <p style="color: var(--text-dim);">AI-Powered Semantic Location Discovery Model</p>
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
            const sidebar = $('#sidebar');
            const mainChat = $('#main-chat');
            const overlay = $('#sidebar-overlay');

            // --- UI ---
            setTimeout(() => $('#welcomeModal').addClass('active'), 600);
            $('#closeModal').click(() => $('#welcomeModal').removeClass('active'));
            $('#openExploresBtn').click(() => $('#exploresModal').addClass('active'));
            $('#closeExplores').click(() => $('#exploresModal').removeClass('active'));
            $('#openSettingsBtn').click(() => $('#settingsModal').addClass('active'));
            $('#closeSettingsBtn').click(() => $('#settingsModal').removeClass('active'));

            $('#menuToggle').click(function() {
                const isMobile = window.innerWidth <= 768;
                if (isMobile) {
                    sidebar.toggleClass('active-mobile');
                    overlay.fadeToggle();
                } else {
                    sidebar.toggleClass('collapsed');
                    mainChat.toggleClass('full-width');
                }
                $(this).find('i').toggleClass('fa-bars fa-times');
            });

            overlay.click(() => {
                sidebar.removeClass('active-mobile');
                overlay.fadeOut();
                $('#menuToggle i').addClass('fa-bars').removeClass('fa-times');
            });

            // --- Voice STT ---
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            if (SpeechRecognition) {
                const recognition = new SpeechRecognition();
                recognition.onstart = () => $('#voiceBtn i').addClass('listening-active');
                recognition.onend = () => $('#voiceBtn i').removeClass('listening-active');
                recognition.onresult = (e) => {
                    const text = e.results[0][0].transcript;
                    userInput.val(text);
                    sendMessage(text);
                };
                $('#voiceBtn').click(() => recognition.start());
            }

            // --- Voice TTS ---
            
			
			
			function speakText(text) {
				window.speechSynthesis.cancel();
				if (!text.trim()) return;

				const utterance = new SpeechSynthesisUtterance(text);
				const voices = window.speechSynthesis.getVoices();

				// Find Indian Male voice
				// "Rishi" is the standard Apple/Microsoft male voice
				// "Google Hindi" is the standard Android male voice
				const indianMaleVoice = voices.find(voice => 
					(voice.lang === 'en-IN' || voice.lang === 'hi-IN') && 
					(voice.name.toLowerCase().includes('male') || 
					 voice.name.toLowerCase().includes('rishi') || 
					 voice.name.toLowerCase().includes('google hindi'))
				);

				if (indianMaleVoice) {
					utterance.voice = indianMaleVoice;
				} else {
					// Fallback to any Indian voice if specific male one isn't found
					const fallbackIndian = voices.find(v => v.lang === 'en-IN' || v.lang === 'hi-IN');
					if (fallbackIndian) utterance.voice = fallbackIndian;
				}

				// Settings for a deeper, masculine tone
				utterance.pitch = 0.9; // Slightly lower pitch for male voice
				utterance.rate = 0.95; // Slightly slower for better clarity

				window.speechSynthesis.speak(utterance);
			}

			// Crucial: This triggers the voice list to load into memory
			window.speechSynthesis.getVoices();
			if (speechSynthesis.onvoiceschanged !== undefined) {
				speechSynthesis.onvoiceschanged = () => speechSynthesis.getVoices();
			}


			
			

            function sendMessage(query) {
                if (!query.trim()) return;
                $('#hero').fadeOut();
                userInput.val('');

                const messageId = 'msg-' + Date.now();
                const userHtml = `
                    <div class="message-row">
                        <div style="font-weight:600; margin-bottom:10px;"><i class="fas fa-user-circle"></i> Rohit Jain</div>
                        <div style="margin-left:32px; color:#cbd5e1; font-size:1.1rem; word-break: break-word;">${query}</div>
                        <div class="ai-card">
                            <div style="color:var(--accent-purple); font-weight:600; margin-bottom:15px;"><i class="fas fa-wand-magic-sparkles"></i> Rohit AI</div>
                            <div id="${messageId}"><i class="fas fa-circle-notch fa-spin"></i> Processing...</div>
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
								// Prepare the text for this specific item
								const itemText = `${item.place_name}. ${item.desc}.`.replace(/"/g, '&quot;');
								
								htmlContent += `
							<div class="result-item" style="margin-bottom: 25px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 15px;">
								<div style="display:flex; justify-content:space-between; align-items:center; flex-wrap: wrap; gap:10px;">
									<div style="display: flex; flex-direction: column; gap: 4px;">
										<h3 style="margin:0; font-size:1.1rem; color: var(--text-main);">${item.place_name}</h3>
										<span class="badge-score" style="width: fit-content;">${item.score}% Match</span>
									</div>
									
									<div style="display: flex; gap: 8px;">
										<button class="v-btn play-audio" data-text="${itemText}" 
											style="min-width: 70px; padding: 8px 12px; font-size: 0.7rem; background: rgba(99, 102, 241, 0.15); border: 1px solid rgba(99, 102, 241, 0.3);">
											<i class="fas fa-play" style="font-size: 0.65rem;"></i> Play
										</button>
										<button class="v-btn stop-audio" 
											style="min-width: 70px; padding: 8px 12px; font-size: 0.7rem; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2);">
											<i class="fas fa-stop" style="font-size: 0.65rem;"></i> Stop
										</button>
									</div>
								</div>
								<p style="color:var(--text-dim); margin:12px 0 0 0; font-size:0.95rem; line-height:1.6;">${item.desc}</p>
							</div>`;
							});
						} else {
							htmlContent = "<p>No results found.</p>";
						}
						
						$(`#${messageId}`).hide().html(htmlContent).fadeIn(400);
						setTimeout(() => chatWindow.animate({ scrollTop: chatWindow[0].scrollHeight }, 300), 100);
					}
                });
            }

            $(document).on('click', '.play-audio', function() { speakText($(this).attr('data-text')); });
            $(document).on('click', '.stop-audio', function() { window.speechSynthesis.cancel(); });
            $('#sendBtn').click(() => sendMessage(userInput.val()));
            userInput.on('keypress', (e) => { if (e.which == 13) sendMessage(userInput.val()); });
        });
    </script>
</body>
</html>