<?php
$page_title = "Kelola Pesanan - Admin";
include 'views/layouts/header.php';
?>

<div class="container-fluid py-4">
    <div class="mb-4">
        <h2 class="fw-bold mb-1"><i class="bi bi-cart-check"></i> Kelola Pesanan</h2>
        <p class="text-muted mb-0">Manage semua pesanan pelanggan</p>
    </div>

    <!-- Status Filter -->
    <div class="mb-4">
        <div class="btn-group" role="group">
            <a href="index.php?action=admin-orders"
                class="btn btn-outline-primary <?= !isset($_GET['status']) ? 'active' : '' ?>">
                <i class="bi bi-list"></i> Semua
            </a>
            <a href="index.php?action=admin-orders&status=pending"
                class="btn btn-outline-warning <?= (isset($_GET['status']) && $_GET['status'] == 'pending') ? 'active' : '' ?>">
                Pending
            </a>
            <a href="index.php?action=admin-orders&status=processing"
                class="btn btn-outline-info <?= (isset($_GET['status']) && $_GET['status'] == 'processing') ? 'active' : '' ?>">
                Processing
            </a>
            <a href="index.php?action=admin-orders&status=shipped"
                class="btn btn-outline-primary <?= (isset($_GET['status']) && $_GET['status'] == 'shipped') ? 'active' : '' ?>">
                Shipped
            </a>
            <a href="index.php?action=admin-orders&status=delivered"
                class="btn btn-outline-success <?= (isset($_GET['status']) && $_GET['status'] == 'delivered') ? 'active' : '' ?>">
                Delivered
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <?php if (!empty($orders)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($order['order_number']) ?></strong>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($order['customer_name']) ?><br>
                                        <small class="text-muted"><?= htmlspecialchars($order['customer_email']) ?></small>
                                    </td>
                                    <td><strong>Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></strong></td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <?= htmlspecialchars($order['payment_method']) ?>
                                        </span>
                                    </td>
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
                                    <td><?= date('d M Y H:i', strtotime($order['order_date'])) ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="index.php?action=admin-order-detail&id=<?= $order['order_id'] ?>"
                                                class="btn btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="index.php?action=admin-order-delete&id=<?= $order['order_id'] ?>"
                                                class="btn btn-outline-danger delete-confirm">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted"></i>
                    <p class="text-muted mt-2">Belum ada pesanan</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>