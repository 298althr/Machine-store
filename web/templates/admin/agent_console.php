<?php
/** @var array|null $agent */
/** @var string|null $agentError */
?>
<script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
<script>
  tailwind.config = {
    darkMode: "class",
    theme: {
      extend: {
        colors: {
          primary: "#3366CC",
          "primary-dark": "#254e99",
          "background-light": "#F3F4F6",
          "background-dark": "#111827",
          "surface-light": "#FFFFFF",
          "surface-dark": "#1F2937",
          "border-light": "#E5E7EB",
          "border-dark": "#374151",
          "message-sent-light": "#3366CC",
          "message-sent-dark": "#3366CC",
          "message-received-light": "#F3F4F6",
          "message-received-dark": "#374151",
          "text-light": "#111827",
          "text-dark": "#F9FAFB",
          "text-muted-light": "#6B7280",
          "text-muted-dark": "#9CA3AF",
        },
        fontFamily: {
          display: ["Inter", "sans-serif"],
          body: ["Inter", "sans-serif"],
        },
      },
    },
  };
</script>
<style>
  :root { --safe-bottom: env(safe-area-inset-bottom, 0px); }
  body { font-family: 'Inter', sans-serif; }
  .custom-scrollbar::-webkit-scrollbar { width: 6px; }
  .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
  .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
  .thread-messages {
    max-height: calc(100vh - 320px);
  }
  .thread-composer {
    position: sticky;
    bottom: 0;
    padding-bottom: calc(16px + var(--safe-bottom));
    z-index: 15;
    box-shadow: 0 -10px 30px rgba(15, 23, 42, 0.08);
  }
  @media (max-width: 1024px) {
    .console-hero { padding: 20px 16px; }
    .admin-console-main { padding: 0 12px 60px; height: auto !important; }
    .console-shell { max-width: none; }
    .agent-console-shell { flex-direction: column; }
    .console-list {
      width: 100%;
      border-right: none;
      border-bottom: 1px solid #e5e7eb;
      max-height: 200px;
    }
  }
  @media (max-width: 768px) {
    .console-actions {
      flex-direction: column;
      align-items: stretch;
      gap: 8px;
    }
    .console-actions button,
    .console-actions select {
      width: 100%;
    }
    .thread-messages {
      max-height: none;
      height: 50vh;
      min-height: 320px;
    }
    #admin-message {
      min-height: 90px;
    }
  }
  @media (max-width: 480px) {
    .agent-console-shell { border-radius: 12px; }
    .thread-messages { height: 55vh; padding: 16px; }
  }
