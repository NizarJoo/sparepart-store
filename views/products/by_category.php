<?php
$page_title = htmlspecialchars($category['category_name']) . " - Toko Sparepart";
include 'views/layouts/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.php?action=home" class="text-white">Home</a></li>
                <li class="breadcrumb-item"><a href="index.php?action=products" class="text-white">Produk</a></li>
                <li class="breadcrumb-item active text-white"><?= htmlspecialchars($category['category_name']) ?></li>
            </ol>
        </nav>
        <h1 class="display-5 fw-bold mb-2"><?= htmlspecialchars($category['category_name']) ?></h1>
        <?php if (!empty($category['description'])): ?>
            <p class="lead mb-0"><?= htmlspecialchars($category['description']) ?></p>
        <?php endif; ?>
    </div>
</section>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">Menampilkan <?= count($products) ?> Produk</h5>
        <a href="index.php?action=products" class="btn btn-outline-primary">
            <i class="bi bi-grid"></i> Lihat Semua Produk
        </a>
    </div>
    
    <?php if (!empty($products)): ?>
        <div class="row g-4">
            <?php foreach ($products as $product): ?>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100">
                        <div class="position-relative">
                            <?php if (!empty($product['image_url']) && file_exists($product['image_url'])): ?>
                                    <img src="<?= htmlspecialchars($product['image_url']) ?>" 
                                         class="card-img-top" 
                                         alt="<?= htmlspecialchars($product['product_name']) ?>"
                                         style="height: 200px; object-fit: cover;">
                            <?php else: ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                         style="height: 200px;">
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
        <div class="alert alert-info text-center">
            <i class="bi bi-info-circle"></i> Belum ada produk dalam kategori ini
        </div>
    <?php endif; ?>
</div>

<?php include 'views/layouts/footer.php'; ?>