<?php
class Database {
    private $host = 'dpg-cvgtcqqqgecs73clgg2g-a'; // Adjust to your PostgreSQL host
    private $db_name = 'inf653_midtermproj'; // Your database name
    private $username = 'inf653_midtermproj_user'; // Your database username
    private $password = 'SU5971hNKDk2IeVbBBfz09jsCdqETpSg'; // Your database password
    private $conn;

    public function connect() {
        $this->conn = null;
        try {
            $dsn = 'pgsql:host=' . $this->host . ';port=5432;dbname=' . $this->db_name;
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }
        return $this->conn;
    }
}
