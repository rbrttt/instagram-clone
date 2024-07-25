<?php

class User {
    private $db;

    public function __construct() {
        $this->db = connect_db(); // Use the connect_db function from config.php
    }

    public function getAllUsers() {
        $stmt = $this->db->prepare("SELECT username FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserDataByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getFollowerCount($user_id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM followers WHERE followed_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    public function getFollowingCount($user_id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM followers WHERE follower_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    public function isFollowing($follower_id, $followed_id) {
        $stmt = $this->db->prepare("SELECT * FROM followers WHERE follower_id = ? AND followed_id = ?");
        $stmt->execute([$follower_id, $followed_id]);
        return $stmt->rowCount() > 0;
    }

    public function updateProfile($user_id, $bio, $profile_pic = null) {
        if ($profile_pic) {
            $stmt = $this->db->prepare("UPDATE users SET profile_pic=:profile_pic, bio=:bio WHERE id=:id");
            $stmt->bindValue(':profile_pic', $profile_pic);
            $stmt->bindValue(':bio', $bio);
            $stmt->bindValue(':id', $user_id);
        } else {
            $stmt = $this->db->prepare("UPDATE users SET bio=:bio WHERE id=:id");
            $stmt->bindValue(':bio', $bio);
            $stmt->bindValue(':id', $user_id);
        }

        if ($stmt->execute()) {
            return "Profile updated successfully!";
        } else {
            return "Error updating profile: " . $stmt->errorInfo()[2];
        }
    }

    public function followUser($follower_id, $followed_id) {
        $stmt = $this->db->prepare("INSERT INTO followers (follower_id, followed_id) VALUES (?, ?)");
        if ($stmt->execute([$follower_id, $followed_id])) {
            return ['success' => true, 'message' => 'Followed successfully!'];
        } else {
            return ['success' => false, 'message' => 'Failed to follow: ' . $stmt->errorInfo()[2]];
        }
    }

    public function unfollowUser($follower_id, $followed_id) {
        $stmt = $this->db->prepare("DELETE FROM followers WHERE follower_id = ? AND followed_id = ?");
        if ($stmt->execute([$follower_id, $followed_id])) {
            return ['success' => true, 'message' => 'Unfollowed successfully!'];
        } else {
            return ['success' => false, 'message' => 'Failed to unfollow: ' . $stmt->errorInfo()[2]];
        }
    }

    public function getFollowers($user_id) {
        $stmt = $this->db->prepare("SELECT u.id, u.username, u.profile_pic 
                                    FROM users u
                                    JOIN followers f ON u.id = f.followed_id
                                    WHERE f.follower_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
?>
