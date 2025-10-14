<?php
/**
 * Category Controller (Admin Only)
 * File: controllers/CategoryController.php
 * Handle category CRUD operations
 */

require_once 'models/Category.php';
require_once 'controllers/AuthController.php';

class CategoryController
{
    private $db;
    private $categoryModel;

    public function __construct($db)
    {
        // Require admin access
        AuthController::requireAdmin();

        $this->db = $db;
        $this->categoryModel = new Category($db);
    }

    // ==================== READ ====================

    /**
     * Show all categories
     */
    public function index()
    {
        $result = $this->categoryModel->getWithProductCount();

        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }

        include 'views/admin/categories/index.php';
    }

    // ==================== CREATE ====================

    /**
     * Show create form
     */
    public function create()
    {
        include 'views/admin/categories/create.php';
    }

    /**
     * Store new category
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=admin-categories');
            exit;
        }

        $category_name = trim($_POST['category_name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        // Validation
        if (empty($category_name)) {
            $_SESSION['error'] = "Nama kategori harus diisi!";
            header('Location: index.php?action=admin-categories-create');
            exit;
        }

        if ($this->categoryModel->isNameExists($category_name)) {
            $_SESSION['error'] = "Nama kategori sudah ada!";
            header('Location: index.php?action=admin-categories-create');
            exit;
        }

        // Create category
        $this->categoryModel->category_name = $category_name;
        $this->categoryModel->description = $description;

        if ($this->categoryModel->create()) {
            $_SESSION['success'] = "Kategori berhasil ditambahkan!";
            header('Location: index.php?action=admin-categories');
        } else {
            $_SESSION['error'] = "Gagal menambahkan kategori!";
            header('Location: index.php?action=admin-categories-create');
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
            $_SESSION['error'] = "Kategori tidak ditemukan!";
            header('Location: index.php?action=admin-categories');
            exit;
        }

        $category = $this->categoryModel->getById($id);

        if (!$category) {
            $_SESSION['error'] = "Kategori tidak ditemukan!";
            header('Location: index.php?action=admin-categories');
            exit;
        }

        include 'views/admin/categories/edit.php';
    }

    /**
     * Update category
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=admin-categories');
            exit;
        }

        $category_id = $_POST['category_id'] ?? 0;
        $category_name = trim($_POST['category_name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if (!$category_id) {
            $_SESSION['error'] = "Kategori tidak ditemukan!";
            header('Location: index.php?action=admin-categories');
            exit;
        }

        // Validation
        if (empty($category_name)) {
            $_SESSION['error'] = "Nama kategori harus diisi!";
            header('Location: index.php?action=admin-categories-edit&id=' . $category_id);
            exit;
        }

        if ($this->categoryModel->isNameExists($category_name, $category_id)) {
            $_SESSION['error'] = "Nama kategori sudah digunakan kategori lain!";
            header('Location: index.php?action=admin-categories-edit&id=' . $category_id);
            exit;
        }

        // Update category
        $this->categoryModel->category_id = $category_id;
        $this->categoryModel->category_name = $category_name;
        $this->categoryModel->description = $description;

        if ($this->categoryModel->update()) {
            $_SESSION['success'] = "Kategori berhasil diupdate!";
            header('Location: index.php?action=admin-categories');
        } else {
            $_SESSION['error'] = "Gagal update kategori!";
            header('Location: index.php?action=admin-categories-edit&id=' . $category_id);
        }

        exit;
    }

    // ==================== DELETE ====================

    /**
     * Delete category
     */
    public function delete()
    {
        $id = $_GET['id'] ?? 0;

        if (!$id) {
            $_SESSION['error'] = "Kategori tidak ditemukan!";
            header('Location: index.php?action=admin-categories');
            exit;
        }

        // Check if category has products
        if ($this->categoryModel->hasProducts($id)) {
            $_SESSION['error'] = "Kategori tidak bisa dihapus karena masih memiliki produk!";
            header('Location: index.php?action=admin-categories');
            exit;
        }

        if ($this->categoryModel->delete($id)) {
            $_SESSION['success'] = "Kategori berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus kategori!";
        }

        header('Location: index.php?action=admin-categories');
        exit;
    }
}
?>