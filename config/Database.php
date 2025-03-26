<?php
class Database {
    // Set private variable to hold the connection
    private $conn;

    // Database connection parameters using Render's provided credentials
    private $host = 'dpg-cvgtcqqqgecs73clgg2g-a'; // Internal hostname for Render services
    private $db_name = 'inf653_midtermproj'; // Database name
    private $username = 'inf653_midtermproj_user'; // Username
    private $password = 'SU5971hNKDk2IeVbBBfz09jsCdqETpSg'; // Password

    // Get the database connection
    public function connect() {
        $this->conn = null;

        try {
            $dsn = 'pgsql:host=' . $this->host . ';dbname=' . $this->db_name;
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo json_encode(['error' => 'Connection Error: ' . $e->getMessage()]);
            exit;
        }

        return $this->conn;
    }
}
