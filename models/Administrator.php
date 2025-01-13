<?php
namespace Models;

class Administrator extends User {
    public function __construct($db) {
        parent::__construct($db);
        $this->role = "administrator";
    }

    public function addProduct(array $data): bool {
        $product = new Product($this->conn);
        return $product->create($data);
    }

    public function deleteProduct(int $id): bool {
        $product = new Product($this->conn);
        return $product->delete($id);
    }

    public function updateOrderStatus(int $orderId, string $status): bool {
        $order = new Order($this->conn);
        return $order->updateStatus($orderId, $status);
    }
}