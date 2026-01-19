<?php

/**
 * ContributorController - Contributor Panel
 */
class ContributorController extends Controller
{
    private $postModel;
    private $categoryModel;

    public function __construct()
    {
        requireContributor();
        $this->postModel = new Post();
        $this->categoryModel = new Category();
    }

    /**
     * Dashboard
     */
    public function index()
    {
        $userId = $_SESSION['user_id'];
        $posts = $this->postModel->getByAuthor($userId);
        
        $stats = [
            'published' => count(array_filter($posts, fn($p) => $p['status'] === 'published')),
            'drafts' => count(array_filter($posts, fn($p) => $p['status'] === 'draft')),
        ];

        return $this->view('contributor/dashboard', [
            'posts' => $posts,
            'stats' => $stats
        ]);
    }

    /**
     * Create post form
     */
    public function create()
    {
        $categories = $this->categoryModel->all();
        return $this->view('contributor/form', ['post' => null, 'categories' => $categories]);
    }

    /**
     * Store new post
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/contributor');
            exit;
        }

        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'excerpt' => trim($_POST['excerpt'] ?? ''),
            'body' => $_POST['body'] ?? '',
            'category_id' => $_POST['category_id'] ?: null,
            'author_id' => $_SESSION['user_id'],
            'status' => $_POST['status'] ?? 'draft'
        ];

        if (empty($data['title'])) {
            setFlash('error', 'Judul harus diisi');
            header('Location: ' . BASE_URL . '/contributor/create');
            exit;
        }

        // Handle cover image upload
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../public/uploads/posts/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $ext = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['cover_image']['tmp_name'], $uploadDir . $filename);
            $data['cover_image'] = 'uploads/posts/' . $filename;
        }

        $this->postModel->create($data);
        setFlash('success', 'Artikel berhasil dibuat');
        header('Location: ' . BASE_URL . '/contributor');
        exit;
    }

    /**
     * Edit post form
     */
    public function edit($id)
    {
        $post = $this->postModel->find($id);
        
        // Check ownership
        if (!$post || $post['author_id'] != $_SESSION['user_id']) {
            setFlash('error', 'Artikel tidak ditemukan');
            header('Location: ' . BASE_URL . '/contributor');
            exit;
        }

        $categories = $this->categoryModel->all();
        return $this->view('contributor/form', ['post' => $post, 'categories' => $categories]);
    }

    /**
     * Update post
     */
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/contributor');
            exit;
        }

        $post = $this->postModel->find($id);
        
        // Check ownership
        if (!$post || $post['author_id'] != $_SESSION['user_id']) {
            setFlash('error', 'Artikel tidak ditemukan');
            header('Location: ' . BASE_URL . '/contributor');
            exit;
        }

        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'excerpt' => trim($_POST['excerpt'] ?? ''),
            'body' => $_POST['body'] ?? '',
            'category_id' => $_POST['category_id'] ?: null,
            'status' => $_POST['status'] ?? 'draft'
        ];

        // Handle cover image upload
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../public/uploads/posts/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $ext = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['cover_image']['tmp_name'], $uploadDir . $filename);
            $data['cover_image'] = 'uploads/posts/' . $filename;
        }

        $this->postModel->update($id, $data);
        setFlash('success', 'Artikel berhasil diupdate');
        header('Location: ' . BASE_URL . '/contributor');
        exit;
    }

    /**
     * Delete post
     */
    public function delete($id)
    {
        $post = $this->postModel->find($id);
        
        // Check ownership
        if (!$post || $post['author_id'] != $_SESSION['user_id']) {
            setFlash('error', 'Artikel tidak ditemukan');
            header('Location: ' . BASE_URL . '/contributor');
            exit;
        }

        $this->postModel->delete($id);
        setFlash('success', 'Artikel berhasil dihapus');
        header('Location: ' . BASE_URL . '/contributor');
        exit;
    }
}
