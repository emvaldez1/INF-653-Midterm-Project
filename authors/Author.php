<?php
class Author {
    // Database connection and table name
    private $conn;
    private $table_name = "authors";

    // Object properties
    public $id;
    public $author;  

    // Constructor with DB connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // Read all authors
    public function read() {
        $query = "SELECT id, author FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read one author by ID
    public function readSingle() {
        $query = "SELECT id, author FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->author = $row['author'];
        }
    }

    // Create a new author
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET author=:author";
        $stmt = $this->conn->prepare($query);
        $this->author = htmlspecialchars(strip_tags($this->author));
        $stmt->bindParam(":author", $this->author);
        return $stmt->execute();
    }

    // Update an existing author
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET author=:author WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    // Delete an author
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);
        return $stmt->execute();
    }
}
