<?php
namespace Models;

class Product {
    private $conn;
    private $table = "products";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create(array $data): bool {
        $query = "INSERT INTO " . $this->table . " 
                SET name=:name, description=:description, price=:price, 
                    stock=:stock, category_id=:category_id";

        $stmt = $this->conn->prepare($query);
        
        $params = [
            ":name" => htmlspecialchars(strip_tags($data['name'])),
            ":description" => htmlspecialchars(strip_tags($data['description'])),
            ":price" => htmlspecialchars(strip_tags($data['price'])),
            ":stock" => htmlspecialchars(strip_tags($data['stock'])),
            ":category_id" => htmlspecialchars(strip_tags($data['category_id']))
        ];

        return $stmt->execute($params);
    }

    public function read(int $id) {
        $query = "SELECT p.*, c.name as category_name 
                FROM " . $this->table . " p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([":id" => $id]);

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function updateStock(int $id, int $quantity): bool {
        $query = "UPDATE " . $this->table . " 
                SET stock = stock + :quantity 
                WHERE id = :id AND (stock + :quantity) >= 0";

        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ":id" => $id,
            ":quantity" => $quantity
        ]);
    }
}