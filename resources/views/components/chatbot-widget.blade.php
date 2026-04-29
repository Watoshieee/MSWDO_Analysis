{{--
MSWDO AI Chatbot Widget
Floating bottom-right panel powered by Google Gemini 1.5 Flash.
Include with: @include('components.chatbot-widget')
--}}

<style>
    /* ── Chatbot Widget Styles ──────────────────────────────────────────── */
    #mswdo-bot-wrap * {
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }

    /* Floating toggle bubble */
    #mswdo-bot-toggle {
        position: fixed;
        bottom: 100px;
        right: 30px;
        z-index: 10500;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 28px rgba(44, 62, 143, 0.45);
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s;
        animation: botSlideIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) both;
    }

    @keyframes botSlideIn {
        from {
            opacity: 0;
            transform: scale(0.5) translateY(20px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    #mswdo-bot-toggle:hover {
        transform: scale(1.12);
        box-shadow: 0 12px 36px rgba(44, 62, 143, 0.55);
    }

    #mswdo-bot-toggle svg {
        width: 28px;
        height: 28px;
        fill: white;
    }

    /* Unread badge */
    #mswdo-bot-badge {
        position: absolute;
        top: -4px;
        right: -4px;
        background: #FDB913;
        color: #1A2A5C;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 0.68rem;
        font-weight: 900;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid white;
        animation: badgePulse 2s ease-in-out infinite;
    }

    @keyframes badgePulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.2);
        }
    }

    #mswdo-bot-badge.hidden {
        display: none;
    }

    /* Chat panel */
    #mswdo-bot-panel {
        position: fixed;
        bottom: 174px;
        right: 30px;
        z-index: 10499;
        width: 370px;
        max-width: calc(100vw - 40px);
        height: 540px;
        max-height: calc(100vh - 140px);
        border-radius: 22px;
        background: #fff;
        box-shadow: 0 24px 64px rgba(0, 0, 0, 0.18), 0 4px 16px rgba(44, 62, 143, 0.12);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transform: scale(0.85) translateY(30px);
        transform-origin: bottom right;
        opacity: 0;
        pointer-events: none;
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.3s;
    }

    #mswdo-bot-panel.open {
        transform: scale(1) translateY(0);
        opacity: 1;
        pointer-events: all;
    }

    /* Panel header */
    #mswdo-bot-header {
        background: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
        padding: 16px 18px;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
    }

    .bot-header-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: rgba(253, 185, 19, 0.2);
        border: 2px solid rgba(253, 185, 19, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .bot-header-avatar svg {
        width: 22px;
        height: 22px;
        fill: #FDB913;
    }

    .bot-header-info {
        flex: 1;
    }

    .bot-header-name {
        color: #fff;
        font-weight: 800;
        font-size: 0.95rem;
        letter-spacing: 0.01em;
    }

    .bot-header-sub {
        color: rgba(255, 255, 255, 0.65);
        font-size: 0.72rem;
        font-weight: 500;
        margin-top: 1px;
    }

    .bot-status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #28a745;
        display: inline-block;
        margin-right: 5px;
        animation: statusPulse 2s ease-in-out infinite;
    }

    @keyframes statusPulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.4;
        }
    }

    #mswdo-bot-close {
        background: rgba(255, 255, 255, 0.12);
        border: none;
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
        flex-shrink: 0;
    }

    #mswdo-bot-close:hover {
        background: rgba(255, 255, 255, 0.25);
    }

    /* FAQ block */
    #mswdo-bot-faq {
        padding: 10px 14px 8px;
        border-bottom: 1px solid #f1f5f9;
        background: #f8faff;
        flex-shrink: 0;
    }

    .bot-faq-title {
        font-size: 0.68rem;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: #2C3E8F;
        margin-bottom: 6px;
    }

    .bot-faq-list {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .bot-faq-btn {
        background: #ffffff;
        border: 1px solid #c7d7f0;
        color: #1e3a8a;
        border-radius: 14px;
        padding: 4px 10px;
        font-size: 0.7rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.18s;
        white-space: nowrap;
    }

    .bot-faq-btn:hover {
        background: #2C3E8F;
        color: #fff;
        border-color: #2C3E8F;
    }

/* Messages area */
    #mswdo-bot-messages {
        flex: 1;
        overflow-y: auto;
        padding: 16px 14px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        scroll-behavior: smooth;
    }

    #mswdo-bot-messages::-webkit-scrollbar {
        width: 4px;
    }

    #mswdo-bot-messages::-webkit-scrollbar-track {
        background: transparent;
    }

    #mswdo-bot-messages::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    /* Message bubbles */
    .bot-msg {
        display: flex;
        gap: 8px;
        align-items: flex-end;
    }

    .bot-msg.user {
        flex-direction: row-reverse;
    }

    .bot-msg-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2C3E8F, #1A2A5C);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .bot-msg-avatar svg {
        width: 14px;
        height: 14px;
        fill: white;
    }

    .bot-msg-avatar.user-av {
        background: linear-gradient(135deg, #FDB913, #E5A500);
    }

    .bot-msg-avatar.user-av svg {
        fill: #1A2A5C;
    }

    .bot-bubble {
        max-width: 78%;
        padding: 10px 14px;
        border-radius: 18px;
        font-size: 0.84rem;
        line-height: 1.55;
        word-break: break-word;
    }

    .bot-bubble.ai {
        background: #f0f5ff;
        color: #1e293b;
        border-bottom-left-radius: 4px;
    }

    .bot-bubble.user {
        background: linear-gradient(135deg, #2C3E8F, #1A2A5C);
        color: white;
        border-bottom-right-radius: 4px;
    }

    .bot-bubble a {
        color: inherit;
        text-decoration: underline;
    }

    /* Markdown-like formatting inside bubbles */
    .bot-bubble strong {
        font-weight: 700;
    }

    .bot-bubble ul,
    .bot-bubble ol {
        margin: 6px 0 0 0;
        padding: 0;
        list-style: none;
    }

    .bot-bubble li {
        margin-bottom: 3px;
    }

    /* Clickable option items inside AI bubbles */
    .bot-option {
        display: inline-block;
        background: white;
        border: 1.5px solid #c7d7f0;
        color: #2C3E8F;
        border-radius: 12px;
        padding: 5px 12px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        margin: 3px 0;
        transition: all 0.18s;
        text-align: left;
        width: 100%;
        font-family: 'Inter', sans-serif;
        line-height: 1.4;
    }

    .bot-option:hover {
        background: #2C3E8F;
        color: white;
        border-color: #2C3E8F;
        transform: translateX(3px);
    }

    /* Typing indicator */
    #mswdo-bot-typing {
        display: none;
        align-items: flex-end;
        gap: 8px;
    }

    #mswdo-bot-typing.show {
        display: flex;
    }

    .typing-dots {
        background: #f0f5ff;
        border-radius: 18px;
        border-bottom-left-radius: 4px;
        padding: 10px 14px;
        display: flex;
        gap: 4px;
        align-items: center;
    }

    .typing-dots span {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #94a3b8;
        animation: typingBounce 1.2s ease-in-out infinite;
    }

    .typing-dots span:nth-child(2) {
        animation-delay: 0.2s;
    }

    .typing-dots span:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes typingBounce {

        0%,
        60%,
        100% {
            transform: translateY(0);
        }

        30% {
            transform: translateY(-8px);
        }
    }

    /* Input area */
    #mswdo-bot-input-area {
        padding: 12px 14px;
        border-top: 1px solid #f1f5f9;
        display: flex;
        gap: 8px;
        align-items: center;
        flex-shrink: 0;
        background: #fff;
    }

    #mswdo-bot-input {
        flex: 1;
        border: 1.5px solid #e2e8f0;
        border-radius: 22px;
        padding: 10px 16px;
        font-size: 0.85rem;
        font-family: 'Inter', sans-serif;
        outline: none;
        transition: border-color 0.2s;
        color: #1e293b;
        background: #f8fafc;
        resize: none;
        max-height: 80px;
        overflow-y: auto;
    }

    #mswdo-bot-input:focus {
        border-color: #2C3E8F;
        background: #fff;
    }

    #mswdo-bot-input::placeholder {
        color: #94a3b8;
    }

    #mswdo-bot-send {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2C3E8F, #1A2A5C);
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    #mswdo-bot-send:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 14px rgba(44, 62, 143, 0.35);
    }

    #mswdo-bot-send:disabled {
        opacity: 0.45;
        cursor: not-allowed;
        transform: none;
    }

    #mswdo-bot-send svg {
        width: 16px;
        height: 16px;
        fill: white;
    }

    /* Footer note */
    .bot-footer-note {
        text-align: center;
        font-size: 0.65rem;
        color: #94a3b8;
        padding: 0 14px 10px;
        flex-shrink: 0;
    }

    /* Mobile */
    @media(max-width:480px) {
        #mswdo-bot-panel {
            width: calc(100vw - 20px);
            right: 10px;
            bottom: 160px;
        }

        #mswdo-bot-toggle {
            right: 16px;
            bottom: 86px;
        }
    }
