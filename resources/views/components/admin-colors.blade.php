{{-- Colors loaded: P={{ $adminPrimaryColor ?? 'DEFAULT' }} S={{ $adminSecondaryColor ?? 'DEFAULT' }} A={{ $adminAccentColor ?? 'DEFAULT' }} --}}
<style>
    :root {
        --primary-blue: {{ $adminPrimaryColor ?? '#2C3E8F' }} !important;
        --secondary-yellow: {{ $adminSecondaryColor ?? '#FDB913' }} !important;
        --accent-red: {{ $adminAccentColor ?? '#C41E24' }} !important;
        --primary-gradient: linear-gradient(135deg, {{ $adminPrimaryColor ?? '#2C3E8F' }} 0%, #1A2A5C 100%) !important;
        --secondary-gradient: linear-gradient(135deg, {{ $adminSecondaryColor ?? '#FDB913' }} 0%, #E5A500 100%) !important;
    }
    
    /* Floating Action Buttons */
    .floating-btn {
        position: fixed;
        width: 56px;
        height: 56px;
        border-radius: 16px;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        z-index: 999;
    }
    
    .floating-btn:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.18);
    }
    
    .floating-btn:active {
        transform: translateY(-2px);
    }
    
    .floating-btn-settings {
        bottom: 108px;
        right: 28px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .floating-btn-settings:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    }
    
    .floating-btn-chat {
        bottom: 28px;
        right: 28px;
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }
    
    .floating-btn-chat:hover {
        background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
    }
    
    /* Tooltip */
    .floating-btn::before {
        content: attr(data-tooltip);
        position: absolute;
        right: 68px;
        background: #1e293b;
        color: white;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s;
        font-family: 'Inter', sans-serif;
    }
    
    .floating-btn:hover::before {
        opacity: 1;
    }
    
    .chat-unread-badge {
        position: absolute;
        top: -6px;
        right: -6px;
        background: #ef4444;
        color: white;
        border-radius: 50%;
        min-width: 20px;
        height: 20px;
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 700;
        padding: 0 6px;
        border: 2px solid white;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
    }
</style>

<!-- Floating Chat Button -->
<button id="globalChatBtn" class="floating-btn floating-btn-chat" data-tooltip="Messages">
    <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
        <path d="M2.678 11.894a1 1 0 0 1 .287.801 10.97 10.97 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8.06 8.06 0 0 0 8 14c3.996 0 7-2.807 7-6 0-3.192-3.004-6-7-6S1 4.808 1 8c0 1.468.617 2.83 1.678 3.894zm-.493 3.905a21.682 21.682 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a9.68 9.68 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9.06 9.06 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105z"/>
    </svg>
    <span id="globalUnreadBadge" class="chat-unread-badge">0</span>
</button>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatBtn = document.getElementById('globalChatBtn');
    if (chatBtn) {
        chatBtn.addEventListener('click', function() {
            const modal = document.getElementById('chatModal');
            if (modal) {
                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();
                if (typeof loadUsers === 'function') loadUsers();
            }
        });
    }
    
    loadGlobalUnreadCount();
    setInterval(loadGlobalUnreadCount, 10000);
});

function loadGlobalUnreadCount() {
    fetch('/admin/chat/unread-count')
        .then(r => r.json())
        .then(data => {
            const badge = document.getElementById('globalUnreadBadge');
            if (badge && data.count > 0) {
                badge.textContent = data.count;
                badge.style.display = 'flex';
            } else if (badge) {
                badge.style.display = 'none';
            }
        })
        .catch(err => console.log('Chat error:', err));
}
</script>
