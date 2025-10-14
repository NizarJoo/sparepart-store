<?php
/**
 * Home Controller
 * File: controllers/HomeController.php
 * Handle homepage
 */

require_once 'models/Product.php';
require_once 'models/Category.php';

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
        while ($row = $featured_result->fetch_assoc()) {
            $featured_products[] = $row;
        }

        // Get categories
        $categories_result = $this->categoryModel->getAll();
        $categories = [];
        while ($row = $categories_result->fetch_assoc()) {
            $categories[] = $row;
        }

        include 'views/home.php';
    }
}
?>