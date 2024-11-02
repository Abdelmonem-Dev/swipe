<?php
require_once __DIR__ . "/../controllers/MessageController.php"; 

// Check if the user is logged in
if (!isset($_SESSION['UserID'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

// Get the logged-in user ID
$senderID = $_SESSION['UserID'];

// Get POST data
$contactID = isset($_POST['contactID']) ? intval($_POST['contactID']) : null;
$messageContent = isset($_POST['message']) ? trim($_POST['message']) : null;

if ($contactID && $messageContent) {
    // Optionally validate message content here (e.g., max length)
    if (strlen($messageContent) > 500) {
        echo json_encode(['error' => 'Message is too long']);
        exit;
    }

    // Get or create a session ID for this conversation
    $sessionID = MessageController::getSessionID($senderID, $contactID);
    if (!$sessionID) {
        $sessionID = MessageController::createSession($senderID, $contactID);
    }

    // Save the message in the database
    $result = MessageController::sendMessage($senderID, $sessionID, $messageContent);

    if ($result) {
        // Return the new message details for immediate display
        echo json_encode([
            'success' => true, 
            'message' => $messageContent, 
            'timestamp' => date("h:i A"), 
            'senderID' => $senderID
        ]);
    } else {
        echo json_encode(['error' => 'Message could not be sent']);
    }
} else {
    echo json_encode(['error' => 'Incomplete data']);
}
?>