<?php
session_start();
include '../../../config/config.php';
include '../php/function.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_index.html");
    exit;
}

$admin = getAdmin($_SESSION['admin_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../../admin/css/admin_dashboard.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome, <?php echo htmlspecialchars($admin['username']); ?></h1>
        <div class="dashboard-menu">
            <ul>
                <li><a href="#" class="menu-item" data-target="manage-users">Manage Users</a></li>
                <li><a href="#" class="menu-item" data-target="manage-messages">Manage Messages</a></li>
                <li><a href="admin_logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="dashboard-content">
            <div id="manage-users" class="dashboard-section" style="display: none;">
                <h2>Total Users: <span id="total-users"></span></h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Full Name</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="users-list">
                        <!-- Users will be populated here -->
                    </tbody>
                </table>
            </div>
            <div id="manage-messages" class="dashboard-section" style="display: none;">
                <h2>Messages</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Sender ID</th>
                            <th>Receiver ID</th>
                            <th>Message</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="messages-list">
                        <!-- Messages will be populated here -->
                    </tbody>
                </table>
                <!-- Edit Message Modal -->
                <div id="edit-message-modal" style="display: none;">
                    <h2>Edit Message</h2>
                    <form id="edit-message-form">
                        <input type="hidden" id="edit-message-id" name="id">
                        <label for="edit-message-content">Message:</label>
                        <textarea id="edit-message-content" name="message" required></textarea><br>
                        <button type="submit">Save Changes</button>
                        <button type="button" id="cancel-edit">Cancel</button>
                    </form>
                </div>
            </div>
            <div id="edit-profile" class="dashboard-section" style="display: none;">
                <h2>Edit Profile</h2>
                <form id="edit-profile-form">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($admin['username']); ?>" required><br>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>" required><br>
                    <label for="profile_pic">Profile Picture:</label>
                    <input type="text" id="profile_pic" name="profile_pic" value="<?php echo htmlspecialchars($admin['profile_pic']); ?>"><br>
                    <button type="submit">Save Changes</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Move loadUsers outside of document ready to make it globally accessible
        function loadUsers() {
            $.ajax({
                url: 'fetch_users.php',
                method: 'GET',
                success: function(data) {
                    var users = JSON.parse(data);
                    var totalUsers = users.length;
                    $('#total-users').text(totalUsers);
                    var usersList = '';
                    users.forEach(function(user) {
                        usersList += '<tr>';
                        usersList += '<td>' + user.id + '</td>';
                        usersList += '<td>' + user.username + '</td>';
                        usersList += '<td>' + user.email + '</td>';
                        usersList += '<td>' + user.fullname + '</td>';
                        usersList += '<td>' + (user.ac_status == 1 ? 'Active' : 'Blocked') + '</td>';
                        usersList += '<td>';
                        usersList += '<button onclick="blockUser(' + user.id + ')">Block</button>';
                        usersList += '<button onclick="unblockUser(' + user.id + ')">Unblock</button>';
                        usersList += '<button onclick="deleteUser(' + user.id + ')">Delete</button>';
                        usersList += '</td>';
                        usersList += '</tr>';
                    });
                    $('#users-list').html(usersList);
                }
            });
        }

        $(document).ready(function() {
            $('.menu-item').click(function() {
                var target = $(this).data('target');
                $('.dashboard-section').hide();
                $('#' + target).show();
            });

            // Load messages
            function loadMessages() {
                $.ajax({
                    url: 'fetch_messages.php',
                    method: 'GET',
                    success: function(data) {
                        var messages = JSON.parse(data);
                        var messagesList = '';
                        messages.forEach(function(message) {
                            messagesList += '<tr>';
                            messagesList += '<td>' + message.id + '</td>';
                            messagesList += '<td>' + message.sender_id + '</td>';
                            messagesList += '<td>' + message.receiver_id + '</td>';
                            messagesList += '<td>' + message.message + '</td>';
                            messagesList += '<td>';
                            messagesList += '<button onclick="editMessage(' + message.id + ', \'' + message.message.replace(/'/g, "\\'") + '\')">Edit</button>';
                            messagesList += '<button onclick="deleteMessage(' + message.id + ')">Delete</button>';
                            messagesList += '</td>';
                            messagesList += '</tr>';
                        });
                        $('#messages-list').html(messagesList);
                    }
                });
            }

            // Load users and messages on page load
            loadUsers();
            loadMessages();

            // Edit profile form submission
            $('#edit-profile-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'edit_profile.php',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        alert(response);
                        location.reload(); // Reload the page to reflect changes
                    }
                });
            });
        });

        // Block user
        function blockUser(userId) {
            $.ajax({
                url: 'block_user.php',
                method: 'POST',
                data: { id: userId },
                success: function(response) {
                    alert(response);
                    loadUsers();
                }
            });
        }

        // Unblock user
        function unblockUser(userId) {
            $.ajax({
                url: 'unblock_user.php',
                method: 'POST',
                data: { id: userId },
                success: function(response) {
                    alert(response);
                    loadUsers();
                }
            });
        }

        // Delete user
        function deleteUser(userId) {
            $.ajax({
                url: 'delete_user.php',
                method: 'POST',
                data: { id: userId },
                success: function(response) {
                    alert(response);
                    loadUsers();
                }
            });
        }
        // Edit message form submission
        $('#edit-message-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'edit_message.php',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        alert(response);
                        $('#edit-message-modal').hide(); // Hide the modal
                        loadMessages(); // Reload messages
                    }
                });
            });
        // Cancel edit
        $('#cancel-edit').click(function() {
                $('#edit-message-modal').hide();
            });
        // Edit message
        function editMessage(messageId, messageContent) {
            $('#edit-message-id').val(messageId);
            $('#edit-message-content').val(messageContent);
            $('#edit-message-modal').show();
        }
        // Delete message
        function deleteMessage(messageId) {
            $.ajax({
                url: 'delete_message.php',
                method: 'POST',
                data: { id: messageId },
                success: function(response) {
                    alert(response);
                    loadMessages();
                }
            });
        }
    </script>
</body>
</html>
