<?php
/**
 * Database Configuration for EthCo Coders Portfolio
 */

class Database {
    private $host = 'localhost';
    private $db_name = 'ethco_portfolio';
    private $username = '''if0_39358485;
    private $password = 'changedpassis';
    private $conn;

    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $exception) {
            throw new Exception("Database connection error: " . $exception->getMessage());
        }
        
        return $this->conn;
    }
}
?>

