<?php
/**
 * Order Model
 * File: models/Order.php
 * Represent tabel 'orders' di database
 */

class Order
{
    // Database connection
    private $conn;
    private $table = 'orders';

    // Properties
    public $order_id;
    public $user_id;
    public $payment_method_id;
    public $order_number;
    public $total_amount;
    public $status;
    public $shipping_address;
    public $notes;
    public $order_date;
    public $updated_at;

    /**
     * Constructor
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // ==================== READ OPERATIONS ====================

    /**
     * Get all orders (admin)
     */
    public function getAll()
    {
        $query = "SELECT 
                    o.*,
                    u.full_name as customer_name,
                    u.email as customer_email,
                    pm.method_name as payment_method
                  FROM {$this->table} o
                  INNER JOIN users u ON o.user_id = u.user_id
                  INNER JOIN payment_methods pm ON o.payment_method_id = pm.payment_method_id
                  ORDER BY o.order_date DESC";

        return $this->conn->query($query);
    }

    /**
     * Get orders by user ID (customer order history)
     */
    public function getByUserId($user_id)
    {
        $query = "SELECT 
                    o.*,
                    pm.method_name as payment_method
                  FROM {$this->table} o
                  INNER JOIN payment_methods pm ON o.payment_method_id = pm.payment_method_id
                  WHERE o.user_id = ?
                  ORDER BY o.order_date DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        return $stmt->get_result();
    }

    /**
     * Get order by ID (with details)
     */
    public function getById($id)
    {
        $query = "SELECT 
                    o.*,
                    u.full_name as customer_name,
                    u.email as customer_email,
                    u.phone as customer_phone,
                    pm.method_name as payment_method,
                    pm.description as payment_description
                  FROM {$this->table} o
                  INNER JOIN users u ON o.user_id = u.user_id
                  INNER JOIN payment_methods pm ON o.payment_method_id = pm.payment_method_id
                  WHERE o.order_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Get order by order number
     */
    public function getByOrderNumber($order_number)
    {
        $query = "SELECT 
                    o.*,
                    u.full_name as customer_name,
                    pm.method_name as payment_method
                  FROM {$this->table} o
                  INNER JOIN users u ON o.user_id = u.user_id
                  INNER JOIN payment_methods pm ON o.payment_method_id = pm.payment_method_id
                  WHERE o.order_number = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $order_number);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Get orders by status
     */
    public function getByStatus($status)
    {
        $query = "SELECT 
                    o.*,
                    u.full_name as customer_name,
                    pm.method_name as payment_method
                  FROM {$this->table} o
                  INNER JOIN users u ON o.user_id = u.user_id
                  INNER JOIN payment_methods pm ON o.payment_method_id = pm.payment_method_id
                  WHERE o.status = ?
                  ORDER BY o.order_date DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $status);
        $stmt->execute();

        return $stmt->get_result();
    }

    // ==================== CREATE OPERATION ====================

    /**
     * Create new order
     */
    public function create()
    {
        $query = "INSERT INTO {$this->table} 
                  (user_id, payment_method_id, order_number, total_amount, status, shipping_address, notes) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->order_number = htmlspecialchars(strip_tags($this->order_number));
        $this->shipping_address = htmlspecialchars(strip_tags($this->shipping_address));
        $this->notes = htmlspecialchars(strip_tags($this->notes));

        $stmt->bind_param(
            "iisdsss",
            $this->user_id,
            $this->payment_method_id,
            $this->order_number,
            $this->total_amount,
            $this->status,
            $this->shipping_address,
            $this->notes
        );

        if ($stmt->execute()) {
            // Return last inserted order_id
            return $this->conn->insert_id;
        }

        return false;
    }

    // ==================== UPDATE OPERATION ====================

    /**
     * Update order status
     */
    public function updateStatus($order_id, $status)
    {
        $query = "UPDATE {$this->table} SET status = ? WHERE order_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $status, $order_id);

        return $stmt->execute();
    }

    /**
     * Update order
     */
    public function update()
    {
        $query = "UPDATE {$this->table} 
                  SET payment_method_id = ?,
                      total_amount = ?,
                      status = ?,
                      shipping_address = ?,
                      notes = ?
                  WHERE order_id = ?";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->shipping_address = htmlspecialchars(strip_tags($this->shipping_address));
        $this->notes = htmlspecialchars(strip_tags($this->notes));

        $stmt->bind_param(
            "idsssi",
            $this->payment_method_id,
            $this->total_amount,
            $this->status,
            $this->shipping_address,
            $this->notes,
            $this->order_id
        );

        return $stmt->execute();
    }

    // ==================== DELETE OPERATION ====================

    /**
     * Cancel order (set status to cancelled)
     */
    public function cancel($order_id)
    {
        return $this->updateStatus($order_id, 'cancelled');
    }

    /**
     * Delete order (hard delete)
     */
    public function delete($id)
    {
        $query = "DELETE FROM {$this->table} WHERE order_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    // ==================== HELPER METHODS ====================

    /**
     * Generate unique order number
     */
    public function generateOrderNumber():string
    {
        $date = date('Ymd');
        $random = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $order_number = "ORD-{$date}-{$random}";

        // Check if exists, regenerate if duplicate
        if ($this->isOrderNumberExists($order_number)) {
            return $this->generateOrderNumber();
        }

        return $order_number;
    }

    /**
     * Check if order number exists
     */
    private function isOrderNumberExists($order_number)
    {
        $query = "SELECT order_id FROM {$this->table} WHERE order_number = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $order_number);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    /**
     * Get total orders count
     */
    public function getTotalOrders()
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();

        return $row['total'];
    }

    /**
     * Get total revenue
     */
    public function getTotalRevenue()
    {
        $query = "SELECT SUM(total_amount) as revenue 
                  FROM {$this->table} 
                  WHERE status IN ('delivered', 'processing', 'shipped')";

        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();

        return $row['revenue'] ?? 0;
    }

    /**
     * Get orders count by status
     */
    public function getCountByStatus($status)
    {
        $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE status = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $status);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['count'];
    }

    /**
     * Get recent orders (for admin dashboard)
     */
    public function getRecentOrders($limit = 10)
    {
        $query = "SELECT 
                    o.*,
                    u.full_name as customer_name
                  FROM {$this->table} o
                  INNER JOIN users u ON o.user_id = u.user_id
                  ORDER BY o.order_date DESC
                  LIMIT ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $limit);
        $stmt->execute();

        return $stmt->get_result();
    }
}
?>