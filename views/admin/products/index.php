<?php
$page_title = "Kelola Produk - Admin";
include '../layouts/header.php';
?>

<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="bi bi-box-seam"></i> Kelola Produk</h2>
            <p class="text-muted mb-0">Manage semua produk</p>
        </div>
        <a href="index.php?action=admin-products-create" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Produk
        </a>
    </div>

    <!-- Products Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <?php if (!empty($products)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="80">ID</th>
                                <th width="100">Gambar</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Brand</th>
                                <th>Harga</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?= $product['product_id'] ?></td>
                                    <td>
                                        <?php if (!empty($product['image_url']) && file_exists($product['image_url'])): ?>
                                            <img src="<?= htmlspecialchars($product['image_url']) ?>"
                                                alt="<?= htmlspecialchars($product['product_name']) ?>" class="img-thumbnail"
                                                style="width: 60px; height: 60px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-light d-flex align-items-center justify-content-center"
                                                style="width: 60px; height: 60px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?= htmlspecialchars($product['product_name']) ?></strong><br>
                                        <small class="text-muted">SKU: <?= htmlspecialchars($product['sku']) ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <?= htmlspecialchars($product['category_name'] ?? '-') ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($product['brand'] ?? '-') ?></td>
                                    <td>Rp <?= number_format($product['price'], 0, ',', '.') ?></td>
                                    <td>
                                        <?php if ($product['stock'] == 0): ?>
                                            <span class="badge bg-danger"><?= $product['stock'] ?></span>
                                        <?php elseif ($product['stock'] <= 5): ?>
                                            <span class="badge bg-warning text-dark"><?= $product['stock'] ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-success"><?= $product['stock'] ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($product['status'] === 'active'): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="index.php?action=admin-products-edit&id=<?= $product['product_id'] ?>"
                                                class="btn btn-outline-primary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="index.php?action=admin-products-delete&id=<?= $product['product_id'] ?>"
                                                class="btn btn-outline-danger delete-confirm" title="Delete">
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
                    <p class="text-muted mt-2">Belum ada produk</p>
                    <a href="index.php?action=admin-products-create" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Tambah Produk Pertama
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>