<?php
/**
 * User Model
 * File: models/User.php
 * Represent tabel 'users' di database
 */

class User
{
    // Database connection
    private $conn;
    private $table = 'users';

    // Properties
    public $user_id;
    public $username;
    public $email;
    public $password;
    public $full_name;
    public $phone;
    public $address;
    public $role;
    public $created_at;
    public $updated_at;

    /**
     * Constructor
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // ==================== AUTHENTICATION ====================

    /**
     * Register new user
     */
    public function register()
    {
        $query = "INSERT INTO {$this->table} 
                  (username, email, password, full_name, phone, address, role) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->full_name = htmlspecialchars(strip_tags($this->full_name));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->address = htmlspecialchars(strip_tags($this->address));

        // Hash password
        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);

        $stmt->bind_param(
            "sssssss",
            $this->username,
            $this->email,
            $hashed_password,
            $this->full_name,
            $this->phone,
            $this->address,
            $this->role
        );

        return $stmt->execute();
    }

    /**
     * Login user
     */
    public function login($email, $password)
    {
        $query = "SELECT * FROM {$this->table} WHERE email = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Verify password
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    /**
     * Check if user is logged in
     */
    public static function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Check if user is admin
     */
    public static function isAdmin()
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    // ==================== READ OPERATIONS ====================

    /**
     * Get all users (admin only)
     */
    public function getAll()
    {
        $query = "SELECT user_id, username, email, full_name, phone, role, created_at 
                  FROM {$this->table} 
                  ORDER BY created_at DESC";

        return $this->conn->query($query);
    }

    /**
     * Get user by ID
     */
    public function getById($id)
    {
        $query = "SELECT user_id, username, email, full_name, phone, address, role, created_at 
                  FROM {$this->table} 
                  WHERE user_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Get user by email
     */
    public function getByEmail($email)
    {
        $query = "SELECT * FROM {$this->table} WHERE email = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Get all customers (role = customer)
     */
    public function getAllCustomers()
    {
        $query = "SELECT user_id, username, email, full_name, phone, created_at 
                  FROM {$this->table} 
                  WHERE role = 'customer'
                  ORDER BY created_at DESC";

        return $this->conn->query($query);
    }

    // ==================== UPDATE OPERATION ====================

    /**
     * Update user profile
     */
    public function update()
    {
        $query = "UPDATE {$this->table} 
                  SET full_name = ?,
                      phone = ?,
                      address = ?
                  WHERE user_id = ?";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->full_name = htmlspecialchars(strip_tags($this->full_name));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->address = htmlspecialchars(strip_tags($this->address));

        $stmt->bind_param("sssi", $this->full_name, $this->phone, $this->address, $this->user_id);

        return $stmt->execute();
    }

    /**
     * Update password
     */
    public function updatePassword($user_id, $new_password)
    {
        $query = "UPDATE {$this->table} SET password = ? WHERE user_id = ?";

        $stmt = $this->conn->prepare($query);

        // Hash new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $stmt->bind_param("si", $hashed_password, $user_id);

        return $stmt->execute();
    }

    /**
     * Update role (admin only)
     */
    public function updateRole($user_id, $role)
    {
        $query = "UPDATE {$this->table} SET role = ? WHERE user_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $role, $user_id);

        return $stmt->execute();
    }

    // ==================== DELETE OPERATION ====================

    /**
     * Delete user
     */
    public function delete($id)
    {
        $query = "DELETE FROM {$this->table} WHERE user_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    // ==================== VALIDATION METHODS ====================

    /**
     * Check if email exists
     */
    public function isEmailExists($email, $exclude_id = null)
    {
        if ($exclude_id) {
            $query = "SELECT user_id FROM {$this->table} WHERE email = ? AND user_id != ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("si", $email, $exclude_id);
        } else {
            $query = "SELECT user_id FROM {$this->table} WHERE email = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("s", $email);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    /**
     * Check if username exists
     */
    public function isUsernameExists($username, $exclude_id = null)
    {
        if ($exclude_id) {
            $query = "SELECT user_id FROM {$this->table} WHERE username = ? AND user_id != ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("si", $username, $exclude_id);
        } else {
            $query = "SELECT user_id FROM {$this->table} WHERE username = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("s", $username);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    /**
     * Validate email format
     */
    public function isValidEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Get total customers count
     */
    public function getTotalCustomers()
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE role = 'customer'";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();

        return $row['total'];
    }
}
?>