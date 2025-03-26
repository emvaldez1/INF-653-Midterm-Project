<?php
class Quote {
    private $conn;
    private $table = 'quotes';

    public $id;
    public $quote;
    public $author_id;
    public $category_id;
    public $author_name;
    public $category_name;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = 'SELECT q.id, q.quote, a.author AS author, c.category AS category
                  FROM quotes q
                  JOIN authors a ON q.author_id = a.id
                  JOIN categories c ON q.category_id = c.id';
        return $this->conn->query($query);
    }

    public function readSingle() {
        $query = 'SELECT q.id, q.quote, a.author AS author, c.category AS category
                  FROM quotes q
                  JOIN authors a ON q.author_id = a.id
                  JOIN categories c ON q.category_id = c.id
                  WHERE q.id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $this->id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $this->quote = $row['quote'];
            $this->author_name = $row['author'];
            $this->category_name = $row['category'];
        }
    }

    public function create() {
        $query = 'INSERT INTO quotes (quote, author_id, category_id) VALUES (:quote, :author_id, :category_id) RETURNING id';
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':quote' => $this->quote,
            ':author_id' => $this->author_id,
            ':category_id' => $this->category_id
        ]);
        $this->id = $stmt->fetchColumn();
        return $this->id ? true : false;
    }

    public function update() {
        $query = 'UPDATE quotes SET quote = :quote, author_id = :author_id, category_id = :category_id WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':quote' => $this->quote,
            ':author_id' => $this->author_id,
            ':category_id' => $this->category_id,
            ':id' => $this->id
        ]);
    }

    public function delete() {
        $query = 'DELETE FROM quotes WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':id' => $this->id]);
    }
}
