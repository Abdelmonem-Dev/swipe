<?PHP

final class Database {
    private static $instance = null;
    private $connection;

    // Database connection constants
    private const DB_HOST = 'localhost';
    private const DB_NAME = 'chat_db';
    private const DB_USER = 'root';
    private const DB_PASSWORD = '';

    // Private constructor to enforce singleton
    private function __construct() {
        $this->connect();
    }

    // Singleton instance retrieval
    private static function getInstance(): Database{
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Establish database connection using PDO
    private function connect() {
        try {
            
            $dsn = 'mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_NAME . ';charset=utf8mb4';
            $this->connection = new PDO($dsn, self::DB_USER, self::DB_PASSWORD, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        } catch (PDOException $e) {
            // Handle error (e.g., log it)
            error_log('Database connection error: ' . $e->getMessage());
            throw new Exception('Database connection failed');
        }
    }

    public static function getConnection() {
        return self::getInstance()->connection;
    }

    public static function close() {
        if (self::$instance) {
            self::$instance->connection = null; 
            self::$instance = null; 
        }
    }
       // Destructor
    public function __destruct() {
        $this->connection = null; 
    }
}

