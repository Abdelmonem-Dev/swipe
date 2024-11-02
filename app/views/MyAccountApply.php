<?php
require_once __DIR__ . "/../controllers/UserController.php"; 

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $firstName = $_POST['firstName'] ?? '';
    $lastName = $_POST['lastName'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $dateOfBirth = $_POST['dateOfBirth'] ?? '';
    $countryID = $_POST['CountryID'] ?? '';

    // Validate form data (basic validation)
    $errors = [];
    if (empty($firstName) || empty($lastName) || empty($email)) {
        $errors[] = 'First name, last name, and email are required.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }


    // Compare current data with submitted data
    $dataChanged = true;

    // Check if each field is different
    if ($_SESSION['FirstName'] == $firstName) {
        $dataChanged = false;
    }
    if ($_SESSION['LastName'] == $lastName) {
        $dataChanged = false;
    }
    if ($_SESSION['Email'] == $email) {
        $dataChanged = false;
    }
    if ($_SESSION['Phone'] == $phone) {
        $dataChanged = false;
    }
    if ($_SESSION['Gender'] == $gender) {
        $dataChanged = false;
    }
    if ($_SESSION['DateOfBirth'] == $dateOfBirth) {
        $dataChanged = false;
    }
    if ($_SESSION['CountryID'] == $countryID) {
        $dataChanged = false;
    }

    // Process data if no errors and data has changed
    if (empty($errors)) {
        if (!$dataChanged) {
            // Prepare user data for update
            $data = [
                'FirstName' => $firstName,
                'LastName' => $lastName,
                'Email' => $email,
                'Phone' => $phone,
                'Gender' => $gender,
                'DateOfBirth' => $dateOfBirth,
                'CountryID' => $countryID,
            ];

            // Call the update method (assuming updateUser is defined in User model)
            if (UserController::updateUser($data)) {
                // Redirect back to the same form with a success message
                header("Location: index.php?status=success");
                exit(); // Stop further script execution
            } else {
                // Redirect back to the same form with an error message
                header("Location: index.php?status=error");
                exit(); // Stop further script execution
            }
        } else {
            // If no data has changed, redirect with a message
            header("Location: index.php?status=noupdate"); // Indicate that no update was necessary
            exit();
        }
    } else {
        // Display validation errors
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    }
}
?>
