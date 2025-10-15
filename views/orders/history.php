<?php
$page_title = "Riwayat Pesanan - Toko Sparepart";
include 'views/layouts/header.php';
?>

<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="bi bi-bag-check"></i> Riwayat Pesanan</h2>

    <?php if (!empty($orders)): ?>
        <div class="row">
            <?php foreach ($orders as $order): ?>
                <div class="col-12 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <!-- Order Info -->
                                <div class="col-md-3">
                                    <p class="text-muted small mb-1">Order Number</p>
                                    <h6 class="mb-2">
                                        <a href="index.php?action=order-detail&id=<?= $order['order_id'] ?>"
                                            class="text-decoration-none">
                                            <?= htmlspecialchars($order['order_number']) ?>
                                        </a>
                                    </h6>
                                    <p class="text-muted small mb-0">
                                        <?= date('d M Y H:i', strtotime($order['order_date'])) ?>
                                    </p>
                                </div>

                                <!-- Payment Method -->
                                <div class="col-md-3">
                                    <p class="text-muted small mb-1">Payment</p>
                                    <span class="badge bg-secondary">
                                        <?= htmlspecialchars($order['payment_method']) ?>
                                    </span>
                                </div>

                                <!-- Total -->
                                <div class="col-md-2">
                                    <p class="text-muted small mb-1">Total</p>
                                    <h6 class="text-primary mb-0">
                                        Rp <?= number_format($order['total_amount'], 0, ',', '.') ?>
                                    </h6>
                                </div>

                                <!-- Status -->
                                <div class="col-md-2">
                                    <p class="text-muted small mb-1">Status</p>
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
                                </div>

                                <!-- Actions -->
                                <div class="col-md-2 text-end">
                                    <a href="index.php?action=order-detail&id=<?= $order['order_id'] ?>"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                    <?php if ($order['status'] === 'pending'): ?>
                                        <a href="index.php?action=order-cancel&id=<?= $order['order_id'] ?>"
                                            class="btn btn-sm btn-outline-danger mt-1"
                                            onclick="return confirm('Batalkan pesanan ini?')">
                                            <i class="bi bi-x-circle"></i> Batal
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <!-- Empty State -->
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body py-5">
                        <i class="bi bi-inbox" style="font-size: 5rem; color: #ccc;"></i>
                        <h4 class="mt-4">Belum Ada Pesanan</h4>
                        <p class="text-muted mb-4">
                            Anda belum pernah melakukan pemesanan
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