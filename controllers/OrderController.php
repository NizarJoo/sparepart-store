<?php
/**
 * Order Controller
 * File: controllers/OrderController.php
 * Handle checkout and order operations
 */

require_once 'models/Order.php';
require_once 'models/OrderDetail.php';
require_once 'models/CartItem.php';
require_once 'models/PaymentMethod.php';
require_once 'models/Product.php';

class OrderController
{
    private $db;
    private $orderModel;
    private $orderDetailModel;
    private $cartModel;
    private $paymentModel;
    private $productModel;

    public function __construct($db)
    {
        $this->db = $db;
        $this->orderModel = new Order($db);
        $this->orderDetailModel = new OrderDetail($db);
        $this->cartModel = new CartItem($db);
        $this->paymentModel = new PaymentMethod($db);
        $this->productModel = new Product($db);
    }

    // ==================== CHECKOUT ====================

    /**
     * Show checkout page
     */
    public function checkout()
    {
        $this->requireLogin();

        $user_id = $_SESSION['user_id'];

        // Get cart items
        $result = $this->cartModel->getByUserId($user_id);
        $cart_items = [];
        while ($row = $result->fetch_assoc()) {
            $cart_items[] = $row;
        }

        // Check if cart is empty
        if (empty($cart_items)) {
            $_SESSION['error'] = "Keranjang belanja kosong!";
            header('Location: index.php?action=cart');
            exit;
        }

        // Validate stock for all items
        $stock_errors = [];
        foreach ($cart_items as $item) {
            if (!$this->productModel->hasStock($item['product_id'], $item['quantity'])) {
                $stock_errors[] = $item['product_name'] . " (stock tersedia: " . $item['stock'] . ")";
            }
        }

        if (!empty($stock_errors)) {
            $_SESSION['error'] = "Stock tidak cukup untuk: " . implode(", ", $stock_errors);
            header('Location: index.php?action=cart');
            exit;
        }

        // Get cart total
        $cart_total = $this->cartModel->getCartTotal($user_id);

        // Get payment methods
        $payment_result = $this->paymentModel->getActive();
        $payment_methods = [];
        while ($row = $payment_result->fetch_assoc()) {
            $payment_methods[] = $row;
        }

        include 'views/orders/checkout.php';
    }

