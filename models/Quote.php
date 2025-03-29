<?php
class Quote {
    // DB connection and table
    private $conn;
    private $table = 'quotes';

    // Quote properties
    public $id;
    public $quote;
    public $author_id;
    public $category_id;

    // Constructor with DB
    public function __construct($db) {
        $this->conn = $db;
    }

    // Read all quotes
    public function read() {
        $query = 'SELECT q.id, q.quote, a.author, c.category FROM ' . $this->table . ' q
                  JOIN authors a ON q.author_id = a.id
                  JOIN categories c ON q.category_id = c.id';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read a single quote by ID
    public function read_single() {
        $query = 'SELECT q.id, q.quote, a.author, c.category FROM ' . $this->table . ' q
                  JOIN authors a ON q.author_id = a.id
                  JOIN categories c ON q.category_id = c.id
                  WHERE q.id = ? LIMIT 0,1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
            $this->quote = $row['quote'];
            $this->author_id = $row['author'];
            $this->category_id = $row['category'];
            return true;
        }
        return false;
    }

    // Create a new quote
    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' (quote, author_id, category_id) VALUES (:quote, :author_id, :category_id)';
        $stmt = $this->conn->prepare($query);
        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Update an existing quote
    public function update() {
        $query = 'UPDATE ' . $this->table . ' SET quote = :quote, author_id = :author_id, category_id = :category_id WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':id', $this->id);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete a quote
    public function delete() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
