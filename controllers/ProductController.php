<?php
/**
 * Product Controller
 * File: controllers/ProductController.php
 * Handle product operations (customer view)
 */

require_once 'models/Product.php';
require_once 'models/Category.php';

class ProductController
{
    private $db;
    private $productModel;
    private $categoryModel;

    public function __construct($db)
    {
        $this->db = $db;
        $this->productModel = new Product($db);
        $this->categoryModel = new Category($db);
    }

    // ==================== CUSTOMER VIEWS ====================

    /**
     * Show all products (customer)
     */
    public function index()
    {
        $result = $this->productModel->getAll();

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        // Get categories for filter
        $categories_result = $this->categoryModel->getAll();
        $categories = [];
        while ($row = $categories_result->fetch_assoc()) {
            $categories[] = $row;
        }

        include 'views/products/index.php';
    }

    /**
     * Show product detail
     */
    public function detail()
    {
        $id = $_GET['id'] ?? 0;

        if (!$id) {
            $_SESSION['error'] = "Produk tidak ditemukan!";
            header('Location: index.php?action=products');
            exit;
        }

        $product = $this->productModel->getById($id);

        if (!$product) {
            $_SESSION['error'] = "Produk tidak ditemukan!";
            header('Location: index.php?action=products');
            exit;
        }

        // Get related products (same category)
        $related_result = $this->productModel->getByCategory($product['category_id']);
        $related_products = [];
        while ($row = $related_result->fetch_assoc()) {
            // Exclude current product
            if ($row['product_id'] != $id) {
                $related_products[] = $row;
            }
        }

        include 'views/products/detail.php';
    }

    /**
     * Filter products by category
     */
    public function byCategory()
    {
        $category_id = $_GET['category_id'] ?? 0;

        if (!$category_id) {
            header('Location: index.php?action=products');
            exit;
        }

        $category = $this->categoryModel->getById($category_id);

        if (!$category) {
            $_SESSION['error'] = "Kategori tidak ditemukan!";
            header('Location: index.php?action=products');
            exit;
        }

        $result = $this->productModel->getByCategory($category_id);

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        // Get all categories for filter
        $categories_result = $this->categoryModel->getAll();
        $categories = [];
        while ($row = $categories_result->fetch_assoc()) {
            $categories[] = $row;
        }

        include 'views/products/by_category.php';
    }

    /**
     * Search products
     */
    public function search()
    {
        $keyword = $_GET['keyword'] ?? '';

        if (empty($keyword)) {
            header('Location: index.php?action=products');
            exit;
        }

        $result = $this->productModel->search($keyword);

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        include 'views/products/search.php';
    }
}
?>