<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Chat Modal -->
<div class="modal fade" id="chatModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content chat-modal">
            <div class="modal-header chat-header">
                <h5 class="modal-title">User Messages</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <!-- User List -->
                <div id="userSelection" class="p-4">
                    <p class="text-muted mb-3" style="font-size:0.9rem;">Select a user to view conversation:</p>
                    <div id="userList"></div>
                </div>

                <!-- Chat Interface -->
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

.user-card {
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

.user-card:hover {
    background: #F0F5FF;
    border-color: #2C3E8F;
    transform: translateX(4px);
}

.user-avatar {
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

.user-info {
    flex: 1;
}

.user-name {
    font-weight: 700;
    color: #1E293B;
    font-size: 0.95rem;
    margin-bottom: 2px;
}

.user-role {
    font-size: 0.75rem;
    color: #94a3b8;
    font-weight: 600;
}

.user-unread {
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

.chat-input-area button:hover:not(:disabled) {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(44, 62, 143, 0.3);
}

.chat-input-area button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.back-to-users {
    padding: 12px 20px;
    background: #F8FAFC;
    border-bottom: 1px solid #E2E8F0;
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    transition: background 0.2s;
}

.back-to-users:hover {
    background: #F0F5FF;
}

.back-to-users svg {
    color: #2C3E8F;
}

.back-to-users span {
    font-weight: 700;
    color: #2C3E8F;
    font-size: 0.9rem;
}
</style>

<script>
let currentUserId = null;
let messageCheckInterval = null;

document.addEventListener('DOMContentLoaded', function() {
    loadUnreadCount();
    setInterval(loadUnreadCount, 10000);
    
    document.getElementById('sendBtn').addEventListener('click', sendMessage);
    document.getElementById('messageInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') sendMessage();
    });
});

function loadUnreadCount() {
    fetch('/admin/chat/unread-count')
        .then(r => r.json())
        .then(data => {
            const badge = document.getElementById('globalUnreadBadge');
            if (data.count > 0) {
                badge.textContent = data.count;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        });
}

function loadUsers() {
    fetch('/admin/chat/users')
        .then(r => r.json())
        .then(users => {
            const list = document.getElementById('userList');
            if (users.length === 0) {
                list.innerHTML = '<p class="text-muted text-center">No messages yet.</p>';
                return;
            }
            
            list.innerHTML = users.map(user => `
                <div class="user-card" onclick="selectUser(${user.id}, '${user.full_name}')">
                    <div class="user-avatar">${user.full_name.charAt(0)}</div>
                    <div class="user-info">
                        <div class="user-name">${user.full_name}</div>
                        <div class="user-role">User</div>
                    </div>
                    ${user.unread_count > 0 ? `<div class="user-unread">${user.unread_count}</div>` : ''}
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16" style="color:#94a3b8;">
                        <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                    </svg>
                </div>
            `).join('');
        });
}

function selectUser(userId, userName) {
    currentUserId = userId;
    document.getElementById('userSelection').style.display = 'none';
    document.getElementById('chatInterface').style.display = 'block';
    
    const chatInterface = document.getElementById('chatInterface');
    if (!document.querySelector('.back-to-users')) {
        const backBtn = document.createElement('div');
        backBtn.className = 'back-to-users';
        backBtn.innerHTML = `
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
            </svg>
            <span>Chat with ${userName}</span>
        `;
        backBtn.onclick = backToUserList;
        chatInterface.prepend(backBtn);
    }
    
    loadMessages(userId);
    
    if (messageCheckInterval) clearInterval(messageCheckInterval);
    messageCheckInterval = setInterval(() => loadMessages(userId), 5000);
}

function backToUserList() {
    currentUserId = null;
    if (messageCheckInterval) clearInterval(messageCheckInterval);
    document.getElementById('userSelection').style.display = 'block';
    document.getElementById('chatInterface').style.display = 'none';
    document.querySelector('.back-to-users')?.remove();
    document.getElementById('chatMessages').innerHTML = '';
    loadUsers();
}

function loadMessages(userId) {
    fetch(`/admin/chat/messages/${userId}`)
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
    
    if (!message || !currentUserId) return;
    
    const sendBtn = document.getElementById('sendBtn');
    sendBtn.disabled = true;
    
    fetch('/admin/chat/send', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            receiver_id: currentUserId,
            message: message
        })
    })
    .then(r => {
        if (!r.ok) throw new Error('Failed to send');
        return r.json();
    })
    .then(() => {
        input.value = '';
        loadMessages(currentUserId);
    })
    .catch(err => {
        console.error('Send error:', err);
        alert('Failed to send message. Please try again.');
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

document.getElementById('chatModal').addEventListener('hidden.bs.modal', function() {
    if (messageCheckInterval) clearInterval(messageCheckInterval);
    backToUserList();
});
</script>
