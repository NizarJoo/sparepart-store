<?php
/**
 * Product Model
 * File: models/Product.php
 * Represent tabel 'products' di database
 * 
 * IMPORTANT: File ini TIDAK perlu require database.php
 * Database connection di-pass lewat constructor dari luar
 */

class Product
{
    // Database connection
    private $conn;
    private $table = 'products';

    // Properties (sesuai kolom di tabel products)
    public $product_id;
    public $category_id;
    public $product_name;
    public $brand;
    public $sku;
    public $description;
    public $price;
    public $stock;
    public $image_url;
    public $status;
    public $created_at;
    public $updated_at;

    /**
     * Constructor - Setup database connection
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // ==================== READ OPERATIONS ====================

    /**
     * Get all products (active only)
     * @return mysqli_result
     */
    public function getAll()
    {
        $query = "SELECT p.*, c.category_name 
                  FROM {$this->table} p
                  LEFT JOIN categories c ON p.category_id = c.category_id
                  WHERE p.status = 'active'
                  ORDER BY p.created_at DESC";

        $result = $this->conn->query($query);
        return $result;
    }

    /**
     * Get all products including inactive (untuk admin)
     * @return mysqli_result
     */
    public function getAllAdmin()
    {
        $query = "SELECT p.*, c.category_name 
                  FROM {$this->table} p
                  LEFT JOIN categories c ON p.category_id = c.category_id
                  ORDER BY p.created_at DESC";

        $result = $this->conn->query($query);
        return $result;
    }

    /**
     * Get product by ID
     * @param int $id
     * @return array|null
     */
    public function getById($id)
    {
        $query = "SELECT p.*, c.category_name 
                  FROM {$this->table} p
                  LEFT JOIN categories c ON p.category_id = c.category_id
                  WHERE p.product_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Get products by category
     * @param int $category_id
     * @return mysqli_result
     */
    public function getByCategory($category_id)
    {
        $query = "SELECT p.*, c.category_name 
                  FROM {$this->table} p
                  LEFT JOIN categories c ON p.category_id = c.category_id
                  WHERE p.category_id = ? AND p.status = 'active'
                  ORDER BY p.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $category_id);
        $stmt->execute();

        return $stmt->get_result();
    }

    /**
     * Search products by keyword
     * @param string $keyword
     * @return mysqli_result
     */
    public function search($keyword)
    {
        $search = "%{$keyword}%";

        $query = "SELECT p.*, c.category_name 
                  FROM {$this->table} p
                  LEFT JOIN categories c ON p.category_id = c.category_id
                  WHERE (p.product_name LIKE ? OR p.brand LIKE ? OR p.description LIKE ?)
                  AND p.status = 'active'
                  ORDER BY p.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sss", $search, $search, $search);
        $stmt->execute();

        return $stmt->get_result();
    }

    /**
     * Get featured/latest products (untuk homepage)
     * @param int $limit
     * @return mysqli_result
     */
    public function getFeatured($limit = 8)
    {
        $query = "SELECT p.*, c.category_name 
                  FROM {$this->table} p
                  LEFT JOIN categories c ON p.category_id = c.category_id
                  WHERE p.status = 'active' AND p.stock > 0
                  ORDER BY p.created_at DESC
                  LIMIT ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $limit);
        $stmt->execute();

        return $stmt->get_result();
    }

    // ==================== CREATE OPERATION ====================

    /**
     * Create new product
     * @return bool
     */
    public function create()
    {
        $query = "INSERT INTO {$this->table} 
                  (category_id, product_name, brand, sku, description, price, stock, image_url, status) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->product_name = htmlspecialchars(strip_tags($this->product_name));
        $this->brand = htmlspecialchars(strip_tags($this->brand));
        $this->sku = htmlspecialchars(strip_tags($this->sku));
        $this->description = htmlspecialchars(strip_tags($this->description));

        // Bind parameters
        $stmt->bind_param(
            "isssdisss",
            $this->category_id,
            $this->product_name,
            $this->brand,
            $this->sku,
            $this->description,
            $this->price,
            $this->stock,
            $this->image_url,
            $this->status
        );

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // ==================== UPDATE OPERATION ====================

    /**
     * Update existing product
     * @return bool
     */
    public function update()
    {
        $query = "UPDATE {$this->table} 
                  SET category_id = ?,
                      product_name = ?,
                      brand = ?,
                      sku = ?,
                      description = ?,
                      price = ?,
                      stock = ?,
                      image_url = ?,
                      status = ?
                  WHERE product_id = ?";

        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->product_name = htmlspecialchars(strip_tags($this->product_name));
        $this->brand = htmlspecialchars(strip_tags($this->brand));
        $this->sku = htmlspecialchars(strip_tags($this->sku));
        $this->description = htmlspecialchars(strip_tags($this->description));

        // Bind parameters
        $stmt->bind_param(
            "isssdiissi",
            $this->category_id,
            $this->product_name,
            $this->brand,
            $this->sku,
            $this->description,
            $this->price,
            $this->stock,
            $this->image_url,
            $this->status,
            $this->product_id
        );

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Update stock only (untuk checkout)
     * @param int $product_id
     * @param int $quantity
     * @return bool
     */
    public function updateStock($product_id, $quantity)
    {
        $query = "UPDATE {$this->table} 
                  SET stock = stock - ? 
                  WHERE product_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $quantity, $product_id);

        return $stmt->execute();
    }

    // ==================== DELETE OPERATION ====================

    /**
     * Delete product (hard delete)
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $query = "DELETE FROM {$this->table} WHERE product_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Soft delete (set status = inactive)
     * Lebih aman karena data ga hilang permanen
     * @param int $id
     * @return bool
     */
    public function softDelete($id)
    {
        $query = "UPDATE {$this->table} SET status = 'inactive' WHERE product_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    // ==================== VALIDATION & HELPER METHODS ====================

    /**
     * Check if SKU already exists (untuk validasi create/update)
     * @param string $sku
     * @param int $exclude_id (untuk update, exclude product sendiri)
     * @return bool
     */
    public function isSkuExists($sku, $exclude_id = null)
    {
        if ($exclude_id) {
            $query = "SELECT product_id FROM {$this->table} WHERE sku = ? AND product_id != ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("si", $sku, $exclude_id);
        } else {
            $query = "SELECT product_id FROM {$this->table} WHERE sku = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("s", $sku);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    /**
     * Check if product has enough stock
     * @param int $product_id
     * @param int $quantity
     * @return bool
     */
    public function hasStock($product_id, $quantity)
    {
        $query = "SELECT stock FROM {$this->table} WHERE product_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return ($row && $row['stock'] >= $quantity);
    }

    /**
     * Get total products count
     * @return int
     */
    public function getTotalCount()
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE status = 'active'";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();

        return $row['total'];
    }

    /**
     * Get low stock products (untuk admin notification)
     * @param int $threshold
     * @return mysqli_result
     */
    public function getLowStock($threshold = 10)
    {
        $query = "SELECT * FROM {$this->table} 
                  WHERE stock <= ? AND status = 'active'
                  ORDER BY stock ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $threshold);
        $stmt->execute();

        return $stmt->get_result();
    }
}
?>