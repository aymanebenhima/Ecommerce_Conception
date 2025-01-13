<?php
namespace Models;

class Category {
    private $conn;
    private $table = "categories";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create(string $name, string $description): bool {
        $query = "INSERT INTO " . $this->table . " 
                SET name=:name, description=:description";

        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ":name" => htmlspecialchars(strip_tags($name)),
            ":description" => htmlspecialchars(strip_tags($description))
        ]);
    }

    public function readAll(): array {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
