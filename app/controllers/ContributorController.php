<?php

/**
 * ContributorController - Contributor Panel
 */
class ContributorController extends Controller
{
    private $postModel;
    private $categoryModel;
    private $tagModel;

    public function __construct()
    {
        requireContributor();
        $this->postModel = new Post();
        $this->categoryModel = new Category();
        $this->tagModel = new Tag();
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
        $allTags = $this->tagModel->all();
        return $this->view('contributor/form', [
            'post' => null, 
            'categories' => $categories,
            'allTags' => $allTags,
            'postTags' => []
        ]);
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

        // Handle manual tags
        $manualTagIds = [];
        if (!empty($_POST['manual_tags'])) {
            $tagsArray = explode(',', $_POST['manual_tags']);
            foreach ($tagsArray as $tagName) {
                $tagId = $this->tagModel->findOrCreate($tagName);
                if ($tagId) {
                    $manualTagIds[] = $tagId;
                }
            }
        }

        $postId = $this->postModel->create($data);

        // Handle tags (merged existing selections + manual input)
        $tagIds = !empty($_POST['tags']) ? array_map('intval', $_POST['tags']) : [];
        $tagIds = array_unique(array_merge($tagIds, $manualTagIds));
        
        if (!empty($tagIds)) {
            $this->tagModel->attachToPost($postId, $tagIds);
        }

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
        $allTags = $this->tagModel->all();
        $postTags = $this->tagModel->getByPost($id);
        return $this->view('contributor/form', [
            'post' => $post, 
            'categories' => $categories,
            'allTags' => $allTags,
            'postTags' => $postTags
        ]);
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

        // Handle manual tags
        $manualTagIds = [];
        if (!empty($_POST['manual_tags'])) {
            $tagsArray = explode(',', $_POST['manual_tags']);
            foreach ($tagsArray as $tagName) {
                $tagId = $this->tagModel->findOrCreate($tagName);
                if ($tagId) {
                    $manualTagIds[] = $tagId;
                }
            }
        }

        // Handle tags
        $tagIds = !empty($_POST['tags']) ? array_map('intval', $_POST['tags']) : [];
        $tagIds = array_unique(array_merge($tagIds, $manualTagIds));
        
        $this->tagModel->attachToPost($id, $tagIds);

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
    /**
     * Edit Profile Form
     */
    public function editProfile()
    {
        $userModel = new User();
        $user = $userModel->find($_SESSION['user_id']);
        
        return $this->view('contributor/profile', [
            'user' => $user
        ]);
    }

    /**
     * Update Profile
     */
    public function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/contributor/editProfile');
            exit;
        }

