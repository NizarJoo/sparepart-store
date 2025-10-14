<?php
$page_title = "Semua Produk - Toko Sparepart";
include '../layouts/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1 class="display-5 fw-bold mb-2">Semua Produk</h1>
        <p class="lead mb-0">Temukan produk yang Anda butuhkan</p>
    </div>
</section>

<div class="container py-5">
    <div class="row">
        <!-- Sidebar Filter -->
        <div class="col-lg-3 mb-4">
            <div class="card sticky-top" style="top: 80px;">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="bi bi-funnel"></i> Filter Produk</h6>
                </div>
                <div class="card-body">
                    <!-- Filter by Category -->
                    <h6 class="fw-bold mb-3">Kategori</h6>
                    <div class="list-group mb-4">
                        <a href="index.php?action=products"
                            class="list-group-item list-group-item-action <?= !isset($_GET['category_id']) ? 'active' : '' ?>">
                            <i class="bi bi-grid"></i> Semua Produk
                        </a>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $cat): ?>
                                <a href="index.php?action=products-by-category&category_id=<?= $cat['category_id'] ?>"
                                    class="list-group-item list-group-item-action">
                                    <i class="bi bi-tag"></i> <?= htmlspecialchars($cat['category_name']) ?>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Info -->
                    <div class="alert alert-info small">
                        <i class="bi bi-info-circle"></i>
                        Menampilkan <strong><?= count($products) ?></strong> produk
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9">
            <!-- Search & Sort Bar -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0">Menampilkan <?= count($products) ?> Produk</h5>

                <div class="d-flex gap-2">
                    <!-- Sort Dropdown -->
                    <select class="form-select form-select-sm" id="sortProducts" style="width: auto;">
                        <option value="">Urutkan</option>
                        <option value="name-asc">Nama A-Z</option>
                        <option value="name-desc">Nama Z-A</option>
                        <option value="price-asc">Harga Terendah</option>
                        <option value="price-desc">Harga Tertinggi</option>
                    </select>
                </div>
            </div>

            <?php if (!empty($products)): ?>
                <div class="row g-4" id="productsContainer">
                    <?php foreach ($products as $product): ?>
                        <div class="col-md-6 col-xl-4 product-item" data-name="<?= strtolower($product['product_name']) ?>"
                            data-price="<?= $product['price'] ?>">
                            <div class="card h-100">
                                <!-- Product Image -->
                                <div class="position-relative">
                                    <?php if (!empty($product['image_url']) && file_exists($product['image_url'])): ?>
                                        <img src="<?= htmlspecialchars($product['image_url']) ?>" class="card-img-top"
                                            alt="<?= htmlspecialchars($product['product_name']) ?>"
                                            style="height: 220px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="bg-light d-flex align-items-center justify-content-center"
                                            style="height: 220px;">
                                            <i class="bi bi-image text-muted" style="font-size: 4rem;"></i>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Stock Badge -->
                                    <?php if ($product['stock'] <= 0): ?>
                                        <span class="position-absolute top-0 end-0 m-2 badge bg-danger">
                                            Stok Habis
                                        </span>
                                    <?php elseif ($product['stock'] <= 5): ?>
                                        <span class="position-absolute top-0 end-0 m-2 badge bg-warning text-dark">
                                            Stock: <?= $product['stock'] ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <div class="card-body">
                                    <!-- Category Badge -->
                                    <span class="badge bg-primary mb-2">
                                        <?= htmlspecialchars($product['category_name'] ?? 'Uncategorized') ?>
                                    </span>

                                    <!-- Product Name -->
                                    <h6 class="card-title">
                                        <a href="index.php?action=product-detail&id=<?= $product['product_id'] ?>"
                                            class="text-decoration-none text-dark">
                                            <?= htmlspecialchars($product['product_name']) ?>
                                        </a>
                                    </h6>

                                    <!-- Brand -->
                                    <?php if (!empty($product['brand'])): ?>
                                        <p class="text-muted small mb-2">
                                            <i class="bi bi-tag"></i> <?= htmlspecialchars($product['brand']) ?>
                                        </p>
                                    <?php endif; ?>

                                    <!-- Price -->
                                    <h5 class="text-primary mb-0">
                                        Rp <?= number_format($product['price'], 0, ',', '.') ?>
                                    </h5>
                                </div>

                                <div class="card-footer bg-white border-0">
                                    <div class="d-grid gap-2">
                                        <a href="index.php?action=product-detail&id=<?= $product['product_id'] ?>"
                                            class="btn btn-outline-primary">
                                            <i class="bi bi-eye"></i> Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle"></i> Tidak ada produk ditemukan
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Sort Script -->
<script>
    document.getElementById('sortProducts').addEventListener('change', function () {
        const container = document.getElementById('productsContainer');
        const items = Array.from(container.getElementsByClassName('product-item'));
        const sortValue = this.value;

        if (!sortValue) return;

        items.sort((a, b) => {
            switch (sortValue) {
                case 'name-asc':
                    return a.dataset.name.localeCompare(b.dataset.name);
                case 'name-desc':
                    return b.dataset.name.localeCompare(a.dataset.name);
                case 'price-asc':
                    return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
                case 'price-desc':
                    return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
            }
        });

        items.forEach(item => container.appendChild(item));
    });
</script>

<?php include '../layouts/footer.php'; ?>