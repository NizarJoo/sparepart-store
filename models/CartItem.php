<?php
/**
 * CartItem Model
 * File: models/CartItem.php
 * Represent tabel 'cart_items' di database
 */

class CartItem
{
    // Database connection
    private $conn;
    private $table = 'cart_items';

    // Properties
    public $cart_id;
    public $user_id;
    public $product_id;
    public $quantity;
    public $added_at;

    /**
     * Constructor
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // ==================== READ OPERATIONS ====================

    /**
     * Get cart items by user ID (with product details)
     */
    public function getByUserId($user_id)
    {
        $query = "SELECT 
                    c.*,
                    p.product_name,
                    p.price,
                    p.stock,
                    p.image_url,
                    p.status,
                    (c.quantity * p.price) as subtotal
                  FROM {$this->table} c
                  INNER JOIN products p ON c.product_id = p.product_id
                  WHERE c.user_id = ?
                  ORDER BY c.added_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        return $stmt->get_result();
    }

    /**
     * Get specific cart item
     */
    public function getItem($user_id, $product_id)
    {
        $query = "SELECT * FROM {$this->table} 
                  WHERE user_id = ? AND product_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Get cart total amount
     */
    public function getCartTotal($user_id)
    {
        $query = "SELECT SUM(c.quantity * p.price) as total
                  FROM {$this->table} c
                  INNER JOIN products p ON c.product_id = p.product_id
                  WHERE c.user_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['total'] ?? 0;
    }

    /**
     * Get cart item count
     */
    public function getCartCount($user_id)
    {
        $query = "SELECT SUM(quantity) as count FROM {$this->table} WHERE user_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['count'] ?? 0;
    }

    // ==================== CREATE OPERATION ====================

    /**
     * Add item to cart
     */
    public function add()
    {
        // Check if item already exists in cart
        $existing = $this->getItem($this->user_id, $this->product_id);

        if ($existing) {
            // Update quantity
            return $this->updateQuantity(
                $existing['cart_id'],
                $existing['quantity'] + $this->quantity
            );
        } else {
            // Insert new item
            $query = "INSERT INTO {$this->table} 
                      (user_id, product_id, quantity) 
                      VALUES (?, ?, ?)";

            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("iii", $this->user_id, $this->product_id, $this->quantity);

            return $stmt->execute();
        }
    }

    // ==================== UPDATE OPERATION ====================

    /**
     * Update quantity
     */
    public function updateQuantity($cart_id, $quantity)
    {
        $query = "UPDATE {$this->table} SET quantity = ? WHERE cart_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $quantity, $cart_id);

        return $stmt->execute();
    }

    /**
     * Increase quantity
     */
    public function increaseQuantity($cart_id)
    {
        $query = "UPDATE {$this->table} SET quantity = quantity + 1 WHERE cart_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $cart_id);

        return $stmt->execute();
    }

    /**
     * Decrease quantity
     */
    public function decreaseQuantity($cart_id)
    {
        $query = "UPDATE {$this->table} 
                  SET quantity = quantity - 1 
                  WHERE cart_id = ? AND quantity > 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $cart_id);

        return $stmt->execute();
    }

    // ==================== DELETE OPERATIONS ====================

    /**
     * Remove item from cart
     */
    public function remove($cart_id)
    {
        $query = "DELETE FROM {$this->table} WHERE cart_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $cart_id);

        return $stmt->execute();
    }

    /**
     * Clear all cart items for user
     */
    public function clearCart($user_id)
    {
        $query = "DELETE FROM {$this->table} WHERE user_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);

        return $stmt->execute();
    }

    // ==================== VALIDATION METHODS ====================

    /**
     * Check if product is in cart
     */
    public function isInCart($user_id, $product_id)
    {
        $item = $this->getItem($user_id, $product_id);
        return $item !== null;
    }

    /**
     * Validate cart items stock
     * Returns array of products with insufficient stock
     */
    public function validateStock($user_id)
    {
        $query = "SELECT 
                    c.cart_id,
                    c.quantity as cart_quantity,
                    p.product_id,
                    p.product_name,
                    p.stock
                  FROM {$this->table} c
                  INNER JOIN products p ON c.product_id = p.product_id
                  WHERE c.user_id = ? AND p.stock < c.quantity";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        return $stmt->get_result();
    }
}
?>