</style>

{{-- ── Widget HTML ──────────────────────────────────────────────────── --}}
<div id="mswdo-bot-wrap">

    {{-- Toggle bubble --}}
    <button id="mswdo-bot-toggle" onclick="msBot.toggle()" title="MSWDO AI Assistant" aria-label="Open MSWDO chatbot">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M12 2C6.477 2 2 6.477 2 12c0 1.82.487 3.53 1.338 5.007L2.07 21.19a1 1 0 001.24 1.24l4.183-1.268A9.953 9.953 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2zm-1 13H7v-2h4v2zm6 0h-4v-2h4v2zm0-4H7V9h10v2z" />
        </svg>
        <span id="mswdo-bot-badge">1</span>
    </button>

    {{-- Chat panel --}}
    <div id="mswdo-bot-panel" role="dialog" aria-label="MSWDO AI Chatbot">

        {{-- Header --}}
        <div id="mswdo-bot-header">
            <div class="bot-header-avatar">
                <svg viewBox="0 0 24 24">
                    <path
                        d="M12 2C6.477 2 2 6.477 2 12c0 1.82.487 3.53 1.338 5.007L2.07 21.19a1 1 0 001.24 1.24l4.183-1.268A9.953 9.953 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2zm-1 13H7v-2h4v2zm6 0h-4v-2h4v2zm0-4H7V9h10v2z" />
                </svg>
            </div>
            <div class="bot-header-info">
                <div class="bot-header-name">MSWDO Assistant</div>
                <div class="bot-header-sub"><span class="bot-status-dot"></span>Online</div>
            </div>
            <button id="mswdo-bot-close" onclick="msBot.toggle()" aria-label="Close chatbot">✕</button>
        </div>

        {{-- FAQs --}}
        <div id="mswdo-bot-faq">
            <div class="bot-faq-title">FAQs</div>
            <div class="bot-faq-list">
                <button class="bot-faq-btn" onclick="msBot.chip('Paano mag register?')">Paano mag-register?</button>
                <button class="bot-faq-btn" onclick="msBot.chip('Paano mag login?')">Paano mag-login?</button>
                <button class="bot-faq-btn" onclick="msBot.chip('Paano mag apply ng PWD?')">PWD application</button>
                <button class="bot-faq-btn" onclick="msBot.chip('Ano ang requirements ng Solo Parent?')">Solo Parent requirements</button>
            </div>
        </div>



        {{-- Messages --}}
        <div id="mswdo-bot-messages">
            {{-- Greeting message injected by JS --}}
        </div>

        {{-- Typing indicator --}}
        <div id="mswdo-bot-typing">
            <div class="bot-msg-avatar">
                <svg viewBox="0 0 24 24">
                    <path
                        d="M12 2C6.477 2 2 6.477 2 12c0 1.82.487 3.53 1.338 5.007L2.07 21.19a1 1 0 001.24 1.24l4.183-1.268A9.953 9.953 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2zm-1 13H7v-2h4v2zm6 0h-4v-2h4v2zm0-4H7V9h10v2z" />
                </svg>
            </div>
            <div class="typing-dots">
                <span></span><span></span><span></span>
            </div>
        </div>

        {{-- Input --}}
        <div id="mswdo-bot-input-area">
            <textarea id="mswdo-bot-input" placeholder="Magtanong o humingi ng tulong..." rows="1"
                aria-label="Chat message" onkeydown="msBot.onKey(event)"></textarea>
            <button id="mswdo-bot-send" onclick="msBot.send()" aria-label="Send message">
                <svg viewBox="0 0 24 24">
                    <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" />
                </svg>
            </button>
        </div>

        <div class="bot-footer-note">MSWDO · Liliw, Majayjay, Magdalena · Laguna, PH</div>

    </div>{{-- /panel --}}
