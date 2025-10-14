<?php
$page_title = "Profile - Toko Sparepart";
include 'views/layouts/header.php';
?>

<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-person-circle" style="font-size: 5rem; color: var(--primary-color);"></i>
                    </div>
                    <h5 class="card-title"><?= htmlspecialchars($user['full_name']) ?></h5>
                    <p class="text-muted mb-0"><?= htmlspecialchars($user['email']) ?></p>
                    <span class="badge bg-primary mt-2"><?= ucfirst($user['role']) ?></span>
                </div>
            </div>

            <div class="list-group mt-3">
                <a href="#profile-info" class="list-group-item list-group-item-action active" data-bs-toggle="list">
                    <i class="bi bi-person"></i> Informasi Profile
                </a>
                <a href="#change-password" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="bi bi-key"></i> Ubah Password
                </a>
                <?php if ($user['role'] === 'customer'): ?>
                    <a href="index.php?action=order-history" class="list-group-item list-group-item-action">
                        <i class="bi bi-bag-check"></i> Riwayat Pesanan
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="tab-content">
                <!-- Profile Info Tab -->
                <div class="tab-pane fade show active" id="profile-info">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-person"></i> Informasi Profile</h5>
                        </div>
                        <div class="card-body">
                            <form action="index.php?action=profile-update" method="POST">
                                <!-- Full Name -->
                                <div class="mb-3">
                                    <label for="full_name" class="form-label">
                                        <i class="bi bi-person-badge"></i> Nama Lengkap
                                    </label>
                                    <input type="text" class="form-control" id="full_name" name="full_name"
                                        value="<?= htmlspecialchars($user['full_name']) ?>" required>
                                </div>

                                <!-- Username (readonly) -->
                                <div class="mb-3">
                                    <label for="username" class="form-label">
                                        <i class="bi bi-person"></i> Username
                                    </label>
                                    <input type="text" class="form-control" id="username"
                                        value="<?= htmlspecialchars($user['username']) ?>" readonly>
                                    <small class="text-muted">Username tidak bisa diubah</small>
                                </div>

                                <!-- Email (readonly) -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        <i class="bi bi-envelope"></i> Email
                                    </label>
                                    <input type="email" class="form-control" id="email"
                                        value="<?= htmlspecialchars($user['email']) ?>" readonly>
                                    <small class="text-muted">Email tidak bisa diubah</small>
                                </div>

                                <!-- Phone -->
                                <div class="mb-3">
                                    <label for="phone" class="form-label">
                                        <i class="bi bi-telephone"></i> No. Telepon
                                    </label>
                                    <input type="tel" class="form-control" id="phone" name="phone"
                                        value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                                </div>

                                <!-- Address -->
                                <div class="mb-3">
                                    <label for="address" class="form-label">
                                        <i class="bi bi-geo-alt"></i> Alamat
                                    </label>
                                    <textarea class="form-control" id="address" name="address"
                                        rows="3"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                                </div>

                                <!-- Member Since -->
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="bi bi-calendar-check"></i> Member Sejak
                                    </label>
                                    <input type="text" class="form-control"
                                        value="<?= date('d F Y', strtotime($user['created_at'])) ?>" readonly>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Change Password Tab -->
                <div class="tab-pane fade" id="change-password">
                    <div class="card">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="bi bi-key"></i> Ubah Password</h5>
                        </div>
                        <div class="card-body">
                            <form action="index.php?action=change-password" method="POST">
                                <!-- Old Password -->
                                <div class="mb-3">
                                    <label for="old_password" class="form-label">
                                        <i class="bi bi-lock"></i> Password Lama
                                    </label>
                                    <input type="password" class="form-control" id="old_password" name="old_password"
                                        required>
                                </div>

                                <!-- New Password -->
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">
                                        <i class="bi bi-lock-fill"></i> Password Baru
                                    </label>
                                    <input type="password" class="form-control" id="new_password" name="new_password"
                                        minlength="6" required>
                                    <small class="text-muted">Minimal 6 karakter</small>
                                </div>

                                <!-- Confirm New Password -->
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">
                                        <i class="bi bi-shield-check"></i> Konfirmasi Password Baru
                                    </label>
                                    <input type="password" class="form-control" id="confirm_password"
                                        name="confirm_password" required>
                                </div>

                                <!-- Alert Info -->
                                <div class="alert alert-warning" role="alert">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    Pastikan Anda mengingat password baru Anda!
                                </div>

                                <!-- Submit Button -->
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="bi bi-key"></i> Ubah Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Password Match Validation -->
<script>
    document.querySelector('#change-password form').addEventListener('submit', function (e) {
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;

        if (newPassword !== confirmPassword) {
            e.preventDefault();
            alert('Password baru dan konfirmasi password tidak sama!');
            document.getElementById('confirm_password').focus();
        }
    });
</script>

<?php include 'views/layouts/footer.php'; ?>