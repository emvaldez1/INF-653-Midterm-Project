<?php
class Database {
    // Set private variable to hold the connection
    private $conn;

    // Database connection parameters
    private $host = 'localhost';
    private $db_name = 'quotesdb';
    private $username = 'root';
    private $password = '';

    // Get the database connection
    public function connect() {
        $this->conn = null;

        try {
            // Prepare the DSN (Data Source Name)
            $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name . ';charset=utf8mb4';

            // Create a new PDO connection
            $this->conn = new PDO($dsn, $this->username, $this->password);
            // Set PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }

        return $this->conn;
    }
}
