<?php
$page_title = "Home - Toko Sparepart Komputer";
include 'layouts/header.php';
?>

<!-- Hero Section -->
<section class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-3">
                    Toko Sparepart Komputer Terlengkap
                </h1>
                <p class="lead mb-4">
                    Temukan berbagai kebutuhan komputer dan aksesoris berkualitas dengan harga terbaik
                </p>
                <div class="d-flex gap-2">
                    <a href="index.php?action=products" class="btn btn-light btn-lg">
                        <i class="bi bi-grid"></i> Lihat Produk
                    </a>
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <a href="index.php?action=register" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-person-plus"></i> Daftar Sekarang
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <i class="bi bi-pc-display-horizontal text-white" style="font-size: 15rem; opacity: 0.2;"></i>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Kategori Produk</h2>
            <p class="text-muted">Pilih kategori sesuai kebutuhan Anda</p>
        </div>

        <div class="row g-4">
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                    <div class="col-md-4 col-lg-2">
                        <a href="index.php?action=products-by-category&category_id=<?= $category['category_id'] ?>"
                            class="text-decoration-none">
                            <div class="card text-center h-100 border-2">
                                <div class="card-body">
                                    <i class="bi bi-hdd-rack text-primary" style="font-size: 3rem;"></i>
                                    <h6 class="mt-3 mb-0"><?= htmlspecialchars($category['category_name']) ?></h6>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="bi bi-info-circle"></i> Belum ada kategori
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">Produk Terbaru</h2>
                <p class="text-muted">Produk terbaru dan terpopuler</p>
            </div>
            <a href="index.php?action=products" class="btn btn-outline-primary">
                Lihat Semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <?php if (!empty($featured_products)): ?>
            <div class="row g-4">
                <?php foreach ($featured_products as $product): ?>
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100">
                            <!-- Product Image -->
                            <div class="position-relative">
                                <?php if (!empty($product['image_url']) && file_exists($product['image_url'])): ?>
                                    <img src="<?= htmlspecialchars($product['image_url']) ?>" class="card-img-top"
                                        alt="<?= htmlspecialchars($product['product_name']) ?>"
                                        style="height: 200px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                <?php endif; ?>

                                <!-- Stock Badge -->
                                <?php if ($product['stock'] <= 5): ?>
                                    <span class="position-absolute top-0 end-0 m-2 badge bg-danger">
                                        Stock: <?= $product['stock'] ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="card-body">
                                <!-- Category -->
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
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="text-primary mb-0">
                                        Rp <?= number_format($product['price'], 0, ',', '.') ?>
                                    </h5>
                                </div>
                            </div>

                            <div class="card-footer bg-white border-0">
                                <a href="index.php?action=product-detail&id=<?= $product['product_id'] ?>"
                                    class="btn btn-outline-primary w-100">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle"></i> Belum ada produk tersedia
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-3">
                <div class="card border-0 bg-transparent">
                    <div class="card-body">
                        <i class="bi bi-truck text-primary" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">Pengiriman Cepat</h5>
                        <p class="text-muted">Pengiriman ke seluruh Indonesia</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 bg-transparent">
                    <div class="card-body">
                        <i class="bi bi-shield-check text-primary" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">Produk Original</h5>
                        <p class="text-muted">100% produk asli bergaransi</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 bg-transparent">
                    <div class="card-body">
                        <i class="bi bi-credit-card text-primary" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">Pembayaran Mudah</h5>
                        <p class="text-muted">Berbagai metode pembayaran</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 bg-transparent">
                    <div class="card-body">
                        <i class="bi bi-headset text-primary" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">Customer Service</h5>
                        <p class="text-muted">Siap membantu Anda 24/7</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'layouts/footer.php'; ?>