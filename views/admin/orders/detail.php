<?php
$page_title = "Detail Pesanan - Admin";
include '../layouts/header.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Order #<?= htmlspecialchars($order['order_number']) ?></h2>
            <p class="text-muted mb-0">Detail pesanan pelanggan</p>
        </div>
        <a href="index.php?action=admin-orders" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
    
    <div class="row g-4">
        <!-- Order Info -->
        <div class="col-lg-8">
            <!-- Order Items -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-box-seam"></i> Item Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order_details as $item): ?>
                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($item['product_name']) ?></strong><br>
                                            <small class="text-muted"><?= htmlspecialchars($item['brand'] ?? '') ?></small>
                                        </td>
                                        <td>Rp <?= number_format($item['price_per_item'], 0, ',', '.') ?></td>
                                        <td><span class="badge bg-primary"><?= $item['quantity'] ?></span></td>
                                        <td><strong>Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></strong></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="table-light">
                                    <td colspan="3" class="text-end"><strong>TOTAL:</strong></td>
                                    <td><h5 class="mb-0 text-primary">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></h5></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Shipping Address -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-geo-alt"></i> Alamat Pengiriman</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0"><?= nl2br(htmlspecialchars($order['shipping_address'])) ?></p>
                    <?php if (!empty($order['notes'])): ?>
                        <hr>
                        <p class="text-muted small mb-0">
                            <strong>Catatan:</strong><br>
                            <?= nl2br(htmlspecialchars($order['notes'])) ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Order Summary & Actions -->
        <div class="col-lg-4">
            <!-- Customer Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-person"></i> Info Customer</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted">Nama</td>
                            <td><strong><?= htmlspecialchars($order['customer_name']) ?></strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Email</td>
                            <td><?= htmlspecialchars($order['customer_email']) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Telepon</td>
                            <td><?= htmlspecialchars($order['customer_phone'] ?? '-') ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- Order Status -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-info-circle"></i> Info Pesanan</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted">Order ID</td>
                            <td><strong>#<?= $order['order_id'] ?></strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Payment</td>
                            <td>
                                <span class="badge bg-secondary">
                                    <?= htmlspecialchars($order['payment_method']) ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status</td>
                            <td>
                                <?php
                                $badge_colors = [
                                    'pending' => 'warning',
                                    'processing' => 'info',
                                    'shipped' => 'primary',
                                    'delivered' => 'success',
                                    'cancelled' => 'danger'
                                ];
                                $color = $badge_colors[$order['status']] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?= $color ?>">
                                    <?= ucfirst($order['status']) ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tanggal</td>
                            <td><?= date('d M Y H:i', strtotime($order['order_date'])) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- Update Status Form -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-arrow-repeat"></i> Update Status</h6>
                </div>
                <div class="card-body">
                    <form action="index.php?action=admin-order-update-status" method="POST">
                        <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Status Pesanan</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="pending" <?= ($order['status'] == 'pending') ? 'selected' : '' ?>>Pending</option>
                                <option value="processing" <?= ($order['status'] == 'processing') ? 'selected' : '' ?>>Processing</option>
                                <option value="shipped" <?= ($order['status'] == 'shipped') ? 'selected' : '' ?>>Shipped</option>
                                <option value="delivered" <?= ($order['status'] == 'delivered') ? 'selected' : '' ?>>Delivered</option>
                                <option value="cancelled" <?= ($order['status'] == 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-lg"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>