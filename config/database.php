<?php
/**
 * Database Configuration - OOP Version (MySQLi)
 * File: config/database.php
 */

class Database
{
    // Database credentials
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $database = 'toko_sparepart';

    // Connection property
    public $conn;

    /**
     * Constructor - Auto connect saat object dibuat
     */
    public function __construct()
    {
        $this->connect();
    }

    /**
     * Method untuk connect ke database
     */
    public function connect()
    {
        $this->conn = null;

        try {
            // Create MySQLi connection
            $this->conn = new mysqli(
                $this->host,
                $this->username,
                $this->password,
                $this->database
            );

            // Check connection error
            if ($this->conn->connect_error) {
                throw new Exception("Koneksi gagal: " . $this->conn->connect_error);
            }

            // Set charset
            $this->conn->set_charset("utf8mb4");

        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }

        return $this->conn;
    }

    /**
     * Method untuk close connection
     */
    public function close()
    {
        if ($this->conn) {
            $this->conn->close();
        }
    }

    /**
     * Method helper untuk escape string (prevent SQL injection)
     */
    public function escape($string)
    {
        return $this->conn->real_escape_string($string);
    }

    /**
     * Method helper untuk execute query
     */
    public function query($sql)
    {
        return $this->conn->query($sql);
    }
}

/**
 * Cara pakai:
 * 
 * $db = new Database();
 * $conn = $db->conn;
 * 
 * // Query
 * $result = $conn->query("SELECT * FROM products");
 * while($row = $result->fetch_assoc()) {
 *     echo $row['product_name'];
 * }
 * 
 * // Close (opsional, otomatis close saat script selesai)
 * $db->close();
 */
?>