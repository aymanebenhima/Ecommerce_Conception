<?php
namespace Models;

class Cart {
    private $conn;
    private $products = [];
    private $total = 0;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function add(int $productId, int $quantity = 1): bool {
        $product = new Product($this->conn);
        $details = $product->read($productId);

        if ($details && $details['stock'] >= $quantity) {
            if (!isset($this->products[$productId])) {
                $this->products[$productId] = [
                    'quantity' => 0,
                    'price' => $details['price'],
                    'name' => $details['name']
                ];
            }
            
            $this->products[$productId]['quantity'] += $quantity;
            $this->calculateTotal();
            return true;
        }
        return false;
    }

    public function remove(int $productId, int $quantity = 1): bool {
        if (isset($this->products[$productId])) {
            if ($this->products[$productId]['quantity'] <= $quantity) {
                unset($this->products[$productId]);
            } else {
                $this->products[$productId]['quantity'] -= $quantity;
            }
            $this->calculateTotal();
            return true;
        }
        return false;
    }

    private function calculateTotal() {
        $this->total = 0;
        foreach ($this->products as $product) {
            $this->total += $product['price'] * $product['quantity'];
        }
    }

    public function clear() {
        $this->products = [];
        $this->total = 0;
    }

    public function getTotal(): float {
        return $this->total;
    }

    public function getProductList(): array {
        return $this->products;
    }

    public function isEmpty(): bool {
        return empty($this->products);
    }
}
