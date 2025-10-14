<?php
/**
 * Admin Order Controller
 * File: controllers/AdminOrderController.php
 * Handle order management (admin only)
 */

require_once 'models/Order.php';
require_once 'models/OrderDetail.php';
require_once 'controllers/AuthController.php';

class AdminOrderController
{
    private $db;
    private $orderModel;
    private $orderDetailModel;

    public function __construct($db)
    {
        // Require admin access
        AuthController::requireAdmin();

        $this->db = $db;
        $this->orderModel = new Order($db);
        $this->orderDetailModel = new OrderDetail($db);
    }

    // ==================== READ ====================

    /**
     * Show all orders
     */
    public function index()
    {
        $result = $this->orderModel->getAll();

        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }

        include 'views/admin/orders/index.php';
    }

    /**
     * Show order detail
     */
    public function detail()
    {
        $order_id = $_GET['id'] ?? 0;

        if (!$order_id) {
            $_SESSION['error'] = "Order tidak ditemukan!";
            header('Location: index.php?action=admin-orders');
            exit;
        }

        // Get order
        $order = $this->orderModel->getById($order_id);

        if (!$order) {
            $_SESSION['error'] = "Order tidak ditemukan!";
            header('Location: index.php?action=admin-orders');
            exit;
        }

        // Get order details
        $details_result = $this->orderDetailModel->getByOrderId($order_id);
        $order_details = [];
        while ($row = $details_result->fetch_assoc()) {
            $order_details[] = $row;
        }

        include 'views/admin/orders/detail.php';
    }

    /**
     * Filter orders by status
     */
    public function byStatus()
    {
        $status = $_GET['status'] ?? 'all';

        if ($status === 'all') {
            $result = $this->orderModel->getAll();
        } else {
            $result = $this->orderModel->getByStatus($status);
        }

        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }

        include 'views/admin/orders/index.php';
    }

    // ==================== UPDATE ====================

    /**
     * Update order status
     */
    public function updateStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=admin-orders');
            exit;
        }

        $order_id = $_POST['order_id'] ?? 0;
        $status = $_POST['status'] ?? '';

        if (!$order_id || empty($status)) {
            $_SESSION['error'] = "Data tidak valid!";
            header('Location: index.php?action=admin-orders');
            exit;
        }

        // Validate status
        $allowed_statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        if (!in_array($status, $allowed_statuses)) {
            $_SESSION['error'] = "Status tidak valid!";
            header('Location: index.php?action=admin-order-detail&id=' . $order_id);
            exit;
        }

        if ($this->orderModel->updateStatus($order_id, $status)) {
            $_SESSION['success'] = "Status order berhasil diupdate!";
        } else {
            $_SESSION['error'] = "Gagal update status order!";
        }

        header('Location: index.php?action=admin-order-detail&id=' . $order_id);
        exit;
    }

    // ==================== DELETE ====================

    /**
     * Delete order (hard delete - be careful!)
     */
    public function delete()
    {
        $order_id = $_GET['id'] ?? 0;

        if (!$order_id) {
            $_SESSION['error'] = "Order tidak ditemukan!";
            header('Location: index.php?action=admin-orders');
            exit;
        }

        // Delete order (CASCADE will delete order_details automatically)
        if ($this->orderModel->delete($order_id)) {
            $_SESSION['success'] = "Order berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus order!";
        }

        header('Location: index.php?action=admin-orders');
        exit;
    }
}
?>