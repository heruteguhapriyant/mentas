<?php

/**
 * AuthController - Login & Register
 */
class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Show login page
     */
    public function login()
    {
        if (isLoggedIn()) {
            $this->redirectByRole();
        }

        return $this->view('auth/login');
    }

    /**
     * Process login
     */
    public function doLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            setFlash('error', 'Email dan password harus diisi');
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        $user = $this->userModel->verifyPassword($email, $password);

        if (!$user) {
            setFlash('error', 'Email atau password salah');
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        if ($user['status'] === 'pending') {
            setFlash('error', 'Akun Anda masih menunggu persetujuan admin');
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        if ($user['status'] === 'rejected') {
            setFlash('error', 'Akun Anda ditolak. Hubungi admin untuk info lebih lanjut');
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        loginUser($user);
        setFlash('success', 'Selamat datang, ' . $user['name']);
        $this->redirectByRole();
    }

    /**
     * Show register page
     */
    public function register()
    {
        if (isLoggedIn()) {
            $this->redirectByRole();
        }

        return $this->view('auth/register');
    }

    /**
     * Process registration
     */
    public function doRegister()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/auth/register');
            exit;
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'phone' => trim($_POST['phone'] ?? ''),
            'bio' => trim($_POST['bio'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
        ];

        // Validation
        $errors = [];

        if (empty($data['name'])) {
            $errors[] = 'Nama harus diisi';
        }

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email tidak valid';
        }

        if (strlen($data['password']) < 6) {
            $errors[] = 'Password minimal 6 karakter';
        }

        if ($_POST['password'] !== $_POST['password_confirm']) {
            $errors[] = 'Konfirmasi password tidak cocok';
        }

        // Check email exists
        if ($this->userModel->findByEmail($data['email'])) {
            $errors[] = 'Email sudah terdaftar';
        }

        if (!empty($errors)) {
            setFlash('error', implode('<br>', $errors));
            header('Location: ' . BASE_URL . '/auth/register');
            exit;
        }

        // Handle avatar upload
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../public/uploads/avatars/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadDir . $filename);
            $data['avatar'] = 'uploads/avatars/' . $filename;
        }

        $this->userModel->create($data);

        setFlash('success', 'Registrasi berhasil! Silakan tunggu persetujuan admin.');
        header('Location: ' . BASE_URL . '/auth/login');
        exit;
    }

    /**
     * Logout
     */
    public function logout()
    {
        logoutUser();
        setFlash('success', 'Anda telah logout');
        header('Location: ' . BASE_URL);
        exit;
    }

    /**
     * Redirect based on user role
     */
    private function redirectByRole()
    {
        if (isAdmin()) {
            header('Location: ' . BASE_URL . '/admin');
        } else {
            header('Location: ' . BASE_URL . '/contributor');
        }
        exit;
    }
}
