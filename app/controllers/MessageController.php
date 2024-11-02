<?php
// Start a session if none exists
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . "/../models/MessageSession.php";

class MessageController {

    public static function displayDiscussions($UserID) {
        try {
            return MessageSession::getDiscussions($UserID);
        } catch (PDOException $e) {
            error_log("Error fetching discussions: " . $e->getMessage());
            return [];
        }
    }
    public static function getLastSessionBetweenUsers($user1ID, $user2ID) {
        return MessageSession::getLastSessionBetweenUsers($user1ID, $user2ID);
    }
    public static function showChat($sessionIDs) {
        try {
            return MessageSession::getMessagesBySessions($sessionIDs);
        } catch (PDOException $e) {
            error_log("Error fetching chat messages: " . $e->getMessage());
            return [];
        }
    }
    public static function sendMessage($senderID, $sessionID, $message) {
        return MessageSession::sendMessage($senderID, $sessionID, $message);
    }

    public static function getMessages($user1, $user2) {
        return MessageSession::getMessages($user1, $user2);
    }
    public static function getSessionID($user1, $user2) {
        return MessageSession::getSessionID($user1, $user2);
    }
    public static function createSession($user1, $user2) {
        return MessageSession::createSession($user1, $user2);
    }
    public static function getLastThreeSessions($user1, $user2) {
        return MessageSession::getLastThreeSessionsBetweenUsers($user1, $user2);
    }
}
?>
