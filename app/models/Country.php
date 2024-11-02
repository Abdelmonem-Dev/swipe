<?php 
require_once __DIR__ . "../../database/dbConnection.php";
class Country {
    protected static $TableName = "Countries";
    protected $countryId;
    protected $countryName;

    public function __construct() {
    }

    public function getCountryName() {
        return $this->countryName;
    }
    public static function getAllCountries(){
        $sql = "SELECT * FROM " . self::$TableName ."";
        $db = Database::getConnection();

        // Prepare and execute the query
        $stmt = $db->prepare($sql);
        $stmt->execute();
        Database::close();
        // Fetch all results as an associative array
        $countries = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return the countries data
        return $countries;
    }
}
