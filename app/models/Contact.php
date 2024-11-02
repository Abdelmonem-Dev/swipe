<?php 
include_once __DIR__ . "/../database/dbConnection.php";

class Contact
{
    private $_contactID;
    private $_dateAdded;
    private $_isDeleted;
    private $_user1ID;
    private $_user2ID;
    private $_requesterID;
    private $_status;

    public function __construct($contactID, $dateAdded, $isDeleted, $user1ID, $user2ID, $requesterID, $status)
    {
        $this->_contactID = $contactID;
        $this->_dateAdded = $dateAdded;
        $this->_isDeleted = $isDeleted;
        $this->_user1ID = $user1ID;
        $this->_user2ID = $user2ID;
        $this->_requesterID = $requesterID;
        $this->_status = $status;
    }

    // Getters
    public function getContactID()
    {
        return $this->_contactID;
    }

    public function getDateAdded()
    {
        return $this->_dateAdded;
    }

    public function getIsDeleted()
    {
        return $this->_isDeleted;
    }

    public function getUser1ID()
    {
        return $this->_user1ID;
    }

    public function getUser2ID()
    {
        return $this->_user2ID;
    }

    public function getRequesterID()
    {
        return $this->_requesterID;
    }

    public function getStatus()
    {
        return $this->_status;
    }

    // Setters
    public function setContactID($contactID)
    {
        $this->_contactID = $contactID;
    }

    public function setDateAdded($dateAdded)
    {
        $this->_dateAdded = $dateAdded;
    }

    public function setIsDeleted($isDeleted)
    {
        $this->_isDeleted = $isDeleted;
    }

    public function setUser1ID($user1ID)
    {
        $this->_user1ID = $user1ID;
    }

    public function setUser2ID($user2ID)
    {
        $this->_user2ID = $user2ID;
    }

    public function setRequesterID($requesterID)
    {
        $this->_requesterID = $requesterID;
    }

    public function setStatus($status)
    {
        $this->_status = $status;
    }

    // Example function to save contact in the database
    public function saveContact($pdo)
    {
        $stmt = $pdo->prepare('INSERT INTO Contacts (DateAdded, IsDeleted, User1ID, User2ID, RequesterID, Status) VALUES (?, ?, ?, ?, ?, ?)');
        return $stmt->execute([$this->_dateAdded, $this->_isDeleted, $this->_user1ID, $this->_user2ID, $this->_requesterID, $this->_status]);
    }

    // Example function to update contact
    public function updateContact($pdo)
    {
        $stmt = $pdo->prepare('UPDATE Contacts SET DateAdded = ?, IsDeleted = ?, User1ID = ?, User2ID = ?, RequesterID = ?, Status = ? WHERE ContactID = ?');
        return $stmt->execute([$this->_dateAdded, $this->_isDeleted, $this->_user1ID, $this->_user2ID, $this->_requesterID, $this->_status, $this->_contactID]);
    }

    // Example function to delete contact (soft delete by setting isDeleted = 1)
    public function deleteContact($pdo)
    {
        $this->_isDeleted = 1;
        $stmt = $pdo->prepare('UPDATE Contacts SET IsDeleted = ? WHERE ContactID = ?');
        return $stmt->execute([$this->_isDeleted, $this->_contactID]);
    }

    // Example function to fetch contact by ID
    public static function getContactByID($pdo, $contactID)
    {
        $stmt = $pdo->prepare('SELECT * FROM Contacts WHERE ContactID = ?');
        $stmt->execute([$contactID]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getAllContacts($userID)
    {
        $dbConnection = Database::getConnection();
        $stmt = $dbConnection->prepare('SELECT * FROM Contacts WHERE (User1ID = ? OR User2ID = ?) AND Status = "Accepted"');
        $stmt->execute([$userID, $userID]);
        Database::close();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getContactsCount($userID)
    {
        $dbConnection = Database::getConnection();
        $stmt = $dbConnection->prepare('SELECT COUNT(*) AS contacts_count FROM Contacts WHERE (User1ID = ? OR User2ID = ?) AND IsDeleted = 0');
        $stmt->execute([$userID, $userID]);

        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        Database::close();

        // Return the contact count
        return $result['contacts_count'] ?? 0; // Return 0 if no count found
    }

    public static function sendContactRequest($senderID, $recipientID) {
        // Assuming a PDO connection
        $db = Database::getConnection();

        // Prepare an SQL statement to insert a new contact request
        $stmt = $db->prepare("INSERT INTO Contacts (User1ID, User2ID, RequesterID, DateAdded, IsDeleted, Status) VALUES (:senderID, :recipientID, :senderID, NOW(), 0, 'Pending')
        ON DUPLICATE KEY UPDATE RequesterID = :senderID, Status = 'Pending'");

        // Bind parameters
        $stmt->bindParam(':senderID', $senderID, PDO::PARAM_INT);
        $stmt->bindParam(':recipientID', $recipientID, PDO::PARAM_INT);

        // Execute the query and check if successful
        return $stmt->execute();
    }
}
