<?php
$page_title = "Tambah Kategori - Admin";
include 'views/layouts/header.php';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Tambah Kategori Baru</h5>
                </div>
                <div class="card-body p-4">
                    <form action="index.php?action=admin-categories-store" method="POST">
                        <div class="mb-3">
                            <label for="category_name" class="form-label">
                                Nama Kategori <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="category_name" 
                                   name="category_name" 
                                   placeholder="Contoh: SSD"
                                   required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" 
                                      id="description" 
                                      name="description" 
                                      rows="3"
                                      placeholder="Deskripsi singkat kategori (opsional)"></textarea>
                        </div>
                        
                        <div class="alert alert-info small">
                            <i class="bi bi-info-circle"></i> Slug akan dibuat otomatis dari nama kategori
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Simpan
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

<?php include 'views/layouts/footer.php'; ?>