<?php
$page_title = "Detail Pesanan - Toko Sparepart";
include 'views/layouts/header.php';
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Order #<?= htmlspecialchars($order['order_number']) ?></h2>
            <p class="text-muted mb-0">
                Dipesan pada: <?= date('d F Y, H:i', strtotime($order['order_date'])) ?> WIB
            </p>
        </div>
        <a href="index.php?action=order-history" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3"><i class="bi bi-info-circle"></i> Status Pesanan</h5>
                    <?php
                    $badge_colors = [
                        'pending' => 'warning',
                        'processing' => 'info',
                        'shipped' => 'primary',
                        'delivered' => 'success',
                        'cancelled' => 'danger'
                    ];
                    $status_text = [
                        'pending' => 'Menunggu Konfirmasi',
                        'processing' => 'Sedang Diproses',
                        'shipped' => 'Dalam Pengiriman',
                        'delivered' => 'Sudah Diterima',
                        'cancelled' => 'Dibatalkan'
                    ];
                    $color = $badge_colors[$order['status']] ?? 'secondary';
                    ?>
                    <div class="alert alert-<?= $color ?> mb-0">
                        <div class="d-flex align-items-center">
                            <i
                                class="bi bi-<?= $order['status'] === 'delivered' ? 'check-circle' : 'clock-history' ?> fs-3 me-3"></i>
                            <div>
                                <h5 class="mb-1"><?= $status_text[$order['status']] ?? ucfirst($order['status']) ?></h5>
                                <p class="mb-0 small">
                                    <?php if ($order['status'] === 'pending'): ?>
                                        Pesanan Anda sedang menunggu konfirmasi dari admin
                                    <?php elseif ($order['status'] === 'processing'): ?>
                                        Pesanan Anda sedang disiapkan untuk pengiriman
                                    <?php elseif ($order['status'] === 'shipped'): ?>
                                        Pesanan Anda sedang dalam perjalanan
                                    <?php elseif ($order['status'] === 'delivered'): ?>
                                        Pesanan Anda telah berhasil diterima
                                    <?php elseif ($order['status'] === 'cancelled'): ?>
                                        Pesanan Anda telah dibatalkan
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <?php if ($order['status'] === 'pending'): ?>
                        <div class="mt-3 text-end">
                            <a href="index.php?action=order-cancel&id=<?= $order['order_id'] ?>"
                                class="btn btn-outline-danger"
                                onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                <i class="bi bi-x-circle"></i> Batalkan Pesanan
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-box-seam"></i> Item Pesanan</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
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
                                            <div class="d-flex align-items-center">
                                                <?php if (!empty($item['image_url']) && file_exists($item['image_url'])): ?>
                                                    <img src="<?= htmlspecialchars($item['image_url']) ?>" class="me-3 rounded"
                                                        style="width: 60px; height: 60px; object-fit: cover;">
                                                <?php endif; ?>
                                                <div>
                                                    <strong><?= htmlspecialchars($item['product_name']) ?></strong>
                                                    <?php if (!empty($item['brand'])): ?>
                                                        <br><small
                                                            class="text-muted"><?= htmlspecialchars($item['brand']) ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Rp <?= number_format($item['price_per_item'], 0, ',', '.') ?></td>
                                        <td><span class="badge bg-primary"><?= $item['quantity'] ?></span></td>
                                        <td><strong>Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></strong></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end"><strong>TOTAL:</strong></td>
                                    <td>
                                        <h5 class="mb-0 text-primary">
                                            Rp <?= number_format($order['total_amount'], 0, ',', '.') ?>
                                        </h5>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

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

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0"><i class="bi bi-receipt"></i> Info Pesanan</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted">Order ID</td>
                            <td class="text-end"><strong>#<?= $order['order_id'] ?></strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Order Number</td>
                            <td class="text-end"><strong><?= htmlspecialchars($order['order_number']) ?></strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tanggal</td>
                            <td class="text-end"><?= date('d M Y', strtotime($order['order_date'])) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Waktu</td>
                            <td class="text-end"><?= date('H:i', strtotime($order['order_date'])) ?> WIB</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Payment</td>
                            <td class="text-end">
                                <span class="badge bg-secondary">
                                    <?= htmlspecialchars($order['payment_method']) ?>
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <?php if (!empty($order['payment_description'])): ?>
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="bi bi-credit-card"></i> Info Pembayaran</h6>
                    </div>
                    <div class="card-body">
                        <p class="small mb-0"><?= nl2br(htmlspecialchars($order['payment_description'])) ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bi bi-person"></i> Info Customer</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted">Nama</td>
                            <td class="text-end"><strong><?= htmlspecialchars($order['customer_name']) ?></strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Email</td>
                            <td class="text-end"><?= htmlspecialchars($order['customer_email']) ?></td>
                        </tr>
                        <?php if (!empty($order['customer_phone'])): ?>
                            <tr>
                                <td class="text-muted">Telepon</td>
                                <td class="text-end"><?= htmlspecialchars($order['customer_phone']) ?></td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>