<?php
$keyword = $_GET['keyword'] ?? '';
$page_title = "Hasil Pencarian: " . htmlspecialchars($keyword) . " - Toko Sparepart";
include '../layouts/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1 class="display-5 fw-bold mb-2">Hasil Pencarian</h1>
        <p class="lead mb-0">Hasil pencarian untuk: <strong>"<?= htmlspecialchars($keyword) ?>"</strong></p>
    </div>
</section>

<div class="container py-5">
    <!-- Search Again -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="index.php" method="GET">
                <input type="hidden" name="action" value="products-search">
                <div class="input-group input-group-lg">
                    <input type="text" class="form-control" name="keyword" placeholder="Cari produk lain..."
                        value="<?= htmlspecialchars($keyword) ?>" required>
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search"></i> Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Count -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">
            <?php if (count($products) > 0): ?>
                Ditemukan <strong><?= count($products) ?></strong> produk
            <?php else: ?>
                Tidak ada hasil ditemukan
            <?php endif; ?>
        </h5>
        <a href="index.php?action=products" class="btn btn-outline-primary">
            <i class="bi bi-grid"></i> Lihat Semua Produk
        </a>
    </div>

    <!-- Products Grid -->
    <?php if (!empty($products)): ?>
        <div class="row g-4">
            <?php foreach ($products as $product): ?>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100">
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
                            <span class="badge bg-primary mb-2">
                                <?= htmlspecialchars($product['category_name'] ?? 'Uncategorized') ?>
                            </span>

                            <h6 class="card-title">
                                <a href="index.php?action=product-detail&id=<?= $product['product_id'] ?>"
                                    class="text-decoration-none text-dark">
                                    <?= htmlspecialchars($product['product_name']) ?>
                                </a>
                            </h6>

                            <?php if (!empty($product['brand'])): ?>
                                <p class="text-muted small mb-2">
                                    <i class="bi bi-tag"></i> <?= htmlspecialchars($product['brand']) ?>
                                </p>
                            <?php endif; ?>

                            <h5 class="text-primary mb-0">
                                Rp <?= number_format($product['price'], 0, ',', '.') ?>
                            </h5>
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
        <!-- No Results -->
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card text-center">
                    <div class="card-body py-5">
                        <i class="bi bi-search text-muted" style="font-size: 5rem;"></i>
                        <h4 class="mt-4">Produk Tidak Ditemukan</h4>
                        <p class="text-muted mb-4">
                            Maaf, kami tidak menemukan produk yang cocok dengan pencarian Anda.
                        </p>

                        <div class="d-flex flex-column gap-2 px-5">
                            <p class="text-start mb-2"><strong>Saran:</strong></p>
                            <ul class="text-start text-muted">
                                <li>Periksa ejaan kata kunci Anda</li>
                                <li>Gunakan kata kunci yang lebih umum</li>
                                <li>Coba kata kunci yang berbeda</li>
                            </ul>
                        </div>

                        <div class="mt-4">
                            <a href="index.php?action=products" class="btn btn-primary">
                                <i class="bi bi-grid"></i> Lihat Semua Produk
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include '../layouts/footer.php'; ?>