        $userModel = new User();
        $userId = $_SESSION['user_id'];
        $user = $userModel->find($userId);

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
            'bio' => trim($_POST['bio'] ?? ''),
            'social_media' => [
                'website' => trim($_POST['website'] ?? ''),
                'instagram' => trim($_POST['instagram'] ?? ''),
                'facebook' => trim($_POST['facebook'] ?? ''),
                'twitter' => trim($_POST['twitter'] ?? '')
            ]
        ];

        // Handle Avatar Upload
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../public/uploads/avatars/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $filename = 'params_' . $userId . '_' . uniqid() . '.' . $ext;
            
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadDir . $filename)) {
                // Remove old avatar if exists and not default
                if (!empty($user['avatar']) && file_exists('../public/' . $user['avatar'])) {
                    unlink('../public/' . $user['avatar']);
                }
                $data['avatar'] = 'uploads/avatars/' . $filename;
            }
        }

        // Handle QRIS Image Upload
        if (isset($_FILES['qris_image']) && $_FILES['qris_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../public/uploads/qris/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $ext = strtolower(pathinfo($_FILES['qris_image']['name'], PATHINFO_EXTENSION));
            
            // Validate image type
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                $filename = 'qris_' . $userId . '_' . uniqid() . '.' . $ext;
                
                if (move_uploaded_file($_FILES['qris_image']['tmp_name'], $uploadDir . $filename)) {
                    // Remove old QRIS if exists
                    if (!empty($user['qris_image']) && file_exists('../public/' . $user['qris_image'])) {
                        unlink('../public/' . $user['qris_image']);
                    }
                    $data['qris_image'] = 'uploads/qris/' . $filename;
                }
            }
        }

        // Update password if provided
        if (!empty($_POST['password'])) {
            if ($_POST['password'] === $_POST['confirm_password']) {
                $data['password'] = $_POST['password'];
            } else {
                setFlash('error', 'Konfirmasi password tidak cocok');
                header('Location: ' . BASE_URL . '/contributor/editProfile');
                exit;
            }
        }

        $userModel->update($userId, $data);
        
        // Update session name if changed
        $_SESSION['user_name'] = $data['name'];

        setFlash('success', 'Profil berhasil diperbarui');
        header('Location: ' . BASE_URL . '/contributor');
        exit;
    }

    /**
     * AJAX Search Tags
     */
    public function searchTags()
    {
        header('Content-Type: application/json');
        
        $query = trim($_GET['q'] ?? '');
        if (empty($query)) {
            echo json_encode([]);
            exit;
        }

        $tags = $this->tagModel->search($query);
        echo json_encode($tags);
        exit;
    }

    /**
     * AJAX Generate Tags
     */
    public function generateTags()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([]);
            exit;
        }

        $title = $_POST['title'] ?? '';
        $body = $_POST['body'] ?? '';
        $text = $title . ' ' . $body;

        // 1. Clean Text
        $text = strip_tags($text);
        $text = strtolower($text);
        $text = preg_replace('/[^\w\s]/', '', $text); // Remove punctuation
        $text = preg_replace('/\s+/', ' ', $text); // Collapse spaces

        // 2. Tokenize
        $words = explode(' ', $text);

        // 3. Stopwords (Indonesian)
        $stopwords = [
            'yang', 'di', 'dan', 'itu', 'dengan', 'untuk', 'tidak', 'ini', 'dari', 'dalam', 'akan', 
            'pada', 'juga', 'saya', 'ke', 'karena', 'tersebut', 'bisa', 'ada', 'mereka', 'lebih', 
            'atau', 'tapi', 'kita', 'adalah', 'hal', 'sebagai', 'sudah', 'telah', 'saat', 'oleh', 
            'apakah', 'bagaimana', 'apa', 'jika', 'sebuah', 'namun', 'satu', 'lain', 'maka', 'ia',
            'dia', 'kamu', 'anda', 'bagi', 'sampai', 'sangat', 'lalu', 'hanya', 'tentang', 'seperti',
            'mana', 'hari', 'tahun', 'ketika', 'setelah', 'belum', 'kami', 'masih', 'banyak', 'tak',
            'para', 'harus', 'semua', 'sedang', 'sementara', 'kemudian', 'agar', 'lagi', 'setiap',
            'menjadi', 'terhadap', 'secara', 'pernah', 'ingin', 'baru', 'mungkin', 'saja', 'ketika',
            'sejak', 'tanpa', 'atas', 'bawah', 'depan', 'belakang', 'pula', 'pun', 'mengapa', 'sehingga'
        ];

        // 4. Filter & Count
        $keywords = [];
        foreach ($words as $word) {
            if (strlen($word) > 3 && !in_array($word, $stopwords) && !is_numeric($word)) {
                if (!isset($keywords[$word])) {
                    $keywords[$word] = 0;
                }
                $keywords[$word]++;
            }
        }

        // 5. Sort by Frequency
        arsort($keywords);

        // 6. Get Top 15
        $topKeywords = array_keys(array_slice($keywords, 0, 15));
        
        // Return structured data like tag objects
        $results = [];
        foreach ($topKeywords as $word) {
            $results[] = ['name' => $word];
        }

        echo json_encode($results);
        exit;
    }
}
