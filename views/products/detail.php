<?php
$page_title = htmlspecialchars($product['product_name']) . " - Toko Sparepart";
include '../layouts/header.php';
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-3">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="index.php?action=home">Home</a></li>
            <li class="breadcrumb-item"><a href="index.php?action=products">Produk</a></li>
            <?php if (!empty($product['category_name'])): ?>
                <li class="breadcrumb-item">
                    <a href="index.php?action=products-by-category&category_id=<?= $product['category_id'] ?>">
                        <?= htmlspecialchars($product['category_name']) ?>
                    </a>
                </li>
            <?php endif; ?>
            <li class="breadcrumb-item active"><?= htmlspecialchars($product['product_name']) ?></li>
        </ol>
    </div>
</nav>

<div class="container py-5">
    <div class="row">
        <!-- Product Image -->
        <div class="col-lg-5 mb-4">
            <div class="card">
                <div class="card-body p-4">
                    <?php if (!empty($product['image_url']) && file_exists($product['image_url'])): ?>
                            <img src="<?= htmlspecialchars($product['image_url']) ?>" 
                                 class="img-fluid rounded" 
                                 alt="<?= htmlspecialchars($product['product_name']) ?>"
                                 style="width: 100%; max-height: 500px; object-fit: contain;">
                    <?php else: ?>
                            <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                 style="height: 400px;">
                                <i class="bi bi-image text-muted" style="font-size: 6rem;"></i>
                            </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Product Info -->
        <div class="col-lg-7">
            <div class="card">
                <div class="card-body p-4">
                    <!-- Category Badge -->
                    <span class="badge bg-primary mb-2">
                        <?= htmlspecialchars($product['category_name'] ?? 'Uncategorized') ?>
                    </span>
                    
                    <!-- Product Name -->
                    <h2 class="fw-bold mb-3"><?= htmlspecialchars($product['product_name']) ?></h2>
                    
                    <!-- Brand & SKU -->
                    <div class="mb-3">
                        <?php if (!empty($product['brand'])): ?>
                            <p class="mb-1">
                                <strong><i class="bi bi-tag"></i> Brand:</strong> 
                                <?= htmlspecialchars($product['brand']) ?>
                            </p>
                        <?php endif; ?>
                        <p class="mb-1">
                            <strong><i class="bi bi-upc"></i> SKU:</strong> 
                            <?= htmlspecialchars($product['sku']) ?>
                        </p>
                    </div>
                    
                    <hr>
                    
                    <!-- Price -->
                    <h3 class="text-primary mb-3">
                        Rp <?= number_format($product['price'], 0, ',', '.') ?>
                    </h3>
                    
                    <!-- Stock Status -->
                    <div class="mb-4">
                        <?php if ($product['stock'] > 0): ?>
                                <span class="badge bg-success fs-6">
                                    <i class="bi bi-check-circle"></i> Tersedia (Stock: <?= $product['stock'] ?>)
                                </span>
                        <?php else: ?>
                                <span class="badge bg-danger fs-6">
                                    <i class="bi bi-x-circle"></i> Stok Habis
                                </span>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Add to Cart Form -->
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'customer'): ?>
                            <?php if ($product['stock'] > 0): ?>
                                <form action="index.php?action=cart-add" method="POST" class="mb-4">
                                    <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                    <input type="hidden" name="redirect" value="product-detail">
                            
                                    <div class="row g-2 align-items-end">
                                        <div class="col-md-4">
                                            <label for="quantity" class="form-label">Jumlah</label>
                                            <input type="number" 
                                                   class="form-control form-control-lg" 
                                                   id="quantity" 
                                                   name="quantity" 
                                                   value="1" 
                                                   min="1" 
                                                   max="<?= $product['stock'] ?>"
                                                   required>
                                        </div>
                                        <div class="col-md-8">
                                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                                <i class="bi bi-cart-plus"></i> Tambah ke Keranjang
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            <?php else: ?>
                                <div class="alert alert-danger">
                                    <i class="bi bi-exclamation-triangle"></i> Produk ini sedang tidak tersedia
                                </div>
                            <?php endif; ?>
                    <?php else: ?>
                            <div class="alert alert-warning">
                                <i class="bi bi-info-circle"></i> 
                                <a href="index.php?action=login" class="alert-link">Login</a> 
                                untuk membeli produk ini
                            </div>
                    <?php endif; ?>
                    
                    <!-- Product Description -->
                    <?php if (!empty($product['description'])): ?>
                        <div class="mt-4">
                            <h5 class="fw-bold mb-3"><i class="bi bi-info-circle"></i> Deskripsi Produk</h5>
                            <p class="text-muted"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Related Products -->
    <?php if (!empty($related_products)): ?>
        <div class="mt-5">
            <h4 class="fw-bold mb-4"><i class="bi bi-grid"></i> Produk Terkait</h4>
        
            <div class="row g-4">
                <?php foreach (array_slice($related_products, 0, 4) as $related): ?>
                    <div class="col-md-3">
                        <div class="card h-100">
                            <div class="position-relative">
                                <?php if (!empty($related['image_url']) && file_exists($related['image_url'])): ?>
                                        <img src="<?= htmlspecialchars($related['image_url']) ?>" 
                                             class="card-img-top" 
                                             alt="<?= htmlspecialchars($related['product_name']) ?>"
                                             style="height: 180px; object-fit: cover;">
                                <?php else: ?>
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                             style="height: 180px;">
                                            <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                        </div>
                                <?php endif; ?>
                            </div>
                    
                            <div class="card-body">
                                <h6 class="card-title">
                                    <a href="index.php?action=product-detail&id=<?= $related['product_id'] ?>" 
                                       class="text-decoration-none text-dark">
                                        <?= htmlspecialchars($related['product_name']) ?>
                                    </a>
                                </h6>
                                <h6 class="text-primary">
                                    Rp <?= number_format($related['price'], 0, ',', '.') ?>
                                </h6>
                            </div>
                    
                            <div class="card-footer bg-white border-0">
                                <a href="index.php?action=product-detail&id=<?= $related['product_id'] ?>" 
                                   class="btn btn-outline-primary btn-sm w-100">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include '../layouts/footer.php'; ?>