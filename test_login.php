<?php
session_start();
require_once 'config/database.php';
require_once 'models/User.php';

$database = new Database();
$db = $database->conn;
$userModel = new User($db);

echo "<h2>🔍 Debug Login</h2>";

// Get user from database
$email = 'admin@tokosparepart.com';
$password = 'admin123';
echo password_hash('admin123', PASSWORD_BCRYPT);


echo "<h3>1. Cek Data User di Database</h3>";
$query = "SELECT * FROM users WHERE email = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    echo "✅ User ditemukan!<br>";
    echo "- User ID: {$user['user_id']}<br>";
    echo "- Email: {$user['email']}<br>";
    echo "- Role: {$user['role']}<br>";
    echo "- Password Hash: <code>{$user['password']}</code><br>";
} else {
    echo "❌ User TIDAK ditemukan!<br>";
    echo "<p><strong>ACTION:</strong> Insert data admin dulu!</p>";
    die();
}

echo "<hr>";

echo "<h3>2. Test Password Verify</h3>";
echo "- Password input: <strong>{$password}</strong><br>";
echo "- Password hash: <code>{$user['password']}</code><br>";

$verify = password_verify($password, $user['password']);

if ($verify) {
    echo "<p style='color: green; font-size: 20px;'>✅ PASSWORD COCOK!</p>";
} else {
    echo "<p style='color: red; font-size: 20px;'>❌ PASSWORD TIDAK COCOK!</p>";
    echo "<p><strong>ACTION:</strong> Update password hash di database!</p>";
}

echo "<hr>";

echo "<h3>3. Test Login Method</h3>";
$loginResult = $userModel->login($email, $password);

if ($loginResult) {
    echo "<p style='color: green; font-size: 20px;'>✅ LOGIN METHOD BERHASIL!</p>";
    echo "<pre>";
    print_r($loginResult);
    echo "</pre>";
} else {
    echo "<p style='color: red; font-size: 20px;'>❌ LOGIN METHOD GAGAL!</p>";
}

echo "<hr>";
echo "<h3>📝 Kesimpulan</h3>";
if ($verify && $loginResult) {
    echo "<p style='color: green;'>✅ Semua OK! Login harusnya bisa jalan.</p>";
    echo "<p><a href='index.php?action=login' class='btn btn-primary'>Coba Login Lagi</a></p>";
} else {
    echo "<p style='color: red;'>❌ Ada masalah! Ikuti ACTION di atas.</p>";
}
?>
```

**Cara pakai:**
1. Save sebagai `test_login_debug.php` di root project
2. Akses: `http://localhost/toko-sparepart/test_login_debug.php`
3. Liat hasilnya, kasih tau gw:
- Apakah user ditemukan?
- Apakah password verify sukses?
- Apakah login method berhasil?

---

## **CEK 3: KEMUNGKINAN TYPO**

Pastikan **GA ADA SPASI** di email/password:

❌ **SALAH:**
```
Email: admin@tokosparepart.com ← ada spasi di belakang
Password: admin123 ← ada spasi
```

✅ **BENAR:**
```
Email: admin@tokosparepart.com
Password: admin123