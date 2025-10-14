<?php
$page_title = "Dashboard Admin - Toko Sparepart";
include 'views/layouts/header.php';
?>

<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="bi bi-speedometer2"></i> Dashboard Admin</h2>
            <p class="text-muted mb-0">Selamat datang, <?= htmlspecialchars($_SESSION['full_name']) ?>!</p>
        </div>
        <div class="text-muted">
            <i class="bi bi-calendar"></i> <?= date('d F Y') ?>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <!-- Total Products -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1">Total Produk</p>
                            <h3 class="fw-bold mb-0"><?= $stats['total_products'] ?></h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-box-seam text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Total Orders -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1">Total Pesanan</p>
                            <h3 class="fw-bold mb-0"><?= $stats['total_orders'] ?></h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-cart-check text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Total Customers -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1">Total Customer</p>
                            <h3 class="fw-bold mb-0"><?= $stats['total_customers'] ?></h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="bi bi-people text-info fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Total Revenue -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1">Total Pendapatan</p>
                            <h3 class="fw-bold mb-0">Rp <?= number_format($stats['total_revenue'], 0, ',', '.') ?></h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="bi bi-currency-dollar text-warning fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Orders Status Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-start border-warning border-4">
                <div class="card-body">
                    <p class="text-muted mb-1">Pending</p>
                    <h4 class="fw-bold mb-0"><?= $stats['pending_orders'] ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-start border-info border-4">
                <div class="card-body">
                    <p class="text-muted mb-1">Processing</p>
                    <h4 class="fw-bold mb-0"><?= $stats['processing_orders'] ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-start border-primary border-4">
                <div class="card-body">
                    <p class="text-muted mb-1">Shipped</p>
                    <h4 class="fw-bold mb-0"><?= $stats['shipped_orders'] ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-start border-success border-4">
                <div class="card-body">
                    <p class="text-muted mb-1">Delivered</p>
                    <h4 class="fw-bold mb-0"><?= $stats['delivered_orders'] ?></h4>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <!-- Recent Orders -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-clock-history"></i> Pesanan Terbaru</h5>
                        <a href="index.php?action=admin-orders" class="btn btn-sm btn-outline-primary">
                            Lihat Semua
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($recent_orders)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Order #</th>
                                        <th>Customer</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_orders as $order): ?>
                                        <tr>
                                            <td>
                                                <a href="index.php?action=admin-order-detail&id=<?= $order['order_id'] ?>" 
                                                   class="text-decoration-none">
                                                    <?= htmlspecialchars($order['order_number']) ?>
                                                </a>
                                            </td>
                                            <td><?= htmlspecialchars($order['customer_name']) ?></td>
                                            <td>Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></td>
                                            <td>
                                                <?php
                                                $badge_class = [
                                                    'pending' => 'warning',
                                                    'processing' => 'info',
                                                    'shipped' => 'primary',
                                                    'delivered' => 'success',
                                                    'cancelled' => 'danger'
                                                ];
                                                ?>
                                                <span class="badge bg-<?= $badge_class[$order['status']] ?? 'secondary' ?>">
                                                    <?= ucfirst($order['status']) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="p-4 text-center text-muted">
                            <i class="bi bi-inbox fs-1"></i>
                            <p class="mb-0 mt-2">Belum ada pesanan</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Low Stock Products -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-exclamation-triangle"></i> Stock Menipis</h5>
                        <a href="index.php?action=admin-products" class="btn btn-sm btn-outline-warning">
                            Lihat Semua
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($low_stock_products)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produk</th>
                                        <th>Kategori</th>
                                        <th>Stock</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($low_stock_products, 0, 5) as $product): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($product['product_name']) ?></td>
                                            <td>
                                                <span class="badge bg-secondary"><?= htmlspecialchars($product['category_name'] ?? '-') ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= $product['stock'] == 0 ? 'danger' : 'warning' ?>">
                                                    <?= $product['stock'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="index.php?action=admin-products-edit&id=<?= $product['product_id'] ?>" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    Edit
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="p-4 text-center text-muted">
                            <i class="bi bi-check-circle fs-1 text-success"></i>
                            <p class="mb-0 mt-2">Semua produk stock aman</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Best Sellers -->
    <?php if (!empty($best_sellers)): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-trophy"></i> Produk Terlaris</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <?php foreach ($best_sellers as $product): ?>
                                <div class="col-md-2">
                                    <div class="card h-100">
                                        <?php if (!empty($product['image_url']) && file_exists($product['image_url'])): ?>
                                                <img src="<?= htmlspecialchars($product['image_url']) ?>" 
                                                     class="card-img-top" 
                                                     alt="<?= htmlspecialchars($product['product_name']) ?>"
                                                     style="height: 120px; object-fit: cover;">
                                        <?php else: ?>
                                                <div class="bg-light d-flex align-items-center justify-content-center" 
                                                     style="height: 120px;">
                                                    <i class="bi bi-image text-muted"></i>
                                                </div>
                                        <?php endif; ?>
                                        <div class="card-body p-2">
                                            <p class="small mb-1 text-truncate"><?= htmlspecialchars($product['product_name']) ?></p>
                                            <p class="text-primary small mb-0 fw-bold">
                                                Terjual: <?= $product['total_sold'] ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'views/layouts/footer.php'; ?>