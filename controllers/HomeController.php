<?php
/**
 * Home Controller
 * File: controllers/HomeController.php
 * Handle homepage
 */

require_once 'models/Product.php';
require_once 'models/Category.php';
require_once 'controllers/CartController.php'; // ✨ tambahin ini untuk hitung cart

class HomeController
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

    /**
     * Show homepage
     */
    public function index()
    {
        // Get featured products
        $featured_result = $this->productModel->getFeatured(8);
        $featured_products = [];
        if ($featured_result) {
            while ($row = $featured_result->fetch_assoc()) {
                $featured_products[] = $row;
            }
        }

        // Get categories
        $categories_result = $this->categoryModel->getAll();
        $categories = [];
        if ($categories_result) {
            while ($row = $categories_result->fetch_assoc()) {
                $categories[] = $row;
            }
        }

        // ✨ Hitung cart count jika user login
        $cart_count = 0;
        if (isset($_SESSION['user_id'])) {
            $cart_count = CartController::getCartCount($this->db);
        }

        // ✨ Set page title
        $page_title = "Toko Sparepart Komputer";

        // ✨ Kirim variabel ke view
        include 'views/home.php';
    }
}
?>