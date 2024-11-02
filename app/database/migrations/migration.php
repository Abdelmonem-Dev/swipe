<?php
require_once '../dbConnection.php';

final class Migration {
    private $connection;

    public function __construct() {
        $this->connection = Database::getConnection();

        if ($this->connection === null) {
            die("Failed to connect to the database.");
        }
    }

    
    // Create Country table
    private function createCountryTable() {
        try {
            $sql = "CREATE TABLE IF NOT EXISTS Countries (
                CountryID INT AUTO_INCREMENT PRIMARY KEY,
                CountryName VARCHAR(100) not null
            )";
            $this->connection->exec($sql); 
        } catch (PDOException $e) {
            echo "Error creating Countries table: " . $e->getMessage();

        }
    }

    // Create Person table
    private function createPersonTable() {
        try {
            $sql = "CREATE TABLE IF NOT EXISTS Persons (
                PersonID INT AUTO_INCREMENT PRIMARY KEY,
                FirstName VARCHAR(30) not null,
                LastName VARCHAR(30),
                Email VARCHAR(255) UNIQUE,
                Phone VARCHAR(15),
                Gender CHAR(1),
                DateOfBirth DATE,
                Age INT,
                CountryID INT,
                FOREIGN KEY (CountryID) REFERENCES Countries(CountryID)
            )";
            $this->connection->exec($sql);
        } catch (PDOException $e) {
            echo "Error creating Persons table: " . $e->getMessage();
        }
    }
    
    private function addUserNameIndex() {
        try {
            $sql = "ALTER TABLE Users ADD INDEX (UserName)";
            $this->connection->exec($sql);
        } catch (PDOException $e) {
            echo "Error adding index on UserName: " . $e->getMessage();
        }
    }
 // Create UserRole table
 private function createUserRoleTable() {
    try {
        $sql = "CREATE TABLE IF NOT EXISTS UsersRoles (
            RoleID INT AUTO_INCREMENT PRIMARY KEY,
            RoleName VARCHAR(100) not null
        )";
        $this->connection->exec($sql);
    } catch (PDOException $e) {
        echo "Error creating UsersRoles table: " . $e->getMessage();
    }
   
}