</div>{{-- /wrap --}}

<script>
    (function () {
        const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
        const ENDPOINT = '/chatbot/message';

        // Conversation history (multi-turn)
        let history = [];
        let isOpen = false;
        let sending = false;

        const panel = document.getElementById('mswdo-bot-panel');
        const messages = document.getElementById('mswdo-bot-messages');
        const input = document.getElementById('mswdo-bot-input');
        const sendBtn = document.getElementById('mswdo-bot-send');
        const typing = document.getElementById('mswdo-bot-typing');
        const badge = document.getElementById('mswdo-bot-badge');

        // ── Greeting ───────────────────────────────────────────────────────
        const greeting = [
            'Mabuhay! \ud83d\udc4b Ako ang inyong **MSWDO AI Assistant** para sa Liliw, Majayjay, at Magdalena, Laguna.',
            '',
            'I-click ang isang button sa itaas o magtanong ng direkta! Maaari akong tumulong sa:',
            '1. Population, Age, Households, Male/Female data',
            '2. 4Ps, PWD, AICS, Solo Parent programs',
            '3. Paano mag-apply at mga requirements',
            '4. Paano mag-login at mag-register sa system'
        ].join('\n');
        appendBotMsg(greeting);

        // ── Toggle open/close ──────────────────────────────────────────────
        window.msBot = {
            toggle() {
                isOpen = !isOpen;
                panel.classList.toggle('open', isOpen);
                if (isOpen) {
                    badge.classList.add('hidden');
                    setTimeout(() => input.focus(), 350);
                    scrollToBottom();
                }
            },
            chip(text) {
                if (!isOpen) this.toggle();
                input.value = text;
                this.send();
            },
            onKey(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    this.send();
                }
                // Auto-resize textarea
                input.style.height = 'auto';
                input.style.height = Math.min(input.scrollHeight, 80) + 'px';
            },
            async send() {
                const msg = input.value.trim();
                if (!msg || sending) return;

                input.value = '';
                input.style.height = 'auto';
                sending = true;
                sendBtn.disabled = true;

                // Show user bubble
                appendUserMsg(msg);

                // Show typing indicator
                typing.classList.add('show');
                messages.appendChild(typing);
                scrollToBottom();

                // Push to history
                history.push({ role: 'user', text: msg });

                try {
                    const resp = await fetch(ENDPOINT, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF,
                        },
                        body: JSON.stringify({
                            message: msg,
                            history: history.slice(-6),
                        }),
                    });

                    const data = await resp.json();
                    typing.classList.remove('show');

                    const reply = data.reply ?? 'Paumanhin, hindi ako nakakuha ng sagot.';
                    appendBotMsg(reply);
                    history.push({ role: 'model', text: reply });

                } catch (err) {
                    typing.classList.remove('show');
                    appendBotMsg('⚠️ Network error. Pakisubukan ulit.');
                }

                sending = false;
                sendBtn.disabled = false;
                scrollToBottom();
                input.focus();
            }
        };

        // ── Helpers ────────────────────────────────────────────────────────
        function appendBotMsg(text) {
            const wrap = document.createElement('div');
            wrap.className = 'bot-msg';
            wrap.innerHTML = `
            <div class="bot-msg-avatar">
                <svg viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.477 2 12c0 1.82.487 3.53 1.338 5.007L2.07 21.19a1 1 0 001.24 1.24l4.183-1.268A9.953 9.953 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2zm-1 13H7v-2h4v2zm6 0h-4v-2h4v2zm0-4H7V9h10v2z"/></svg>
            </div>
            <div class="bot-bubble ai">${formatMarkdown(text)}</div>`;
            messages.appendChild(wrap);
            scrollToBottom();
        }

        function appendUserMsg(text) {
            const wrap = document.createElement('div');
            wrap.className = 'bot-msg user';
            wrap.innerHTML = `
            <div class="bot-bubble user">${escapeHtml(text)}</div>
            <div class="bot-msg-avatar user-av">
                <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
            </div>`;
            messages.appendChild(wrap);
        }

        function scrollToBottom() {
            requestAnimationFrame(() => {
                messages.scrollTop = messages.scrollHeight;
            });
        }

        function escapeHtml(str) {
            return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }

        // Markdown → HTML with clickable list items
        function formatMarkdown(text) {
            let html = escapeHtml(text);

            // Bold **text**
            html = html.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');

            // Process line by line so we can detect list items
            const lines = html.split('\n');
            const out = [];

            lines.forEach(line => {
                // Numbered list: "1. text" or "1) text"
                const numMatch = line.match(/^(\d+)[.)\s]\s+(.+)$/);
                // Bullet list: "• text", "- text", "* text"
                const bulMatch = line.match(/^[•\-\*]\s+(.+)$/);

                if (numMatch) {
                    const label = numMatch[1] + '. ' + numMatch[2];
                    const val = numMatch[2];
                    out.push(`<button class="bot-option" onclick="msBot.chip(this.dataset.val)" data-val="${escAttr(val)}">${label}</button>`);
                } else if (bulMatch) {
                    const val = bulMatch[1];
                    out.push(`<button class="bot-option" onclick="msBot.chip(this.dataset.val)" data-val="${escAttr(val)}">• ${val}</button>`);
                } else {
                    out.push(line);
                }
            });

            return out.join('<br>');
        }

        function escAttr(str) {
            return str.replace(/"/g, '&quot;').replace(/'/g, '&#39;');
        }

    })();
</script>