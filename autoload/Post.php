<?php

class Post {
    private $db;

    public function __construct() {
        $this->db = connect_db(); // Use the connect_db function from config.php
    }

    public function createPost($user_id, $caption, $image) {
        $stmt = $this->db->prepare("INSERT INTO posts (user_id, image, caption) VALUES (:user_id, :image, :caption)");
        $stmt->bindValue(':user_id', $user_id);
        $stmt->bindValue(':image', $image);
        $stmt->bindValue(':caption', $caption);

        if ($stmt->execute()) {
            return "Post created successfully!";
        } else {
            return "Error creating post: " . $stmt->errorInfo()[2];
        }
    }

    public function getUserPosts($user_id) {
        $stmt = $this->db->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPostById($post_id) {
        $stmt = $this->db->prepare("SELECT * FROM posts WHERE id = ?");
        $stmt->execute([$post_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deletePost($post_id) {
        $stmt = $this->db->prepare("DELETE FROM posts WHERE id = ?");
        if ($stmt->execute([$post_id])) {
            return "Post deleted successfully!";
        } else {
            return "Error deleting post: " . $stmt->errorInfo()[2];
        }
    }
}
?>
