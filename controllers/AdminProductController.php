<?php
/**
 * Admin Product Controller
 * File: controllers/AdminProductController.php
 * Handle product CRUD operations (admin only)
 */

require_once 'models/Product.php';
require_once 'models/Category.php';
require_once 'controllers/AuthController.php';

class AdminProductController
{
    private $db;
    private $productModel;
    private $categoryModel;

    public function __construct($db)
    {
        // Require admin access
        AuthController::requireAdmin();

        $this->db = $db;
        $this->productModel = new Product($db);
        $this->categoryModel = new Category($db);
    }

    // ==================== READ ====================

    /**
     * Show all products (admin)
     */
    public function index()
    {
        $result = $this->productModel->getAllAdmin();

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        include 'views/admin/products/index.php';
    }

    // ==================== CREATE ====================

    /**
     * Show create form
     */
    public function create()
    {
        // Get categories for dropdown
        $categories_result = $this->categoryModel->getAll();
        $categories = [];
        while ($row = $categories_result->fetch_assoc()) {
            $categories[] = $row;
        }

        include 'views/admin/products/create.php';
    }

    /**
     * Store new product
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=admin-products');
            exit;
        }

        // Get form data
        $category_id = $_POST['category_id'] ?? 0;
        $product_name = trim($_POST['product_name'] ?? '');
        $brand = trim($_POST['brand'] ?? '');
        $sku = trim($_POST['sku'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = $_POST['price'] ?? 0;
        $stock = $_POST['stock'] ?? 0;
        $status = $_POST['status'] ?? 'active';

        // Validation
        $errors = [];

        if (empty($product_name)) {
            $errors[] = "Nama produk harus diisi!";
        }

        if (empty($sku)) {
            $errors[] = "SKU harus diisi!";
        } elseif ($this->productModel->isSkuExists($sku)) {
            $errors[] = "SKU sudah digunakan!";
        }

        if ($price <= 0) {
            $errors[] = "Harga harus lebih dari 0!";
        }

        if ($stock < 0) {
            $errors[] = "Stock tidak boleh negatif!";
        }

        // Handle image upload
        $image_url = '';
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === 0) {
            $upload_result = $this->uploadImage($_FILES['product_image']);
            if ($upload_result['success']) {
                $image_url = $upload_result['path'];
            } else {
                $errors[] = $upload_result['message'];
            }
        }

        // If has errors
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            header('Location: index.php?action=admin-products-create');
            exit;
        }

        // Create product
        $this->productModel->category_id = $category_id;
        $this->productModel->product_name = $product_name;
        $this->productModel->brand = $brand;
        $this->productModel->sku = $sku;
        $this->productModel->description = $description;
        $this->productModel->price = $price;
        $this->productModel->stock = $stock;
        $this->productModel->image_url = $image_url;
        $this->productModel->status = $status;

        if ($this->productModel->create()) {
            $_SESSION['success'] = "Produk berhasil ditambahkan!";
            header('Location: index.php?action=admin-products');
        } else {
            $_SESSION['error'] = "Gagal menambahkan produk!";
            header('Location: index.php?action=admin-products-create');
        }

        exit;
    }

    // ==================== UPDATE ====================

    /**
     * Show edit form
     */
    public function edit()
    {
        $id = $_GET['id'] ?? 0;

        if (!$id) {
            $_SESSION['error'] = "Produk tidak ditemukan!";
            header('Location: index.php?action=admin-products');
            exit;
        }

        $product = $this->productModel->getById($id);

        if (!$product) {
            $_SESSION['error'] = "Produk tidak ditemukan!";
            header('Location: index.php?action=admin-products');
            exit;
        }

        // Get categories for dropdown
        $categories_result = $this->categoryModel->getAll();
        $categories = [];
        while ($row = $categories_result->fetch_assoc()) {
            $categories[] = $row;
        }

        include 'views/admin/products/edit.php';
    }

