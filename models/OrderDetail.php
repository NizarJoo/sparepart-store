<?php
/**
 * OrderDetail Model
 * File: models/OrderDetail.php
 * Represent tabel 'order_details' di database
 */

class OrderDetail
{
    // Database connection
    private $conn;
    private $table = 'order_details';

    // Properties
    public $order_detail_id;
    public $order_id;
    public $product_id;
    public $quantity;
    public $price_per_item;
    public $subtotal;

    /**
     * Constructor
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // ==================== READ OPERATIONS ====================

    /**
     * Get order details by order ID (with product info)
     */
    public function getByOrderId($order_id)
    {
        $query = "SELECT 
                    od.*,
                    p.product_name,
                    p.brand,
                    p.image_url
                  FROM {$this->table} od
                  INNER JOIN products p ON od.product_id = p.product_id
                  WHERE od.order_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();

        return $stmt->get_result();
    }

    /**
     * Get order detail by ID
     */
    public function getById($id)
    {
        $query = "SELECT 
                    od.*,
                    p.product_name,
                    p.brand
                  FROM {$this->table} od
                  INNER JOIN products p ON od.product_id = p.product_id
                  WHERE od.order_detail_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // ==================== CREATE OPERATION ====================

    /**
     * Create single order detail
     */
    public function create()
    {
        $query = "INSERT INTO {$this->table} 
                  (order_id, product_id, quantity, price_per_item, subtotal) 
                  VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        $stmt->bind_param(
            "iiidd",
            $this->order_id,
            $this->product_id,
            $this->quantity,
            $this->price_per_item,
            $this->subtotal
        );

        return $stmt->execute();
    }

    /**
     * Create multiple order details (dari cart items)
     * @param int $order_id
     * @param array $cart_items (array of cart items with product details)
     * @return bool
     */
    public function createFromCart($order_id, $cart_items)
    {
        // Start transaction
        $this->conn->begin_transaction();

        try {
            foreach ($cart_items as $item) {
                $this->order_id = $order_id;
                $this->product_id = $item['product_id'];
                $this->quantity = $item['quantity'];
                $this->price_per_item = $item['price'];
                $this->subtotal = $item['quantity'] * $item['price'];

                if (!$this->create()) {
                    throw new Exception("Failed to create order detail");
                }
            }

            // Commit transaction
            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            // Rollback on error
            $this->conn->rollback();
            return false;
        }
    }

    /**
     * Bulk insert order details (lebih efisien untuk banyak item)
     */
    public function bulkInsert($order_id, $items)
    {
        if (empty($items)) {
            return false;
        }

        // Build query
        $query = "INSERT INTO {$this->table} 
                  (order_id, product_id, quantity, price_per_item, subtotal) 
                  VALUES ";

        $values = [];
        $params = [];
        $types = "";

        foreach ($items as $item) {
            $values[] = "(?, ?, ?, ?, ?)";
            $params[] = $order_id;
            $params[] = $item['product_id'];
            $params[] = $item['quantity'];
            $params[] = $item['price'];
            $params[] = $item['quantity'] * $item['price'];
            $types .= "iiidd";
        }

        $query .= implode(", ", $values);

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param($types, ...$params);

        return $stmt->execute();
    }

    // ==================== UPDATE OPERATION ====================

    /**
     * Update order detail
     */
    public function update()
    {
        $query = "UPDATE {$this->table} 
                  SET quantity = ?,
                      price_per_item = ?,
                      subtotal = ?
                  WHERE order_detail_id = ?";

        $stmt = $this->conn->prepare($query);

        // Recalculate subtotal
        $this->subtotal = $this->quantity * $this->price_per_item;

        $stmt->bind_param(
            "iddi",
            $this->quantity,
            $this->price_per_item,
            $this->subtotal,
            $this->order_detail_id
        );

        return $stmt->execute();
    }

    // ==================== DELETE OPERATION ====================

    /**
     * Delete order detail
     */
    public function delete($id)
    {
        $query = "DELETE FROM {$this->table} WHERE order_detail_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    /**
     * Delete all details for an order
     */
    public function deleteByOrderId($order_id)
    {
        $query = "DELETE FROM {$this->table} WHERE order_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $order_id);

        return $stmt->execute();
    }

    // ==================== HELPER METHODS ====================

    /**
     * Get total items count in order
     */
    public function getTotalItems($order_id)
    {
        $query = "SELECT SUM(quantity) as total FROM {$this->table} WHERE order_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['total'] ?? 0;
    }

    /**
     * Get total amount for order (sum of subtotals)
     */
    public function getTotalAmount($order_id)
    {
        $query = "SELECT SUM(subtotal) as total FROM {$this->table} WHERE order_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['total'] ?? 0;
    }

    /**
     * Get best selling products
     */
    public function getBestSellers($limit = 10)
    {
        $query = "SELECT 
                    p.product_id,
                    p.product_name,
                    p.brand,
                    p.image_url,
                    SUM(od.quantity) as total_sold,
                    SUM(od.subtotal) as total_revenue
                  FROM {$this->table} od
                  INNER JOIN products p ON od.product_id = p.product_id
                  GROUP BY od.product_id
                  ORDER BY total_sold DESC
                  LIMIT ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $limit);
        $stmt->execute();

        return $stmt->get_result();
    }

    /**
     * Get product sales count
     */
    public function getProductSalesCount($product_id)
    {
        $query = "SELECT SUM(quantity) as total FROM {$this->table} WHERE product_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['total'] ?? 0;
    }
}
?>