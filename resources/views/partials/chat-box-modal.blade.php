<div id="chatBoxModal" style="display:none;position:fixed;bottom:20px;right:20px;width:360px;height:500px;
     background:#fff;border-radius:12px;box-shadow:0 8px 32px rgba(0,0,0,.15);z-index:9000;
     flex-direction:column;overflow:hidden;">
    
    {{-- Header --}}
    <div style="background:#4f46e5;color:#fff;padding:14px 16px;display:flex;align-items:center;justify-content:space-between;">
        <div>
            <div id="chatName" style="font-weight:700;font-size:14px;"></div>
            <div id="chatStatus" style="font-size:11px;opacity:.9;"></div>
        </div>
        <button type="button" onclick="closeChatBox()" style="background:rgba(255,255,255,.2);border:none;color:#fff;width:30px;height:30px;border-radius:50%;cursor:pointer;font-size:16px;">✕</button>
    </div>
    
    {{-- Messages --}}
    <div id="msgZone" style="flex:1;overflow-y:auto;padding:12px;background:#f9fafb;display:flex;flex-direction:column;gap:8px;">
        <div style="text-align:center;color:#9ca3af;margin:auto;">Start a conversation...</div>
    </div>
    
    {{-- Input --}}
    <div style="padding:12px;border-top:1px solid #e5e7eb;">
        <div style="display:flex;gap:8px;">
            <input type="text" id="msgInput" class="form-control" placeholder="Type a message..." style="font-size:13px;">
            <button class="btn btn-primary" type="button" onclick="sendMessage()" style="width:80px;padding:6px 12px;">
                <i class="bi bi-send-fill"></i>
            </button>
        </div>
    </div>
</div>

<style>
#msgZone { display:flex; flex-direction:column; gap:8px; }
.msg { max-width:85%; padding:8px 12px; border-radius:10px; word-wrap:break-word; font-size:12px; }
.msg-mine { align-self:flex-end; background:#4f46e5; color:#fff; }
.msg-other { align-self:flex-start; background:#e5e7eb; color:#1e1f24; }
.msg-time { font-size:10px; opacity:.7; margin-top:3px; display:block; }
</style>