</style>
<section class="admin-agent-console" style="font-family: 'Inter', sans-serif;">
  <header class="console-hero px-6 py-5 bg-background-light dark:bg-background-dark">
    <div class="max-w-7xl mx-auto">
      <p class="text-sm font-medium text-text-muted-light dark:text-text-muted-dark mb-1">Live Agent Desk</p>
      <h1 class="text-3xl font-bold tracking-tight text-text-light dark:text-text-dark mb-2">Customer Chats</h1>
      <?php if (!empty($agent)): ?>
        <p class="text-sm text-text-muted-light dark:text-text-muted-dark">
          Signed in as <span class="font-semibold text-text-light dark:text-text-dark"><?= htmlspecialchars($agent['name']) ?></span>. Telegram replies stay in sync.
        </p>
      <?php endif; ?>
    </div>
  </header>
  <main class="admin-console-main flex-1 px-6 pb-6" style="height: calc(100vh - 140px); min-height: 600px;">
    <div class="console-shell max-w-7xl mx-auto h-full flex flex-col">
      <div class="console-actions flex items-center space-x-3 mb-4">
        <button id="refresh-chats" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-text-light dark:text-text-dark text-sm font-medium rounded transition-colors duration-150 shadow-sm">Refresh</button>
        <div class="relative">
          <select id="chat-filter" class="form-select pl-4 pr-10 py-2 bg-white dark:bg-surface-dark border border-border-light dark:border-border-dark rounded text-sm text-text-light dark:text-text-dark focus:ring-primary focus:border-primary shadow-sm cursor-pointer">
            <option value="open">Open only</option>
            <option value="">All chats</option>
            <option value="closed">Closed</option>
          </select>
        </div>
      </div>

  <?php if (!empty($agentError)): ?>
    <div class="agent-console-error">
      <p><?= htmlspecialchars($agentError) ?></p>
      <a href="/setup-database" class="btn btn-primary">Run setup</a>
    </div>
  <?php else: ?>
      <div class="agent-console-shell bg-surface-light dark:bg-surface-dark rounded-xl shadow-lg border border-border-light dark:border-border-dark flex flex-1 overflow-hidden">
        <aside class="console-list w-80 border-r border-border-light dark:border-border-dark flex flex-col bg-white dark:bg-surface-dark">
          <div class="overflow-y-auto flex-1 custom-scrollbar" id="chat-list">
            <div id="chat-list-empty" class="p-6 text-center text-text-muted-light dark:text-text-muted-dark text-sm">
              <p>No chats yet. They will appear here when customers start messaging.</p>
            </div>
          </div>
        </aside>

        <section class="console-thread flex-1 flex flex-col bg-white dark:bg-surface-dark relative">
          <div id="chat-thread-placeholder" class="flex items-center justify-center h-full text-text-muted-light dark:text-text-muted-dark text-sm">
            <p>Select a chat from the left to start responding.</p>
          </div>
          <div id="chat-thread-panel" hidden class="flex flex-col h-full">
            <header class="px-6 py-4 border-b border-border-light dark:border-border-dark flex justify-between items-center bg-white dark:bg-surface-dark z-10">
              <div>
                <div id="thread-tracking" class="text-sm text-text-muted-light dark:text-text-muted-dark mb-0.5"></div>
                <h2 id="thread-customer" class="text-xl font-bold text-text-light dark:text-text-dark"></h2>
              </div>
              <div class="flex items-center space-x-3">
                <div id="thread-status" class="flex items-center text-green-600 dark:text-green-400 text-sm font-medium">
                  <span class="h-2 w-2 rounded-full bg-green-500 mr-2"></span>
                  Online
                </div>
                <button type="button" id="wipe-chat-btn" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-xs font-semibold text-text-light dark:text-text-dark rounded transition-colors">Wipe Chat</button>
              </div>
            </header>
            <div id="thread-messages" class="thread-messages flex-1 overflow-y-auto p-6 space-y-4 bg-white dark:bg-surface-dark custom-scrollbar"></div>
            <footer class="thread-composer p-4 border-t border-border-light dark:border-border-dark bg-white dark:bg-surface-dark">
              <form id="admin-chat-form" enctype="multipart/form-data">
                <div class="flex items-end gap-3">
                  <div class="relative flex-1">
                    <textarea name="message" id="admin-message" placeholder="Type a reply..." rows="3" class="block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-text-light dark:text-text-dark shadow-sm focus:border-primary focus:ring-primary sm:text-sm resize-none py-3 px-4"></textarea>
                    <div class="absolute bottom-2 right-2 text-gray-400">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    </div>
                  </div>
                  <div class="flex flex-col gap-2">
                    <label class="p-2 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors cursor-pointer">
                      <input type="file" id="admin-attachment" name="attachment" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" style="display: none;">
                      <span class="material-icons text-xl transform rotate-45">attach_file</span>
                    </label>
                    <button type="submit" id="send-btn" class="px-6 py-2 bg-red-700 hover:bg-red-800 text-white font-medium rounded-md shadow-sm transition-colors duration-150 flex items-center justify-center h-[42px]">Send</button>
                  </div>
                </div>
                <div class="mt-2 text-xs text-text-muted-light dark:text-text-muted-dark">Max 5 MB • PDF, JPG, PNG, DOCX</div>
              </form>
            </footer>
          </div>
        </section>
      </div>
    </div>
  </main>
  <?php endif; ?>
</section>

