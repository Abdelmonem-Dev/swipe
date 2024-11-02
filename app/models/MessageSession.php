<?php
include_once __DIR__."/../database/dbConnection.php";

class MessageSession {
    private static $table = 'MessagesSessions';

    // Constants for message read statuses
    const READ_STATUS_READ = 1;
    const READ_STATUS_UNREAD = 0;

    /**
     * Get discussions based on filter ('all', 'read', 'unread').
     * @param int $userId
     * @param string $filter
     * @return array
     */
    public static function getDiscussions($userId) {
        // Query to fetch the latest discussion, message details, and read status for the user
        $query = "
            SELECT 
                ms.SessionID,
                MAX(m.DateTime) AS last_message_date,
                MAX(m.MessageContent) AS last_message_content,
                SUM(CASE WHEN m.ReadStatus = 0 AND m.SenderID != :userId THEN 1 ELSE 0 END) AS new_messages,
                m.ReadStatus
            FROM 
                " . self::$table . " ms
            LEFT JOIN 
                Messages m ON m.SessionID = ms.SessionID
            WHERE 
                (ms.User1ID = :userId OR ms.User2ID = :userId)
            GROUP BY 
                ms.SessionID
            ORDER BY 
                last_message_date DESC
            LIMIT 1"; // Get only the latest discussion record
    
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare($query);
            $stmt->execute(['userId' => $userId]);
            
            // Fetch the single latest discussion record
            $discussion = $stmt->fetch(PDO::FETCH_ASSOC); 
    
            return $discussion ?: []; // Return an empty array if no discussions found
        } catch (PDOException $e) {
            error_log("Error fetching discussions: " . $e->getMessage());
            return []; // Return empty array on error
        }
    }
    
    
    
    
    /**
     * Retrieve messages for a specific session.
     * @param int $sessionID
     * @return array
     */
    public static function getMessagesBySessions(array $sessionIDs) {
        if (empty($sessionIDs)) {
            return []; // Return an empty array if no session IDs are provided
        }
    
        // Create a comma-separated string of placeholders for prepared statements
        $placeholders = rtrim(str_repeat('?,', count($sessionIDs)), ','); // Ensure it constructs properly
    
        // Prepare your SQL query with multiple placeholders
        $query = "
            SELECT 
                m.MessageContent, 
                m.DateTime, 
                m.SenderID, 
                u.ProfilePictureURL AS avatar, 
                u.UserName AS sender_name
            FROM 
                Messages m
            JOIN 
                Users u ON m.SenderID = u.UserID
            WHERE 
                m.SessionID IN ($placeholders)
            ORDER BY 
                m.DateTime
        ";
    
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare($query);
            
            
            // Extract session ID values from the associative array
            $sessionIDsFlat = array_column($sessionIDs, 'SessionID');
    

    
            // Execute with the flat array of session IDs
            $stmt->execute($sessionIDsFlat); 
    
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching messages by sessions: " . $e->getMessage());
            return []; // Return an empty array on error
        }
    }
    
    
    
    public static function getLastSessionBetweenUsers($user1ID, $user2ID) {
        $query = "
            SELECT 
                SessionID 
            FROM 
                messagessessions
            WHERE 
                ((User1ID = :user1ID AND User2ID = :user2ID) 
                OR 
                (User1ID = :user2ID AND User2ID = :user1ID))
                AND 
                (SessionStatusID = 1)
            LIMIT 1
        ";
    
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':user1ID', $user1ID, PDO::PARAM_INT);
            $stmt->bindValue(':user2ID', $user2ID, PDO::PARAM_INT);
            $stmt->execute();
    
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result ? $result['SessionID'] : null;
        } catch (PDOException $e) {
            error_log("Error fetching last session between users: " . $e->getMessage());
            return null;
        }
    }
    
    
    public static function sendMessage($senderID, $sessionID, $message) {
        $query = "INSERT INTO Messages (MessageContent, DateTime,SessionID,SenderID) VALUES (:message, NOW(),:sessionID,:senderID)";
        $conn = Database::getConnection();
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':senderID', $senderID, PDO::PARAM_INT);
        $stmt->bindParam(':sessionID', $sessionID, PDO::PARAM_INT);
        $stmt->bindParam(':message', $message, PDO::PARAM_STR);
        return $stmt->execute();
    }
    
    /**
     * Get messages between two users.
     * @param int $user1
     * @param int $user2
     * @return array
     */
    public static function getMessages($user1, $user2) {
        $query = "
            SELECT * FROM Messages 
            WHERE (SenderID = :user1 AND ReceiverID = :user2) OR (SenderID = :user2 AND ReceiverID = :user1)
            ORDER BY DateTime
        ";
        $conn = Database::getConnection();
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user1', $user1, PDO::PARAM_INT);
        $stmt->bindParam(':user2', $user2, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function getSessionID($user1, $user2) {
        $query = "SELECT SessionID FROM messagessessions WHERE ((User1ID = :user1 AND User2ID = :user2) OR (User1ID = :user2 AND User2ID = :user1)) and SessionStatusID = 1 LIMIT 1";
        $conn = Database::getConnection();
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user1', $user1, PDO::PARAM_INT);
        $stmt->bindParam(':user2', $user2, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['SessionID'] : null;
    }

    public static function createSession($user1, $user2) {
        $existingSession = self::getLastSessionBetweenUsers($user1, $user2);
        if ($existingSession) {
            return $existingSession; // Return existing session ID if found
        }
    
        $query = "INSERT INTO messagessessions (User1ID, User2ID, StartDateTime, sessionStatusID) VALUES (:user1, :user2, NOW(), 1)";   
        $conn = Database::getConnection();
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user1', $user1, PDO::PARAM_INT);
        $stmt->bindParam(':user2', $user2, PDO::PARAM_INT);
        $stmt->execute();
        return $conn->lastInsertId(); // Return the new SessionID
    }
      public static function sessionExistsBetweenUsers($user1ID, $user2ID) {
        $query = "
            SELECT 
                SessionID 
            FROM 
                messagessessions
            WHERE 
                (User1ID = :user1ID AND User2ID = :user2ID) 
                OR 
                (User1ID = :user2ID AND User2ID = :user1ID)
            LIMIT 1
        ";
    
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':user1ID', $user1ID, PDO::PARAM_INT);
            $stmt->bindValue(':user2ID', $user2ID, PDO::PARAM_INT);
            $stmt->execute();
    
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['SessionID'] : null;
        } catch (PDOException $e) {
            error_log("Error checking if session exists between users: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if a given session is active.
     * @param int $sessionID
     * @return bool True if active, false otherwise
     */
    public static function isSessionActive($sessionID) {
        $query = "
            SELECT 
                sessionStatusID 
            FROM 
                messagessessions
            WHERE 
                SessionID = :sessionID and sessionStatusID = 1 
            LIMIT 1
        ";
    
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':sessionID', $sessionID, PDO::PARAM_INT);
            $stmt->execute();
    
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result && $result['is_active'] == 1;
        } catch (PDOException $e) {
            error_log("Error checking if session is active: " . $e->getMessage());
            return false;
        }
    }
    public static function getLastThreeSessionsBetweenUsers($user1ID, $user2ID) {
        $query = "
            SELECT 
                SessionID
            FROM 
                messagessessions
            WHERE 
                ((User1ID = :user1ID AND User2ID = :user2ID) 
                OR 
                (User1ID = :user2ID AND User2ID = :user1ID))
            ORDER BY 
                LastMessageDateTime DESC
            LIMIT 3
        ";
    
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':user1ID', $user1ID, PDO::PARAM_INT);
            $stmt->bindValue(':user2ID', $user2ID, PDO::PARAM_INT);
            $stmt->execute();
    
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results; // Return an array of session data
        } catch (PDOException $e) {
            error_log("Error fetching last three sessions between users: " . $e->getMessage());
            return []; // Return an empty array on error
        }
    }
    
}
?>
