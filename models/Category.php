<?php
class Category {
    private $conn;
    private $table_name = "categories";

    public $id;
    public $category;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT id, category FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readSingle() {
        $query = "SELECT id, category FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->category = $row['category'];
        }
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET category=:category";
        $stmt = $this->conn->prepare($query);
        $this->category = htmlspecialchars(strip_tags($this->category));
        $stmt->bindParam(":category", $this->category);
        return $stmt->execute();
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET category=:category WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $this->category = htmlspecialchars(strip_tags($this->category));
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);
        return $stmt->execute();
    }
}
