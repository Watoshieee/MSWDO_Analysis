@if(session('success') || session('error'))
    <div id="adminNotification" style="position:fixed;top:84px;right:18px;z-index:1080;max-width:420px;background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;border:1px solid rgba(255,255,255,.18);border-radius:12px;padding:14px 18px;box-shadow:0 10px 28px rgba(26,42,92,.35);font-size:.84rem;font-weight:700;display:flex;align-items:center;gap:12px;opacity:0;transform:translateX(100px);transition:all 0.4s cubic-bezier(0.68,-0.55,0.265,1.55);overflow:hidden;">
        <span style="flex:1;">{{ session('success') ?: session('error') }}</span>
        <button onclick="dismissNotification()" style="background:rgba(255,255,255,0.15);border:none;color:white;border-radius:6px;width:24px;height:24px;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:1.1rem;line-height:1;padding:0;transition:all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'">&times;</button>
        <div id="notificationTimer" style="position:absolute;bottom:0;left:0;height:3px;background:rgba(253,185,19,0.9);width:100%;transform-origin:left;animation:timerShrink 5s linear forwards;"></div>
    </div>
    <style>
        @keyframes timerShrink {
            from { width: 100%; }
            to { width: 0%; }
        }
    </style>
    <script>
        setTimeout(() => {
            const notif = document.getElementById('adminNotification');
            if (notif) {
                notif.style.opacity = '1';
                notif.style.transform = 'translateX(0)';
            }
        }, 100);
        
        setTimeout(() => dismissNotification(), 5000);
        
        function dismissNotification() {
            const notif = document.getElementById('adminNotification');
            if (notif) {
                notif.style.opacity = '0';
                notif.style.transform = 'translateX(100px)';
                setTimeout(() => notif.remove(), 400);
            }
        }
    </script>
@endif
