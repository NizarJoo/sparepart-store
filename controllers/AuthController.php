<?php
/**
 * Auth Controller
 * File: controllers/AuthController.php
 * Handle authentication (login, register, logout)
 */

require_once 'models/User.php';

class AuthController
{
    private $db;
    private $userModel;

    public function __construct($db)
    {
        $this->db = $db;
        $this->userModel = new User($db);
    }

    // ==================== LOGIN ====================

    /**
     * Show login form
     */
    public function showLogin()
    {
        // If already logged in, redirect
        if (isset($_SESSION['user_id'])) {
            $this->redirectByRole();
            return;
        }

        include 'views/auth/login.php';
    }

    /**
     * Process login
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=login');
            exit;
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Validation
        if (empty($email) || empty($password)) {
            $_SESSION['error'] = "Email dan password harus diisi!";
            header('Location: index.php?action=login');
            exit;
        }

        // Attempt login
        $user = $this->userModel->login($email, $password);

        if ($user) {
            // Set session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            $_SESSION['success'] = "Login berhasil! Selamat datang, {$user['full_name']}";

            // Redirect by role
            $this->redirectByRole();
        } else {
            $_SESSION['error'] = "Email atau password salah!";
            header('Location: index.php?action=login');
        }

        exit;
    }

    // ==================== REGISTER ====================

    /**
     * Show register form
     */
    public function showRegister()
    {
        // If already logged in, redirect
        if (isset($_SESSION['user_id'])) {
            $this->redirectByRole();
            return;
        }

        include 'views/auth/register.php';
    }

    /**
     * Process registration
     */
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=register');
            exit;
        }

        // Get form data
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $full_name = trim($_POST['full_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');

        // Validation
        $errors = [];

        if (empty($username)) {
            $errors[] = "Username harus diisi!";
        }

        if (empty($email)) {
            $errors[] = "Email harus diisi!";
        } elseif (!$this->userModel->isValidEmail($email)) {
            $errors[] = "Format email tidak valid!";
        } elseif ($this->userModel->isEmailExists($email)) {
            $errors[] = "Email sudah terdaftar!";
        }

        if ($this->userModel->isUsernameExists($username)) {
            $errors[] = "Username sudah dipakai!";
        }

        if (empty($password)) {
            $errors[] = "Password harus diisi!";
        } elseif (strlen($password) < 6) {
            $errors[] = "Password minimal 6 karakter!";
        }

        if ($password !== $confirm_password) {
            $errors[] = "Password dan konfirmasi password tidak sama!";
        }

        if (empty($full_name)) {
            $errors[] = "Nama lengkap harus diisi!";
        }

        // If has errors, redirect back
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            header('Location: index.php?action=register');
            exit;
        }

        // Create user
        $this->userModel->username = $username;
        $this->userModel->email = $email;
        $this->userModel->password = $password;
        $this->userModel->full_name = $full_name;
        $this->userModel->phone = $phone;
        $this->userModel->address = $address;
        $this->userModel->role = 'customer';

        if ($this->userModel->register()) {
            $_SESSION['success'] = "Registrasi berhasil! Silakan login.";
            header('Location: index.php?action=login');
        } else {
            $_SESSION['error'] = "Registrasi gagal! Silakan coba lagi.";
            header('Location: index.php?action=register');
        }

        exit;
    }

    // ==================== LOGOUT ====================

    /**
     * Logout user
     */
    public function logout()
    {
        // Destroy session
        session_destroy();

        // Redirect to home
        header('Location: index.php');
        exit;
    }

    // ==================== PROFILE ====================

    /**
     * Show user profile
     */
    public function profile()
    {
        $this->requireLogin();

        $user = $this->userModel->getById($_SESSION['user_id']);

        include 'views/auth/profile.php';
    }

    /**
     * Update profile
     */
    public function updateProfile()
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=profile');
            exit;
        }

        $full_name = trim($_POST['full_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');

        // Validation
        if (empty($full_name)) {
            $_SESSION['error'] = "Nama lengkap harus diisi!";
            header('Location: index.php?action=profile');
            exit;
        }

        // Update
        $this->userModel->user_id = $_SESSION['user_id'];
        $this->userModel->full_name = $full_name;
        $this->userModel->phone = $phone;
        $this->userModel->address = $address;

        if ($this->userModel->update()) {
            $_SESSION['full_name'] = $full_name;
            $_SESSION['success'] = "Profile berhasil diupdate!";
        } else {
            $_SESSION['error'] = "Gagal update profile!";
        }

        header('Location: index.php?action=profile');
        exit;
    }

    /**
     * Change password
     */
    public function changePassword()
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=profile');
            exit;
        }

        $old_password = $_POST['old_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Get current user
        $user = $this->userModel->getByEmail($_SESSION['email']);

        // Verify old password
        if (!password_verify($old_password, $user['password'])) {
            $_SESSION['error'] = "Password lama salah!";
            header('Location: index.php?action=profile');
            exit;
        }

        // Validate new password
        if (strlen($new_password) < 6) {
            $_SESSION['error'] = "Password baru minimal 6 karakter!";
            header('Location: index.php?action=profile');
            exit;
        }

        if ($new_password !== $confirm_password) {
            $_SESSION['error'] = "Password baru dan konfirmasi tidak sama!";
            header('Location: index.php?action=profile');
            exit;
        }

        // Update password
        if ($this->userModel->updatePassword($_SESSION['user_id'], $new_password)) {
            $_SESSION['success'] = "Password berhasil diubah!";
        } else {
            $_SESSION['error'] = "Gagal ubah password!";
        }

        header('Location: index.php?action=profile');
        exit;
    }

    // ==================== HELPER METHODS ====================

    /**
     * Redirect based on user role
     */
    private function redirectByRole()
    {
        if ($_SESSION['role'] === 'admin') {
            header('Location: index.php?action=admin-dashboard');
        } else {
            header('Location: index.php?action=home');
        }
    }

    /**
     * Require user to be logged in
     */
    private function requireLogin()
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Silakan login terlebih dahulu!";
            header('Location: index.php?action=login');
            exit;
        }
    }

    /**
     * Require admin role
     */
    public static function requireAdmin()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = "Akses ditolak! Anda bukan admin.";
            header('Location: index.php?action=home');
            exit;
        }
    }
}
?>