<?php
/**
 * Agent live chat page
 * Variables provided: $initialTracking, $csrfToken, $title
 */
?>

<div class="container-modern section-padding" style="padding-top: 40px;">
  <!-- Cinematic Header Matrix -->
  <section style="position: relative; border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 80px; min-height: 400px; display: flex; align-items: center; padding: 100px; background: #0f172a; box-shadow: var(--shadow-2xl);">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(90deg, rgba(15, 23, 42, 1) 0%, rgba(15, 23, 42, 0.7) 100%), url('https://images.unsplash.com/photo-1516387933999-ed331c9c5a6d?q=80&w=2070&auto=format&fit=crop') center/cover; opacity: 0.8;"></div>
    <div style="position: relative; z-index: 1; color: white; max-width: 800px;">
      <div style="display: inline-block; padding: 8px 24px; background: var(--accent); border-radius: 8px; font-size: 0.85rem; font-weight: 900; text-transform: uppercase; margin-bottom: 32px; letter-spacing: 4px; box-shadow: 0 10px 20px rgba(220, 38, 38, 0.3);">
        Customs & Clearance Matrix
      </div>
      <h1 style="font-size: 5rem; font-family: 'Outfit', sans-serif; line-height: 0.95; margin: 0 0 32px 0; font-weight: 900; letter-spacing: -3px;">Direct Agent<br><span style="color: var(--accent);">Communication.</span></h1>
      <p style="font-size: 1.5rem; color: rgba(255,255,255,0.7); max-width: 650px; line-height: 1.6; font-weight: 500; letter-spacing: -0.5px;">
        Initialize real-time technical synchronization with your assigned logistics engineering specialist.
      </p>
    </div>
  </section>

  <div class="agent-chat-wrapper" style="display: grid; grid-template-columns: 400px 1fr; gap: 60px; max-width: 1400px; margin: 0 auto 120px;">
    <!-- Authorization Panel -->
    <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-2xl); border: 1px solid rgba(0,0,0,0.05); overflow: hidden; height: fit-content;">
      <div style="padding: 40px 60px; background: #f8fafc; border-bottom: 2px solid #f1f5f9; position: relative; overflow: hidden;">
        <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; color: var(--primary); margin: 0; font-weight: 900; letter-spacing: -1px; position: relative; z-index: 1;">Portal Access</h3>
        <p style="color: var(--accent); font-size: 0.75rem; margin: 8px 0 0 0; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; position: relative; z-index: 1;">Synchronization Registry</p>
      </div>
      <div style="padding: 60px;">
        <form id="agent-tracking-form">
          <div class="form-group-modern" style="margin-bottom: 32px;">
            <label style="font-size: 0.75rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); display: block; margin-bottom: 12px;">Asset Registry Number</label>
            <input type="text" id="tracking_number" name="tracking_number" placeholder="STR20251205XXXX" value="<?= htmlspecialchars($initialTracking) ?>" required style="width: 100%; height: 64px; padding: 0 24px; border: 3px solid #f8fafc; border-radius: 8px; font-weight: 900; font-size: 1.1rem; outline: none; transition: all 0.4s; background: #f8fafc; font-family: 'Outfit', sans-serif; color: var(--primary); text-transform: uppercase;" onfocus="this.style.borderColor='var(--accent)'; this.style.background='white';" onblur="this.style.borderColor='#f8fafc'; this.style.background='#f8fafc';">
          </div>
          <button type="submit" class="btn-modern btn-accent" style="width: 100%; height: 72px; font-size: 1.1rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; border-radius: 8px;">Initialize Connection</button>
        </form>
        <div id="tracking-feedback" style="margin-top: 24px; font-size: 0.9rem; font-weight: 700;"></div>
        
        <div style="margin-top: 48px; border-top: 2px solid #f1f5f9; padding-top: 40px;">
          <h4 style="font-family: 'Outfit', sans-serif; font-size: 0.9rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; color: var(--primary); margin-bottom: 24px;">Security Protocol</h4>
          <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 16px;">
            <li style="display: flex; gap: 12px; font-size: 0.9rem; font-weight: 600; color: var(--text-muted);">
              <span style="color: var(--accent);">✓</span> Only verified STREICHER IDs.
            </li>
            <li style="display: flex; gap: 12px; font-size: 0.9rem; font-weight: 600; color: var(--text-muted);">
              <span style="color: var(--accent);">✓</span> End-to-end encrypted sync.
            </li>
            <li style="display: flex; gap: 12px; font-size: 0.9rem; font-weight: 600; color: var(--text-muted);">
              <span style="color: var(--accent);">✓</span> Automatic document archiving.
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Communication Matrix -->
    <div id="chat-panel" style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-2xl); border: 1px solid rgba(0,0,0,0.05); overflow: hidden; display: flex; flex-direction: column; min-height: 800px;">
      <header style="padding: 40px 60px; border-bottom: 2px solid #f1f5f9; background: #f8fafc; display: flex; justify-content: space-between; align-items: center;">
        <div>
          <div id="chat-tracking-label" style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 900; color: var(--primary); letter-spacing: -0.5px;">Waiting for Matrix Sync…</div>
          <div id="chat-agent-name" hidden style="font-size: 0.85rem; font-weight: 900; color: var(--accent); text-transform: uppercase; letter-spacing: 2px; margin-top: 8px;"></div>
        </div>
        <div style="display: flex; align-items: center; gap: 16px; background: white; padding: 12px 24px; border-radius: 40px; border: 2px solid #f1f5f9; box-shadow: var(--shadow-sm);">
          <div style="position: relative; display: flex; height: 12px; width: 12px;">
            <span id="chat-status-pulse" style="animation: ping 1s cubic-bezier(0, 0, 0.2, 1) infinite; position: absolute; display: inline-flex; height: 100%; width: 100%; border-radius: 9999px; background: #94a3b8; opacity: 0.75;"></span>
            <span id="chat-status-dot" style="position: relative; display: inline-flex; border-radius: 9999px; height: 12px; width: 12px; background: #94a3b8;"></span>
          </div>
          <span id="chat-status" style="font-size: 0.85rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted);">Offline</span>
        </div>
      </header>

      <div id="chat-messages" style="flex: 1; padding: 60px; overflow-y: auto; background: white; display: flex; flex-direction: column; gap: 32px;" class="chat-scroll">
        <div id="chat-placeholder" style="text-align: center; margin: auto; max-width: 400px; color: var(--text-muted);">
          <div style="font-size: 5rem; margin-bottom: 32px; opacity: 0.1;">💬</div>
          <p style="font-size: 1.25rem; font-weight: 900; font-family: 'Outfit', sans-serif; color: var(--primary);">Initialize Synchronization</p>
          <p style="font-size: 1rem; font-weight: 500; margin-top: 12px;">Enter a valid Asset Registry Number to load your interdisciplinary conversation matrix.</p>
        </div>
      </div>

      <footer id="chat-composer" hidden style="padding: 40px 60px; background: #f8fafc; border-top: 2px solid #f1f5f9;">
        <form id="chat-message-form" enctype="multipart/form-data">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
          <div style="background: white; border: 3px solid #f1f5f9; border-radius: 12px; padding: 12px; display: flex; flex-direction: column; gap: 20px; transition: all 0.3s;" id="composer-matrix">
            <textarea name="message" id="chat-message" placeholder="Type your technical synchronization message..." style="width: 100%; height: 120px; padding: 20px; border: none; font-size: 1.1rem; font-weight: 500; outline: none; resize: none; font-family: inherit; background: transparent;" onfocus="document.getElementById('composer-matrix').style.borderColor='var(--accent)'" onblur="document.getElementById('composer-matrix').style.borderColor='#f1f5f9'"></textarea>
            
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px 24px; background: #f8fafc; border-radius: 8px;">
              <div style="display: flex; gap: 32px; align-items: center;">
                <label style="cursor: pointer; display: flex; align-items: center; gap: 12px; font-weight: 900; font-size: 0.85rem; color: var(--primary); text-transform: uppercase; letter-spacing: 1px;">
                  <span style="font-size: 1.5rem;">📎</span> ATTACH DOCUMENT MATRIX
                  <input type="file" name="attachment" id="chat-attachment" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" style="display: none;">
                </label>
                <div id="attachment-name" style="font-size: 0.85rem; font-weight: 700; color: var(--accent); letter-spacing: 0.5px;"></div>
              </div>
              <button type="submit" class="btn-modern btn-accent" style="padding: 16px 40px; font-size: 0.9rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; border-radius: 8px;">Initialize Sync</button>
            </div>
          </div>
        </form>
      </footer>
    </div>
  </div>
