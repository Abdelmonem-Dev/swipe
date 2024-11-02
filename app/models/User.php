<?php 
include_once __DIR__."/Person.php";
include_once __DIR__."/../database/dbConnection.php";

final class User extends Person {
    private $_userId;
    private $_userName;
    private $_passwordHash;
    private $_emailVerified;
    private $_profilePictureURL;
    private $_bio;
    private $_lastLogin;
    private $_lastPassword;
    private $_createdAt;
    private $_updatedAt;
    private $_deletedAt;
    private $_permissions;
    private $_lastPasswordReset;
    private $_isOnline;
    private $_role;
    private $_status;

    // Constructor
    public function __construct(
        $userName = "",
        $passwordHash = "",
        $emailVerified = 0,
        $profilePictureURL = "",
        $bio = "",
        $lastLogin = null,
        $lastPassword = null,
        $createdAt = null,
        $updatedAt = null,
        $deletedAt = null,
        $permissions = 1,
        $lastPasswordReset = null,
        $isOnline = 1,
        $role = 3,
        $status = 1,
        $firstName = "",
        $lastName = "",
        $email = "",
        $phone = "",
        $gender = "",
        $dateOfBirth = null,
        $age = null,
        $country = ""
    ) {
        // Call parent constructor
        parent::__construct( $firstName, $lastName, $email, $phone, $gender, $dateOfBirth, $age, $country);
        
        // Initialize User-specific properties
        $this->_userName = $userName;
        $this->_passwordHash = $passwordHash;
        $this->_emailVerified = $emailVerified;
        $this->_profilePictureURL = $profilePictureURL;
        $this->_bio = $bio;
        $this->_lastLogin = $lastLogin;
        $this->_lastPassword = $lastPassword;
        $this->_createdAt = $createdAt;
        $this->_updatedAt = $updatedAt;
        $this->_deletedAt = $deletedAt;
        $this->_permissions = $permissions;
        $this->_lastPasswordReset = $lastPasswordReset;
        $this->_isOnline = $isOnline;
        $this->_role = $role;
        $this->_status = $status;
    }

    // Getters
    public function getUserId() {
        return $this->_userId;
    }

    public function getUserName() {
        return $this->_userName;
    }

    public function getPasswordHash() {
        return $this->_passwordHash;
    }

    public function isEmailVerified() {
        return $this->_emailVerified;
    }

    public function getProfilePictureURL() {
        return $this->_profilePictureURL;
    }

    public function getBio() {
        return $this->_bio;
    }

    public function getLastLogin() {
        return $this->_lastLogin;
    }

    public function getLastPassword() {
        return $this->_lastPassword;
    }

    public function getCreatedAt() {
        return $this->_createdAt;
    }

    public function getUpdatedAt() {
        return $this->_updatedAt;
    }

    public function getDeletedAt() {
        return $this->_deletedAt;
    }

    public function getPermissions() {
        return $this->_permissions;
    }

    public function getLastPasswordReset() {
        return $this->_lastPasswordReset;
    }

    public function isOnline() {
        return $this->_isOnline;
    }

    public function getRole() {
        return $this->_role;
    }

    public function getStatus() {
        return $this->_status;
    }

    // Setters
    public function setUserName($userName) {
        $this->_userName = $userName;
    }

    public function setPasswordHash($passwordHash) {
        $this->_passwordHash = $passwordHash;
    }

    public function setEmailVerified($emailVerified) {
        $this->_emailVerified = $emailVerified;
    }

    public function setProfilePictureURL($profilePictureURL) {
        $this->_profilePictureURL = $profilePictureURL;
    }

    public function setBio($bio) {
        $this->_bio = $bio;
    }

    public function setLastLogin($lastLogin) {
        $this->_lastLogin = $lastLogin;
    }

    public function setLastPassword($lastPassword) {
        $this->_lastPassword = $lastPassword;
    }

    public function setUpdatedAt($updatedAt) {
        $this->_updatedAt = $updatedAt;
    }

    public function setDeletedAt($deletedAt) {
        $this->_deletedAt = $deletedAt;
    }

    public function setPermissions($permissions) {
        $this->_permissions = $permissions;
    }

    public function setLastPasswordReset($lastPasswordReset) {
        $this->_lastPasswordReset = $lastPasswordReset;
    }

    public function setIsOnline($isOnline) {
        $this->_isOnline = $isOnline;
    }

