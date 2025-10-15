<?php
$page_title = "Keranjang Belanja - Toko Sparepart";
include 'views/layouts/header.php';
?>

<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="bi bi-cart3"></i> Keranjang Belanja</h2>
    
    <?php if (!empty($cart_items)): ?>
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <?php foreach ($cart_items as $item): ?>
                            <div class="row align-items-center border-bottom py-3">
                                <!-- Product Image -->
                                <div class="col-md-2">
                                    <?php if (!empty($item['image_url']) && file_exists($item['image_url'])): ?>
                                            <img src="<?= htmlspecialchars($item['image_url']) ?>" 
                                                 class="img-fluid rounded" 
                                                 alt="<?= htmlspecialchars($item['product_name']) ?>">
                                    <?php else: ?>
                                            <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                                 style="height: 80px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                    <?php endif; ?>
                                </div>
                        
                                <!-- Product Info -->
                                <div class="col-md-4">
                                    <h6 class="mb-1">
                                        <a href="index.php?action=product-detail&id=<?= $item['product_id'] ?>" 
                                           class="text-decoration-none text-dark">
                                            <?= htmlspecialchars($item['product_name']) ?>
                                        </a>
                                    </h6>
                                    <p class="text-muted small mb-0">
                                        Stock: <?= $item['stock'] ?>
                                    </p>
                                </div>
                        
                                <!-- Price -->
                                <div class="col-md-2 text-center">
                                    <p class="mb-0 fw-bold">
                                        Rp <?= number_format($item['price'], 0, ',', '.') ?>
                                    </p>
                                </div>
                        
                                <!-- Quantity Controls -->
                                <div class="col-md-2">
                                    <div class="input-group input-group-sm">
                                        <a href="index.php?action=cart-decrease&id=<?= $item['cart_id'] ?>" 
                                           class="btn btn-outline-secondary">
                                            <i class="bi bi-dash"></i>
                                        </a>
                                        <input type="text" 
                                               class="form-control text-center" 
                                               value="<?= $item['quantity'] ?>" 
                                               readonly>
                                        <a href="index.php?action=cart-increase&id=<?= $item['cart_id'] ?>" 
                                           class="btn btn-outline-secondary">
                                            <i class="bi bi-plus"></i>
                                        </a>
                                    </div>
                                </div>
                        
                                <!-- Subtotal & Remove -->
                                <div class="col-md-2 text-end">
                                    <p class="mb-2 fw-bold text-primary">
                                        Rp <?= number_format($item['subtotal'], 0, ',', '.') ?>
                                    </p>
                                    <a href="index.php?action=cart-remove&id=<?= $item['cart_id'] ?>" 
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('Hapus item ini dari keranjang?')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    
                        <!-- Clear Cart Button -->
                        <div class="mt-3 text-end">
                            <a href="index.php?action=cart-clear" 
                               class="btn btn-outline-danger"
                               onclick="return confirm('Kosongkan semua keranjang?')">
                                <i class="bi bi-trash"></i> Kosongkan Keranjang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        
            <!-- Cart Summary -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 80px;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-receipt"></i> Ringkasan Belanja</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Item:</span>
                            <strong><?= count($cart_items) ?> item</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Quantity:</span>
                            <strong>
                                <?php
                                $total_qty = 0;
                                foreach ($cart_items as $item) {
                                    $total_qty += $item['quantity'];
                                }
                                echo $total_qty;
                                ?>
                            </strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="mb-0">Total:</h5>
                            <h4 class="mb-0 text-primary">
                                Rp <?= number_format($cart_total, 0, ',', '.') ?>
                            </h4>
                        </div>
                    
                        <div class="d-grid gap-2">
                            <a href="index.php?action=checkout" class="btn btn-primary btn-lg">
                                <i class="bi bi-credit-card"></i> Checkout
                            </a>
                            <a href="index.php?action=products" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Lanjut Belanja
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Empty Cart -->
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body py-5">
                        <i class="bi bi-cart-x" style="font-size: 5rem; color: #ccc;"></i>
                        <h4 class="mt-4">Keranjang Belanja Kosong</h4>
                        <p class="text-muted mb-4">
                            Anda belum menambahkan produk ke keranjang
                        </p>
                        <a href="index.php?action=products" class="btn btn-primary btn-lg">
                            <i class="bi bi-grid"></i> Mulai Belanja
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'views/layouts/footer.php'; ?>