<?php
/**
 * Cart Controller
 * File: controllers/CartController.php
 * Handle shopping cart operations
 */

require_once 'models/CartItem.php';
require_once 'models/Product.php';

class CartController
{
    private $db;
    private $cartModel;
    private $productModel;

    public function __construct($db)
    {
        $this->db = $db;
        $this->cartModel = new CartItem($db);
        $this->productModel = new Product($db);
    }

    // ==================== VIEW CART ====================

    /**
     * Show cart page
     */
    public function index()
    {
        $this->requireLogin();

        $user_id = $_SESSION['user_id'];

        // Get cart items
        $result = $this->cartModel->getByUserId($user_id);
        $cart_items = [];
        while ($row = $result->fetch_assoc()) {
            $cart_items[] = $row;
        }

        // Get cart total
        $cart_total = $this->cartModel->getCartTotal($user_id);

        include 'views/cart/index.php';
    }

    // ==================== ADD TO CART ====================

    /**
     * Add product to cart
     */
    public function add()
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=products');
            exit;
        }

        $product_id = $_POST['product_id'] ?? 0;
        $quantity = $_POST['quantity'] ?? 1;

        if (!$product_id) {
            $_SESSION['error'] = "Produk tidak valid!";
            header('Location: index.php?action=products');
            exit;
        }

        // Check product exists and has stock
        $product = $this->productModel->getById($product_id);

        if (!$product) {
            $_SESSION['error'] = "Produk tidak ditemukan!";
            header('Location: index.php?action=products');
            exit;
        }

        if ($product['status'] !== 'active') {
            $_SESSION['error'] = "Produk tidak tersedia!";
            header('Location: index.php?action=products');
            exit;
        }

        // Check stock availability
        if (!$this->productModel->hasStock($product_id, $quantity)) {
            $_SESSION['error'] = "Stock tidak cukup! Stock tersedia: " . $product['stock'];
            header('Location: index.php?action=product-detail&id=' . $product_id);
            exit;
        }

        // Add to cart
        $this->cartModel->user_id = $_SESSION['user_id'];
        $this->cartModel->product_id = $product_id;
        $this->cartModel->quantity = $quantity;

        if ($this->cartModel->add()) {
            $_SESSION['success'] = "Produk berhasil ditambahkan ke keranjang!";
        } else {
            $_SESSION['error'] = "Gagal menambahkan ke keranjang!";
        }

        // Redirect back
        $redirect = $_POST['redirect'] ?? 'products';
        header('Location: index.php?action=' . $redirect . '&id=' . $product_id);
        exit;
    }

    // ==================== UPDATE QUANTITY ====================

    /**
     * Update item quantity
     */
    public function updateQuantity()
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=cart');
            exit;
        }

        $cart_id = $_POST['cart_id'] ?? 0;
        $quantity = $_POST['quantity'] ?? 1;

        if ($quantity < 1) {
            $_SESSION['error'] = "Quantity minimal 1!";
            header('Location: index.php?action=cart');
            exit;
        }

        if ($this->cartModel->updateQuantity($cart_id, $quantity)) {
            $_SESSION['success'] = "Quantity berhasil diupdate!";
        } else {
            $_SESSION['error'] = "Gagal update quantity!";
        }

        header('Location: index.php?action=cart');
        exit;
    }

    /**
     * Increase quantity
     */
    public function increase()
    {
        $this->requireLogin();

        $cart_id = $_GET['id'] ?? 0;

        if ($this->cartModel->increaseQuantity($cart_id)) {
            $_SESSION['success'] = "Quantity ditambah!";
        } else {
            $_SESSION['error'] = "Gagal menambah quantity!";
        }

        header('Location: index.php?action=cart');
        exit;
    }

    /**
     * Decrease quantity
     */
    public function decrease()
    {
        $this->requireLogin();

        $cart_id = $_GET['id'] ?? 0;

        if ($this->cartModel->decreaseQuantity($cart_id)) {
            $_SESSION['success'] = "Quantity dikurangi!";
        } else {
            $_SESSION['error'] = "Gagal mengurangi quantity!";
        }

        header('Location: index.php?action=cart');
        exit;
    }

    // ==================== REMOVE FROM CART ====================

    /**
     * Remove item from cart
     */
    public function remove()
    {
        $this->requireLogin();

        $cart_id = $_GET['id'] ?? 0;

        if (!$cart_id) {
            $_SESSION['error'] = "Item tidak valid!";
            header('Location: index.php?action=cart');
            exit;
        }

        if ($this->cartModel->remove($cart_id)) {
            $_SESSION['success'] = "Item berhasil dihapus dari keranjang!";
        } else {
            $_SESSION['error'] = "Gagal menghapus item!";
        }

        header('Location: index.php?action=cart');
        exit;
    }

    /**
     * Clear all cart
     */
    public function clear()
    {
        $this->requireLogin();

        $user_id = $_SESSION['user_id'];

        if ($this->cartModel->clearCart($user_id)) {
            $_SESSION['success'] = "Keranjang berhasil dikosongkan!";
        } else {
            $_SESSION['error'] = "Gagal mengosongkan keranjang!";
        }

        header('Location: index.php?action=cart');
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

    /**
     * Get cart count (for navbar badge)
     */
    public static function getCartCount($db)
    {
        if (!isset($_SESSION['user_id'])) {
            return 0;
        }

        $cartModel = new CartItem($db);
        return $cartModel->getCartCount($_SESSION['user_id']);
    }
}
?>