    public function setRole($role) {
        $this->_role = $role;
    }

    public function setStatus($status) {
        $this->_status = $status;
    }


    protected static function addUser($UserName, $PasswordHash,$RoleID =3,$StatusID = 3, $PersonID){
        $dbConnection = Database::getConnection();

        $stmt = $dbConnection->prepare("INSERT INTO users (UserName, PasswordHash,RoleID,StatusID, PersonID) VALUES (?,?, ?, ?, ?)");

        $result = $stmt->execute([
            $UserName,
            $PasswordHash,
            $RoleID,
            $StatusID,
            $PersonID
        ]);

        return $result;
    }
    public function signUp() {
        try {
            $dbConnection = Database::getConnection();
            $dbConnection->beginTransaction(); // Start transaction for both inserts
    
            // Check if the email already exists
            $existingUser = User::checkEmail($this->_email);
            echo "Existing User Count: " . $existingUser . "\n";
            
            if ($existingUser > 0) {
                $dbConnection->rollBack(); // Roll back the transaction
                echo "Email already exists.";
                return false;
            }
    
            // Insert into persons table
            $personResult = User::addPerson($this->_firstName, $this->_email, $this->_countryID);
            
            if ($personResult) {
                // Get the last inserted PersonID
                $personId = $dbConnection->lastInsertId();
                echo "PersonID: " . $personId . "\n";
                
                $this->_userName = $this->_email;
                // Insert into users table    
                $result = User::addUser($this->_userName, $this->_passwordHash, 3, 3, $personId);
                
                if ($result) {
                    $dbConnection->commit(); // Commit transaction
                    return true;
                } else {
                    $dbConnection->rollBack(); // Roll back the transaction in case of failure
                    echo "Error inserting into users table.";
                    return false;
                }
            } else {
                $dbConnection->rollBack(); // Roll back the transaction if person insert fails
                echo "Error inserting into persons table.";
                return false;
            }
        } catch (PDOException $e) {
            if ($dbConnection->inTransaction()) {
                $dbConnection->rollBack(); // Roll back on any exception
            }
    
            echo "Database error: " . $e->getMessage();
            return false;
        } finally {
            Database::close();
        }
    }
    public function login($email, $password) {
        try {
            $dbConnection = Database::getConnection();

            // Check if the email exists in the users table
            $stmt = $dbConnection->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
    
            // Fetch the user data
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Check if the user was found
            if (!$user) {
                echo "Email not found.";
                return false; // Email does not exist
            }
    
            // Verify the password
            if (password_verify($password, $user['password_hash'])) {
                // Password is correct
                echo "Login successful.";
    
                // Optional: Update the last login timestamp or any other login-specific info
                $updateStmt = $dbConnection->prepare("UPDATE users SET last_login = NOW() WHERE email = ?");
                $updateStmt->execute([$email]);
    
                // Store the user's session data
                $_SESSION['UserID'] = $user['PersonID'];
                $_SESSION['UserData'] = $user;
    
                return true;
            } else {
                // Password is incorrect
                echo "Invalid password.";
                return false;
            }
        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
            return false;
        } finally {
            Database::close(); // Close the database connection
        }
    }
    public static function getPersonIDByUserName($UserName) {
        $dbConnection = Database::getConnection();

        // Prepare the SQL statement to prevent SQL injection
        $stmt = $dbConnection->prepare("SELECT PersonID FROM Users WHERE UserName = ?");
        $stmt->execute([$UserName]);

        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Return the PersonID if found, or null if not
        return $result ? $result['PersonID'] : null;
    }
    public static function getPersonIDByEmail($email) {
        $dbConnection = Database::getConnection();

        // Prepare the SQL statement to prevent SQL injection
        $stmt = $dbConnection->prepare("SELECT PersonID FROM Persons WHERE Email = ?");
        $stmt->execute([$email]);

        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Return the PersonID if found, or null if not
        return $result ? $result['PersonID'] : null;
    }
    public static function getByPersonID($personId) {
        $dbConnection = Database::getConnection();
        
        $stmt = $dbConnection->prepare("
            SELECT 
                u.UserID, u.UserName, u.PasswordHash, u.EmailVerified,
                u.ProfilePictureURL, u.Bio, u.LastLogin, u.LastPassword, 
                u.CreatedAt, u.UpdatedAt, u.DeletedAt, u.Permissions, 
                u.LastPasswordReset, u.IsOnline, u.RoleID, u.StatusID, 
                p.FirstName, p.LastName, p.Email, p.Phone, p.Gender, 
                p.DateOfBirth, p.Age, p.CountryID
            FROM users u
            JOIN persons p ON u.PersonID = p.PersonID
            WHERE p.PersonID = ?
        ");
        
        $stmt->execute([$personId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        Database::close();
        return $result ?: null; 
    }
    
 // Private method to check if the username is unique
 private static function isUserNameUnique($userName) {
    $dbConnection = Database::getConnection();
    
    try {
        // Prepare the SQL statement to search for the UserName
        $stmt = $dbConnection->prepare("SELECT COUNT(*) FROM users WHERE UserName = ?");
        
        // Execute the query with the provided userName
        $stmt->execute([$userName]);
        
        // Fetch the result (it will return a count of how many rows match the given userName)
        $count = $stmt->fetchColumn();
        
    } catch (PDOException $e) {
        // Log the error and return false in case of failure
        error_log("Error checking username uniqueness: " . $e->getMessage());
        return false;
    } finally {
        Database::close(); // Ensure connection is always closed
    }

    // If count is 0, the UserName is unique
    return $count == 0;
}
// Public method to update the username
public static function updateUserName($userName, $personId) {
    $dbConnection = Database::getConnection();
    
    // Check if the username is unique
    if (self::isUserNameUnique($userName)) {
        try {
            // Proceed to update the username
            $stmt = $dbConnection->prepare("UPDATE users SET UserName = ? WHERE PersonID = ?");
            $result = $stmt->execute([$userName, $personId]);
            
            if ($result) {
                return true; // Update successful
            } else {
                return false; // Update failed
            }

        } catch (PDOException $e) {
            // Log the error and return false
            error_log("Error updating username: " . $e->getMessage());
            return false;

        } finally {
            Database::close(); // Ensure connection is closed
        }
        
    } else {
        // Handle the case where the username is not unique
        return "Username already exists. Please choose a different one.";
    }
}


public function updateUser($userId, $data) {
    // Build the SQL query dynamically based on the fields in the $data array
    $fields = [];
    foreach ($data as $key => $value) {
        $fields[] = "$key = :$key";
    }
    
    $sql = "UPDATE Persons SET " . implode(', ', $fields) . " WHERE PersonID = :UserID";
    $dbConnection = Database::getConnection();
    $stmt = $dbConnection->prepare($sql);

    // Bind the values from the data array
    foreach ($data as $key => $value) {
        $stmt->bindValue(":$key", $value);
    }
    
    // Bind the user ID
    $stmt->bindValue(":UserID", $userId, PDO::PARAM_INT);
    $Result = $stmt->execute();
    Database::close(); // Ensure connection is closed

    return $Result; // Return true if the update is successful
}
public function updateUserPassword($userId, $data) {
    // Build the SQL query dynamically based on the fields in the $data array
    $fields = [];
    foreach ($data as $key => $value) {
        $fields[] = "$key = :$key";
    }
    
    // Construct the SQL update query
    $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE PersonID = :UserID";
    
    // Get a connection to the database
    $dbConnection = Database::getConnection();
    $stmt = $dbConnection->prepare($sql);

    // Bind the values from the data array
    foreach ($data as $key => $value) {
        $stmt->bindValue(":$key", $value);
    }
    
    // Bind the user ID
    $stmt->bindValue(":UserID", $userId, PDO::PARAM_INT);
    
    // Execute the query
    $result = $stmt->execute();
    
    // Close the connection
    Database::close();

    // Return the result of the execution
    return $result;
}
public function getUsers() {
    $dbConnection = Database::getConnection();
    $stmt = $dbConnection->prepare('SELECT * FROM users'); // Adjust the fields as necessary
    $stmt->execute();
    
    // Fetch all results as an associative array
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    Database::close(); // Ensure the connection is closed
    return $users;
}
public static function getAllUsers($UserID) {
    $dbConnection = Database::getConnection();
    $stmt = $dbConnection->prepare("SELECT UserID, UserName FROM users WHERE UserId != :currentUserId");
    $stmt->bindValue(':currentUserId', $UserID, PDO::PARAM_INT);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    Database::close();
    return $users;
}
}