</div>

<style>
.chat-scroll::-webkit-scrollbar { width: 8px; }
.chat-scroll::-webkit-scrollbar-track { background: transparent; }
.chat-scroll::-webkit-scrollbar-thumb { background: #f1f5f9; border-radius: 4px; }
.chat-scroll::-webkit-scrollbar-thumb:hover { background: #e2e8f0; }

.message-bubble {
  max-width: 80%;
  padding: 32px;
  border-radius: 12px;
  box-shadow: var(--shadow-sm);
  position: relative;
  transition: all 0.3s;
}
.message-bubble:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }

@keyframes ping {
  75%, 100% { transform: scale(2); opacity: 0; }
}

.feedback.error { color: var(--accent); }
.feedback.success { color: #16a34a; }
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
  const chatStatusDot = document.getElementById('chat-status-dot');
  const chatStatusPulse = document.getElementById('chat-status-pulse');
  const chatTrackingLabel = document.getElementById('chat-tracking-label');
  const messageForm = document.getElementById('chat-message-form');
  const messageInput = document.getElementById('chat-message');
  const attachmentInput = document.getElementById('chat-attachment');
  const attachmentNameDisplay = document.getElementById('attachment-name');

  let chatId = null;
  let pollInterval = null;
  let lastMessageId = null;
  let chatToken = null;

  function setFeedback(message, type = 'info') {
    feedback.textContent = message || '';
    feedback.className = 'feedback ' + type;
  }

  function updateStatus(status, isOpen) {
    chatStatus.textContent = status;
    if (isOpen) {
      chatStatusDot.style.background = '#10b981';
      chatStatusPulse.style.background = '#10b981';
      chatStatus.style.color = '#059669';
    } else {
      chatStatusDot.style.background = '#94a3b8';
      chatStatusPulse.style.background = '#94a3b8';
      chatStatus.style.color = 'var(--text-muted)';
    }
  }

  function resetChat() {
    chatId = null;
    chatToken = null;
    lastMessageId = null;
    updateStatus('Offline', false);
    chatTrackingLabel.textContent = 'Waiting for Matrix Sync…';
    chatMessages.innerHTML = '';
    chatMessages.appendChild(chatPlaceholder);
    chatPlaceholder.hidden = false;
    chatComposer.hidden = true;
    if (pollInterval) {
      clearInterval(pollInterval);
      pollInterval = null;
    }
  }

  function renderMessage(msg) {
    if (msg.id && document.querySelector(`[data-message-id="${msg.id}"]`)) return;
    
    const isCustomer = msg.sender_type === 'customer';
    const wrapper = document.createElement('div');
    wrapper.style.display = 'flex';
    wrapper.style.flexDirection = 'column';
    wrapper.style.alignItems = isCustomer ? 'flex-end' : 'flex-start';
    if (msg.id) wrapper.setAttribute('data-message-id', msg.id);

    const meta = document.createElement('div');
    meta.style.display = 'flex';
    meta.style.alignItems = 'center';
    meta.style.gap = '12px';
    meta.style.marginBottom = '12px';
    meta.style.fontSize = '0.75rem';
    meta.style.fontWeight = '900';
    meta.style.textTransform = 'uppercase';
    meta.style.letterSpacing = '1px';
    meta.style.color = 'var(--text-muted)';
    
    const sender = msg.sender_name || (isCustomer ? 'AUTHORIZED REPRESENTATIVE' : 'STREICHER AGENT');
    const date = new Date(msg.created_at).toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });
    meta.innerHTML = `<span>${sender}</span> <span style="opacity: 0.3;">•</span> <span>${date}</span>`;
    
    const bubble = document.createElement('div');
    bubble.className = 'message-bubble';
    bubble.style.background = isCustomer ? 'var(--accent)' : '#f8fafc';
    bubble.style.color = isCustomer ? 'white' : 'var(--primary)';
    bubble.style.border = isCustomer ? 'none' : '2px solid #f1f5f9';
    bubble.style.fontWeight = '500';
    bubble.style.lineHeight = '1.6';
    bubble.style.fontSize = '1.05rem';

    if (msg.message) {
      const text = document.createElement('div');
      text.style.whiteSpace = 'pre-wrap';
      text.textContent = msg.message;
      bubble.appendChild(text);
    }

    if (msg.attachment_name && msg.attachment_url) {
      const attachment = document.createElement('div');
      attachment.style.marginTop = msg.message ? '20px' : '0';
      attachment.style.padding = '16px';
      attachment.style.background = isCustomer ? 'rgba(255,255,255,0.1)' : 'white';
      attachment.style.borderRadius = '8px';
      attachment.style.border = isCustomer ? '1px solid rgba(255,255,255,0.2)' : '1px solid #f1f5f9';
      attachment.style.display = 'flex';
      attachment.style.alignItems = 'center';
      attachment.style.gap = '12px';
      
      attachment.innerHTML = `
        <span style="font-size: 1.5rem;">📄</span>
        <div style="flex: 1;">
          <div style="font-size: 0.9rem; font-weight: 900;">${msg.attachment_name}</div>
          <a href="${msg.attachment_url}" target="_blank" style="font-size: 0.75rem; font-weight: 900; color: inherit; text-decoration: underline; text-transform: uppercase; letter-spacing: 1px;">Initialize Download</a>
        </div>
      `;
      bubble.appendChild(attachment);
    }

    wrapper.appendChild(meta);
    wrapper.appendChild(bubble);
    chatMessages.appendChild(wrapper);
    chatMessages.scrollTop = chatMessages.scrollHeight;
  }

  async function pollMessages() {
    if (!chatId || !chatToken) return;
    try {
      const params = new URLSearchParams();
      params.set('chat_id', chatId);
      params.set('token', chatToken);
      if (lastMessageId) params.set('since_id', lastMessageId);
      
      const res = await fetch('/api/agent-chat/messages?' + params.toString());
      if (!res.ok) throw new Error('poll_failed');
      const data = await res.json();
      
      if (Array.isArray(data.messages)) {
        data.messages.forEach(msg => {
          renderMessage(msg);
          lastMessageId = msg.id;
        });
      }
      if (data.status) updateStatus(data.status, data.is_open);
      if (data.is_open === false) {
        chatComposer.hidden = true;
        setFeedback('Synchronization matrix terminated: Asset delivered.', 'info');
        clearInterval(pollInterval);
      }
    } catch (err) {
      console.error('Polling error', err);
    }
  }

  trackingForm.addEventListener('submit', async (event) => {
    event.preventDefault();
    resetChat();
    setFeedback('Synchronizing with Global Agent Matrix…');
    const trackingNumber = trackingInput.value.trim();
    if (!trackingNumber) return setFeedback('Registry identifier required', 'error');

    try {
      const res = await fetch('/api/agent-chat/start', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ tracking_number: trackingNumber, csrf_token: '<?= htmlspecialchars($csrfToken) ?>' }),
      });
      const data = await res.json();
      if (!res.ok || !data.ok) throw new Error(data.error || 'Sync Failure');

      chatId = data.chat_id;
      chatToken = data.token;
      chatTrackingLabel.textContent = `Asset: ${data.tracking_number}`;
      updateStatus(data.is_open ? 'Online Sync' : 'Matrix Closed', data.is_open);
      chatComposer.hidden = !data.is_open;
      chatPlaceholder.hidden = true;
      chatMessages.innerHTML = '';
      
      const agentNameEl = document.getElementById('chat-agent-name');
      if (data.agent && data.agent.name) {
        agentNameEl.textContent = `Assigned Engineering Specialist: ${data.agent.name}`;
        agentNameEl.hidden = false;
      }
      
      setFeedback('Matrix Synchronized. Loading Registry History…', 'success');
      if (Array.isArray(data.messages)) {
        data.messages.forEach(msg => {
          renderMessage(msg);
          lastMessageId = msg.id;
        });
      }
      pollInterval = setInterval(pollMessages, 4000);
      pollMessages();
    } catch (err) {
      console.error(err);
      setFeedback('Matrix Sync Failure. Verify Registry credentials.', 'error');
    }
  });

  attachmentInput.addEventListener('change', () => {
    const fileName = attachmentInput.files[0]?.name;
    attachmentNameDisplay.textContent = fileName ? `✓ ${fileName}` : '';
  });

  messageForm.addEventListener('submit', async (event) => {
    event.preventDefault();
    if (!chatId || !chatToken) return;
    
    const submitBtn = messageForm.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = 'DISPATCHING...';
    
    const formData = new FormData(messageForm);
    formData.append('chat_id', chatId);
    formData.append('token', chatToken);
    
    try {
      const res = await fetch('/api/agent-chat/send', { method: 'POST', body: formData });
      const data = await res.json();
      if (!res.ok || !data.ok) throw new Error(data.error || 'dispatch_failed');
      
      messageInput.value = '';
      attachmentInput.value = '';
      attachmentNameDisplay.textContent = '';
      renderMessage(data.message);
      lastMessageId = data.message.id;
    } catch (err) {
      console.error(err);
      setFeedback('Dispatch Failure. Matrix synchronization interrupted.', 'error');
    } finally {
      submitBtn.disabled = false;
      submitBtn.textContent = originalBtnText;
    }
  });

  if (trackingInput.value.trim()) trackingForm.dispatchEvent(new Event('submit'));
})();
</script>
