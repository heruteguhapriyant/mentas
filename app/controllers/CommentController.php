<?php

class CommentController extends Controller
{
    private $commentModel;

    public function __construct()
    {
        $this->commentModel = new Comment();
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/blog');
            exit;
        }

        $data = [
            'post_id' => $_POST['post_id'],
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'body' => trim($_POST['body'] ?? ''),
            'user_id' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null
        ];

        // If user is logged in, auto-fill
        if (isset($_SESSION['user_id'])) {
            $data['name'] = $_SESSION['user_name'];
            $data['email'] = $_SESSION['user_email']; 
        }

        // Validate
        if (empty($data['name']) || empty($data['email']) || empty($data['body'])) {
            setFlash('error', 'Semua kolom harus diisi');
            header('Location: ' . $_SERVER['HTTP_REFERER']);
             exit;
        }

        if ($this->commentModel->create($data)) {
            setFlash('success', 'Komentar berhasil dikirim');
        } else {
            setFlash('error', 'Gagal mengirim komentar');
        }

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}
