<?php
$page_title = "Login - Toko Sparepart";
include 'views/layouts/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-box-arrow-in-right" style="font-size: 3rem; color: var(--primary-color);"></i>
                        <h2 class="mt-3 fw-bold">Login</h2>
                        <p class="text-muted">Masuk ke akun Anda</p>
                    </div>
                    
                    <form action="index.php?action=login-process" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope"></i> Email
                            </label>
                            <input type="email" 
                                   class="form-control form-control-lg" 
                                   id="email" 
                                   name="email" 
                                   placeholder="nama@email.com"
                                   value="<?= $_SESSION['old']['email'] ?? '' ?>"
                                   required 
                                   autofocus>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock"></i> Password
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control form-control-lg" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Masukkan password"
                                       required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye" id="toggleIcon"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Ingat saya
                            </label>
                        </div>
                        
                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </button>
                        </div>
                        
                        <div class="text-center my-3">
                            <span class="text-muted">atau</span>
                        </div>
                        
                        <div class="text-center">
                            <p class="mb-0">Belum punya akun?</p>
                            <a href="index.php?action=register" class="btn btn-outline-primary w-100 mt-2">
                                <i class="bi bi-person-plus"></i> Buat Akun Baru
                            </a>
                        </div>
                    </form>
                    
                    <!-- <div class="alert alert-info mt-4" role="alert">
                        <strong><i class="bi bi-info-circle"></i> Demo Akun:</strong>
                        <ul class="mb-0 mt-2 small">
                            <li><strong>Admin:</strong> admin@tokosparepart.com / admin123</li>
                            <li><strong>Customer:</strong> customer@example.com / customer123</li>
                        </ul>
                    </div> -->
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

<!-- Toggle Password Visibility Script -->
<script>
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
</script>

<?php
unset($_SESSION['old']); // Clear old input
include 'views/layouts/footer.php';
?>