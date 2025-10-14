<?php
/**
 * Admin Dashboard Controller
 * File: controllers/AdminDashboardController.php
 * Handle admin dashboard and statistics
 */

require_once 'models/Product.php';
require_once 'models/Order.php';
require_once 'models/User.php';
require_once 'models/OrderDetail.php';
require_once 'controllers/AuthController.php';

class AdminDashboardController
{
    private $db;
    private $productModel;
    private $orderModel;
    private $userModel;
    private $orderDetailModel;

    public function __construct($db)
    {
        // Require admin access
        AuthController::requireAdmin();

        $this->db = $db;
        $this->productModel = new Product($db);
        $this->orderModel = new Order($db);
        $this->userModel = new User($db);
        $this->orderDetailModel = new OrderDetail($db);
    }

    /**
     * Show admin dashboard
     */
    public function index()
    {
        // Get statistics
        $stats = [
            'total_products' => $this->productModel->getTotalCount(),
            'total_orders' => $this->orderModel->getTotalOrders(),
            'total_customers' => $this->userModel->getTotalCustomers(),
            'total_revenue' => $this->orderModel->getTotalRevenue(),
            'pending_orders' => $this->orderModel->getCountByStatus('pending'),
            'processing_orders' => $this->orderModel->getCountByStatus('processing'),
            'shipped_orders' => $this->orderModel->getCountByStatus('shipped'),
            'delivered_orders' => $this->orderModel->getCountByStatus('delivered'),
        ];

        // Get recent orders
        $recent_orders_result = $this->orderModel->getRecentOrders(5);
        $recent_orders = [];
        while ($row = $recent_orders_result->fetch_assoc()) {
            $recent_orders[] = $row;
        }

        // Get low stock products
        $low_stock_result = $this->productModel->getLowStock(10);
        $low_stock_products = [];
        while ($row = $low_stock_result->fetch_assoc()) {
            $low_stock_products[] = $row;
        }

        // Get best sellers
        $best_sellers_result = $this->orderDetailModel->getBestSellers(5);
        $best_sellers = [];
        while ($row = $best_sellers_result->fetch_assoc()) {
            $best_sellers[] = $row;
        }

        include 'views/admin/dashboard.php';
    }
}
?>