<style>
.admin-agent-console {
  display: flex;
  flex-direction: column;
  gap: 16px;
  padding-bottom: 40px;
}
.agent-console-header {
  display: flex;
  flex-direction: column;
  gap: 12px;
}
.agent-console-actions {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
}
.agent-console-actions select {
  border: 1px solid #cbd5f5;
  border-radius: 10px;
  padding: 8px 12px;
}
.agent-console-grid {
  display: flex;
  flex-direction: column;
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 16px;
  overflow: hidden;
}
.chat-list {
  border-bottom: 1px solid #e2e8f0;
  max-height: 260px;
  overflow-y: auto;
}
.chat-list-item {
  display: flex;
  justify-content: space-between;
  padding: 12px 16px;
  border-bottom: 1px solid #f1f5f9;
  cursor: pointer;
  gap: 12px;
}
.chat-list-item.active {
  background: #f1f5f9;
}
.chat-meta { font-size: 0.85rem; color: #64748b; }
.chat-thread {
  display: flex;
  flex-direction: column;
  min-height: 420px;
}
.thread-body {
  flex: 1;
  padding: 16px;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  gap: 10px;
}
.thread-message { max-width: 85%; padding: 10px 14px; border-radius: 12px; background: #e2e8f0; position: relative; }
.thread-message.agent { align-self: flex-end; background: #2563eb; color: white; }
.thread-message.customer { align-self: flex-start; background: #f8fafc; }
.thread-message .meta { font-size: 0.75rem; opacity: 0.7; margin-bottom: 4px; }
.thread-message.sending { opacity: 0.6; }
.thread-message.sending::after { content: '⏳'; position: absolute; top: 8px; right: 8px; }
.message-actions { display: none; gap: 4px; margin-top: 6px; }
.thread-message:hover .message-actions { display: flex; }
.message-actions button { font-size: 0.75rem; padding: 4px 8px; border: none; border-radius: 6px; cursor: pointer; background: rgba(0,0,0,0.1); color: inherit; }
.message-actions button:hover { background: rgba(0,0,0,0.2); }
.thread-composer {
  border-top: 1px solid #e2e8f0;
  padding: 12px 16px;
}
#admin-message {
  width: 100%;
  border: 1px solid #cbd5f5;
  border-radius: 12px;
  padding: 10px;
  resize: vertical;
  min-height: 70px;
}
.composer-actions {
  margin-top: 8px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.attachment input { display: none; }
.thread-header {
  padding: 16px;
  border-bottom: 1px solid #e2e8f0;
  display: flex;
  justify-content: space-between;
  gap: 12px;
  flex-wrap: wrap;
}
.thread-status { font-weight: 600; color: #0f172a; }
@media (min-width: 960px) {
  .agent-console-grid {
    flex-direction: row;
    min-height: 600px;
  }
  .chat-list {
    width: 320px;
    border-right: 1px solid #e2e8f0;
    border-bottom: none;
    max-height: none;
  }
  .chat-thread { flex: 1; }
}
</style>

<?php if (empty($agentError)): ?>
<script>
(function() {
  const listEl = document.getElementById('chat-list');
  const listEmptyEl = document.getElementById('chat-list-empty');
  const placeholder = document.getElementById('chat-thread-placeholder');
  const panel = document.getElementById('chat-thread-panel');
  const threadMessages = document.getElementById('thread-messages');
  const threadTracking = document.getElementById('thread-tracking');
  const threadCustomer = document.getElementById('thread-customer');
  const threadStatus = document.getElementById('thread-status');
  const filterEl = document.getElementById('chat-filter');
  const refreshBtn = document.getElementById('refresh-chats');
  const form = document.getElementById('admin-chat-form');
  const messageInput = document.getElementById('admin-message');
  const attachmentInput = document.getElementById('admin-attachment');

  let chats = [];
  let activeChatId = null;
  let pollInterval = null;
  let lastMessageId = null;

  function fmtDate(value) {
    if (!value) return '';
    const date = new Date(value.replace(' ', 'T'));
    return date.toLocaleString('en-US', { timeZone: 'America/Chicago' });
  }

  function renderChats() {
    listEl.innerHTML = '';
    if (!chats.length) {
      listEmptyEl.hidden = false;
      return;
    }
    listEmptyEl.hidden = true;
    chats.forEach(chat => {
      const item = document.createElement('button');
      item.type = 'button';
      item.className = 'chat-list-item' + (chat.id === activeChatId ? ' active' : '');
      item.dataset.chatId = chat.id;
      item.innerHTML = `
        <div>
          <strong>${chat.tracking_number}</strong>
          <div class="chat-meta">${chat.customer_name || 'Unknown'} • ${chat.destination_city || ''}</div>
        </div>
        <div class="chat-meta">${fmtDate(chat.last_message_at || chat.updated_at)}</div>
      `;
      item.addEventListener('click', () => selectChat(chat.id));
      listEl.appendChild(item);
    });
  }

  async function fetchChats() {
    const params = new URLSearchParams();
    if (filterEl.value) params.set('status', filterEl.value);
    const res = await fetch('/admin/api/agent/chats?' + params.toString());
    if (!res.ok) return;
    const data = await res.json();
    chats = data.chats || [];
    renderChats();
  }

  function renderMessage(msg, isPending = false) {
    const isAgent = msg.sender_type === 'agent';
    const wrapper = document.createElement('div');
    wrapper.className = isAgent ? 'flex justify-end' : 'flex justify-start';
    wrapper.dataset.messageId = msg.id || 'pending';
    
    const bubble = document.createElement('div');
    bubble.className = 'max-w-[70%]';
    
    const inner = document.createElement('div');
    inner.className = isAgent 
      ? 'bg-primary text-white rounded-2xl rounded-tr-sm px-4 py-2 shadow-sm'
      : 'bg-message-received-light dark:bg-message-received-dark text-text-light dark:text-text-dark rounded-2xl rounded-tl-sm px-4 py-2 shadow-sm';
    if (isPending) inner.style.opacity = '0.6';
    
    const meta = document.createElement('div');
    meta.className = 'text-[10px] mb-1 flex justify-between gap-4';
    meta.style.opacity = isAgent ? '0.75' : '0.7';
    const metaLeft = document.createElement('span');
    metaLeft.textContent = msg.sender_name || msg.sender_type;
    const metaRight = document.createElement('span');
    metaRight.textContent = fmtDate(msg.created_at);
    meta.appendChild(metaLeft);
    meta.appendChild(metaRight);
    inner.appendChild(meta);
    
    if (msg.message) {
      const text = document.createElement('p');
      text.className = 'text-sm';
      text.textContent = msg.message;
      inner.appendChild(text);
    }
    
    if (msg.attachment_name && msg.attachment_url) {
      const link = document.createElement('a');
      link.href = msg.attachment_url;
      link.target = '_blank';
      link.rel = 'noopener';
      link.className = 'text-xs underline mt-1 block';
      link.textContent = '📎 ' + msg.attachment_name;
      inner.appendChild(link);
    }
    
    if (!isPending && msg.id) {
      const actions = document.createElement('div');
      actions.className = 'flex gap-2 mt-2 text-xs';
      const editBtn = document.createElement('button');
      editBtn.textContent = 'Edit';
      editBtn.className = 'px-2 py-1 rounded hover:bg-black hover:bg-opacity-10';
      editBtn.onclick = () => editMessage(msg.id);
      const delBtn = document.createElement('button');
      delBtn.textContent = 'Delete';
      delBtn.className = 'px-2 py-1 rounded hover:bg-black hover:bg-opacity-10';
      delBtn.onclick = () => deleteMessage(msg.id);
      actions.appendChild(editBtn);
      actions.appendChild(delBtn);
      inner.appendChild(actions);
    }
    
    bubble.appendChild(inner);
    wrapper.appendChild(bubble);
    threadMessages.appendChild(wrapper);
  }

  async function selectChat(chatId) {
    activeChatId = chatId;
    lastMessageId = null;
    threadMessages.innerHTML = '';
    panel.hidden = false;
    placeholder.hidden = true;
    const res = await fetch(`/admin/api/agent/chats/${chatId}/messages`);
    if (!res.ok) {
      panel.hidden = true;
      placeholder.hidden = false;
      return;
    }
    const data = await res.json();
    const chat = data.chat;
    threadTracking.textContent = chat.tracking_number;
    threadCustomer.textContent = chat.customer_name || 'Unnamed Customer';
    threadStatus.textContent = chat.is_open ? 'Online' : 'Closed';
    threadMessages.innerHTML = '';
    data.messages.forEach(msg => {
      renderMessage(msg);
      lastMessageId = msg.id;
    });
    threadMessages.scrollTop = threadMessages.scrollHeight;
    if (pollInterval) clearInterval(pollInterval);
    pollInterval = setInterval(pollMessages, 2000);
    pollMessages();
    renderChats();
  }

  async function pollMessages() {
    if (!activeChatId) return;
    const params = new URLSearchParams();
    if (lastMessageId) params.set('since_id', lastMessageId);
    const res = await fetch(`/admin/api/agent/chats/${activeChatId}/messages?` + params.toString());
    if (!res.ok) return;
    const data = await res.json();
    data.messages.forEach(msg => {
      renderMessage(msg);
      lastMessageId = msg.id;
    });
    if (data.messages.length) {
      threadMessages.scrollTop = threadMessages.scrollHeight;
    }
  }

  const sendBtn = document.getElementById('send-btn');
  
  let isSending = false;
  
  async function sendAdminMessage() {
    if (!activeChatId || isSending) return;
    
    const messageText = messageInput.value.trim();
    if (!messageText && !attachmentInput.files.length) return;
    
    // Prevent duplicate sends
    isSending = true;
    sendBtn.disabled = true;
    sendBtn.textContent = 'Sending...';
    
    // Build FormData BEFORE clearing input
    const formData = new FormData(form);
    
    // Clear input immediately after capturing
    const textToSend = messageText;
    messageInput.value = '';
    attachmentInput.value = '';
    
    const pendingMsg = {
      id: null,
      sender_type: 'agent',
      sender_name: 'You',
      message: textToSend,
      created_at: new Date().toISOString().replace('T', ' ').substring(0, 19)
    };
    renderMessage(pendingMsg, true);
    threadMessages.scrollTop = threadMessages.scrollHeight;
    try {
      const res = await fetch(`/admin/api/agent/chats/${activeChatId}/message`, {
        method: 'POST',
        body: formData,
      });
      const data = await res.json();
      
      // Remove pending message
      const pendingEl = threadMessages.querySelector('[data-message-id="pending"]');
      if (pendingEl) pendingEl.remove();
      
      if (res.ok && data.message) {
        renderMessage(data.message);
        lastMessageId = data.message.id;
        threadMessages.scrollTop = threadMessages.scrollHeight;
      } else {
        alert('Failed to send message: ' + (data.error || 'Unknown error'));
        messageInput.value = textToSend; // Restore text on error
      }
    } catch (err) {
      const pendingEl = threadMessages.querySelector('[data-message-id="pending"]');
      if (pendingEl) pendingEl.remove();
      alert('Network error: ' + err.message);
      messageInput.value = textToSend; // Restore text on error
    } finally {
      isSending = false;
      sendBtn.disabled = false;
      sendBtn.textContent = 'Send';
    }
  }
  
  form.addEventListener('submit', async (event) => {
    event.preventDefault();
    await sendAdminMessage();
  });
  
  // Auto-send when file is selected (WhatsApp style)
  attachmentInput.addEventListener('change', async (event) => {
    if (attachmentInput.files.length > 0) {
      await sendAdminMessage();
    }
  });

  // Wipe chat functionality
  const wipeChatBtn = document.getElementById('wipe-chat-btn');
  wipeChatBtn.addEventListener('click', async () => {
    if (!activeChatId) return;
    if (!confirm('Are you sure you want to delete ALL messages in this chat? This cannot be undone.')) return;
    
    try {
      const res = await fetch(`/admin/api/agent/chats/${activeChatId}/wipe`, {
        method: 'DELETE'
      });
      const data = await res.json();
      if (res.ok) {
        threadMessages.innerHTML = '';
        lastMessageId = null;
        alert('Chat wiped successfully');
      } else {
        alert('Failed to wipe chat: ' + (data.error || 'Unknown error'));
      }
    } catch (err) {
      alert('Network error: ' + err.message);
    }
  });
  
  // Global functions for message actions
  window.editMessage = async (messageId) => {
    const msgEl = threadMessages.querySelector(`[data-message-id="${messageId}"]`);
    if (!msgEl) return;
    
    const textEl = msgEl.querySelector('.message-text');
    if (!textEl) return;
    
    const currentText = textEl.textContent;
    const newText = prompt('Edit message:', currentText);
    if (newText === null || newText === currentText) return;
    
    try {
      const res = await fetch(`/admin/api/agent/messages/${messageId}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ message: newText })
      });
      const data = await res.json();
      if (res.ok) {
        textEl.textContent = newText;
      } else {
        alert('Failed to edit message: ' + (data.error || 'Unknown error'));
      }
    } catch (err) {
      alert('Network error: ' + err.message);
    }
  };
  
  window.deleteMessage = async (messageId) => {
    if (!confirm('Delete this message?')) return;
    
    const msgEl = threadMessages.querySelector(`[data-message-id="${messageId}"]`);
    if (!msgEl) return;
    
    try {
      const res = await fetch(`/admin/api/agent/messages/${messageId}`, {
        method: 'DELETE'
      });
      const data = await res.json();
      if (res.ok) {
        msgEl.remove();
      } else {
        alert('Failed to delete message: ' + (data.error || 'Unknown error'));
      }
    } catch (err) {
      alert('Network error: ' + err.message);
    }
  };
  
  filterEl.addEventListener('change', fetchChats);
  refreshBtn.addEventListener('click', fetchChats);

  fetchChats();
})();
</script>
<?php endif; ?>
