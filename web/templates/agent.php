<?php
/**
 * Agent live chat page
 * Variables provided: $initialTracking, $csrfToken, $title
 */
?>
<script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet"/>
<script>
  tailwind.config = {
    darkMode: "class",
    theme: {
      extend: {
        colors: {
          primary: "#c53030",
          secondary: "#3b66cf",
          "background-light": "#f1f5f9",
          "background-dark": "#111827",
          "surface-light": "#ffffff",
          "surface-dark": "#1f2937",
          "border-light": "#e2e8f0",
          "border-dark": "#374151",
        },
        fontFamily: {
          display: ["Inter", "sans-serif"],
          body: ["Inter", "sans-serif"],
        },
        borderRadius: {
          DEFAULT: "0.5rem",
          xl: "0.75rem",
          '2xl': "1rem",
        },
      },
    },
  };
</script>
<style>
  :root { --safe-bottom: env(safe-area-inset-bottom, 0px); }
  html, body { font-family: 'Inter', sans-serif; background: #f1f5f9; height: 100%; margin: 0; padding: 0; }
  .chat-scroll::-webkit-scrollbar { width: 6px; }
  .chat-scroll::-webkit-scrollbar-track { background: transparent; }
  .chat-scroll::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
  .dark .chat-scroll::-webkit-scrollbar-thumb { background-color: #4b5563; }
  
  /* Layout improvements */
  .agent-chat-page {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
  }
  
  @media (max-width: 768px) {
    /* Force viewport fit */
    html, body { overflow: hidden !important; height: 100dvh !important; position: fixed; width: 100%; }
    
    /* Hide site-wide UI elements */
    nav, .site-header, footer, .site-footer, .agent-hero-card, header:not(.agent-thread-header) {
      display: none !important;
    }
    
    .agent-chat-page {
      height: 100dvh;
      padding: 0 !important;
      gap: 0 !important;
    }
    
    .agent-chat-wrapper {
      margin: 0 !important;
      border-radius: 0 !important;
      height: 100%;
      flex: 1;
      display: flex;
      flex-direction: column;
    }
    
    .agent-chat-grid {
      height: 100%;
      flex-direction: column !important;
      flex: 1;
      display: flex;
    }
    
    .agent-thread-panel {
      flex: 1;
      display: flex;
      flex-direction: column;
      height: 0; /* Let flex grow take it */
      min-height: 0 !important;
      order: 1;
      border-radius: 0 !important;
      background: #fff !important;
    }
    
    .agent-thread-body {
      flex: 1 !important;
      height: auto !important;
      max-height: none !important;
      padding: 12px !important;
    }
    
    .agent-composer {
      position: sticky;
      bottom: 0;
      padding: 12px 16px !important;
      padding-bottom: calc(12px + var(--safe-bottom)) !important;
      background: white !important;
      border-top: 1px solid #e2e8f0 !important;
      z-index: 50;
      flex-shrink: 0;
    }
    
    .agent-tracking-panel {
      order: 3;
      padding: 12px 16px !important;
      border-top: 1px solid #e2e8f0;
      border-bottom: none !important;
      flex-shrink: 0;
      background: #f8fafc !important;
    }
    
    .agent-tracking-panel h2 { font-size: 14px !important; margin-bottom: 4px !important; }
    .agent-tracking-panel p { font-size: 11px !important; margin-bottom: 8px !important; }
    .agent-tracking-panel form { display: flex; gap: 8px; margin-top: 4px !important; }
    .agent-tracking-panel label { display: none !important; }
    .agent-tracking-panel input { margin-bottom: 0 !important; padding: 6px 10px !important; font-size: 13px !important; flex: 1; }
    .agent-tracking-panel button { margin: 0 !important; padding: 6px 12px !important; width: auto !important; font-size: 13px !important; }
    .agent-tracking-panel ul { display: none !important; }
    
    #chat-message {
      min-height: 40px !important;
      height: 40px !important;
      font-size: 14px !important;
      padding: 8px 12px !important;
    }
    
    .agent-thread-header {
      padding: 12px 16px !important;
      background: #fff;
      border-bottom: 1px solid #e2e8f0;
    }
  }
</style>
<section class="agent-chat-page" style="padding: 0; background: #f1f5f9; min-height: 100vh;">
  <div class="agent-hero-card" style="background: linear-gradient(135deg, #1e3a8a, #3b82f6); color: white; padding: 32px; border-radius: 18px; margin: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
    <div style="max-width: 800px;">
      <p style="text-transform: uppercase; font-size: 12px; font-weight: 600; letter-spacing: 1px; opacity: 0.9; margin-bottom: 8px;">Customs & Clearance Desk</p>
      <h1 style="font-size: 32px; font-weight: 700; margin-bottom: 12px;">Chat live with your assigned agent</h1>
      <p style="font-size: 16px; opacity: 0.9; line-height: 1.6;">
        Enter your tracking number to reach the specialist assigned to your shipment. Share documents, photos and chat until your cargo is delivered.
      </p>
    </div>
  </div>

  <div class="agent-chat-wrapper" style="display: flex; flex-direction: column; gap: 0; margin: 0 24px 24px; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
    <div class="agent-chat-grid" style="display: flex; flex-wrap: wrap;">
      <div class="agent-tracking-panel" style="flex: 1; min-width: 320px; padding: 32px; border-right: 1px solid #e2e8f0; background: white;">
        <h2 style="font-size: 24px; font-weight: 700; color: #111827; margin-bottom: 8px;">Start a conversation</h2>
        <p style="font-size: 14px; color: #6b7280; margin-bottom: 24px;">Trackings must be active and assigned to an agent.</p>
        <form id="agent-tracking-form">
          <label for="tracking_number" style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px;">Tracking number</label>
          <input type="text" id="tracking_number" name="tracking_number" placeholder="STR20251205XXXX" value="<?= htmlspecialchars($initialTracking) ?>" required style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; font-family: monospace; margin-bottom: 16px;">
          <button type="submit" style="width: 100%; background: #c53030; color: white; font-weight: 600; padding: 12px; border-radius: 8px; border: none; cursor: pointer; transition: all 0.2s; margin-bottom: 16px;" onmouseover="this.style.background='#991b1b'" onmouseout="this.style.background='#c53030'">Connect to agent</button>
        </form>
        <div id="tracking-feedback" role="status" style="font-size: 14px; margin-bottom: 16px;"></div>
        <ul style="list-style: disc; padding-left: 20px; font-size: 13px; color: #6b7280; line-height: 1.8;">
          <li>Only official Streicher tracking numbers are accepted.</li>
          <li>Documents up to 5 MB. Supported: PDF, JPG, PNG, DOCX.</li>
          <li>Chats remain open until delivery, then become read-only.</li>
        </ul>
      </div>

      <div class="agent-thread-panel" style="flex: 2; min-width: 480px; display: flex; flex-direction: column; background: #f9fafb;">
        <header class="agent-thread-header" style="padding: 20px 24px; border-bottom: 1px solid #e2e8f0; background: white; display: flex; justify-content: space-between; align-items: center;">
          <div>
            <p style="font-size: 11px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Live agent channel</p>
            <h3 id="chat-tracking-label" style="font-size: 18px; font-weight: 700; color: #111827; font-family: monospace;">Waiting for tracking…</h3>
            <p id="chat-agent-name" hidden style="font-size: 13px; color: #3b82f6; margin-top: 4px; font-weight: 500;"></p>
          </div>
          <div style="display: flex; align-items: center; gap: 8px;">
            <span style="position: relative; display: flex; height: 12px; width: 12px;">
              <span style="animation: ping 1s cubic-bezier(0, 0, 0.2, 1) infinite; position: absolute; display: inline-flex; height: 100%; width: 100%; border-radius: 9999px; background: #10b981; opacity: 0.75;"></span>
              <span style="position: relative; display: inline-flex; border-radius: 9999px; height: 12px; width: 12px; background: #10b981;"></span>
            </span>
            <span id="chat-status" style="font-size: 14px; font-weight: 500; color: #374151;">Offline</span>
          </div>
        </header>

        <div class="agent-thread-body chat-scroll" style="flex: 1; padding: 24px; overflow-y: auto; min-height: 400px; max-height: 600px;">
          <div id="chat-placeholder" style="display: flex; align-items: center; justify-content: center; height: 100%; color: #9ca3af; font-size: 14px;">
            <p>Enter a valid tracking number to load your conversation.</p>
          </div>
          <div id="chat-messages" hidden style="display: flex; flex-direction: column; gap: 16px;"></div>
        </div>

        <footer id="chat-composer" class="agent-composer" hidden style="padding: 24px; background: white; border-top: 1px solid #e2e8f0;">
          <form id="chat-message-form" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
            <div style="display: flex; align-items: start; gap: 12px;">
              <div style="flex: 1; position: relative;">
                <textarea name="message" id="chat-message" placeholder="Type a message" rows="2" style="width: 100%; padding: 12px 40px 12px 16px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; resize: none; transition: all 0.2s;"></textarea>
                <div style="position: absolute; bottom: 8px; right: 8px; color: #9ca3af; pointer-events: none;">
                  <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 19l-6-6m6 0l-6 6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                </div>
              </div>
              <div style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
                <label style="color: #9ca3af; cursor: pointer; padding: 8px; border-radius: 9999px; transition: all 0.2s;" onmouseover="this.style.background='#f3f4f6'; this.style.color='#374151'" onmouseout="this.style.background='transparent'; this.style.color='#9ca3af'">
                  <input type="file" name="attachment" id="chat-attachment" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" style="display: none;">
                  <span class="material-icons-outlined" style="font-size: 20px; transform: rotate(45deg);">attach_file</span>
                </label>
              </div>
              <button type="submit" style="background: #c53030; color: white; font-weight: 500; padding: 12px 24px; border-radius: 8px; border: none; cursor: pointer; transition: all 0.2s; height: 50px;" onmouseover="this.style.background='#991b1b'" onmouseout="this.style.background='#c53030'">Send</button>
            </div>
            <div style="margin-top: 8px; font-size: 12px; color: #6b7280; font-weight: 500;">Max 5 MB • PDF, JPG, PNG, DOCX</div>
          </form>
        </footer>
      </div>
    </div>
  </div>
</section>

<style>
.agent-chat-page {
  padding: 48px 0 96px;
  display: flex;
  flex-direction: column;
  gap: 32px;
}
.agent-hero {
  background: linear-gradient(135deg, #0f172a, #1e293b);
  color: white;
  padding: 32px;
  border-radius: 18px;
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 32px;
}
.agent-hero .eyebrow {
  text-transform: uppercase;
  letter-spacing: 0.2em;
  font-size: 0.85rem;
  margin-bottom: 12px;
  color: rgba(255,255,255,0.7);
}
.agent-hero h1 {
  margin: 0;
  font-size: clamp(2rem, 5vw, 2.8rem);
}
.agent-hero .subtitle {
  color: rgba(255,255,255,0.8);
  font-size: 1.05rem;
  margin-top: 12px;
}
.agent-stats {
  background: rgba(15,23,42,0.6);
  border: 1px solid rgba(255,255,255,0.1);
  border-radius: 12px;
  padding: 16px 20px;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
  gap: 16px;
}
.agent-stats .label {
  font-size: 0.85rem;
  color: rgba(255,255,255,0.7);
}
.agent-stats strong {
  display: block;
  font-size: 1.2rem;
  margin-top: 4px;
}
.agent-chat-card {
  background: white;
  border-radius: 18px;
  border: 1px solid #e2e8f0;
  display: grid;
  grid-template-columns: minmax(280px, 360px) 1fr;
  overflow: hidden;
}
.tracking-panel {
  padding: 32px;
  border-right: 1px solid #e2e8f0;
  background: #f8fafc;
}
.tracking-panel form {
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin-top: 16px;
}
.tracking-panel input {
  border: 1px solid #cbd5f5;
  border-radius: 10px;
  padding: 12px;
  font-size: 1rem;
}
.tracking-panel button {
  margin-top: 8px;
}
.tracking-panel .rules {
  margin-top: 24px;
  padding-left: 20px;
  color: #475569;
  font-size: 0.95rem;
}
.tracking-panel .rules li { margin-bottom: 6px; }
.chat-panel {
  background: white;
  display: flex;
  flex-direction: column;
}
.chat-header {
  padding: 24px;
  border-bottom: 1px solid #e2e8f0;
  display: flex;
  justify-content: space-between;
}
.chat-status {
  font-weight: 600;
  color: #0f172a;
}
.chat-body {
  flex: 1;
  overflow: hidden;
  position: relative;
}
.chat-messages {
  height: 100%;
  overflow-y: auto;
  padding: 24px;
  display: flex;
  flex-direction: column;
  gap: 12px;
}
.chat-message {
  max-width: 70%;
  padding: 12px 16px;
  border-radius: 16px;
  background: #e2e8f0;
  color: #0f172a;
}
.chat-message.agent { align-self: flex-start; background: #f1f5f9; }
.chat-message.customer { align-self: flex-end; background: #2563eb; color: white; }
.chat-message .meta {
  font-size: 0.8rem;
  opacity: 0.8;
  margin-bottom: 4px;
}
.chat-message .text { white-space: pre-wrap; }
.chat-message .attachment {
  margin-top: 8px;
  font-size: 0.9rem;
}
.chat-composer {
  border-top: 1px solid #e2e8f0;
  padding: 16px 24px;
}
.composer-row {
  display: flex;
  gap: 12px;
}
.composer-row textarea {
  flex: 1;
  border: 1px solid #cbd5f5;
  border-radius: 12px;
  padding: 12px;
  resize: vertical;
}
.attachment-btn input { display: none; }
.feedback { margin-top: 12px; min-height: 24px; }
.feedback.error { color: #dc2626; }
.feedback.success { color: #16a34a; }
@media (max-width: 1024px) {
  .agent-hero { grid-template-columns: 1fr; }
  .agent-chat-card { grid-template-columns: 1fr; }
  .tracking-panel { border-right: none; border-bottom: 1px solid #e2e8f0; }
}
@media (max-width: 640px) {
  .composer-row { flex-direction: column; }
  .chat-message { max-width: 100%; }
  .agent-chat-page { padding: 24px 0 64px; }
}
</style>

<script>
(function() {
  const trackingForm = document.getElementById('agent-tracking-form');
  const trackingInput = document.getElementById('tracking_number');
  const feedback = document.getElementById('tracking-feedback');
  const chatMessages = document.getElementById('chat-messages');
  const chatPlaceholder = document.getElementById('chat-placeholder');
  const chatComposer = document.getElementById('chat-composer');
  const chatStatus = document.getElementById('chat-status');
  const chatTrackingLabel = document.getElementById('chat-tracking-label');
  const messageForm = document.getElementById('chat-message-form');
  const messageInput = document.getElementById('chat-message');
  const attachmentInput = document.getElementById('chat-attachment');

  let chatId = null;
  let pollInterval = null;
  let lastMessageId = null;
  let chatToken = null;

  function setFeedback(message, type = 'info') {
    feedback.textContent = message || '';
    feedback.className = 'feedback ' + type;
  }

  function resetChat() {
    chatId = null;
    chatToken = null;
    lastMessageId = null;
    chatStatus.textContent = 'Offline';
    chatTrackingLabel.textContent = 'Waiting for tracking…';
    chatMessages.innerHTML = '';
    chatMessages.hidden = true;
    chatPlaceholder.hidden = false;
    chatComposer.hidden = true;
    if (pollInterval) {
      clearInterval(pollInterval);
      pollInterval = null;
    }
  }

  function renderMessage(msg) {
    // Check if message already exists
    if (msg.id && document.querySelector(`[data-message-id="${msg.id}"]`)) {
      return;
    }
    
    const isCustomer = msg.sender_type === 'customer';
    const wrapper = document.createElement('div');
    wrapper.style.display = 'flex';
    wrapper.style.flexDirection = 'column';
    wrapper.style.alignItems = isCustomer ? 'flex-end' : 'flex-start';
    if (msg.id) {
      wrapper.setAttribute('data-message-id', msg.id);
    }

    const bubble = document.createElement('div');
    bubble.style.maxWidth = '80%';
    bubble.style.background = isCustomer ? '#3b66cf' : '#e5e7eb';
    bubble.style.color = isCustomer ? 'white' : '#111827';
    bubble.style.borderRadius = '16px';
    bubble.style.borderTopRightRadius = isCustomer ? '4px' : '16px';
    bubble.style.borderTopLeftRadius = isCustomer ? '16px' : '4px';
    bubble.style.padding = '12px 16px';
    bubble.style.boxShadow = '0 1px 2px rgba(0,0,0,0.1)';

    const meta = document.createElement('div');
    meta.style.fontSize = '11px';
    meta.style.opacity = isCustomer ? '0.9' : '0.7';
    meta.style.marginBottom = '4px';
    meta.style.display = 'flex';
    meta.style.justifyContent = 'space-between';
    meta.style.gap = '16px';
    const sender = msg.sender_name || (msg.sender_type === 'customer' ? 'You' : 'Agent');
    const date = new Date(msg.created_at).toLocaleString('en-US', { timeZone: 'America/Chicago' });
    meta.innerHTML = `<span>${sender}</span><span>${date}</span>`;
    bubble.appendChild(meta);

    if (msg.message) {
      const text = document.createElement('p');
      text.style.fontSize = '14px';
      text.style.margin = '0';
      text.style.lineHeight = '1.5';
      text.textContent = msg.message;
      bubble.appendChild(text);
    }

    if (msg.attachment_name && msg.attachment_url) {
      const attachment = document.createElement('div');
      attachment.style.marginTop = '8px';
      attachment.style.fontSize = '13px';
      const link = document.createElement('a');
      link.href = msg.attachment_url;
      link.target = '_blank';
      link.rel = 'noopener';
      link.style.color = isCustomer ? 'white' : '#3b82f6';
      link.style.textDecoration = 'underline';
      link.textContent = `📎 ${msg.attachment_name}`;
      attachment.appendChild(link);
      bubble.appendChild(attachment);
    }

    wrapper.appendChild(bubble);
    chatMessages.appendChild(wrapper);
  }

  async function pollMessages() {
    if (!chatId || !chatToken) return;
    try {
      const params = new URLSearchParams();
      params.set('chat_id', chatId);
      params.set('token', chatToken);
      if (lastMessageId) {
        params.set('since_id', lastMessageId);
      }
      const res = await fetch('/api/agent-chat/messages?' + params.toString());
      if (!res.ok) throw new Error('poll_failed');
      const data = await res.json();
      if (Array.isArray(data.messages)) {
        data.messages.forEach(msg => {
          renderMessage(msg);
          lastMessageId = msg.id;
        });
        if (data.messages.length > 0) {
          chatMessages.hidden = false;
          chatPlaceholder.hidden = true;
          chatMessages.scrollTop = chatMessages.scrollHeight;
        }
      }
      if (data.status) {
        chatStatus.textContent = data.status;
      }
      if (data.is_open === false) {
        chatComposer.hidden = true;
        setFeedback('This chat is closed because the shipment is delivered.', 'info');
        clearInterval(pollInterval);
      }
    } catch (err) {
      console.error('Polling error', err);
    }
  }

  trackingForm.addEventListener('submit', async (event) => {
    event.preventDefault();
    resetChat();
    setFeedback('Connecting to your agent…');
    const trackingNumber = trackingInput.value.trim();
    if (!trackingNumber) {
      setFeedback('Tracking number is required', 'error');
      return;
    }
    try {
      const res = await fetch('/api/agent-chat/start', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ tracking_number: trackingNumber, csrf_token: '<?= htmlspecialchars($csrfToken) ?>' }),
      });
      const data = await res.json();
      if (!res.ok || !data.ok) {
        throw new Error(data.error || 'Unable to start chat');
      }
      chatId = data.chat_id;
      chatToken = data.token;
      chatTrackingLabel.textContent = data.tracking_number;
      chatStatus.textContent = data.is_open ? 'Online' : 'Closed';
      chatComposer.hidden = !data.is_open;
      chatPlaceholder.hidden = true;
      chatMessages.hidden = false;
      chatMessages.innerHTML = '';
      
      // Show assigned agent name
      const agentNameEl = document.getElementById('chat-agent-name');
      if (data.agent && data.agent.name) {
        agentNameEl.textContent = `Assigned Agent: ${data.agent.name}`;
        agentNameEl.hidden = false;
      }
      
      setFeedback('Connected. Chat history loading…', 'success');
      if (Array.isArray(data.messages)) {
        data.messages.forEach(msg => {
          renderMessage(msg);
          lastMessageId = msg.id;
        });
        chatMessages.scrollTop = chatMessages.scrollHeight;
      }
      pollInterval = setInterval(pollMessages, 4000);
      pollMessages();
    } catch (err) {
      console.error(err);
      setFeedback('Unable to connect. Please verify your tracking number.', 'error');
    }
  });

  let isSending = false;
  
  async function sendMessage() {
    if (!chatId || !chatToken || isSending) return;
    
    const messageText = messageInput.value.trim();
    const hasFile = attachmentInput.files.length > 0;
    if (!messageText && !hasFile) return;
    
    isSending = true;
    const formData = new FormData(messageForm);
    formData.append('chat_id', chatId);
    formData.append('token', chatToken);
    
    // Clear input immediately
    const textToSend = messageText;
    messageInput.value = '';
    const fileToSend = hasFile ? attachmentInput.files[0].name : null;
    attachmentInput.value = '';
    
    setFeedback('Sending message…');
    try {
      const res = await fetch('/api/agent-chat/send', {
        method: 'POST',
        body: formData,
      });
      const data = await res.json();
      if (!res.ok || !data.ok) {
        throw new Error(data.error || 'send_failed');
      }
      setFeedback('Message sent', 'success');
      renderMessage(data.message);
      lastMessageId = data.message.id;
      chatMessages.scrollTop = chatMessages.scrollHeight;
    } catch (err) {
      console.error(err);
      setFeedback('Failed to send message. ' + (err.message || ''), 'error');
      messageInput.value = textToSend; // Restore on error
    } finally {
      isSending = false;
    }
  }
  
  messageForm.addEventListener('submit', async (event) => {
    event.preventDefault();
    await sendMessage();
  });
  
  // Auto-send when file is selected (WhatsApp style)
  attachmentInput.addEventListener('change', async (event) => {
    if (attachmentInput.files.length > 0) {
      await sendMessage();
    }
  });

  // Auto connect if tracking prefilled
  if (trackingInput.value.trim()) {
    trackingForm.dispatchEvent(new Event('submit'));
  }
})();
</script>
