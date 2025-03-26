<?php
class Category {
    private $conn;
    private $table = 'categories';

    public $id;
    public $category;

    public function __construct($db) {
        $this->conn = $db;
    }

    // READ all categories
    public function read() {
        $query = "SELECT * FROM {$this->table} ORDER BY id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // READ single category by id
    public function read_single() {
        $query = "SELECT id, category FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->category = $row['category'];
            return true;
        }
        return false;
    }

    // CREATE new category
    public function create() {
        $query = "INSERT INTO {$this->table} (category) VALUES (:category)";
        $stmt = $this->conn->prepare($query);
        $this->category = htmlspecialchars(strip_tags($this->category));
        $stmt->bindParam(':category', $this->category);
        return $stmt->execute();
    }

    // UPDATE category by id
    public function update() {
        $query = "UPDATE {$this->table} SET category = :category WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':category', $this->category);
        return $stmt->execute();
    }

    // DELETE category by id
    public function delete() {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
