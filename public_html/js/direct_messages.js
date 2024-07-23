document.addEventListener('DOMContentLoaded', function() {
    const chatBox = document.querySelector('.chat-box');
    const chatContent = document.querySelector('.chat-content');
    const chatInput = document.getElementById('chatInput');
    const sendMessageBtn = document.getElementById('sendMessageBtn');
    let currentChatUserId = null;

    document.querySelectorAll('.users-list ul li').forEach(user => {
        user.addEventListener('click', function() {
            currentChatUserId = this.getAttribute('data-user-id');
            loadMessages(currentChatUserId);
        });
    });

    sendMessageBtn.addEventListener('click', function() {
        sendMessageIfValid();
    });

    chatInput.addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            sendMessageIfValid();
            event.preventDefault(); // Prevents the default action of the Enter key
        }
    });

    function sendMessageIfValid() {
        if (currentChatUserId && chatInput.value.trim()) {
            sendMessage(currentChatUserId, chatInput.value.trim());
            chatInput.value = '';
        }
    }

    function loadMessages(userId) {
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
            }
        }).catch(error => {
            console.error('Error loading messages:', error);
        });
    }

    function sendMessage(receiverId, message) {
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
                loadMessages(receiverId);
            }
        }).catch(error => {
            console.error('Error sending message:', error);
        });
    }
});
