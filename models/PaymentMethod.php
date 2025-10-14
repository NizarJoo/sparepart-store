<?php
/**
 * PaymentMethod Model
 * File: models/PaymentMethod.php
 * Represent tabel 'payment_methods' di database
 */

class PaymentMethod
{
    // Database connection
    private $conn;
    private $table = 'payment_methods';

    // Properties
    public $payment_method_id;
    public $method_name;
    public $description;
    public $status;

    /**
     * Constructor
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // ==================== READ OPERATIONS ====================

    /**
     * Get all payment methods
     */
    public function getAll()
    {
        $query = "SELECT * FROM {$this->table} ORDER BY method_name ASC";
        return $this->conn->query($query);
    }

    /**
     * Get active payment methods only (untuk customer)
     */
    public function getActive()
    {
        $query = "SELECT * FROM {$this->table} WHERE status = 'active' ORDER BY method_name ASC";
        return $this->conn->query($query);
    }

    /**
     * Get payment method by ID
     */
    public function getById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE payment_method_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // ==================== CREATE OPERATION ====================

    /**
     * Create new payment method
     */
    public function create()
    {
        $query = "INSERT INTO {$this->table} 
                  (method_name, description, status) 
                  VALUES (?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->method_name = htmlspecialchars(strip_tags($this->method_name));
        $this->description = htmlspecialchars(strip_tags($this->description));

        $stmt->bind_param("sss", $this->method_name, $this->description, $this->status);

        return $stmt->execute();
    }

    // ==================== UPDATE OPERATION ====================

    /**
     * Update payment method
     */
    public function update()
    {
        $query = "UPDATE {$this->table} 
                  SET method_name = ?,
                      description = ?,
                      status = ?
                  WHERE payment_method_id = ?";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->method_name = htmlspecialchars(strip_tags($this->method_name));
        $this->description = htmlspecialchars(strip_tags($this->description));

        $stmt->bind_param("sssi", $this->method_name, $this->description, $this->status, $this->payment_method_id);

        return $stmt->execute();
    }

    /**
     * Toggle status (active/inactive)
     */
    public function toggleStatus($id)
    {
        $query = "UPDATE {$this->table} 
                  SET status = IF(status = 'active', 'inactive', 'active') 
                  WHERE payment_method_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    // ==================== DELETE OPERATION ====================

    /**
     * Delete payment method
     */
    public function delete($id)
    {
        $query = "DELETE FROM {$this->table} WHERE payment_method_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    // ==================== VALIDATION METHODS ====================

    /**
     * Check if payment method name exists
     */
    public function isNameExists($name, $exclude_id = null)
    {
        if ($exclude_id) {
            $query = "SELECT payment_method_id FROM {$this->table} WHERE method_name = ? AND payment_method_id != ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("si", $name, $exclude_id);
        } else {
            $query = "SELECT payment_method_id FROM {$this->table} WHERE method_name = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("s", $name);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    /**
     * Get total active payment methods
     */
    public function getTotalActive()
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE status = 'active'";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();

        return $row['total'];
    }
}
?>