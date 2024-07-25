<?php
include_once '../../../config/config.php';

function checkAdminUser($login_data) {
    $pdo = connect_db(); 
    $username_or_email = $login_data['username_or_email'];
    $password = md5($login_data['password']); // Hash the password with MD5

    $query = "SELECT * FROM admin WHERE (username=:username_or_email OR email=:username_or_email) AND password=:password";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username_or_email', $username_or_email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    $data['user'] = $stmt->fetch(PDO::FETCH_ASSOC) ?? array();
    if (count($data['user']) > 0) {
        $data['status'] = true;
        $data['user_id'] = $data['user']['id'];
    } else {
        $data['status'] = false;
    }
    return $data;
}

function getAdmin($user_id) {
    $pdo = connect_db();
    $query = "SELECT * FROM admin WHERE id=:user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    return $admin;
}

function getMessagesList() {
    $pdo = connect_db();
    $query = "SELECT * FROM messages ORDER BY id DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $messages;
}

function totalUsersCount() {
    $pdo = connect_db(); 
    $query = "SELECT count(*) as row FROM users";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['row'];
    return $count;
}

function getUsersList() {
    $pdo = connect_db();
    $query = "SELECT * FROM users ORDER BY id DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $users;
}

function loginUserByAdmin($email) {
    $pdo = connect_db();
    $query = "SELECT * FROM users WHERE email=:email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $data['user'] = $stmt->fetch(PDO::FETCH_ASSOC) ?? array();
    $data['status'] = count($data['user']) > 0;
    return $data;
}

function blockUserByAdmin($user_id) {
    $pdo = connect_db();

    $query = "UPDATE users SET ac_status=2 WHERE id=:user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $success = $stmt->execute();
    return $success;
}

function unblockUserByAdmin($user_id) {
    $pdo = connect_db();

    $query = "UPDATE users SET ac_status=1 WHERE id=:user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $success = $stmt->execute();
    return $success;
}

function deleteUserByAdmin($user_id) {
    $pdo = connect_db();

    // Start a transaction
    $pdo->beginTransaction();

    try {
        // Delete records in the messages table that reference this user
        $queryMessages = "DELETE FROM messages WHERE sender_id=:user_id OR receiver_id=:user_id";
        $stmtMessages = $pdo->prepare($queryMessages);
        $stmtMessages->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmtMessages->execute();
        $stmtMessages->closeCursor();

        // Delete records in the posts table that reference this user
        $queryPosts = "DELETE FROM posts WHERE user_id=:user_id";
        $stmtPosts = $pdo->prepare($queryPosts);
        $stmtPosts->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmtPosts->execute();
        $stmtPosts->closeCursor();

        // Delete records in the followers table that reference this user
        $query1 = "DELETE FROM followers WHERE follower_id=:user_id OR followed_id=:user_id";
        $stmt1 = $pdo->prepare($query1);
        $stmt1->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt1->execute();
        $stmt1->closeCursor();

        // Delete the user from the users table
        $query2 = "DELETE FROM users WHERE id=:user_id";
        $stmt2 = $pdo->prepare($query2);
        $stmt2->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt2->execute();
        $stmt2->closeCursor();

        // Commit the transaction
        $pdo->commit();
        return true;

    } catch (Exception $e) {
        // Rollback the transaction on error
        $pdo->rollBack();
        echo $e->getMessage();
        return false;
    }
}

// Function to delete a message by its ID
function deleteMessage($message_id) {
    $pdo = connect_db();
    $query = "DELETE FROM messages WHERE id=:message_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':message_id', $message_id, PDO::PARAM_INT);
    $success = $stmt->execute();
    return $success;
}

// Function to update a message by its ID
function updateMessage($message_id, $new_message) {
    $pdo = connect_db();
    $query = "UPDATE messages SET message=:new_message WHERE id=:message_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':new_message', $new_message);
    $stmt->bindParam(':message_id', $message_id, PDO::PARAM_INT);
    $success = $stmt->execute();
    return $success;
}
?>
