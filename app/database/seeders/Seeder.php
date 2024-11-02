<?php
require_once '../dbConnection.php';

final class Seeder {
    private $connection;

    public function __construct() {
        $this->connection = Database::getConnection();
    }

// Seed the Country table
public function seedCountryTable() {
    try {
        $sql = "INSERT INTO Countries (CountryName) VALUES
            ('Afghanistan'),
            ('Albania'),
            ('Algeria'),
            ('Andorra'),
            ('Angola'),
            ('Antigua and Barbuda'),
            ('Argentina'),
            ('Armenia'),
            ('Australia'),
            ('Austria'),
            ('Azerbaijan'),
            ('Bahamas'),
            ('Bahrain'),
            ('Bangladesh'),
            ('Barbados'),
            ('Belarus'),
            ('Belgium'),
            ('Belize'),
            ('Benin'),
            ('Bhutan'),
            ('Bolivia'),
            ('Bosnia and Herzegovina'),
            ('Botswana'),
            ('Brazil'),
            ('Brunei'),
            ('Bulgaria'),
            ('Burkina Faso'),
            ('Burundi'),
            ('Cabo Verde'),
            ('Cambodia'),
            ('Cameroon'),
            ('Canada'),
            ('Central African Republic'),
            ('Chad'),
            ('Chile'),
            ('China'),
            ('Colombia'),
            ('Comoros'),
            ('Congo (Congo-Brazzaville)'),
            ('Costa Rica'),
            ('Croatia'),
            ('Cuba'),
            ('Cyprus'),
            ('Czechia'),
            ('Democratic Republic of the Congo'),
            ('Denmark'),
            ('Djibouti'),
            ('Dominica'),
            ('Dominican Republic'),
            ('Ecuador'),
            ('Egypt'),
            ('El Salvador'),
            ('Equatorial Guinea'),
            ('Eritrea'),
            ('Estonia'),
            ('Eswatini'),
            ('Ethiopia'),
            ('Fiji'),
            ('Finland'),
            ('France'),
            ('Gabon'),
            ('Gambia'),
            ('Georgia'),
            ('Germany'),
            ('Ghana'),
            ('Greece'),
            ('Grenada'),
            ('Guatemala'),
            ('Guinea'),
            ('Guinea-Bissau'),
            ('Guyana'),
            ('Haiti'),
            ('Honduras'),
            ('Hungary'),
            ('Iceland'),
            ('India'),
            ('Indonesia'),
            ('Iran'),
            ('Iraq'),
            ('Ireland'),
            ('Italy'),
            ('Jamaica'),
            ('Japan'),
            ('Jordan'),
            ('Kazakhstan'),
            ('Kenya'),
            ('Kiribati'),
            ('Kuwait'),
            ('Kyrgyzstan'),
            ('Laos'),
            ('Latvia'),
            ('Lebanon'),
            ('Lesotho'),
            ('Liberia'),
            ('Libya'),
            ('Liechtenstein'),
            ('Lithuania'),
            ('Luxembourg'),
            ('Madagascar'),
            ('Malawi'),
            ('Malaysia'),
            ('Maldives'),
            ('Mali'),
            ('Malta'),
            ('Marshall Islands'),
            ('Mauritania'),
            ('Mauritius'),
            ('Mexico'),
            ('Micronesia'),
            ('Moldova'),
            ('Monaco'),
            ('Mongolia'),
            ('Montenegro'),
            ('Morocco'),
            ('Mozambique'),
            ('Myanmar'),
            ('Namibia'),
            ('Nauru'),
            ('Nepal'),
            ('Netherlands'),
            ('New Zealand'),
            ('Nicaragua'),
            ('Niger'),
            ('Nigeria'),
            ('North Korea'),
            ('North Macedonia'),
            ('Norway'),
            ('Oman'),
            ('Pakistan'),
            ('Palau'),
            ('Panama'),
            ('Papua New Guinea'),
            ('Paraguay'),
            ('Peru'),
            ('Philippines'),
            ('Poland'),
            ('Portugal'),
            ('Qatar'),
            ('Romania'),
            ('Russia'),
            ('Rwanda'),
            ('Saint Kitts and Nevis'),
            ('Saint Lucia'),
            ('Saint Vincent and the Grenadines'),
            ('Samoa'),
            ('San Marino'),
            ('Sao Tome and Principe'),
            ('Saudi Arabia'),
            ('Senegal'),
            ('Serbia'),
            ('Seychelles'),
            ('Sierra Leone'),
            ('Singapore'),
            ('Slovakia'),
            ('Slovenia'),
            ('Solomon Islands'),
            ('Somalia'),
            ('South Africa'),
            ('South Korea'),
            ('South Sudan'),
            ('Spain'),
            ('Sri Lanka'),
            ('Sudan'),
            ('Suriname'),
            ('Sweden'),
            ('Switzerland'),
            ('Syria'),
            ('Taiwan'),
            ('Tajikistan'),
            ('Tanzania'),
            ('Thailand'),
            ('Timor-Leste'),
            ('Togo'),
            ('Tonga'),
            ('Trinidad and Tobago'),
            ('Tunisia'),
            ('Turkey'),
            ('Turkmenistan'),
            ('Tuvalu'),
            ('Uganda'),
            ('Ukraine'),
            ('United Arab Emirates'),
            ('United Kingdom'),
            ('United States'),
            ('Uruguay'),
            ('Uzbekistan'),
            ('Vanuatu'),
            ('Vatican City'),
            ('Venezuela'),
            ('Vietnam'),
            ('Yemen'),
            ('Zambia'),
            ('Zimbabwe')";
        
        $this->connection->exec($sql);
        echo "Country table seeded successfully.\n";
    } catch (PDOException $e) {
        echo "Error seeding Countries table: " . $e->getMessage() . "\n";
    }
}

