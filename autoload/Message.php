<?php

class Message {
    private $db;

    public function __construct() {
        $this->db = connect_db(); // Use the connect_db function from config.php
    }

    public function isAllowedToMessage($user_id, $contact_id) {
        $stmt = $this->db->prepare("SELECT * FROM followers WHERE follower_id = ? AND followed_id = ?");
        $stmt->execute([$user_id, $contact_id]);
        return $stmt->rowCount() > 0;
    }

    public function getMessages($user_id, $contact_id, $last_message_id = 0) {
        $stmt = $this->db->prepare("SELECT messages.id, messages.message, messages.sender_id, users.username AS sender_name, users.profile_pic AS sender_profile_pic
                                    FROM messages
                                    JOIN users ON messages.sender_id = users.id
                                    WHERE ((messages.sender_id = ? AND messages.receiver_id = ?)
                                       OR (messages.sender_id = ? AND messages.receiver_id = ?))
                                       AND messages.id > ?
                                    ORDER BY messages.created_at ASC");
        $stmt->execute([$user_id, $contact_id, $contact_id, $user_id, $last_message_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function sendMessage($user_id, $receiver_id, $message) {
        $stmt = $this->db->prepare("INSERT INTO messages (sender_id, receiver_id, message, created_at) VALUES (?, ?, ?, NOW())");
        if ($stmt->execute([$user_id, $receiver_id, $message])) {
            return ['success' => true];
        } else {
            return ['success' => false, 'message' => 'Failed to send message.'];
        }
    }
}
?>
