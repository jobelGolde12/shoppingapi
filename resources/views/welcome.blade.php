<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --secondary: #7209b7;
            --dark: #212529;
            --light: #f8f9fa;
            --gray: #6c757d;
            --success: #4cc9f0;
            --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --hover-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--dark);
            line-height: 1.6;
        }

        header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            padding: 20px 15px;
            color: white;
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        header::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: translateX(-100%);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            100% { transform: translateX(100%); }
        }

        .container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(500px, 1fr));
            gap: 25px;
        }

        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
            }
        }

        .box {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 0;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            border-top: 4px solid var(--primary);
            position: relative;
            overflow: hidden;
        }

        .box:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }

        .box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }

        .box h2 {
            font-size: 20px;
            margin-bottom: 12px;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .box h2 i {
            color: var(--primary);
        }

        .box p {
            font-size: 14px;
            color: var(--gray);
            margin-bottom: 20px;
            background: var(--light);
            padding: 8px 12px;
            border-radius: 6px;
            font-family: monospace;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            font-weight: 600;
            display: block;
            margin-bottom: 6px;
            color: var(--dark);
            font-size: 14px;
        }

        input, textarea {
            width: 100%;
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 15px;
            transition: all 0.3s;
            background: #fafafa;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
            background: white;
        }

        button {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            color: white;
            padding: 12px 24px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 6px rgba(67, 97, 238, 0.2);
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(67, 97, 238, 0.3);
        }

        button:active {
            transform: translateY(0);
        }

        .output {
            margin-top: 20px;
            background: #1a1a1a;
            color: var(--success);
            padding: 18px;
            font-family: 'Fira Code', monospace;
            min-height: 100px;
            border-radius: 8px;
            white-space: pre-wrap;
            overflow-x: auto;
            font-size: 14px;
            border-left: 4px solid var(--success);
            position: relative;
        }

        .output::before {
            content: 'Response';
            position: absolute;
            top: 0;
            right: 0;
            background: var(--success);
            color: #1a1a1a;
            padding: 4px 10px;
            font-size: 12px;
            font-weight: bold;
            border-bottom-left-radius: 4px;
        }

        .loading {
            display: none;
            text-align: center;
            padding: 10px;
        }

        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .status-indicator {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 8px;
        }

        .status-success {
            background-color: #4ade80;
        }

        .status-error {
            background-color: #f87171;
        }

        .status-pending {
            background-color: #fbbf24;
        }

        .api-method {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            margin-right: 8px;
        }

        .method-get { background: #10b981; color: white; }
        .method-post { background: #3b82f6; color: white; }
        .method-put { background: #f59e0b; color: white; }
        .method-delete { background: #ef4444; color: white; }

        .footer {
            text-align: center;
            padding: 20px;
            color: var(--gray);
            font-size: 14px;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<header>
    <i class="fas fa-comments"></i> Shopping API Testing Tool
</header>

<div class="container">

    <!-- START CONVERSATION -->
    <div class="box">
        <h2><i class="fas fa-plus-circle"></i> Start Conversation</h2>
        <p><span class="api-method method-post">POST</span> /api/conversation/start</p>
        
        <div class="form-group">
            <label for="user_one_id">User One ID</label>
            <input id="user_one_id" placeholder="Enter user ID">
        </div>
        
        <div class="form-group">
            <label for="user_two_id">User Two ID</label>
            <input id="user_two_id" placeholder="Enter user ID">
        </div>

        <button onclick="startConversation()">
            <i class="fas fa-play"></i> Start Conversation
        </button>
        
        <div class="loading" id="loading_startConversation">
            <div class="loading-spinner"></div> Processing...
        </div>
        
        <div id="out_startConversation" class="output">Response will appear here</div>
    </div>

    <!-- SEND MESSAGE -->
    <div class="box">
        <h2><i class="fas fa-paper-plane"></i> Send Message</h2>
        <p><span class="api-method method-post">POST</span> /api/message/send</p>

        <div class="form-group">
            <label for="send_conversation_id">Conversation ID</label>
            <input id="send_conversation_id" placeholder="Enter conversation ID">
        </div>
        
        <div class="form-group">
            <label for="send_sender_id">Sender ID</label>
            <input id="send_sender_id" placeholder="Enter sender ID">
        </div>
        
        <div class="form-group">
            <label for="send_message">Message</label>
            <textarea id="send_message" rows="3" placeholder="Type your message here"></textarea>
        </div>

        <button onclick="sendMessage()">
            <i class="fas fa-paper-plane"></i> Send Message
        </button>
        
        <div class="loading" id="loading_sendMessage">
            <div class="loading-spinner"></div> Sending...
        </div>
        
        <div id="out_sendMessage" class="output">Response will appear here</div>
    </div>

    <!-- GET MESSAGES -->
    <div class="box">
        <h2><i class="fas fa-envelope-open-text"></i> Get Messages</h2>
        <p><span class="api-method method-get">GET</span> /api/message/{conversation_id}</p>

        <div class="form-group">
            <label for="get_messages_convo">Conversation ID</label>
            <input id="get_messages_convo" placeholder="Enter conversation ID">
        </div>

        <button onclick="getMessages()">
            <i class="fas fa-search"></i> Fetch Messages
        </button>
        
        <div class="loading" id="loading_getMessages">
            <div class="loading-spinner"></div> Fetching...
        </div>
        
        <div id="out_getMessages" class="output">Response will appear here</div>
    </div>

    <!-- UPDATE MESSAGE -->
    <div class="box">
        <h2><i class="fas fa-edit"></i> Update Message</h2>
        <p><span class="api-method method-put">PUT</span> /api/message/{id}</p>

        <div class="form-group">
            <label for="update_msg_id">Message ID</label>
            <input id="update_msg_id" placeholder="Enter message ID">
        </div>
        
        <div class="form-group">
            <label for="update_msg_text">New Message</label>
            <textarea id="update_msg_text" rows="3" placeholder="Enter new message content"></textarea>
        </div>

        <button onclick="updateMessage()">
            <i class="fas fa-save"></i> Update Message
        </button>
        
        <div class="loading" id="loading_updateMessage">
            <div class="loading-spinner"></div> Updating...
        </div>
        
        <div id="out_updateMessage" class="output">Response will appear here</div>
    </div>

    <!-- DELETE MESSAGE -->
    <div class="box">
        <h2><i class="fas fa-trash-alt"></i> Delete Message</h2>
        <p><span class="api-method method-delete">DELETE</span> /api/message/{id}</p>

        <div class="form-group">
            <label for="delete_msg_id">Message ID</label>
            <input id="delete_msg_id" placeholder="Enter message ID">
        </div>

        <button onclick="deleteMessage()" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
            <i class="fas fa-trash"></i> Delete Message
        </button>
        
        <div class="loading" id="loading_deleteMessage">
            <div class="loading-spinner"></div> Deleting...
        </div>
        
        <div id="out_deleteMessage" class="output">Response will appear here</div>
    </div>

    <!-- MARK AS READ -->
    <div class="box">
        <h2><i class="fas fa-check-double"></i> Mark Message as Read</h2>
        <p><span class="api-method method-put">PUT</span> /api/message/{id}/read</p>

        <div class="form-group">
            <label for="read_msg_id">Message ID</label>
            <input id="read_msg_id" placeholder="Enter message ID">
        </div>

        <button onclick="markAsRead()">
            <i class="fas fa-check"></i> Mark as Read
        </button>
        
        <div class="loading" id="loading_markAsRead">
            <div class="loading-spinner"></div> Processing...
        </div>
        
        <div id="out_markAsRead" class="output">Response will appear here</div>
    </div>

    <!-- GET USER CONVERSATIONS -->
    {{-- <div class="box">
        <h2><i class="fas fa-comments"></i> My Conversations</h2>
        <p><span class="api-method method-get">GET</span> /api/conversations/{user_id}</p>

        <div class="form-group">
            <label for="user_conversations_id">User ID</label>
            <input id="user_conversations_id" placeholder="Enter user ID">
        </div>

        <button onclick="getUserConversations()">
            <i class="fas fa-list"></i> Fetch Conversations
        </button>
        
        <div class="loading" id="loading_userConversations">
            <div class="loading-spinner"></div> Fetching...
        </div>
        
        <div id="out_userConversations" class="output">Response will appear here</div>
    </div> --}}

</div>

<script>
const API = "/api";

// ============================
// JSON / XML Toggle
// ============================
let responseFormat = localStorage.getItem("format") || "json";

document.addEventListener("DOMContentLoaded", () => {
    const toggle = document.createElement("div");
    toggle.style = "padding: 10px; text-align:center; margin-bottom: 20px;";

    toggle.innerHTML = `
        <label style="font-weight: bold; margin-right: 10px;">Response Format:</label>
        <select id="formatToggle" style="padding: 6px 10px; border-radius: 6px; border:1px solid #ccc;">
            <option value="json" ${responseFormat === "json" ? "selected" : ""}>JSON</option>
            <option value="xml"  ${responseFormat === "xml" ? "selected" : ""}>XML</option>
        </select>
    `;

    document.body.prepend(toggle);

    document.getElementById("formatToggle").addEventListener("change", (e) => {
        responseFormat = e.target.value;
        localStorage.setItem("format", responseFormat);
    });
});

// Attach ?format=
function applyFormat(url) {
    const sep = url.includes("?") ? "&" : "?";
    return url + sep + "format=" + responseFormat;
}

// ============================
//  Request Helper
// ============================
async function request(url, method = "GET", body = null) {

    url = applyFormat(url);

    let headers = {
        "Content-Type": "application/json",
        "Accept": responseFormat === "xml" ? "application/xml" : "application/json"
    };

    const res = await fetch(url, {
        method,
        headers,
        body: body ? JSON.stringify(body) : null
    });

    const text = await res.text();

    // Return XML EXACTLY when XML was requested
    if (responseFormat === "xml") {
        return text;
    }

    // Otherwise parse JSON safely
    try {
        return JSON.parse(text);
    } catch {
        return text; // fallback
    }
}

// ============================
//  Loading State Helper
// ============================
function setLoadingState(buttonId, isLoading) {
    const button = document.querySelector(`button[onclick="${buttonId}()"]`);
    const loadingElement = document.getElementById(`loading_${buttonId}`);
    
    if (isLoading) {
        button.disabled = true;
        loadingElement.style.display = 'block';
    } else {
        button.disabled = false;
        loadingElement.style.display = 'none';
    }
}
function formatXml(xml) {
    const PADDING = '    '; // 4 spaces
    const reg = /(>)(<)(\/*)/g;
    let formatted = '';
    let pad = 0;

    xml = xml.replace(reg, '$1\r\n$2$3');
    xml.split('\r\n').forEach((node) => {
        let indent = 0;
        if (node.match(/.+<\/\w[^>]*>$/)) {
            indent = 0;
        } else if (node.match(/^<\/\w/)) {
            if (pad !== 0) pad -= 1;
        } else if (node.match(/^<\w([^>]*[^\/])?>.*$/)) {
            indent = 1;
        } else {
            indent = 0;
        }

        formatted += PADDING.repeat(pad) + node + '\r\n';
        pad += indent;
    });

    return formatted.trim();
}

// ============================
// API CALLS
// ============================
async function startConversation() {
    setLoadingState('startConversation', true);
    try {
        const result = await request(`${API}/conversation/start`, "POST", {
            user_one_id: document.getElementById("user_one_id").value,
            user_two_id: document.getElementById("user_two_id").value,
        });
        if (typeof result === "string" && responseFormat === "xml") {
     document.getElementById("out_startConversation").innerText = formatXml(result);
        } else {
            document.getElementById("out_startConversation").innerText = JSON.stringify(result, null, 4);
        }

    } finally { setLoadingState('startConversation', false); }
}

async function sendMessage() {
    setLoadingState('sendMessage', true);
    try {
        const result = await request(`${API}/message/send`, "POST", {
            conversation_id: document.getElementById("send_conversation_id").value,
            sender_id: document.getElementById("send_sender_id").value,
            message: document.getElementById("send_message").value,
        });
        if (typeof result === "string" && responseFormat === "xml") {
        document.getElementById("out_sendMessage").innerText = formatXml(result);
        } else {
            document.getElementById("out_sendMessage").innerText = JSON.stringify(result, null, 4);
        }

    } finally { setLoadingState('sendMessage', false); }
}

async function getMessages() {
    setLoadingState('getMessages', true);
    try {
        const id = document.getElementById("get_messages_convo").value;
        const result = await request(`${API}/message/${id}`);
        if (typeof result === "string" && responseFormat === "xml") {
    document.getElementById("out_getMessages").innerText = formatXml(result);
} else {
    document.getElementById("out_getMessages").innerText = JSON.stringify(result, null, 4);
}

    } finally { setLoadingState('getMessages', false); }
}

async function updateMessage() {
    setLoadingState('updateMessage', true);
    try {
        const id = document.getElementById("update_msg_id").value;
        const result = await request(`${API}/message/${id}`, "PUT", {
            message: document.getElementById("update_msg_text").value,
        });
        if (typeof result === "string" && responseFormat === "xml") {
            document.getElementById("out_updateMessage").innerText = formatXml(result);
        } else {
            document.getElementById("out_updateMessage").innerText = JSON.stringify(result, null, 4);
        }
    } finally { setLoadingState('updateMessage', false); }
}

async function deleteMessage() {
    setLoadingState('deleteMessage', true);
    try {
        const id = document.getElementById("delete_msg_id").value;
        const result = await request(`${API}/message/${id}`, "DELETE");
        if (typeof result === "string" && responseFormat === "xml") {
            document.getElementById("out_deleteMessage").innerText = formatXml(result);
        } else {
            document.getElementById("out_deleteMessage").innerText = JSON.stringify(result, null, 4);
        }
    } finally { setLoadingState('deleteMessage', false); }
}

async function markAsRead() {
    setLoadingState('markAsRead', true);
    try {
        const id = document.getElementById("read_msg_id").value;
        const result = await request(`${API}/message/${id}/read`, "PUT");
        if (typeof result === "string" && responseFormat === "xml") {
            document.getElementById("out_markAsRead").innerText = formatXml(result);
        } else {
            document.getElementById("out_markAsRead").innerText = JSON.stringify(result, null, 4);
        }
    } finally { setLoadingState('markAsRead', false); }
}

async function getUserConversations() {
    setLoadingState('getUserConversations', true);
    try {
        const id = document.getElementById("user_conversations_id").value;
        
        if (!id) {
            document.getElementById("out_userConversations").innerText = "Error: Please enter a User ID";
            return;
        }

        const result = await request(`${API}/conversations/${id}`);
        
        if (typeof result === "string" && responseFormat === "xml") {
            document.getElementById("out_userConversations").innerText = formatXml(result);
        } else {
            document.getElementById("out_userConversations").innerText = JSON.stringify(result, null, 4);
        }

    } catch (error) {
        console.error("API Error:", error);
        document.getElementById("out_userConversations").innerText = `Error: ${error.message}`;
    } finally { 
        setLoadingState('getUserConversations', false); 
    }
}
</script>


</body>
</html>