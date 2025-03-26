<?php
class Quote {
    private $conn;
    private $table = 'quotes';

    public $id;
    public $quote;
    public $authorId;
    public $categoryId;
    // These hold joined data from foreign keys:
    public $author;
    public $category;

    public function __construct($db) {
        $this->conn = $db;
    }

    // READ all quotes (with author and category names)
    public function read() {
        $query = "SELECT q.id, q.quote, a.author, c.category 
                  FROM quotes q 
                  JOIN authors a ON q.authorId = a.id 
                  JOIN categories c ON q.categoryId = c.id
                  ORDER BY q.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // READ single quote by q.id (with author and category)
    public function read_single() {
        $query = "SELECT q.id, q.quote, a.author, c.category 
                  FROM quotes q 
                  JOIN authors a ON q.authorId = a.id 
                  JOIN categories c ON q.categoryId = c.id
                  WHERE q.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            // Fill object properties for output
            $this->quote    = $row['quote'];
            $this->author   = $row['author'];
            $this->category = $row['category'];
            return true;
        }
        return false;
    }

    // READ quotes by authorId (filter by author)
    public function read_by_author() {
        $query = "SELECT q.id, q.quote, a.author, c.category 
                  FROM quotes q 
                  JOIN authors a ON q.authorId = a.id 
                  JOIN categories c ON q.categoryId = c.id
                  WHERE q.authorId = :authorId 
                  ORDER BY q.id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':authorId', $this->authorId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    // READ quotes by categoryId (filter by category)
    public function read_by_category() {
        $query = "SELECT q.id, q.quote, a.author, c.category 
                  FROM quotes q 
                  JOIN authors a ON q.authorId = a.id 
                  JOIN categories c ON q.categoryId = c.id
                  WHERE q.categoryId = :categoryId 
                  ORDER BY q.id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':categoryId', $this->categoryId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    // READ quotes by both categoryId and authorId (compound filter)
    public function read_by_author_and_category() {
        $query = "SELECT q.id, q.quote, a.author, c.category 
                  FROM quotes q 
                  JOIN authors a ON q.authorId = a.id 
                  JOIN categories c ON q.categoryId = c.id
                  WHERE q.authorId = :authorId AND q.categoryId = :categoryId
                  ORDER BY q.id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':authorId', $this->authorId, PDO::PARAM_INT);
        $stmt->bindParam(':categoryId', $this->categoryId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    // CREATE new quote (requires valid authorId and categoryId)
    public function create() {
        $query = "INSERT INTO {$this->table} (quote, authorId, categoryId) 
                  VALUES (:quote, :authorId, :categoryId)";
        $stmt = $this->conn->prepare($query);
        // Clean inputs
        $this->quote      = htmlspecialchars(strip_tags($this->quote));
        $this->authorId   = htmlspecialchars(strip_tags($this->authorId));
        $this->categoryId = htmlspecialchars(strip_tags($this->categoryId));
        // Bind parameters
        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':authorId', $this->authorId, PDO::PARAM_INT);
        $stmt->bindParam(':categoryId', $this->categoryId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // UPDATE quote by id (requires valid new authorId and categoryId)
    public function update() {
        $query = "UPDATE {$this->table} 
                  SET quote = :quote, authorId = :authorId, categoryId = :categoryId
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        // Clean inputs
        $this->id         = htmlspecialchars(strip_tags($this->id));
        $this->quote      = htmlspecialchars(strip_tags($this->quote));
        $this->authorId   = htmlspecialchars(strip_tags($this->authorId));
        $this->categoryId = htmlspecialchars(strip_tags($this->categoryId));
        // Bind parameters
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':authorId', $this->authorId, PDO::PARAM_INT);
        $stmt->bindParam(':categoryId', $this->categoryId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // DELETE quote by id
    public function delete() {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
