<?php
/**
 * Main Entry Point & Router
 * File: index.php
 * Handle all requests and route to appropriate controller
 */

// Start session
session_start();

// Load database config
require_once 'config/database.php';

// Initialize database connection
$database = new Database();
$db = $database->conn;

// Get action from URL
$action = $_GET['action'] ?? 'home';

// Simple routing
switch ($action) {

    // ==================== HOME ====================
    case 'home':
        require_once 'controllers/HomeController.php';
        $controller = new HomeController($db);
        $controller->index();
        break;

    // ==================== AUTHENTICATION ====================
    case 'login':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController($db);
        $controller->showLogin();
        break;

    case 'login-process':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController($db);
        $controller->login();
        break;

    case 'register':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController($db);
        $controller->showRegister();
        break;

    case 'register-process':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController($db);
        $controller->register();
        break;

    case 'logout':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController($db);
        $controller->logout();
        break;

    case 'profile':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController($db);
        $controller->profile();
        break;

    case 'profile-update':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController($db);
        $controller->updateProfile();
        break;

    case 'change-password':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController($db);
        $controller->changePassword();
        break;

    // ==================== PRODUCTS (Customer) ====================
    case 'products':
        require_once 'controllers/ProductController.php';
        $controller = new ProductController($db);
        $controller->index();
        break;

    case 'product-detail':
        require_once 'controllers/ProductController.php';
        $controller = new ProductController($db);
        $controller->detail();
        break;

    case 'products-by-category':
        require_once 'controllers/ProductController.php';
        $controller = new ProductController($db);
        $controller->byCategory();
        break;

    case 'products-search':
        require_once 'controllers/ProductController.php';
        $controller = new ProductController($db);
        $controller->search();
        break;

    // ==================== CART ====================
    case 'cart':
        require_once 'controllers/CartController.php';
        $controller = new CartController($db);
        $controller->index();
        break;

    case 'cart-add':
        require_once 'controllers/CartController.php';
        $controller = new CartController($db);
        $controller->add();
        break;

    case 'cart-update':
        require_once 'controllers/CartController.php';
        $controller = new CartController($db);
        $controller->updateQuantity();
        break;

    case 'cart-increase':
        require_once 'controllers/CartController.php';
        $controller = new CartController($db);
        $controller->increase();
        break;

    case 'cart-decrease':
        require_once 'controllers/CartController.php';
        $controller = new CartController($db);
        $controller->decrease();
        break;

    case 'cart-remove':
        require_once 'controllers/CartController.php';
        $controller = new CartController($db);
        $controller->remove();
        break;

    case 'cart-clear':
        require_once 'controllers/CartController.php';
        $controller = new CartController($db);
        $controller->clear();
        break;

    // ==================== ORDERS (Customer) ====================
    case 'checkout':
        require_once 'controllers/OrderController.php';
        $controller = new OrderController($db);
        $controller->checkout();
        break;

    case 'checkout-process':
        require_once 'controllers/OrderController.php';
        $controller = new OrderController($db);
        $controller->processCheckout();
        break;

    case 'order-history':
        require_once 'controllers/OrderController.php';
        $controller = new OrderController($db);
        $controller->history();
        break;

    case 'order-detail':
        require_once 'controllers/OrderController.php';
        $controller = new OrderController($db);
        $controller->detail();
        break;

    case 'order-cancel':
        require_once 'controllers/OrderController.php';
        $controller = new OrderController($db);
        $controller->cancel();
        break;

    // ==================== ADMIN DASHBOARD ====================
    case 'admin-dashboard':
        require_once 'controllers/AdminDashboardController.php';
        $controller = new AdminDashboardController($db);
        $controller->index();
        break;

    // ==================== ADMIN PRODUCTS ====================
    case 'admin-products':
        require_once 'controllers/AdminProductController.php';
        $controller = new AdminProductController($db);
        $controller->index();
        break;

    case 'admin-products-create':
        require_once 'controllers/AdminProductController.php';
        $controller = new AdminProductController($db);
        $controller->create();
        break;

    case 'admin-products-store':
        require_once 'controllers/AdminProductController.php';
        $controller = new AdminProductController($db);
        $controller->store();
        break;

    case 'admin-products-edit':
        require_once 'controllers/AdminProductController.php';
        $controller = new AdminProductController($db);
        $controller->edit();
        break;

    case 'admin-products-update':
        require_once 'controllers/AdminProductController.php';
        $controller = new AdminProductController($db);
        $controller->update();
        break;

    case 'admin-products-delete':
        require_once 'controllers/AdminProductController.php';
        $controller = new AdminProductController($db);
        $controller->delete();
        break;

    // ==================== ADMIN CATEGORIES ====================
    case 'admin-categories':
        require_once 'controllers/CategoryController.php';
        $controller = new CategoryController($db);
        $controller->index();
        break;

    case 'admin-categories-create':
        require_once 'controllers/CategoryController.php';
        $controller = new CategoryController($db);
        $controller->create();
        break;

    case 'admin-categories-store':
        require_once 'controllers/CategoryController.php';
        $controller = new CategoryController($db);
        $controller->store();
        break;

    case 'admin-categories-edit':
        require_once 'controllers/CategoryController.php';
        $controller = new CategoryController($db);
        $controller->edit();
        break;

    case 'admin-categories-update':
        require_once 'controllers/CategoryController.php';
        $controller = new CategoryController($db);
        $controller->update();
        break;

    case 'admin-categories-delete':
        require_once 'controllers/CategoryController.php';
        $controller = new CategoryController($db);
        $controller->delete();
        break;

    // ==================== ADMIN ORDERS ====================
    case 'admin-orders':
        require_once 'controllers/AdminOrderController.php';
        $controller = new AdminOrderController($db);
        $controller->index();
        break;

    case 'admin-order-detail':
        require_once 'controllers/AdminOrderController.php';
        $controller = new AdminOrderController($db);
        $controller->detail();
        break;

    case 'admin-order-update-status':
        require_once 'controllers/AdminOrderController.php';
        $controller = new AdminOrderController($db);
        $controller->updateStatus();
        break;

    case 'admin-order-delete':
        require_once 'controllers/AdminOrderController.php';
        $controller = new AdminOrderController($db);
        $controller->delete();
        break;

    // ==================== 404 ====================
    default:
        http_response_code(404);
        echo "<h1>404 - Page Not Found</h1>";
        echo "<p>Action '{$action}' tidak ditemukan!</p>";
        echo "<a href='index.php'>Kembali ke Home</a>";
        break;
}
?>