<?php
$page_title = "Edit Produk - Admin";
include 'views/layouts/header.php';
?>

<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="bi bi-pencil-square"></i> Edit Produk</h2>
            <p class="text-muted mb-0">Update informasi produk</p>
        </div>
        <a href="index.php?action=admin-products" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="index.php?action=admin-products-update" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">

                        <div class="row">
                            <!-- Product Name -->
                            <div class="col-md-8 mb-3">
                                <label for="product_name" class="form-label">
                                    Nama Produk <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="product_name" name="product_name"
                                    value="<?= htmlspecialchars($product['product_name']) ?>" required>
                            </div>

                            <!-- Brand -->
                            <div class="col-md-4 mb-3">
                                <label for="brand" class="form-label">Brand</label>
                                <input type="text" class="form-control" id="brand" name="brand"
                                    value="<?= htmlspecialchars($product['brand'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="row">
                            <!-- Category -->
                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label">
                                    Kategori <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="">Pilih Kategori</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['category_id'] ?>"
                                            <?= ($product['category_id'] == $cat['category_id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat['category_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- SKU -->
                            <div class="col-md-6 mb-3">
                                <label for="sku" class="form-label">
                                    SKU <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="sku" name="sku"
                                    value="<?= htmlspecialchars($product['sku']) ?>" required>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi Produk</label>
                            <textarea class="form-control" id="description" name="description"
                                rows="4"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                        </div>

                        <div class="row">
                            <!-- Price -->
                            <div class="col-md-4 mb-3">
                                <label for="price" class="form-label">
                                    Harga <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" id="price" name="price" min="0"
                                        value="<?= $product['price'] ?>" required>
                                </div>
                            </div>

                            <!-- Stock -->
                            <div class="col-md-4 mb-3">
                                <label for="stock" class="form-label">
                                    Stock <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control" id="stock" name="stock" min="0"
                                    value="<?= $product['stock'] ?>" required>
                            </div>

                            <!-- Status -->
                            <div class="col-md-4 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="active" <?= ($product['status'] == 'active') ? 'selected' : '' ?>>Active
                                    </option>
                                    <option value="inactive" <?= ($product['status'] == 'inactive') ? 'selected' : '' ?>>
                                        Inactive</option>
                                </select>
                            </div>
                        </div>

                        <!-- Current Image -->
                        <?php if (!empty($product['image_url']) && file_exists($product['image_url'])): ?>
                            <div class="mb-3">
                                <label class="form-label">Gambar Saat Ini</label>
                                <div>
                                    <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="Current Image"
                                        class="img-thumbnail" style="max-width: 200px;">
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- New Product Image -->
                        <div class="mb-4">
                            <label for="product_image" class="form-label">Ganti Gambar (Opsional)</label>
                            <input type="file" class="form-control" id="product_image" name="product_image"
                                accept="image/*">
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar</small>
                        </div>

                        <hr>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Update Produk
                            </button>
                            <a href="index.php?action=admin-products" class="btn btn-secondary">
                                <i class="bi bi-x-lg"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Product Info Card -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title fw-bold"><i class="bi bi-info-circle"></i> Info Produk</h5>
                    <table class="table table-sm">
                        <tr>
                            <td class="text-muted">Product ID</td>
                            <td><strong><?= $product['product_id'] ?></strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Dibuat</td>
                            <td><?= date('d M Y', strtotime($product['created_at'])) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Terakhir Update</td>
                            <td><?= date('d M Y H:i', strtotime($product['updated_at'])) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>