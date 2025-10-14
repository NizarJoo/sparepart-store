<?php
$page_title = "Tambah Produk - Admin";
include 'views/layouts/header.php';
?>

<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="bi bi-plus-circle"></i> Tambah Produk Baru</h2>
            <p class="text-muted mb-0">Isi form untuk menambah produk</p>
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
                    <form action="index.php?action=admin-products-store" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <!-- Product Name -->
                            <div class="col-md-8 mb-3">
                                <label for="product_name" class="form-label">
                                    Nama Produk <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="product_name" name="product_name"
                                    value="<?= $_SESSION['old']['product_name'] ?? '' ?>" required>
                            </div>

                            <!-- Brand -->
                            <div class="col-md-4 mb-3">
                                <label for="brand" class="form-label">Brand</label>
                                <input type="text" class="form-control" id="brand" name="brand"
                                    value="<?= $_SESSION['old']['brand'] ?? '' ?>">
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
                                            <?= (isset($_SESSION['old']['category_id']) && $_SESSION['old']['category_id'] == $cat['category_id']) ? 'selected' : '' ?>>
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
                                    placeholder="Contoh: SSD-KING-240" value="<?= $_SESSION['old']['sku'] ?? '' ?>"
                                    required>
                                <small class="text-muted">Kode unik produk</small>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi Produk</label>
                            <textarea class="form-control" id="description" name="description"
                                rows="4"><?= $_SESSION['old']['description'] ?? '' ?></textarea>
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
                                        value="<?= $_SESSION['old']['price'] ?? '' ?>" required>
                                </div>
                            </div>

                            <!-- Stock -->
                            <div class="col-md-4 mb-3">
                                <label for="stock" class="form-label">
                                    Stock <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control" id="stock" name="stock" min="0"
                                    value="<?= $_SESSION['old']['stock'] ?? '0' ?>" required>
                            </div>

                            <!-- Status -->
                            <div class="col-md-4 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="active" selected>Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <!-- Product Image -->
                        <div class="mb-4">
                            <label for="product_image" class="form-label">Gambar Produk</label>
                            <input type="file" class="form-control" id="product_image" name="product_image"
                                accept="image/*">
                            <small class="text-muted">Max 2MB. Format: JPG, PNG, WEBP</small>
                        </div>

                        <hr>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Simpan Produk
                            </button>
                            <a href="index.php?action=admin-products" class="btn btn-secondary">
                                <i class="bi bi-x-lg"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Help Card -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold"><i class="bi bi-info-circle"></i> Panduan</h5>
                    <ul class="small">
                        <li>Nama produk harus unik dan jelas</li>
                        <li>SKU harus unik untuk setiap produk</li>
                        <li>Pastikan kategori sudah tersedia</li>
                        <li>Upload gambar dengan kualitas baik</li>
                        <li>Isi deskripsi produk secara detail</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
unset($_SESSION['old']);
include 'views/layouts/footer.php';
?>