</main>
<!-- Main Content Ends Here -->

<!-- Footer -->
<footer class="bg-dark text-white mt-5 py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-3">
                <h5><i class="bi bi-pc-display-horizontal"></i> Toko Sparepart</h5>
                <p class="text-white-50">
                    Menyediakan berbagai kebutuhan komputer dan aksesoris berkualitas dengan harga terjangkau.
                </p>
            </div>

            <div class="col-md-4 mb-3">
                <h5>Link Cepat</h5>
                <ul class="list-unstyled">
                    <li><a href="index.php?action=home" class="text-white-50 text-decoration-none">Home</a></li>
                    <li><a href="index.php?action=products" class="text-white-50 text-decoration-none">Produk</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($_SESSION['role'] === 'customer'): ?>
                            <li><a href="index.php?action=cart" class="text-white-50 text-decoration-none">Keranjang</a></li>
                            <li><a href="index.php?action=order-history" class="text-white-50 text-decoration-none">Pesanan
                                    Saya</a></li>
                        <?php else: ?>
                            <li><a href="index.php?action=admin-dashboard" class="text-white-50 text-decoration-none">Dashboard
                                    Admin</a></li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="col-md-4 mb-3">
                <h5>Kontak</h5>
                <ul class="list-unstyled text-white-50">
                    <li><i class="bi bi-geo-alt"></i> Malang, Jawa Timur</li>
                    <li><i class="bi bi-telephone"></i> +62 812-3456-7890</li>
                    <li><i class="bi bi-envelope"></i> info@tokosparepart.com</li>
                </ul>
                <div class="mt-3">
                    <a href="#" class="text-white me-3"><i class="bi bi-facebook fs-4"></i></a>
                    <a href="#" class="text-white me-3"><i class="bi bi-instagram fs-4"></i></a>
                    <a href="#" class="text-white me-3"><i class="bi bi-twitter fs-4"></i></a>
                    <a href="#" class="text-white"><i class="bi bi-whatsapp fs-4"></i></a>
                </div>
            </div>
        </div>

        <hr class="bg-white-50">

        <div class="row">
            <div class="col-12 text-center text-white-50">
                <p class="mb-0">&copy; <?= date('Y') ?> Toko Sparepart Komputer. All Rights Reserved.</p>
                <small>Dibuat untuk Tugas UTS Pemrograman Web</small>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap 5 JS Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JavaScript -->
<script>
    // Auto-hide alerts after 5 seconds
    setTimeout(function () {
        let alerts = document.querySelectorAll('.alert');
        alerts.forEach(function (alert) {
            let bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);

    // Confirm delete actions
    document.querySelectorAll('.delete-confirm').forEach(function (element) {
        element.addEventListener('click', function (e) {
            if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                e.preventDefault();
            }
        });
    });

    // Format currency inputs
    document.querySelectorAll('.currency-input').forEach(function (input) {
        input.addEventListener('blur', function () {
            let value = parseFloat(this.value.replace(/[^0-9.-]+/g, ''));
            if (!isNaN(value)) {
                this.value = value.toLocaleString('id-ID');
            }
        });
    });
</script>
</body>

</html>