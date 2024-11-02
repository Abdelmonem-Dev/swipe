<?php
session_start(); // Start the session to access user session data
require_once __DIR__ . "/../controllers/ContactController.php"; 

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the recipient ID from the form
    $recipientId = $_POST['recipientId'] ?? '';

    // Validate the recipient ID
    if (!empty($recipientId)) {
        // Attempt to send the friend request
        if (ContactController::sendContactRequest($_SESSION['UserID'], $recipientId)) {
            // Redirect with a success message
            header("Location: index.php?status=success");
            exit();
        } else {
            // Redirect with an error message
            header("Location: index.php?status=error");
            exit();
        }
    } else {
        // Redirect with a validation error message
        header("Location: index.php?status=error&message=Please select a recipient.");
        exit();
    }
} else {
    // Redirect to the form if accessed directly
    header("Location: index.php");
    exit();
}
?>
