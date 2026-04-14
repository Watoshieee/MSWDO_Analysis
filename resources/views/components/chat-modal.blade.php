<!-- Chat Button (Fixed Position) -->
<button id="chatBtn" class="chat-float-btn" title="Contact Admin">
    <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
        <path d="M2.678 11.894a1 1 0 0 1 .287.801 10.97 10.97 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8.06 8.06 0 0 0 8 14c3.996 0 7-2.807 7-6 0-3.192-3.004-6-7-6S1 4.808 1 8c0 1.468.617 2.83 1.678 3.894zm-.493 3.905a21.682 21.682 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a9.68 9.68 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9.06 9.06 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105z"/>
    </svg>
    <span id="unreadBadge" class="chat-badge" style="display:none;">0</span>
</button>

<!-- Chat Modal -->
<div class="modal fade" id="chatModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content chat-modal">
            <div class="modal-header chat-header">
                <h5 class="modal-title">Contact Admin</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <!-- Admin Selection (shown initially) -->
                <div id="adminSelection" class="p-4">
                    <p class="text-muted mb-3" style="font-size:0.9rem;">Select an admin from your municipality to start chatting:</p>
                    <div id="adminList"></div>
                </div>

                <!-- Chat Interface (hidden initially) -->
                <div id="chatInterface" style="display:none;">
                    <div class="chat-messages" id="chatMessages"></div>
                    <div class="chat-input-area">
                        <input type="text" id="messageInput" class="form-control" placeholder="Type your message..." maxlength="1000">
                        <button id="sendBtn" class="btn btn-primary">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576 6.636 10.07Zm6.787-8.201L1.591 6.602l4.339 2.76 7.494-7.493Z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.chat-float-btn {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
    color: white;
    border: none;
    box-shadow: 0 8px 24px rgba(44, 62, 143, 0.35);
    cursor: pointer;
    transition: all 0.3s;
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
}

.chat-float-btn:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(44, 62, 143, 0.45);
}

.chat-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #C41E24;
    color: white;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    font-weight: 800;
    border: 3px solid white;
}

.chat-modal {
    border-radius: 20px;
    overflow: hidden;
    border: none;
}

.chat-header {
    background: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
    color: white;
    padding: 18px 24px;
    border: none;
}

.chat-header .modal-title {
    font-weight: 800;
    font-size: 1.1rem;
}

.admin-card {
    padding: 14px 18px;
    border-radius: 12px;
    border: 1px solid #E2E8F0;
    background: white;
    margin-bottom: 10px;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 12px;
}

.admin-card:hover {
    background: #F0F5FF;
    border-color: #2C3E8F;
    transform: translateX(4px);
}

.admin-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 1rem;
    flex-shrink: 0;
}

.admin-info {
    flex: 1;
}

.admin-name {
    font-weight: 700;
    color: #1E293B;
    font-size: 0.95rem;
    margin-bottom: 2px;
}

.admin-role {
    font-size: 0.75rem;
    color: #94a3b8;
    font-weight: 600;
}

.chat-messages {
    height: 400px;
    overflow-y: auto;
    padding: 20px;
    background: #F8FAFC;
}

.message {
    margin-bottom: 16px;
    display: flex;
    flex-direction: column;
}

.message.sent {
    align-items: flex-end;
}

.message.received {
    align-items: flex-start;
}

.message-bubble {
    max-width: 70%;
    padding: 10px 14px;
    border-radius: 14px;
    font-size: 0.9rem;
    line-height: 1.5;
    word-wrap: break-word;
}

.message.sent .message-bubble {
    background: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
    color: white;
    border-bottom-right-radius: 4px;
}

.message.received .message-bubble {
    background: white;
    color: #1E293B;
    border: 1px solid #E2E8F0;
    border-bottom-left-radius: 4px;
}

.message-time {
    font-size: 0.7rem;
    color: #94a3b8;
    margin-top: 4px;
    font-weight: 600;
}

.chat-input-area {
    padding: 16px 20px;
    background: white;
    border-top: 1px solid #E2E8F0;
    display: flex;
    gap: 10px;
}

.chat-input-area input {
    flex: 1;
    border-radius: 20px;
    border: 1px solid #E2E8F0;
    padding: 10px 16px;
    font-size: 0.9rem;
}

.chat-input-area input:focus {
    border-color: #2C3E8F;
    box-shadow: 0 0 0 3px rgba(44, 62, 143, 0.1);
}

.chat-input-area button {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
    border: none;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
}

.chat-input-area button:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(44, 62, 143, 0.3);
}

.back-to-admins {
    padding: 12px 20px;
    background: #F8FAFC;
    border-bottom: 1px solid #E2E8F0;
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    transition: background 0.2s;
}

.back-to-admins:hover {
    background: #F0F5FF;
}

.back-to-admins svg {
    color: #2C3E8F;
}

