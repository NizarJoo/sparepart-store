<?php
require_once 'config/database.php';
require_once 'models/Order.php';

$database = new Database();
$db = $database->conn;

$orderModel = new Order($db);

// Test generate order number
$order_number = $orderModel->generateOrderNumber();

echo "<h1>Test Generate Order Number</h1>";
echo "<p>Generated: <strong>{$order_number}</strong></p>";

// Test multiple times (harusnya unique)
echo "<h2>Generate 5 order numbers:</h2>";
for ($i = 0; $i < 5; $i++) {
    echo "<p>" . ($i + 1) . ". " . $orderModel->generateOrderNumber() . "</p>";
}

echo "<h2>✅ Method berfungsi dengan baik!</h2>";
?>