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
            // Prepare the DSN (Data Source Name) for PostgreSQL
            $dsn = 'pgsql:host=' . $this->host . ';port=5432;dbname=' . $this->db_name . ';options=\'--client_encoding=UTF8\'';

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
