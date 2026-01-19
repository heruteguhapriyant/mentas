<?php

/**
 * AdminController - Admin Panel
 */
class AdminController extends Controller
{
    private $userModel;
    private $categoryModel;
    private $postModel;

    public function __construct()
    {
        requireAdmin();
        $this->userModel = new User();
        $this->categoryModel = new Category();
        $this->postModel = new Post();
    }

    /**
     * Dashboard
     */
    public function index()
    {
        $stats = [
            'posts' => $this->postModel->count('published'),
            'drafts' => $this->postModel->count('draft'),
            'categories' => count($this->categoryModel->all(false)),
            'pending_contributors' => count($this->userModel->getPendingContributors()),
            'active_contributors' => count($this->userModel->getActiveContributors()),
        ];

        $recentPosts = $this->postModel->getRecent(5);
        $pendingContributors = $this->userModel->getPendingContributors();

        return $this->view('admin/dashboard', [
            'stats' => $stats,
            'recentPosts' => $recentPosts,
            'pendingContributors' => $pendingContributors
        ]);
    }

    // =====================
    // CATEGORY MANAGEMENT
    // =====================

    public function categories()
    {
        $categories = $this->categoryModel->getWithPostCount();
        return $this->view('admin/categories/index', ['categories' => $categories]);
    }

    public function categoryCreate()
    {
        return $this->view('admin/categories/form', ['category' => null]);
    }

    public function categoryStore()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/categories');
            exit;
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];

        if (empty($data['name'])) {
            setFlash('error', 'Nama kategori harus diisi');
            header('Location: ' . BASE_URL . '/admin/categoryCreate');
            exit;
        }

        $this->categoryModel->create($data);
        setFlash('success', 'Kategori berhasil ditambahkan');
        header('Location: ' . BASE_URL . '/admin/categories');
        exit;
    }

    public function categoryEdit($id)
    {
        $category = $this->categoryModel->find($id);
        if (!$category) {
            setFlash('error', 'Kategori tidak ditemukan');
            header('Location: ' . BASE_URL . '/admin/categories');
            exit;
        }

        return $this->view('admin/categories/form', ['category' => $category]);
    }

    public function categoryUpdate($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/categories');
            exit;
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];

        $this->categoryModel->update($id, $data);
        setFlash('success', 'Kategori berhasil diupdate');
        header('Location: ' . BASE_URL . '/admin/categories');
        exit;
    }

    public function categoryDelete($id)
    {
        $this->categoryModel->delete($id);
        setFlash('success', 'Kategori berhasil dihapus');
        header('Location: ' . BASE_URL . '/admin/categories');
        exit;
    }

    // =====================
    // USER/CONTRIBUTOR MANAGEMENT
    // =====================

    public function users()
    {
        $users = $this->userModel->all();
        return $this->view('admin/users/index', ['users' => $users]);
    }

    public function userApprove($id)
    {
        $this->userModel->updateStatus($id, 'active');
        setFlash('success', 'Contributor berhasil diapprove');
        header('Location: ' . BASE_URL . '/admin/users');
        exit;
    }

    public function userReject($id)
    {
        $this->userModel->updateStatus($id, 'rejected');
        setFlash('success', 'Contributor ditolak');
        header('Location: ' . BASE_URL . '/admin/users');
        exit;
    }

    public function userDelete($id)
    {
        $this->userModel->delete($id);
        setFlash('success', 'User berhasil dihapus');
        header('Location: ' . BASE_URL . '/admin/users');
        exit;
    }

    // =====================
    // POST MANAGEMENT
    // =====================

    public function posts()
    {
        $posts = $this->postModel->all();
        return $this->view('admin/posts/index', ['posts' => $posts]);
    }

    public function postCreate()
    {
        $categories = $this->categoryModel->all();
        return $this->view('admin/posts/form', ['post' => null, 'categories' => $categories]);
    }

    public function postStore()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/posts');
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
        header('Location: ' . BASE_URL . '/admin/posts');
        exit;
    }

    public function postEdit($id)
    {
        $post = $this->postModel->find($id);
        $categories = $this->categoryModel->all();

        if (!$post) {
            setFlash('error', 'Artikel tidak ditemukan');
            header('Location: ' . BASE_URL . '/admin/posts');
            exit;
        }

        return $this->view('admin/posts/form', ['post' => $post, 'categories' => $categories]);
    }

    public function postUpdate($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/posts');
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
        header('Location: ' . BASE_URL . '/admin/posts');
        exit;
    }

    public function postDelete($id)
    {
        $this->postModel->delete($id);
        setFlash('success', 'Artikel berhasil dihapus');
        header('Location: ' . BASE_URL . '/admin/posts');
        exit;
    }
}
