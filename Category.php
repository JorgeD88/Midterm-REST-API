<?php

class Category {
    private $conn;
    private $table = 'categories';

    public $id;
    public $category;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read($id = null) {
        $query = 'SELECT id, category FROM ' . $this->table;

        if ($id !== null) {
            $query .= ' WHERE id = :id';
        }

        $stmt = $this->conn->prepare($query);

        if ($id !== null) {
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' (category) VALUES (:category)';
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':category', $this->category);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

    public function update() {
        $query = 'UPDATE ' . $this->table . ' SET category = :category WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':category', $this->category);

        return $stmt->execute();
    }

    public function delete() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function exists($id) {
        $query = 'SELECT id FROM ' . $this->table . ' WHERE id = :id LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}
