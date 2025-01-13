<?php
namespace Models;

class Order {
    private $conn;
    private $table = "orders";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create(int $clientId, array $products): bool {
        try {
            $this->conn->beginTransaction();

            // Create the order
            $query = "INSERT INTO " . $this->table . " 
                    SET client_id=:client_id, total=:total, status='pending'";
            
            $stmt = $this->conn->prepare($query);
            $total = $this->calculateTotal($products);
            
            $stmt->execute([
                ":client_id" => $clientId,
                ":total" => $total
            ]);

            $orderId = $this->conn->lastInsertId();

            // Add products to the order
            foreach($products as $productId => $details) {
                $this->addOrderProduct($orderId, $productId, $details);
            }

            $this->conn->commit();
            return true;
        } catch(\Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    private function addOrderProduct(int $orderId, int $productId, array $details) {
        $query = "INSERT INTO order_products 
                SET order_id=:order_id, product_id=:product_id, 
                    quantity=:quantity, unit_price=:price";
                    
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ":order_id" => $orderId,
            ":product_id" => $productId,
            ":quantity" => $details['quantity'],
            ":price" => $details['price']
        ]);

        // Update stock
        $product = new Product($this->conn);
        $product->updateStock($productId, -$details['quantity']);
    }

    private function calculateTotal(array $products): float {
        $total = 0;
        foreach($products as $product) {
            $total += $product['price'] * $product['quantity'];
        }
        return $total;
    }

    public function updateStatus(int $orderId, string $status): bool {
        $validStatuses = ['pending', 'confirmed', 'in_preparation', 'shipped', 'delivered', 'canceled'];
        
        if(!in_array($status, $validStatuses)) {
            return false;
        }

        $query = "UPDATE " . $this->table . " 
                SET status=:status 
                WHERE id=:id";

        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ":id" => $orderId,
            ":status" => $status
        ]);
    }
}
