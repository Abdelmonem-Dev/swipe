<?php
session_start(); // Start the session to manage user data

require_once __DIR__ . "/../../controllers/UserController.php"; // Adjust path as necessary

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form inputs
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Check if inputs are provided
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Email and Password are required."; // Store error in session
        header("Location: sign-in.php");
        exit; // Ensure no further code is executed
    }

    // Clean inputs to prevent SQL injection or XSS attacks
    $email = htmlspecialchars(trim($email));
    $password = htmlspecialchars(trim($password));

    // Validate user credentials
    $loginResult = UserController::login($email, $password); // Assuming `login` method checks credentials

    if ($loginResult === true) {
        // Successful login, redirect to the dashboard
        header("Location: ../index.php"); // Adjust as necessary
        exit;
    } else {
        // If authentication fails, store the error message in session
        $_SESSION['error'] = implode(", ", $loginResult); // Join error messages into a single string
        header("Location: sign-in.php");
        exit;
    }
} else {
    // If request is not POST, redirect to the login page
    header("Location: sign-in.php");
    exit;
}
?>
