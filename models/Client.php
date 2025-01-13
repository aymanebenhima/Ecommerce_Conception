<?php
namespace Models;

class Client extends User {
    private $cart;

    public function __construct($db) {
        parent::__construct($db);
        $this->role = "client";
        $this->cart = new Cart($db);
    }

    public function getCart(): Cart {
        return $this->cart;
    }

    public function placeOrder(): ?Order {
        if ($this->cart->isEmpty()) {
            throw new \Exception("The cart is empty");
        }

        $order = new Order($this->conn);
        if ($order->create($this->id, $this->cart->getProductList())) {
            $this->cart->clear();
            return $order;
        }
        return null;
    }
}