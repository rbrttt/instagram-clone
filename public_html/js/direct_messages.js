document.addEventListener('DOMContentLoaded', function() {
    const isDebugging = false; // Set to true to enable debugging

    if (isDebugging) console.log('Document fully loaded and parsed');

    const chatBox = document.querySelector('.chat-box');
    const chatContent = document.querySelector('.chat-content');
    const chatInput = document.getElementById('chatInput');
    const sendMessageBtn = document.getElementById('sendMessageBtn');
    const chatHeader = document.querySelector('.chat-header h3');
    const searchInput = document.querySelector('.search-bar input');
    let currentChatUserId = null;

    const users = document.querySelectorAll('.users-list ul li');
    const userList = document.querySelector('.users-list ul');

    if (isDebugging) console.log('Number of users found:', users.length);

    users.forEach(user => {
        user.addEventListener('click', function() {
            currentChatUserId = this.getAttribute('data-user-id');
            const username = this.querySelector('span').textContent;
            if (isDebugging) console.log(`User clicked: ${username} with ID: ${currentChatUserId}`);
            updateChatHeader(username);
            loadMessages(currentChatUserId);
        });
    });

    sendMessageBtn.addEventListener('click', function() {
        if (isDebugging) console.log('Send message button clicked');
        sendMessageIfValid();
    });

    chatInput.addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            if (isDebugging) console.log('Enter key pressed in chat input');
            sendMessageIfValid();
            event.preventDefault(); // Prevents the default action of the Enter key
        }
    });

    searchInput.addEventListener('input', function() {
        const query = searchInput.value.toLowerCase();
        filterUsers(query);
    });

    function sendMessageIfValid() {
        if (currentChatUserId && chatInput.value.trim()) {
            if (isDebugging) console.log(`Sending message to user ID: ${currentChatUserId}`);
            sendMessage(currentChatUserId, chatInput.value.trim());
            chatInput.value = '';
        }
    }

    function loadMessages(userId) {
        if (isDebugging) console.log(`Loading messages for user ID: ${userId}`);
        fetch('get_messages.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ contact_id: userId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (isDebugging) console.log('Messages loaded successfully');
                chatContent.innerHTML = '';
                data.messages.forEach(message => {
                    const messageElement = document.createElement('div');
                    messageElement.classList.add('message');
                    if (message.sender_id === currentUserId) {
                        messageElement.classList.add('sent');
                    } else {
                        messageElement.classList.add('received');
                    }
                    const profilePic = `<img src="${message.sender_profile_pic}" alt="Profile Picture">`;
                    messageElement.innerHTML = `${profilePic} ${message.sender_name}: ${message.message}`;
                    chatContent.appendChild(messageElement);
                });
                scrollToBottom();
            } else {
                if (isDebugging) console.error('Failed to load messages:', data.message);
            }
        }).catch(error => {
            if (isDebugging) console.error('Error loading messages:', error);
        });
    }

    function sendMessage(receiverId, message) {
        if (isDebugging) console.log(`Sending message to user ID: ${receiverId}`);
        fetch('send_message.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ receiver_id: receiverId, message: message })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (isDebugging) console.log('Message sent successfully');
                loadMessages(receiverId);
            } else {
                if (isDebugging) console.error('Failed to send message:', data.message);
            }
        }).catch(error => {
            if (isDebugging) console.error('Error sending message:', error);
        });
    }

    function updateChatHeader(username) {
        if (isDebugging) console.log(`Updating chat header to: You are now chatting with ${username}`);
        chatHeader.textContent = `You are now chatting with ${username}`;
    }

    function filterUsers(query) {
        users.forEach(user => {
            const username = user.querySelector('span').textContent.toLowerCase();
            if (username.includes(query)) {
                user.style.display = 'flex'; // Show the user
            } else {
                user.style.display = 'none'; // Hide the user
            }
        });
    }

    function scrollToBottom() {
        chatContent.scrollTop = chatContent.scrollHeight;
    }
});
