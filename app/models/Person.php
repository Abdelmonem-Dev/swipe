<?php 
abstract class Person {
    protected $_personId;
    protected $_firstName;
    protected $_lastName;
    protected $_email;
    protected $_phone;
    protected $_gender;
    protected $_dateOfBirth;
    protected $_age;
    protected $_countryID;

    // Constructor
    public function __construct($firstName, $lastName, $email, $phone, $gender, $dateOfBirth, $age, $countryID) {
        $this->_firstName = $firstName;
        $this->_lastName = $lastName;
        $this->_email = $email;
        $this->_phone = $phone;
        $this->_gender = $gender;
        $this->_dateOfBirth = $dateOfBirth;
        $this->_age = $age;
        $this->_countryID = $countryID;
    }

    // Getters and Setters for all properties

    public function getPersonId() {
        return $this->_personId;
    }

    public function setPersonId($personId) {
        $this->_personId = $personId;
    }

    public function getFirstName() {
        return $this->_firstName;
    }

    public function setFirstName($firstName) {
        $this->_firstName = $firstName;
    }

    public function getLastName() {
        return $this->_lastName;
    }

    public function setLastName($lastName) {
        $this->_lastName = $lastName;
    }

    public function getEmail() {
        return $this->_email;
    }

    public function setEmail($email) {
        $this->_email = $email;
    }

    public function getPhone() {
        return $this->_phone;
    }

    public function setPhone($phone) {
        $this->_phone = $phone;
    }

    public function getGender() {
        return $this->_gender;
    }

    public function setGender($gender) {
        $this->_gender = $gender;
    }

    public function getDateOfBirth() {
        return $this->_dateOfBirth;
    }

    public function setDateOfBirth($dateOfBirth) {
        $this->_dateOfBirth = $dateOfBirth;
    }

    public function getAge() {
        return $this->_age;
    }

    public function setAge($age) {
        $this->_age = $age;
    }

    public function getCountryID() {
        return $this->_countryID;
    }

    public function setCountryID($countryID) {
        $this->_countryID = $countryID;
    }
    protected static function checkEmail($email){
        $dbConnection = Database::getConnection();
    
        $stmt = $dbConnection->prepare("SELECT COUNT(Email) as email_count FROM persons WHERE Email = ?");
        $stmt->execute([$email]);
    
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result['email_count']; // Return only the count
    }
    
    protected static function addPerson($firstName,$email,$countryID){
        $dbConnection = Database::getConnection();

        $stmt = $dbConnection->prepare("INSERT INTO Persons (FirstName, Email, CountryID) VALUES (?, ?, ?)");
        $personResult = $stmt->execute([$firstName,$email,$countryID]);

        return $personResult;
    }
}