    // Seed the Person table
    private function seedPersonTable() {
        try {
            $sql = "INSERT INTO Persons (FirstName, LastName, Email, Phone, Gender, DateOfBirth, Age, CountryID) VALUES
                ('John', 'Doe', 'john@example.com', '555-1234', 'M', '1990-01-01', 34, 1),
                ('Jane', 'Smith', 'jane@example.com', '555-5678', 'F', '1985-05-12', 39, 84)";
            $this->connection->exec($sql);
            echo "Person table seeded successfully.\n";
        } catch (PDOException $e) {
            echo "Error seeding Persons table: " . $e->getMessage() . "\n";
        }
    }

    // Seed the UserRole table
    private function seedUserRoleTable() {
        try {
            $sql = "INSERT INTO UsersRoles (RoleName) VALUES
                ('Admin'),
                ('Moderator'),
                ('User'),
                ('Guest')";
            $this->connection->exec($sql);
            echo "UserRole table seeded successfully.\n";
        } catch (PDOException $e) {
            echo "Error seeding UsersRoles table: " . $e->getMessage() . "\n";
        }
    }

    // Seed the UsersStatus table
    private function seedStatusTable() {
        try {
            $sql = "INSERT INTO UsersStatus (Status) VALUES
                ('Active'),
                ('Inactive'),
                ('Suspended')";
            $this->connection->exec($sql);
            echo "Status table seeded successfully.\n";
        } catch (PDOException $e) {
            echo "Error seeding Status table: " . $e->getMessage() . "\n";
        }
    }

    // Seed the User table
    private function seedUserTable() {
        try {
            $sql = "INSERT INTO Users (UserName, PasswordHash, EmailVerified, ProfilePictureURL, Bio, DeletedAt, Permissions, LastPasswordReset, RoleID, StatusID, PersonID) VALUES
                ('johndoe', 'passwordhash1', 1, 'profile1.jpg', 'Hello, I am John', NULL, 1, 'passwordhash1', 1, 1, 1),
                ('janesmith', 'passwordhash2', 1, 'profile2.jpg', 'Hi, I am Jane', NULL, 1, 'passwordhash2', 2, 1, 2)";
            $this->connection->exec($sql);
            echo "User table seeded successfully.\n";
        } catch (PDOException $e) {
            echo "Error seeding Users table: " . $e->getMessage() . "\n";
        }
    }
    // Seed the SessionStatus table
    private function seedSessionStatusTable() {
        try {
            $sql = "INSERT INTO SessionsStatus (StatusName) VALUES
                ('Active'),
                ('Ended')";
            $this->connection->exec($sql);
            echo "SessionStatus table seeded successfully.\n";
        } catch (PDOException $e) {
            echo "Error seeding SessionsStatus table: " . $e->getMessage() . "\n";
        }
    }

