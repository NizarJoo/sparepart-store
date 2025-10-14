<?php
$page_title = "Register - Toko Sparepart";
include 'views/layouts/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <!-- Header -->
                    <div class="text-center mb-4">
                        <i class="bi bi-person-plus" style="font-size: 3rem; color: var(--primary-color);"></i>
                        <h2 class="mt-3 fw-bold">Buat Akun Baru</h2>
                        <p class="text-muted">Daftar untuk mulai berbelanja</p>
                    </div>
                    
                    <!-- Register Form -->
                    <form action="index.php?action=register-process" method="POST">
                        <div class="row">
                            <!-- Username -->
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">
                                    <i class="bi bi-person"></i> Username <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="username" 
                                       name="username" 
                                       placeholder="username"
                                       value="<?= $_SESSION['old']['username'] ?? '' ?>"
                                       required>
                            </div>
                            
                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope"></i> Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" 
                                       class="form-control" 
                                       id="email" 
                                       name="email" 
                                       placeholder="nama@email.com"
                                       value="<?= $_SESSION['old']['email'] ?? '' ?>"
                                       required>
                            </div>
                        </div>
                        
                        <!-- Full Name -->
                        <div class="mb-3">
                            <label for="full_name" class="form-label">
                                <i class="bi bi-person-badge"></i> Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="full_name" 
                                   name="full_name" 
                                   placeholder="Nama lengkap Anda"
                                   value="<?= $_SESSION['old']['full_name'] ?? '' ?>"
                                   required>
                        </div>
                        
                        <div class="row">
                            <!-- Password -->
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock"></i> Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Min. 6 karakter"
                                           minlength="6"
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Minimal 6 karakter</small>
                            </div>
                            
                            <!-- Confirm Password -->
                            <div class="col-md-6 mb-3">
                                <label for="confirm_password" class="form-label">
                                    <i class="bi bi-lock-fill"></i> Konfirmasi Password <span class="text-danger">*</span>
                                </label>
                                <input type="password" 
                                       class="form-control" 
                                       id="confirm_password" 
                                       name="confirm_password" 
                                       placeholder="Ulangi password"
                                       required>
                            </div>
                        </div>
                        
                        <!-- Phone -->
                        <div class="mb-3">
                            <label for="phone" class="form-label">
                                <i class="bi bi-telephone"></i> No. Telepon
                            </label>
                            <input type="tel" 
                                   class="form-control" 
                                   id="phone" 
                                   name="phone" 
                                   placeholder="08xxxxxxxxxx"
                                   value="<?= $_SESSION['old']['phone'] ?? '' ?>">
                        </div>
                        
                        <!-- Address -->
                        <div class="mb-3">
                            <label for="address" class="form-label">
                                <i class="bi bi-geo-alt"></i> Alamat
                            </label>
                            <textarea class="form-control" 
                                      id="address" 
                                      name="address" 
                                      rows="2" 
                                      placeholder="Alamat lengkap Anda"><?= $_SESSION['old']['address'] ?? '' ?></textarea>
                        </div>
                        
                        <!-- Terms -->
                        <!-- <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" required>
                            <label class="form-check-label" for="terms">
                                Saya setuju dengan <a href="#">syarat dan ketentuan</a>
                            </label>
                        </div> -->
                        
                        <!-- Submit Button -->
                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-person-plus"></i> Daftar Sekarang
                            </button>
                        </div>
                        
                        <!-- Login Link -->
                        <div class="text-center">
                            <p class="mb-0">Sudah punya akun?</p>
                            <a href="index.php?action=login" class="btn btn-outline-primary w-100 mt-2">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Back to Home -->
            <div class="text-center mt-3">
                <a href="index.php?action=home" class="text-decoration-none">
                    <i class="bi bi-arrow-left"></i> Kembali ke Home
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Toggle Password & Validation Script -->
<script>
    // Toggle Password Visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('bi-eye');
            toggleIcon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('bi-eye-slash');
            toggleIcon.classList.add('bi-eye');
        }
    });
    
    // Password Match Validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Password dan konfirmasi password tidak sama!');
            document.getElementById('confirm_password').focus();
        }
    });
</script>

<?php
unset($_SESSION['old']); // Clear old input
include 'views/layouts/footer.php';
?>