.back-to-admins span {
    font-weight: 700;
    color: #2C3E8F;
    font-size: 0.9rem;
}
</style>

<script>
let currentAdminId = null;
let messageCheckInterval = null;

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    loadUnreadCount();
    
    // Check for new messages every 10 seconds
    setInterval(loadUnreadCount, 10000);
    
    document.getElementById('chatBtn').addEventListener('click', function() {
        const modal = new bootstrap.Modal(document.getElementById('chatModal'));
        modal.show();
        loadAdmins();
    });
    
    document.getElementById('sendBtn').addEventListener('click', sendMessage);
    document.getElementById('messageInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') sendMessage();
    });
});

function loadUnreadCount() {
    fetch('/chat/unread-count')
        .then(r => r.json())
        .then(data => {
            const badge = document.getElementById('unreadBadge');
            if (data.count > 0) {
                badge.textContent = data.count;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        });
}

function loadAdmins() {
    fetch('/chat/admins')
        .then(r => r.json())
        .then(admins => {
            const list = document.getElementById('adminList');
            if (admins.length === 0) {
                list.innerHTML = '<p class="text-muted text-center">No admins available in your municipality.</p>';
                return;
            }
            
            list.innerHTML = admins.map(admin => `
                <div class="admin-card" onclick="selectAdmin(${admin.id}, '${admin.full_name}')">
                    <div class="admin-avatar">${admin.full_name.charAt(0)}</div>
                    <div class="admin-info">
                        <div class="admin-name">${admin.full_name}</div>
                        <div class="admin-role">Admin - ${admin.municipality}</div>
                    </div>
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16" style="color:#94a3b8;">
                        <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                    </svg>
                </div>
            `).join('');
        });
}

function selectAdmin(adminId, adminName) {
    currentAdminId = adminId;
    document.getElementById('adminSelection').style.display = 'none';
    document.getElementById('chatInterface').style.display = 'block';
    
    // Add back button
    const chatInterface = document.getElementById('chatInterface');
    if (!document.querySelector('.back-to-admins')) {
        const backBtn = document.createElement('div');
        backBtn.className = 'back-to-admins';
        backBtn.innerHTML = `
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
            </svg>
            <span>Chat with ${adminName}</span>
        `;
        backBtn.onclick = backToAdminList;
        chatInterface.prepend(backBtn);
    }
    
    loadMessages(adminId);
    
    // Auto-refresh messages every 5 seconds
    if (messageCheckInterval) clearInterval(messageCheckInterval);
    messageCheckInterval = setInterval(() => loadMessages(adminId), 5000);
}

function backToAdminList() {
    currentAdminId = null;
    if (messageCheckInterval) clearInterval(messageCheckInterval);
    document.getElementById('adminSelection').style.display = 'block';
    document.getElementById('chatInterface').style.display = 'none';
    document.querySelector('.back-to-admins')?.remove();
    document.getElementById('chatMessages').innerHTML = '';
}

function loadMessages(adminId) {
    fetch(`/chat/messages/${adminId}`)
        .then(r => r.json())
        .then(messages => {
            const container = document.getElementById('chatMessages');
            const wasAtBottom = container.scrollHeight - container.scrollTop === container.clientHeight;
            
            container.innerHTML = messages.map(msg => {
                const isSent = msg.sender_id == {{ Auth::id() }};
                const time = new Date(msg.created_at).toLocaleTimeString('en-US', {hour: '2-digit', minute: '2-digit'});
                return `
                    <div class="message ${isSent ? 'sent' : 'received'}">
                        <div class="message-bubble">${escapeHtml(msg.message)}</div>
                        <div class="message-time">${time}</div>
                    </div>
                `;
            }).join('');
            
            if (wasAtBottom || messages.length > 0) {
                container.scrollTop = container.scrollHeight;
            }
            
            loadUnreadCount();
        });
}

function sendMessage() {
    const input = document.getElementById('messageInput');
    const message = input.value.trim();
    
    if (!message || !currentAdminId) return;
    
    const sendBtn = document.getElementById('sendBtn');
    sendBtn.disabled = true;
    
    fetch('/chat/send', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            receiver_id: currentAdminId,
            message: message
        })
    })
    .then(r => {
        if (!r.ok) {
            return r.json().then(err => {
                console.error('Server error:', err);
                throw new Error(err.message || 'Failed to send');
            });
        }
        return r.json();
    })
    .then(() => {
        input.value = '';
        loadMessages(currentAdminId);
    })
    .catch(err => {
        console.error('Send error:', err);
        alert('Failed to send message: ' + err.message);
    })
    .finally(() => {
        sendBtn.disabled = false;
    });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Cleanup on modal close
document.getElementById('chatModal').addEventListener('hidden.bs.modal', function() {
    if (messageCheckInterval) clearInterval(messageCheckInterval);
    backToAdminList();
});
</script>