    // Seed the MessageSession table
    private function seedMessageSessionTable() {
        try {
            $sql = "INSERT INTO MessagesSessions (EndDateTime, User1ID, User2ID, SessionStatusID) VALUES
                (NULL, 1, 2, 1)";
            $this->connection->exec($sql);
            echo "MessageSession table seeded successfully.\n";
        } catch (PDOException $e) {
            echo "Error seeding MessagesSessions table: " . $e->getMessage() . "\n";
        }
    }


    // Seed the Message table
    private function seedMessageTable() {
        try {
            $sql = "INSERT INTO Messages (MessageContent, ReadStatus, SessionID, SenderID) VALUES
                ('Hello Jane!', 0, 1, 1),
                ('Hi John!', 0, 1, 2)";
            $this->connection->exec($sql);
            echo "Message table seeded successfully.\n";
        } catch (PDOException $e) {
            echo "Error seeding Messages table: " . $e->getMessage() . "\n";
        }
    }

    // Seed the Contact table
    private function seedContactTable() {
        try {
            // Insert or update for User 1 -> User 2
            $sql = "INSERT INTO Contacts (IsDeleted, User1ID, User2ID, RequesterID, Status) VALUES
                (0, 1, 2, 1, 'Accepted')  -- User 1 sends a request to User 2
                ON DUPLICATE KEY UPDATE 
                    RequesterID = VALUES(RequesterID), 
                    Status = 'Pending';";  // Update the RequesterID and set status to Pending if it exists
    
            $this->connection->exec($sql);
    
            // Insert or update for User 2 -> User 1
            $sql = "INSERT INTO Contacts (IsDeleted, User1ID, User2ID, RequesterID, Status) VALUES
                (0, 2, 1, 2, 'Accepted')  -- User 2 sends a request to User 1
                ON DUPLICATE KEY UPDATE 
                    RequesterID = VALUES(RequesterID), 
                    Status = 'Pending';";  // Update the RequesterID and set status to Pending if it exists
    
            $this->connection->exec($sql);
    
            echo "Contact table seeded successfully.\n";
        } catch (PDOException $e) {
            echo "Error seeding Contacts table: " . $e->getMessage() . "\n";
        }
    }
    
    
    
    
 // Drop all data from the tables
 private function dropAllData() {
    try {
        // Disable foreign key checks
        $this->connection->exec("SET FOREIGN_KEY_CHECKS = 0;");

        // List of tables to truncate in the correct order
        $tables = [
            'Messages',           // Dependent on MessagesSessions
            'MessagesSessions',    // Dependent on Users
            'Contacts',           // Dependent on Users
            'Users',              // Dependent on UsersRoles and Persons
            'UsersRoles',         // No dependencies
            'UsersStatus',             // No dependencies
            'SessionsStatus',     // No dependencies
            'Persons',            // Dependent on Countries
            'Countries'           // No dependencies
        ];

        foreach ($tables as $table) {
            $sql = "TRUNCATE TABLE $table";
            $this->connection->exec($sql);
            echo "Data in table $table dropped successfully.\n";
        }

        // Re-enable foreign key checks
        $this->connection->exec("SET FOREIGN_KEY_CHECKS = 1;");
    } catch (PDOException $e) {
        echo "Error dropping data: " . $e->getMessage() . "\n";
    }
}

    // Run all seeders
    public function runSeeders() {
        $this->dropAllData();
        $this->seedCountryTable();
        $this->seedPersonTable();
        $this->seedUserRoleTable();
        $this->seedStatusTable();
        $this->seedSessionStatusTable();
        $this->seedUserTable();
        $this->seedMessageSessionTable();
        $this->seedMessageTable();
        $this->seedContactTable();


        $this->connection = null; 
    }
}

// Usage
$seeder = new Seeder();
$seeder->runSeeders();