    /**
     * Update product
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=admin-products');
            exit;
        }

        $product_id = $_POST['product_id'] ?? 0;

        if (!$product_id) {
            $_SESSION['error'] = "Produk tidak ditemukan!";
            header('Location: index.php?action=admin-products');
            exit;
        }

        // Get existing product
        $existing = $this->productModel->getById($product_id);

        // Get form data
        $category_id = $_POST['category_id'] ?? 0;
        $product_name = trim($_POST['product_name'] ?? '');
        $brand = trim($_POST['brand'] ?? '');
        $sku = trim($_POST['sku'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = $_POST['price'] ?? 0;
        $stock = $_POST['stock'] ?? 0;
        $status = $_POST['status'] ?? 'active';

        // Validation
        $errors = [];

        if (empty($product_name)) {
            $errors[] = "Nama produk harus diisi!";
        }

        if (empty($sku)) {
            $errors[] = "SKU harus diisi!";
        } elseif ($this->productModel->isSkuExists($sku, $product_id)) {
            $errors[] = "SKU sudah digunakan produk lain!";
        }

        if ($price <= 0) {
            $errors[] = "Harga harus lebih dari 0!";
        }

        if ($stock < 0) {
            $errors[] = "Stock tidak boleh negatif!";
        }

        // Handle image upload (optional on update)
        $image_url = $existing['image_url'];
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === 0) {
            $upload_result = $this->uploadImage($_FILES['product_image']);
            if ($upload_result['success']) {
                // Delete old image
                if (!empty($existing['image_url']) && file_exists($existing['image_url'])) {
                    unlink($existing['image_url']);
                }
                $image_url = $upload_result['path'];
            } else {
                $errors[] = $upload_result['message'];
            }
        }

        // If has errors
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: index.php?action=admin-products-edit&id=' . $product_id);
            exit;
        }

        // Update product
        $this->productModel->product_id = $product_id;
        $this->productModel->category_id = $category_id;
        $this->productModel->product_name = $product_name;
        $this->productModel->brand = $brand;
        $this->productModel->sku = $sku;
        $this->productModel->description = $description;
        $this->productModel->price = $price;
        $this->productModel->stock = $stock;
        $this->productModel->image_url = $image_url;
        $this->productModel->status = $status;

        if ($this->productModel->update()) {
            $_SESSION['success'] = "Produk berhasil diupdate!";
            header('Location: index.php?action=admin-products');
        } else {
            $_SESSION['error'] = "Gagal update produk!";
            header('Location: index.php?action=admin-products-edit&id=' . $product_id);
        }

        exit;
    }

    // ==================== DELETE ====================

    /**
     * Delete product
     */
    public function delete()
    {
        $id = $_GET['id'] ?? 0;

        if (!$id) {
            $_SESSION['error'] = "Produk tidak ditemukan!";
            header('Location: index.php?action=admin-products');
            exit;
        }

        // Get product data (untuk hapus gambar)
        $product = $this->productModel->getById($id);

        // Soft delete (set inactive)
        if ($this->productModel->softDelete($id)) {
            $_SESSION['success'] = "Produk berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus produk!";
        }

        header('Location: index.php?action=admin-products');
        exit;
    }

    // ==================== HELPER METHODS ====================

    /**
     * Upload product image
     */
    private function uploadImage($file)
    {
        // Validate file
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        $max_size = 2 * 1024 * 1024; // 2MB

        if (!in_array($file['type'], $allowed_types)) {
            return ['success' => false, 'message' => 'Format gambar tidak valid! (JPG, PNG, WEBP)'];
        }

        if ($file['size'] > $max_size) {
            return ['success' => false, 'message' => 'Ukuran gambar maksimal 2MB!'];
        }

        // Generate filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'product_' . time() . '_' . uniqid() . '.' . $extension;

        // Upload directory
        $upload_dir = 'assets/images/products/';

        // Create directory if not exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $upload_path = $upload_dir . $filename;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
            return ['success' => true, 'path' => $upload_path];
        }

        return ['success' => false, 'message' => 'Gagal upload gambar!'];
    }
}
?>