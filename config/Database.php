<?php
class Database {
    // Set private variable to hold the connection
    private $conn;

    // Database connection parameters using Render's provided credentials
    private $host = 'dpg-cvgtcqqqgecs73clgg2g-a'; // Internal hostname for Render services
    private $db_name = 'inf653_midtermproj'; // Database name
    private $username = 'inf653_midtermproj_user'; // Username
    private $password = 'SU5971hNKDk2IeVbBBfz09jsCdqETpSg'; // Password

// DB Connect
    public function connect() {
        $this->conn = null;
        try {
            // Create PDO connection string for PostgreSQL
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name}";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            // Set PDO error mode to exception for error handling
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
            exit();
        }
        return $this->conn;
    }
}