// Create Status table
private function createUserStatusTable() {
    try {
        $sql = "CREATE TABLE IF NOT EXISTS UsersStatus (
            StatusID INT AUTO_INCREMENT PRIMARY KEY,
            Status VARCHAR(100) not null
        )";
        $this->connection->exec($sql);
    } catch (PDOException $e) {
        echo "Error creating UsersStatus table: " . $e->getMessage();
    }
}
  // Create SessionStatus table
  private function createSessionStatusTable() {
    try {
        $sql = "CREATE TABLE IF NOT EXISTS SessionsStatus (
            SessionStatusID INT AUTO_INCREMENT PRIMARY KEY,
            StatusName VARCHAR(100) not null
        )";
        $this->connection->exec($sql);
    } catch (PDOException $e) {
        echo "Error creating SessionsStatus table: " . $e->getMessage();
    }
}

    // Create User table
    private function createUserTable() {
        try {
            $sql = "CREATE TABLE IF NOT EXISTS Users (
                UserID INT AUTO_INCREMENT PRIMARY KEY,
                UserName VARCHAR(50) UNIQUE not null,
                PasswordHash VARCHAR(255) not null,
                EmailVerified TINYINT(1) DEFAULT 0,
                ProfilePictureURL VARCHAR(500),
                Bio TEXT,
                LastLogin DATETIME DEFAULT CURRENT_TIMESTAMP,
                LastPassword DATETIME DEFAULT CURRENT_TIMESTAMP,
                CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP,
                UpdatedAt DATETIME DEFAULT CURRENT_TIMESTAMP,
                DeletedAt DATETIME DEFAULT NULL,
                Permissions INT not null DEFAULT 1,
                LastPasswordReset VARCHAR(255),
                IsOnline TINYINT(1) DEFAULT 1,
                RoleID INT DEFAULT 3,
                StatusID INT DEFAULT 1,
                PersonID INT,
                FOREIGN KEY (RoleID) REFERENCES UsersRoles(RoleID),
                FOREIGN KEY (StatusID) REFERENCES UsersStatus(StatusID),
                FOREIGN KEY (PersonID) REFERENCES Persons(PersonID)
            )";
            $this->connection->exec($sql);
            $this->addUserNameIndex();
        } catch (PDOException $e) {
            echo "Error creating Users table: " . $e->getMessage();
        }
    }

     // Create triggers
     private function createTriggers() {
        try {
            $triggers = [
                "CREATE TRIGGER update_last_login
                BEFORE UPDATE ON Users
                FOR EACH ROW
                BEGIN
                    IF NEW.IsOnline = 1 THEN
                        SET NEW.LastLogin = NOW();
                    END IF;
                END;",
    
                "CREATE TRIGGER update_last_password
                BEFORE UPDATE ON Users
                FOR EACH ROW
                BEGIN
                    IF OLD.PasswordHash != NEW.PasswordHash THEN
                        SET NEW.LastPassword = NOW();
                    END IF;
                END;",
    
                "CREATE TRIGGER update_last_password_reset
                BEFORE UPDATE ON Users
                FOR EACH ROW
                BEGIN
                    IF OLD.PasswordHash != NEW.PasswordHash THEN
                        SET NEW.LastPasswordReset = NOW();
                    END IF;
                END;",
    
                "CREATE TRIGGER before_user_update
                BEFORE UPDATE ON Users
                FOR EACH ROW
                BEGIN
                    SET NEW.UpdatedAt = NOW();
                END;",
    
                "CREATE TRIGGER calculate_Persons_age_insert
                BEFORE INSERT ON Persons
                FOR EACH ROW
                BEGIN
                    IF NEW.DateOfBirth IS NOT NULL THEN
                        SET NEW.Age = TIMESTAMPDIFF(YEAR, NEW.DateOfBirth, CURDATE());
                    END IF;
                END;",
    
                "CREATE TRIGGER calculate_Persons_age_update
                BEFORE UPDATE ON Persons
                FOR EACH ROW
                BEGIN
                    IF NEW.DateOfBirth IS NOT NULL THEN
                        SET NEW.Age = TIMESTAMPDIFF(YEAR, NEW.DateOfBirth, CURDATE());
                    END IF;
                END;",
    
                "CREATE TRIGGER prevent_owner_deletion
                BEFORE DELETE ON Users
                FOR EACH ROW
                BEGIN
                    IF OLD.RoleID = 1 THEN 
                        SIGNAL SQLSTATE '45000' 
                        SET MESSAGE_TEXT = 'Deletion of the owner is not allowed.';
                    END IF;
                END;"
            ];
    
            // Execute triggers creation
            foreach ($triggers as $trigger) {
                $this->connection->exec($trigger);
            }
    
            // Create the scheduled event for updating inactive sessions
            $event = "
                DROP EVENT IF EXISTS update_inactive_sessions;
                CREATE EVENT update_inactive_sessions
                ON SCHEDULE EVERY 30 MINUTE
                DO
                BEGIN
                    -- Update SessionStatusID to 2 if no messages have been sent in the last hour
                    UPDATE messagessessions s
                    SET s.SessionStatusID = 2
                    WHERE s.SessionID NOT IN (
                        SELECT DISTINCT m.SessionID
                        FROM Messages m
                        WHERE m.DateTime >= NOW() - INTERVAL 1 HOUR
                    )
                    AND s.SessionStatusID <> 2; -- Optional: Only update if the status is not already 2
                END;
            ";
    
            // Execute event creation
            $this->connection->exec($event);
    
        } catch (PDOException $e) {
            echo "Error creating triggers or event: " . $e->getMessage();
        }
    }
    
    // Create MessageSession table
    private function createMessageSessionTable() {
        try {
            $sql = "CREATE TABLE IF NOT EXISTS MessagesSessions (
                SessionID INT AUTO_INCREMENT PRIMARY KEY,
                StartDateTime DATETIME not null DEFAULT CURRENT_TIMESTAMP,
                EndDateTime DATETIME,
                LastMessageDateTime DATETIME DEFAULT CURRENT_TIMESTAMP,
                User1ID INT,
                User2ID INT,
                SessionStatusID INT,
                FOREIGN KEY (User1ID) REFERENCES Users(UserID),
                FOREIGN KEY (User2ID) REFERENCES Users(UserID),
                FOREIGN KEY (SessionStatusID) REFERENCES SessionsStatus(SessionStatusID)
            )";
            $this->connection->exec($sql);
        } catch (PDOException $e) {
            echo "Error creating MessagesSessions table: " . $e->getMessage();
        }
    }

    // Create Message table
    private function createMessageTable() {
        try {
            $sql = "CREATE TABLE IF NOT EXISTS Messages (
                MessageID INT AUTO_INCREMENT PRIMARY KEY,
                MessageContent TEXT not null,
                DateTime DATETIME,
                ReadStatus BOOLEAN,
                SessionID INT,
                SenderID INT,
                FOREIGN KEY (SessionID) REFERENCES MessagesSessions(SessionID),
                FOREIGN KEY (SenderID) REFERENCES Users(UserID)
            )";
            $this->connection->exec($sql);
        } catch (PDOException $e) {
            echo "Error creating Messages table: " . $e->getMessage();
        }
    }

    // Create Contact table
    private function createContactTable() {
        try {
            $sql = "CREATE TABLE IF NOT EXISTS Contacts (
                ContactID INT AUTO_INCREMENT PRIMARY KEY,
                DateAdded DATETIME DEFAULT CURRENT_TIMESTAMP,
                IsDeleted TINYINT(1) NOT NULL DEFAULT 0,
                User1ID INT NOT NULL,                          -- First user in the contact relationship
                User2ID INT NOT NULL,                          -- Second user in the contact relationship
                RequesterID INT NOT NULL,                      -- Tracks the last user who sent the request
                Status ENUM('Pending', 'Accepted', 'Rejected') DEFAULT 'Pending',  -- Tracks the connection status
                FOREIGN KEY (User1ID) REFERENCES Users(UserID) ON DELETE CASCADE,  -- Handle deletion
                FOREIGN KEY (User2ID) REFERENCES Users(UserID) ON DELETE CASCADE,  -- Handle deletion
                FOREIGN KEY (RequesterID) REFERENCES Users(UserID) ON DELETE CASCADE, -- Handle deletion
                UNIQUE (User1ID, User2ID)                      -- Ensures only one record per user pair
            )";
            $this->connection->exec($sql);
            echo "Contacts table created successfully.\n";  // Confirm table creation
        } catch (PDOException $e) {
            echo "Error creating Contacts table: " . $e->getMessage();  // Error handling
        }
    }
    
    
       // Drop triggers
       private function dropTriggers() {
        $triggers = [
            'update_last_login',
            'update_last_password_reset',
            'before_user_update',
            'calculate_user_age'
        ];

        foreach ($triggers as $trigger) {
            $sql = "DROP TRIGGER IF EXISTS $trigger";
            try {
                $this->connection->exec($sql);
            } catch (PDOException $e) {
                echo "Error dropping trigger $trigger: " . $e->getMessage();
            }
        }
    }
    private function dropTables() {

        $this->dropTriggers();

        $tables = [
            'Messages',          // Depends on MessagesSessions and Users
            'Contacts',          // Depends on Users
            'MessagesSessions',  // Depends on Users and SessionsStatus
            'Users',             // Depends on UsersRoles, Status, and Persons
            'UsersRoles',        // No dependencies
            'UsersStatus',            // No dependencies
            'SessionsStatus',    // No dependencies
            'Persons',           // Depends on Countries
            'Countries'         // No dependencies
        ];
    
        foreach ($tables as $table) {
            $sql = "DROP TABLE IF EXISTS $table";
            $this->connection->exec($sql);
        }
        echo "Database droped successfully\n";

    }
    // Run all migrations
    public function runMigrations() {

        $this->dropTables();

        $this->createCountryTable();
        $this->createPersonTable();
        $this->createUserRoleTable();
        $this->createUserStatusTable();
        $this->createSessionStatusTable();
        $this->createUserTable();
        $this->createMessageSessionTable();
        $this->createMessageTable();
        $this->createContactTable();
        $this->createTriggers();  // Call the method to create triggers
        echo "Database added successfully\n";
        echo "Triggers created successfully.\n";
        echo "Events created successfully.\n";

        $this->connection = null;
    }
}

// Usage
$migration = new Migration();
$migration->runMigrations();