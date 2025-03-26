<?php
class Author {
    private $conn;
    private $table = 'authors';  // Database table name

    // Table columns as public properties
    public $id;
    public $author;

    // Constructor with DB connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // READ all authors
    public function read() {
        $query = "SELECT * FROM {$this->table} ORDER BY id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // READ single author by id
    public function read_single() {
        $query = "SELECT id, author FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            // Populate object properties
            $this->author = $row['author'];
            return true;  // Data found
        }
        return false;  // Not found
    }

    // CREATE new author
    public function create() {
        $query = "INSERT INTO {$this->table} (author) VALUES (:author)";
        $stmt = $this->conn->prepare($query);
        // Clean input
        $this->author = htmlspecialchars(strip_tags($this->author));
        $stmt->bindParam(':author', $this->author);
        // Execute and return status
        return $stmt->execute();
    }

    // UPDATE author by id
    public function update() {
        $query = "UPDATE {$this->table} SET author = :author WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        // Clean input
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':author', $this->author);
        return $stmt->execute();
    }

    // DELETE author by id
    public function delete() {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
