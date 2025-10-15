<?php
$page_title = "Checkout - Toko Sparepart";
include 'views/layouts/header.php';
?>

<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="bi bi-credit-card"></i> Checkout</h2>

    <form action="index.php?action=checkout-process" method="POST">
        <div class="row">
            <!-- Checkout Form -->
            <div class="col-lg-8 mb-4">
                <!-- Shipping Address -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Alamat Pengiriman</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="shipping_address" class="form-label">
                                Alamat Lengkap <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="shipping_address" name="shipping_address" rows="4"
                                placeholder="Masukkan alamat lengkap untuk pengiriman"
                                required><?= htmlspecialchars($_SESSION['old']['shipping_address'] ?? '') ?></textarea>
                            <small class="text-muted">
                                Pastikan alamat lengkap dengan nama jalan, nomor rumah, kecamatan, kota
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-wallet2"></i> Metode Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($payment_methods)): ?>
                            <?php foreach ($payment_methods as $payment): ?>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="payment_method_id"
                                        id="payment_<?= $payment['payment_method_id'] ?>"
                                        value="<?= $payment['payment_method_id'] ?>" required>
                                    <label class="form-check-label w-100" for="payment_<?= $payment['payment_method_id'] ?>">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <strong><?= htmlspecialchars($payment['method_name']) ?></strong>
                                                <?php if (!empty($payment['description'])): ?>
                                                    <p class="text-muted small mb-0 mt-1">
                                                        <?= htmlspecialchars($payment['description']) ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle"></i> Tidak ada metode pembayaran tersedia
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Order Notes -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-chat-left-text"></i> Catatan Pesanan (Opsional)</h5>
                    </div>
                    <div class="card-body">
                        <textarea class="form-control" name="notes" rows="3"
                            placeholder="Tambahkan catatan untuk pesanan (opsional)"><?= htmlspecialchars($_SESSION['old']['notes'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 80px;">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="bi bi-receipt"></i> Ringkasan Pesanan</h5>
                    </div>
                    <div class="card-body">
                        <!-- Cart Items Summary -->
                        <div class="mb-3">
                            <?php foreach ($cart_items as $item): ?>
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="flex-grow-1">
                                        <p class="mb-0 small"><?= htmlspecialchars($item['product_name']) ?></p>
                                        <p class="mb-0 text-muted" style="font-size: 0.8rem;">
                                            <?= $item['quantity'] ?> x Rp <?= number_format($item['price'], 0, ',', '.') ?>
                                        </p>
                                    </div>
                                    <div class="text-end">
                                        <strong class="small">Rp
                                            <?= number_format($item['subtotal'], 0, ',', '.') ?></strong>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <hr>

                        <!-- Totals -->
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <strong>Rp <?= number_format($cart_total, 0, ',', '.') ?></strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Ongkir:</span>
                            <strong class="text-success">GRATIS</strong>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="mb-0">Total:</h5>
                            <h4 class="mb-0 text-primary">
                                Rp <?= number_format($cart_total, 0, ',', '.') ?>
                            </h4>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle"></i> Buat Pesanan
                            </button>
                            <a href="index.php?action=cart" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali ke Keranjang
                            </a>
                        </div>

                        <!-- Info -->
                        <div class="alert alert-info mt-3 small mb-0">
                            <i class="bi bi-info-circle"></i>
                            Dengan melanjutkan, Anda menyetujui syarat dan ketentuan yang berlaku
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php
unset($_SESSION['old']);
include 'views/layouts/footer.php';
?>