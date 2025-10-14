<?php
$page_title = "Edit Kategori - Admin";
include '../layouts/header.php';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Edit Kategori</h5>
                </div>
                <div class="card-body p-4">
                    <form action="index.php?action=admin-categories-update" method="POST">
                        <input type="hidden" name="category_id" value="<?= $category['category_id'] ?>">

                        <div class="mb-3">
                            <label for="category_name" class="form-label">
                                Nama Kategori <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="category_name" name="category_name"
                                value="<?= htmlspecialchars($category['category_name']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description"
                                rows="3"><?= htmlspecialchars($category['description'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Slug Saat Ini</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($category['slug']) ?>"
                                readonly>
                            <small class="text-muted">Slug akan di-update otomatis</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-save"></i> Update
                            </button>
                            <a href="index.php?action=admin-categories" class="btn btn-secondary">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>