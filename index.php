<?php

require_once 'vendor/autoload.php';

use Config\Database;
use Models\{Client, Administrator, Product, Category, Order};

// Configure error display
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Helper function to display results
function displayResult($message, $success) {
    echo str_pad("Test: " . $message, 50) . " : " . 
         ($success ? "Success" : "Failed") . "<br>";
}

// Database connection
$database = new Database();
$db = $database->getConnection();

echo "<br><h1>E-commerce System Tests</h1><br>";

try {
    // 1. Administrator creation test
    echo "<br><h2>Administrator Tests:</h2><br>";
    $admin = new Administrator($db);
    $result = $admin->create(
        "Admin Test",
        "admin@test.com",
        "password123",
        "administrator"
    );
    displayResult("Administrator creation", $result);

    // 2. Administrator login test
    $result = $admin->login("admin@test.com", "password123");
    displayResult("Administrator login", $result);

    // 3. Category creation test
    echo "<br><h2>Category Tests</h2><br>";
    $category = new Category($db);
    $result = $category->create(
        "Electronics",
        "Electronic products and accessories"
    );
    displayResult("Category creation", $result);

    // Retrieve categories
    $categories = $category->readAll();
    $category_id = $categories[0]['id'];
    displayResult("Retrieve categories", count($categories) > 0);

    // 4. Product creation test
    echo "<br><h2>Product Tests</h2><br>";
    $product = new Product($db);
    $productData = [
        'name' => 'Smartphone XYZ',
        'description' => 'A great smartphone',
        'price' => 599.99,
        'stock' => 10,
        'category_id' => $category_id
    ];
    $result = $admin->addProduct($productData);
    displayResult("Product creation", $result);

    // Read product
    $productInfo = $product->read(1);
    displayResult("Read product", $productInfo !== false);

    // 5. Client creation test
    echo "<br><h2>Client Tests</h2><br>";
    $client = new Client($db);
    $result = $client->create(
        "Client Test",
        "client@test.com",
        "password123",
        "client"
    );
    displayResult("Client creation", $result);

    // Client login
    $result = $client->login("client@test.com", "password123");
    displayResult("Client login", $result);

    // 6. Cart tests
    echo "<br><h2>Cart Tests</h2><br>";
    $cart = $client->getCart();
    
    // Add product to cart
    $result = $cart->add(1, 2); // Add 2 smartphones
    displayResult("Add product to cart", $result);

    // Verify cart total
    $total = $cart->getTotal();
    displayResult("Calculate cart total", $total == (599.99 * 2));

    // 7. Order creation test
    echo "<br><h2>Order Tests</h2><br>";
    try {
        $order = $client->placeOrder();
        displayResult("Order creation", $order !== null);
    } catch (\Exception $e) {
        displayResult("Order creation", false);
        echo "<pre>Error: " . $e->getMessage() . "</pre><br>";
    }

    // 8. Order status update test
    $result = $admin->updateOrderStatus(1, 'confirmed');
    displayResult("Update order status", $result);

    // 9. Stock update test
    $result = $product->updateStock(1, -2); // Remove 2 from stock
    displayResult("Stock update", $result);

    echo "<br>All tests completed!<br>";

} catch (\Exception $e) {
    echo "<br><pre>Error during tests: " . $e->getMessage() . "</pre><br>";
}

// Complete usage example
echo "<br>=== Complete Usage Example ===<br>";

try {
    // 1. Create a new client
    $newClient = new Client($db);
    $newClient->create(
        "John Doe",
        "john@example.com",
        "password123",
        "client"
    );
    
    // 2. Client login
    $newClient->login("john@example.com", "password123");
    
    // 3. Add products to cart
    $clientCart = $newClient->getCart();
    $clientCart->add(1, 1); // Add 1 smartphone
    
    // 4. Display cart contents
    echo "<br><h2>Cart contents</h2><br>";
    $cartProducts = $clientCart->getProductList();
    foreach ($cartProducts as $id => $details) {
        echo sprintf(
            "- %s: %d x %.2f€ = %.2f€\n",
            $details['name'],
            $details['quantity'],
            $details['price'],
            $details['price'] * $details['quantity']
        );
    }
    echo "Total: " . $clientCart->getTotal() . "MAD<br>";
    
    // 5. Place the order
    $clientOrder = $newClient->placeOrder();
    
    // 6. Administer the order
    $admin->updateOrderStatus(1, 'in_preparation');
    
    echo "<br>Order created and in preparation!<br>";

} catch (\Exception $e) {
    echo "<br><pre>Error in usage example: " . $e->getMessage() . "</pre><br>";
}
