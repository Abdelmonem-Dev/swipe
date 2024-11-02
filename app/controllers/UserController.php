<?php 
include_once  __DIR__ ."/../models/User.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
class UserController {
    public static function signUp($firstName, $email, $password, $countryID) {
        $errors = [];
        
        // Trim input values
        $firstName = trim($firstName);
        $email = trim($email);
        $password = trim($password);
        $countryID = trim($countryID);
    
        // Validate first name
        if (empty($firstName)) {
            $errors[] = "First name is required.";
        }
    
        // Validate email
        if (empty($email)) {
            $errors[] = "Email is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }
    
        // Validate password (ensure it is >= 6 characters)
        if (empty($password)) {
            $errors[] = "Password is required.";
        } elseif (strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters long.";
        }
    
        // Validate country ID
        if (empty($countryID)) {
            $errors[] = "Country is required.";
        }
    
        // If there are validation errors, return them
        if (!empty($errors)) {
            return $errors; // Return the array of errors
        }
    
        // Create User instance and set properties
        $user = new User();
        $user->setFirstName($firstName);
        $user->setEmail($email);
        $user->setPasswordHash(password_hash($password, PASSWORD_DEFAULT)); // Hash the password
        $user->setCountryID($countryID);
            
        // Check if the signup was successful
        if ($user->signUp()) {
            $PersonID = User::getPersonIDByEmail($user->getEmail());
            // Fetch user data by PersonID
            $UserData = $user->getByPersonID($PersonID);
            $_SESSION['UserID'] = $PersonID; // Store the new User ID in session
            $_SESSION['UserData'] = $UserData; // Store user data in session
            return true; 
        } else {
            return false; // Sign up failed
        }
    }
    
    public static function login($email, $password) {
        $errors = [];
    
        // Trim input values
        $email = trim($email);
        $password = trim($password);
    
        // Validate email
        if (empty($email)) {
            $errors[] = "Email is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }
    
        // Validate password
        if (empty($password)) {
            $errors[] = "Password is required.";
        }
    
        // If there are validation errors, return them
        if (!empty($errors)) {
            return $errors; // Return the array of errors
        }
    
        // Create a new User instance
        $user = new User();
        $user->setEmail($email);
    
        // Get PersonID from email
        $PersonID = User::getPersonIDByEmail($user->getEmail());
    
        // Fetch user data by PersonID
        $userData = $user->getByPersonID($PersonID);
    
        if (!$userData) {
            $errors[] = "No account found with this email.";
            return $errors;
        }
    
        // Verify the provided password
        if (password_verify($password, $userData['PasswordHash'])) {
            // Successful login, set up the session
            $_SESSION['UserID'] = $PersonID;
            $_SESSION['UserData'] = $userData; // Store user data in session
            return true; // Successful login
        } else {
            $errors[] = "Invalid password.";
            return $errors; // Return error
        }
    }
    public static function updateUserName($UserName) {
        // Validate input
        if (empty($UserName)) {
            return false;
        }
        return User::updateUserName($UserName, $_SESSION['UserID']);
    }
    private static function UpdateSESSIONS($UserID){
        $userData = User::getByPersonID($UserID);
        $_SESSION['UserData'] = $userData;

    }
    public static function updateUser($data){
        $user = new User();
        $PersonID = $_SESSION['UserID'];
        if($user->updateUser( $PersonID,$data)){
            UserController::UpdateSESSIONS($PersonID);
            return true;
        }
        return false;
    }
    public static function updateUserPrivacy($data) {
        $user = new User();
    
        // Attempt to update the password
        if ($user->updateUserPassword($_SESSION['UserID'], $data)) {
            // If successful, update the session
            UserController::updateSESSIONS($_SESSION['UserID']);
            return true; // Indicate success
        } else {
            return false; // Indicate failure
        }
    }
    
    public static function verifyPassword($currentPassword) {
        // Fetch the current user's password hash from the session
        $userData = $_SESSION['UserData'];

        // Verify the password using PHP's password_verify function
        return password_verify($currentPassword, $userData['PasswordHash']);
    }
    public static function getUserData($PersonID){
        return User::getByPersonID($PersonID);
    }
    public static function getPersonIDByUserName($UserName){
        return User::getPersonIDByUserName($UserName);
    }
    public static function getAllUsers() {
       
        return User::getAllUsers($_SESSION['UserID']);
    }

}