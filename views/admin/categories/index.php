<?php
$page_title = "Kelola Kategori - Admin";
include '../layouts/header.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="bi bi-tags"></i> Kelola Kategori</h2>
            <p class="text-muted mb-0">Manage kategori produk</p>
        </div>
        <a href="index.php?action=admin-categories-create" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Kategori
        </a>
    </div>
    
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <?php if (!empty($categories)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="80">ID</th>
                                <th>Nama Kategori</th>
                                <th>Slug</th>
                                <th>Jumlah Produk</th>
                                <th>Dibuat</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td><?= $category['category_id'] ?></td>
                                    <td><strong><?= htmlspecialchars($category['category_name']) ?></strong></td>
                                    <td><code><?= htmlspecialchars($category['slug']) ?></code></td>
                                    <td>
                                        <span class="badge bg-primary"><?= $category['product_count'] ?? 0 ?> produk</span>
                                    </td>
                                    <td><?= date('d M Y', strtotime($category['created_at'])) ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="index.php?action=admin-categories-edit&id=<?= $category['category_id'] ?>" 
                                               class="btn btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="index.php?action=admin-categories-delete&id=<?= $category['category_id'] ?>" 
                                               class="btn btn-outline-danger delete-confirm">
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
                    <p class="text-muted mt-2">Belum ada kategori</p>
                    <a href="index.php?action=admin-categories-create" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Tambah Kategori
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>