    /**
     * Process checkout
     */
    public function processCheckout()
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=checkout');
            exit;
        }

        $user_id = $_SESSION['user_id'];
        $payment_method_id = $_POST['payment_method_id'] ?? 0;
        $shipping_address = trim($_POST['shipping_address'] ?? '');
        $notes = trim($_POST['notes'] ?? '');

        // Validation
        if (!$payment_method_id) {
            $_SESSION['error'] = "Pilih metode pembayaran!";
            header('Location: index.php?action=checkout');
            exit;
        }

        if (empty($shipping_address)) {
            $_SESSION['error'] = "Alamat pengiriman harus diisi!";
            header('Location: index.php?action=checkout');
            exit;
        }

        // Get cart items
        $result = $this->cartModel->getByUserId($user_id);
        $cart_items = [];
        while ($row = $result->fetch_assoc()) {
            $cart_items[] = $row;
        }

        if (empty($cart_items)) {
            $_SESSION['error'] = "Keranjang belanja kosong!";
            header('Location: index.php?action=cart');
            exit;
        }

        // Calculate total
        $total_amount = 0;
        foreach ($cart_items as $item) {
            $total_amount += $item['subtotal'];
        }

        // Start transaction
        $this->db->begin_transaction();

        try {
            // Generate order number
            $order_number = $this->orderModel->generateOrderNumber();

            // Create order
            $this->orderModel->user_id = $user_id;
            $this->orderModel->payment_method_id = $payment_method_id;
            $this->orderModel->order_number = $order_number;
            $this->orderModel->total_amount = $total_amount;
            $this->orderModel->status = 'pending';
            $this->orderModel->shipping_address = $shipping_address;
            $this->orderModel->notes = $notes;

            $order_id = $this->orderModel->create();

            if (!$order_id) {
                throw new Exception("Gagal membuat order");
            }

            // Create order details & update stock
            foreach ($cart_items as $item) {
                // Check stock again
                if (!$this->productModel->hasStock($item['product_id'], $item['quantity'])) {
                    throw new Exception("Stock {$item['product_name']} tidak cukup!");
                }

                // Create order detail
                $this->orderDetailModel->order_id = $order_id;
                $this->orderDetailModel->product_id = $item['product_id'];
                $this->orderDetailModel->quantity = $item['quantity'];
                $this->orderDetailModel->price_per_item = $item['price'];
                $this->orderDetailModel->subtotal = $item['subtotal'];

                if (!$this->orderDetailModel->create()) {
                    throw new Exception("Gagal membuat order detail");
                }

                // Update product stock
                if (!$this->productModel->updateStock($item['product_id'], $item['quantity'])) {
                    throw new Exception("Gagal update stock");
                }
            }

            // Clear cart
            $this->cartModel->clearCart($user_id);

            // Commit transaction
            $this->db->commit();

            $_SESSION['success'] = "Order berhasil dibuat! Nomor order: {$order_number}";
            header('Location: index.php?action=order-detail&id=' . $order_id);

        } catch (Exception $e) {
            // Rollback on error
            $this->db->rollback();

            $_SESSION['error'] = "Checkout gagal: " . $e->getMessage();
            header('Location: index.php?action=checkout');
        }

        exit;
    }

    // ==================== ORDER HISTORY ====================

    /**
     * Show customer order history
     */
    public function history()
    {
        $this->requireLogin();

        $user_id = $_SESSION['user_id'];

        $result = $this->orderModel->getByUserId($user_id);
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }

        include 'views/orders/history.php';
    }

    /**
     * Show order detail
     */
    public function detail()
    {
        $this->requireLogin();

        $order_id = $_GET['id'] ?? 0;

        if (!$order_id) {
            $_SESSION['error'] = "Order tidak ditemukan!";
            header('Location: index.php?action=order-history');
            exit;
        }

        // Get order
        $order = $this->orderModel->getById($order_id);

        if (!$order) {
            $_SESSION['error'] = "Order tidak ditemukan!";
            header('Location: index.php?action=order-history');
            exit;
        }

        // Check ownership (customer can only see their own orders)
        if ($_SESSION['role'] !== 'admin' && $order['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = "Akses ditolak!";
            header('Location: index.php?action=order-history');
            exit;
        }

        // Get order details
        $details_result = $this->orderDetailModel->getByOrderId($order_id);
        $order_details = [];
        while ($row = $details_result->fetch_assoc()) {
            $order_details[] = $row;
        }

        include 'views/orders/detail.php';
    }

    /**
     * Cancel order (customer)
     */
    public function cancel()
    {
        $this->requireLogin();

        $order_id = $_GET['id'] ?? 0;

        if (!$order_id) {
            $_SESSION['error'] = "Order tidak ditemukan!";
            header('Location: index.php?action=order-history');
            exit;
        }

        $order = $this->orderModel->getById($order_id);

        // Check ownership
        if ($order['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = "Akses ditolak!";
            header('Location: index.php?action=order-history');
            exit;
        }

        // Only pending orders can be cancelled
        if ($order['status'] !== 'pending') {
            $_SESSION['error'] = "Order tidak bisa dibatalkan!";
            header('Location: index.php?action=order-detail&id=' . $order_id);
            exit;
        }

        if ($this->orderModel->cancel($order_id)) {
            $_SESSION['success'] = "Order berhasil dibatalkan!";
        } else {
            $_SESSION['error'] = "Gagal membatalkan order!";
        }

        header('Location: index.php?action=order-detail&id=' . $order_id);
        exit;
    }

    // ==================== HELPER METHODS ====================

    /**
     * Require user to be logged in
     */
    private function requireLogin()
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Silakan login terlebih dahulu!";
            header('Location: index.php?action=login');
            exit;
        }
    }
}
?>