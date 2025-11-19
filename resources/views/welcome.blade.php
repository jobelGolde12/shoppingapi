
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>
<style>
    body {
        margin: 0;
        background: #f8f9fa;
        font-family: Arial, sans-serif;
    }

    header {
        background: #3f51b5;
        padding: 15px;
        color: white;
        text-align: center;
        font-size: 22px;
        font-weight: bold;
    }

    .container {
        max-width: 900px;
        margin: 20px auto;
        padding: 20px;
    }

    .box {
        background: white;
        border-radius: 6px;
        padding: 20px;
        margin-bottom: 25px;
        border: 1px solid #ddd;
    }

    .box h2 {
        font-size: 20px;
        margin-bottom: 10px;
    }

    .box p {
        font-size: 14px;
        color: #666;
        margin-bottom: 15px;
    }

    label {
        font-weight: bold;
        display: block;
        margin: 6px 0 2px;
    }

    input, textarea {
        width: 100%;
        padding: 8px;
        margin-bottom: 12px;
        border-radius: 4px;
        border: 1px solid #bbb;
        font-size: 14px;
    }

    button {
        background: #3f51b5;
        border: none;
        color: white;
        padding: 10px 18px;
        font-size: 15px;
        cursor: pointer;
        border-radius: 4px;
    }

    button:hover {
        background: #303f9f;
    }

    .output {
        margin-top: 15px;
        background: #272822;
        color: #00e676;
        padding: 15px;
        font-family: monospace;
        min-height: 80px;
        border-radius: 4px;
        white-space: pre-wrap;
    }
</style>
</head>
<body>

<header>Messaging API Testing Tool</header>

<div class="container">

    <!-- START CONVERSATION -->
    <div class="box">
        <h2>Start Conversation</h2>
        <p>POST /api/conversation/start</p>
        <label>User One ID</label>
        <input id="user_one_id">

        <label>User Two ID</label>
        <input id="user_two_id">

        <button onclick="startConversation()">Start</button>
        <div id="out_startConversation" class="output"></div>
    </div>

    <!-- SEND MESSAGE -->
    <div class="box">
        <h2>Send Message</h2>
        <p>POST /api/message/send</p>

        <label>Conversation ID</label>
        <input id="send_conversation_id">

        <label>Sender ID</label>
        <input id="send_sender_id">

        <label>Message</label>
        <textarea id="send_message"></textarea>

        <button onclick="sendMessage()">Send</button>
        <div id="out_sendMessage" class="output"></div>
    </div>

    <!-- GET MESSAGES -->
    <div class="box">
        <h2>Get Messages</h2>
        <p>GET /api/message/{conversation_id}</p>

        <label>Conversation ID</label>
        <input id="get_messages_convo">

        <button onclick="getMessages()">Fetch</button>
        <div id="out_getMessages" class="output"></div>
    </div>

    <!-- UPDATE MESSAGE -->
    <div class="box">
        <h2>Update Message</h2>
        <p>PUT /api/message/{id}</p>

        <label>Message ID</label>
        <input id="update_msg_id">

        <label>New Message</label>
        <textarea id="update_msg_text"></textarea>

        <button onclick="updateMessage()">Update</button>
        <div id="out_updateMessage" class="output"></div>
    </div>

    <!-- DELETE MESSAGE -->
    <div class="box">
        <h2>Delete Message</h2>
        <p>DELETE /api/message/{id}</p>

        <label>Message ID</label>
        <input id="delete_msg_id">

        <button onclick="deleteMessage()">Delete</button>
        <div id="out_deleteMessage" class="output"></div>
    </div>

    <!-- MARK AS READ -->
    <div class="box">
        <h2>Mark Message as Read</h2>
        <p>PUT /api/message/{id}/read</p>

        <label>Message ID</label>
        <input id="read_msg_id">

        <button onclick="markAsRead()">Mark</button>
        <div id="out_markAsRead" class="output"></div>
    </div>

    <!-- GET USER CONVERSATIONS -->
    <div class="box">
        <h2>My Conversations</h2>
        <p>GET /api/conversations/{user_id}</p>

        <label>User ID</label>
        <input id="user_conversations_id">

        <button onclick="getUserConversations()">Fetch</button>
        <div id="out_userConversations" class="output"></div>
    </div>

</div>

<script>
    const API = "/api";

    async function request(url, method = "GET", body = null) {
        return fetch(url, {
            method,
            headers: { "Content-Type": "application/json" },
            body: body ? JSON.stringify(body) : null
        }).then(res => res.json());
    }

    // 1. Start Conversation
    async function startConversation() {
        const result = await request(`${API}/conversation/start`, "POST", {
            user_one_id: document.getElementById("user_one_id").value,
            user_two_id: document.getElementById("user_two_id").value,
        });
        document.getElementById("out_startConversation").innerText = JSON.stringify(result, null, 4);
    }

    // 2. Send Message
    async function sendMessage() {
        const result = await request(`${API}/message/send`, "POST", {
            conversation_id: document.getElementById("send_conversation_id").value,
            sender_id: document.getElementById("send_sender_id").value,
            message: document.getElementById("send_message").value,
        });
        document.getElementById("out_sendMessage").innerText = JSON.stringify(result, null, 4);
    }

    // 3. Get Messages
    async function getMessages() {
        const id = document.getElementById("get_messages_convo").value;
        const result = await request(`${API}/message/${id}`);
        document.getElementById("out_getMessages").innerText = JSON.stringify(result, null, 4);
    }

    // 4. Update Message
    async function updateMessage() {
        const id = document.getElementById("update_msg_id").value;
        const result = await request(`${API}/message/${id}`, "PUT", {
            message: document.getElementById("update_msg_text").value,
        });
        document.getElementById("out_updateMessage").innerText = JSON.stringify(result, null, 4);
    }

    // 5. Delete Message
    async function deleteMessage() {
        const id = document.getElementById("delete_msg_id").value;
        const result = await request(`${API}/message/${id}`, "DELETE");
        document.getElementById("out_deleteMessage").innerText = JSON.stringify(result, null, 4);
    }

    // 6. Mark as Read
    async function markAsRead() {
        const id = document.getElementById("read_msg_id").value;
        const result = await request(`${API}/message/${id}/read`, "PUT");
        document.getElementById("out_markAsRead").innerText = JSON.stringify(result, null, 4);
    }

    // 7. Get User Conversations
    async function getUserConversations() {
        const id = document.getElementById("user_conversations_id").value;
        const result = await request(`${API}/conversations/${id}`);
        document.getElementById("out_userConversations").innerText = JSON.stringify(result, null, 4);
    }
</script>

</body>
</html>

