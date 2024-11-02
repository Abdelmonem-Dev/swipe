<?php
require_once __DIR__ . "/../controllers/UserController.php"; 

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $currentPassword = $_POST['password'] ?? '';
    $newPassword = $_POST['newPassword'] ?? '';

    // Validate form data
    $errors = [];
    if (empty($currentPassword) || empty($newPassword)) {
        $errors[] = 'Both current password and new password are required.';
    }

    // If new password is provided, validate the current password
    if (!empty($newPassword)) {
        if (!UserController::verifyPassword($currentPassword)) {
            $errors[] = 'The current password is incorrect.';
        } else {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        }
    } else {
        $hashedPassword = null;
    }

    // If no errors, proceed with updating the password
    if (empty($errors)) {
        // Prepare the data array for updating the password
        $data = [];
        if ($hashedPassword) {
            $data['PasswordHash'] = $hashedPassword;
        }

        // Call the update method
        if (UserController::updateUserPrivacy($data)) {
            // Redirect back to the same form with a success message
            header("Location: index.php?status=success");
            exit(); // Stop further script execution
        } else {
            // Redirect back to the same form with an error message
            header("Location: index.php?status=error");
            exit(); // Stop further script execution
        }

    } else {
        // Display validation errors
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    }
}
?>
