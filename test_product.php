<?php
// test_product.php
require_once 'config/database.php';
require_once 'models/Product.php';

echo "<h1>Test Product Model</h1>";

try {
    // Setup database
    $database = new Database();
    $db = $database->conn;

    echo "<p style='color: green;'>✅ Database connected!</p>";

    // Create Product model
    $productModel = new Product($db);
    echo "<p style='color: green;'>✅ Product model loaded!</p>";

    // Test getAll
    echo "<h2>Get All Products:</h2>";
    $result = $productModel->getAll();

    if ($result && $result->num_rows > 0) {
        echo "<p>Found <strong>" . $result->num_rows . "</strong> products</p>";

        echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
        echo "<tr style='background: #f0f0f0;'>
                <th>ID</th>
                <th>Name</th>
                <th>Brand</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
              </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['product_id']}</td>";
            echo "<td>{$row['product_name']}</td>";
            echo "<td>" . ($row['brand'] ?? '-') . "</td>";
            echo "<td>" . ($row['category_name'] ?? '-') . "</td>";
            echo "<td>Rp " . number_format($row['price'], 0, ',', '.') . "</td>";
            echo "<td>{$row['stock']}</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p style='color: orange;'>⚠️ No products found</p>";
    }

    echo "<hr>";
    echo "<h2 style='color: green;'>✅ Test completed successfully!</h2>";

} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?>