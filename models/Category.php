<?php
/**
 * Category Model
 * File: models/Category.php
 * Represent tabel 'categories' di database
 */

class Category
{
    // Database connection
    private $conn;
    private $table = 'categories';

    // Properties
    public $category_id;
    public $category_name;
    public $description;
    public $slug;
    public $created_at;

    /**
     * Constructor
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // ==================== READ OPERATIONS ====================

    /**
     * Get all categories
     */
    public function getAll()
    {
        $query = "SELECT * FROM {$this->table} ORDER BY category_name ASC";
        return $this->conn->query($query);
    }

    /**
     * Get category by ID
     */
    public function getById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE category_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Get category by slug
     */
    public function getBySlug($slug)
    {
        $query = "SELECT * FROM {$this->table} WHERE slug = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $slug);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Get categories with product count
     */
    public function getWithProductCount()
    {
        $query = "SELECT c.*, COUNT(p.product_id) as product_count 
                  FROM {$this->table} c
                  LEFT JOIN products p ON c.category_id = p.category_id 
                  AND p.status = 'active'
                  GROUP BY c.category_id
                  ORDER BY c.category_name ASC";

        return $this->conn->query($query);
    }

    // ==================== CREATE OPERATION ====================

    /**
     * Create new category
     */
    public function create()
    {
        $query = "INSERT INTO {$this->table} 
                  (category_name, description, slug) 
                  VALUES (?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->category_name = htmlspecialchars(strip_tags($this->category_name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->slug = $this->generateSlug($this->category_name);

        $stmt->bind_param("sss", $this->category_name, $this->description, $this->slug);

        return $stmt->execute();
    }

    // ==================== UPDATE OPERATION ====================

    /**
     * Update category
     */
    public function update()
    {
        $query = "UPDATE {$this->table} 
                  SET category_name = ?,
                      description = ?,
                      slug = ?
                  WHERE category_id = ?";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->category_name = htmlspecialchars(strip_tags($this->category_name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->slug = $this->generateSlug($this->category_name);

        $stmt->bind_param("sssi", $this->category_name, $this->description, $this->slug, $this->category_id);

        return $stmt->execute();
    }

    // ==================== DELETE OPERATION ====================

    /**
     * Delete category
     * Note: Akan error jika masih ada produk yang pakai kategori ini (FOREIGN KEY RESTRICT)
     */
    public function delete($id)
    {
        $query = "DELETE FROM {$this->table} WHERE category_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    // ==================== HELPER METHODS ====================

    /**
     * Generate slug from category name
     */
    private function generateSlug($text)
    {
        // Convert to lowercase
        $slug = strtolower($text);

        // Replace spaces with hyphens
        $slug = str_replace(' ', '-', $slug);

        // Remove special characters
        $slug = preg_replace('/[^a-z0-9\-]/', '', $slug);

        // Remove multiple hyphens
        $slug = preg_replace('/-+/', '-', $slug);

        // Trim hyphens from ends
        $slug = trim($slug, '-');

        return $slug;
    }

    /**
     * Check if category name exists
     */
    public function isNameExists($name, $exclude_id = null)
    {
        if ($exclude_id) {
            $query = "SELECT category_id FROM {$this->table} WHERE category_name = ? AND category_id != ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("si", $name, $exclude_id);
        } else {
            $query = "SELECT category_id FROM {$this->table} WHERE category_name = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("s", $name);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    /**
     * Check if category has products
     */
    public function hasProducts($id)
    {
        $query = "SELECT COUNT(*) as count FROM products WHERE category_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['count'] > 0;
    }
}
?>