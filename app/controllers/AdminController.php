<?php

/**
 * AdminController - Admin Panel
 */
class AdminController extends Controller
{
    private $userModel;
    private $categoryModel;
    private $postModel;
    private $tagModel;

    public function __construct()
    {
        requireAdmin();
        $this->userModel = new User();
        $this->categoryModel = new Category();
        $this->postModel = new Post();
        $this->tagModel = new Tag();
        $this->collaborationModel = new Collaboration();
    }

    /**
     * Dashboard
     */
    public function index()
    {
        $stats = [
            'posts' => $this->postModel->count('published'),
            'drafts' => $this->postModel->count('draft') + $this->postModel->count('pending'),
            'categories' => count($this->categoryModel->all(false)),
            'pending_contributors' => count($this->userModel->getPendingContributors()),
            'active_contributors' => count($this->userModel->getActiveContributors()),
        ];

        $recentPosts = $this->postModel->getRecent(5);
        $pendingContributors = $this->userModel->getPendingContributors();
        $pendingPosts = $this->postModel->getPendingPosts();

        return $this->view('admin/dashboard', [
            'stats' => $stats,
            'recentPosts' => $recentPosts,
            'pendingContributors' => $pendingContributors,
            'pendingPosts' => $pendingPosts
        ]);
    }

    // =====================
    // CATEGORY MANAGEMENT
    // =====================

    public function categories()
    {
        $type = $_GET['type'] ?? null;
        
        if ($type) {
            $categories = $this->categoryModel->all(false, $type);
        } else {
            $categories = $this->categoryModel->all(false);
        }
        
        return $this->view('admin/categories/index', [
            'categories' => $categories,
            'activeType' => $type
        ]);
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
            'type' => $_POST['type'] ?? 'blog',
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
            'type' => $_POST['type'] ?? 'blog',
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
        $search = $_GET['q'] ?? null;
        $status = $_GET['status'] ?? null;
        $users = $this->userModel->all(null, $status, 'created_at DESC', $search);
        return $this->view('admin/users/index', [
            'users' => $users, 
            'search' => $search,
            'currentStatus' => $status
        ]);
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
    // TAG MANAGEMENT
    // =====================

    public function tags()
    {
        $search = $_GET['q'] ?? null;
        $tags = $this->tagModel->getPopular(100, $search);
        return $this->view('admin/tags/index', ['tags' => $tags, 'search' => $search]);
    }

    public function tagCreate()
    {
        return $this->view('admin/tags/form', ['tag' => null]);
    }

    public function tagStore()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/tags');
            exit;
        }

        $data = [
            'name' => trim($_POST['name'] ?? '')
        ];

        if (empty($data['name'])) {
            setFlash('error', 'Nama tag harus diisi');
            header('Location: ' . BASE_URL . '/admin/tagCreate');
            exit;
        }

        $this->tagModel->create($data);
        setFlash('success', 'Tag berhasil ditambahkan');
        header('Location: ' . BASE_URL . '/admin/tags');
        exit;
    }

    public function tagEdit($id)
    {
        $tag = $this->tagModel->find($id);
        if (!$tag) {
            setFlash('error', 'Tag tidak ditemukan');
            header('Location: ' . BASE_URL . '/admin/tags');
            exit;
        }

        return $this->view('admin/tags/form', ['tag' => $tag]);
    }

    public function tagUpdate($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/tags');
            exit;
        }

        $data = [
            'name' => trim($_POST['name'] ?? '')
        ];

        // Generate slug if name changed
        $slug = strtolower(trim($data['name']));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $data['slug'] = trim($slug, '-');

        $this->tagModel->update($id, $data);
        setFlash('success', 'Tag berhasil diupdate');
        header('Location: ' . BASE_URL . '/admin/tags');
        exit;
    }

    public function tagDelete($id)
    {
        $this->tagModel->delete($id);
        setFlash('success', 'Tag berhasil dihapus');
        header('Location: ' . BASE_URL . '/admin/tags');
        exit;
    }

    // =====================
    // POST MANAGEMENT
    // =====================

    public function posts()
    {
        $search = $_GET['q'] ?? null;
        $status = $_GET['status'] ?? 'all';
        $posts = $this->postModel->all(null, 0, $status, $search);
        return $this->view('admin/posts/index', [
            'posts' => $posts, 
            'search' => $search,
            'currentStatus' => $status
        ]);
    }

    public function postCreate()
    {
        $categories = $this->categoryModel->all(false, 'blog'); // Filter by blog type
        $allTags = $this->tagModel->all();
        return $this->view('admin/posts/form', ['post' => null, 'categories' => $categories, 'allTags' => $allTags, 'postTags' => []]);
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
            'status' => $_POST['status'] ?? 'draft',
            'published_at' => !empty($_POST['published_at']) ? $_POST['published_at'] : null
        ];

        // Handle cover image upload
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = dirname(__DIR__, 2) . '/public/uploads/posts/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $ext = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['cover_image']['tmp_name'], $uploadDir . $filename);
            $data['cover_image'] = 'uploads/posts/' . $filename;
        }

        $postId = $this->postModel->create($data);

        // Handle existing tags from checkboxes
        $tagIds = [];
        if (!empty($_POST['tags'])) {
            $tagIds = array_map('intval', $_POST['tags']);
        }

        // Handle manual/generated tags
        if (!empty($_POST['manual_tags'])) {
            $manualTags = array_filter(array_map('trim', explode(',', $_POST['manual_tags'])));
            foreach ($manualTags as $tagName) {
                if (!empty($tagName)) {
                    // Use findOrCreate - creates tag if not exists
                    $tagId = $this->tagModel->findOrCreate($tagName);
                    if ($tagId) {
                        $tagIds[] = $tagId;
                    }
                }
            }
        }

        // Attach all tags (remove duplicates)
        if (!empty($tagIds)) {
            $tagIds = array_unique($tagIds);
            $this->tagModel->attachToPost($postId, $tagIds);
        }

        setFlash('success', 'Artikel berhasil dibuat');
        header('Location: ' . BASE_URL . '/admin/posts');
        exit;
    }

    public function postEdit($id)
    {
        $post = $this->postModel->find($id);
        $categories = $this->categoryModel->all(false, 'blog'); // Filter by blog type
        $allTags = $this->tagModel->all();
        $postTags = $this->tagModel->getByPost($id);

        if (!$post) {
            setFlash('error', 'Artikel tidak ditemukan');
            header('Location: ' . BASE_URL . '/admin/posts');
            exit;
        }

        return $this->view('admin/posts/form', [
            'post' => $post, 
            'categories' => $categories, 
            'allTags' => $allTags,
            'postTags' => $postTags
        ]);
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
            'status' => $_POST['status'] ?? 'draft',
            'published_at' => !empty($_POST['published_at']) ? $_POST['published_at'] : null
        ];

        // Handle cover image removal
        if (isset($_POST['remove_cover_image']) && $_POST['remove_cover_image'] === '1') {
            $existingPost = $this->postModel->find($id);
            if (!empty($existingPost['cover_image'])) {
                $oldFile = dirname(__DIR__, 2) . '/public/' . $existingPost['cover_image'];
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }
            $data['cover_image'] = null;
        }

        // Handle cover image upload
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = dirname(__DIR__, 2) . '/public/uploads/posts/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $ext = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['cover_image']['tmp_name'], $uploadDir . $filename);
            $data['cover_image'] = 'uploads/posts/' . $filename;
        }

        $this->postModel->update($id, $data);

        // Handle existing tags from checkboxes
        $tagIds = [];
        if (!empty($_POST['tags'])) {
            $tagIds = array_map('intval', $_POST['tags']);
        }

        // Handle manual/generated tags
        if (!empty($_POST['manual_tags'])) {
            $manualTags = array_filter(array_map('trim', explode(',', $_POST['manual_tags'])));
            foreach ($manualTags as $tagName) {
                if (!empty($tagName)) {
                    // Use findOrCreate - creates tag if not exists
                    $tagId = $this->tagModel->findOrCreate($tagName);
                    if ($tagId) {
                        $tagIds[] = $tagId;
                    }
                }
            }
        }

        // Attach all tags (remove duplicates)
        if (!empty($tagIds)) {
            $tagIds = array_unique($tagIds);
        }
        $this->tagModel->attachToPost($id, $tagIds);

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

    public function postApprove($id)
    {
        $this->postModel->updateStatus($id, 'published');
        setFlash('success', 'Artikel berhasil dipublish');
        header('Location: ' . BASE_URL . '/admin/posts');
        exit;
    }

    public function postReject($id)
    {
        $this->postModel->updateStatus($id, 'draft'); // Revert to draft
        setFlash('success', 'Artikel dikembalikan ke draft');
        header('Location: ' . BASE_URL . '/admin/posts');
        exit;
    }

    // =====================
    // ZINES MANAGEMENT (Buletin Sastra)
    // =====================


    public function zines()
    {
        $search = $_GET['q'] ?? null;
        $zineModel = new Zine();
        $zines = $zineModel->all(null, 0, false, $search); // Get all including inactive with search
        $categories = $zineModel->getCategories();
        return $this->view('admin/zines/index', [
            'zines' => $zines,
            'categories' => $categories,
            'search' => $search
        ]);
    }

    public function zineCreate()
    {
        $zineModel = new Zine();
        $categories = $zineModel->getCategories();
        $contributors = $this->userModel->getActiveContributors(true);
        return $this->view('admin/zines/form', [
            'zine' => null,
            'categories' => $categories,
            'contributors' => $contributors
        ]);
    }

    public function zineStore()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/zines');
            exit;
        }

        // Check for empty POST (likely file upload limit exceeded)
        if (empty($_POST) && $_SERVER['CONTENT_LENGTH'] > 0) {
            setFlash('error', 'Ukuran file terlalu besar. Maksimal upload: ' . ini_get('upload_max_filesize'));
            header('Location: ' . BASE_URL . '/admin/zineCreate');
            exit;
        }

        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'excerpt' => trim($_POST['excerpt'] ?? ''),
            'category_id' => $_POST['category_id'] ?? null,
            'author_id' => !empty($_POST['author_id']) ? $_POST['author_id'] : null,
            'image_position' => $_POST['image_position'] ?? 'center',
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];

        // Handle cover image upload
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = dirname(__DIR__, 2) . '/public/uploads/zines/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $ext = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['cover_image']['tmp_name'], $uploadDir . $filename);
            $data['cover_image'] = 'uploads/zines/' . $filename;
        }

        // Handle PDF Link
        $data['pdf_link'] = trim($_POST['pdf_link'] ?? '');

        $zineModel = new Zine();
        $zineModel->create($data);
        setFlash('success', 'Buletin berhasil ditambahkan');
        header('Location: ' . BASE_URL . '/admin/zines');
        exit;
    }

    public function zineEdit($id)
    {
        $zineModel = new Zine();
        $zine = $zineModel->find($id);

        if (!$zine) {
            setFlash('error', 'Bulletin tidak ditemukan');
            header('Location: ' . BASE_URL . '/admin/zines');
            exit;
        }

        $categories = $zineModel->getCategories();
        $contributors = $this->userModel->getActiveContributors(true);
        return $this->view('admin/zines/form', [
            'zine' => $zine,
            'categories' => $categories,
            'contributors' => $contributors
        ]);
    }

    public function zineUpdate($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/zines');
            exit;
        }

        // Check for empty POST (likely file upload limit exceeded)
        if (empty($_POST) && $_SERVER['CONTENT_LENGTH'] > 0) {
            setFlash('error', 'Ukuran file terlalu besar. Maksimal upload: ' . ini_get('upload_max_filesize'));
            header('Location: ' . BASE_URL . '/admin/zineEdit/' . $id);
            exit;
        }

        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'excerpt' => trim($_POST['excerpt'] ?? ''),
            'category_id' => $_POST['category_id'] ?? null,
            'author_id' => !empty($_POST['author_id']) ? $_POST['author_id'] : null,
            'image_position' => $_POST['image_position'] ?? 'center',
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];

        // Handle cover image upload
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = dirname(__DIR__, 2) . '/public/uploads/zines/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $ext = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['cover_image']['tmp_name'], $uploadDir . $filename);
            $data['cover_image'] = 'uploads/zines/' . $filename;
        }

        // Handle PDF Link
        $data['pdf_link'] = trim($_POST['pdf_link'] ?? '');

        $zineModel = new Zine();
        $zineModel->update($id, $data);
        setFlash('success', 'Buletin berhasil diupdate');
        header('Location: ' . BASE_URL . '/admin/zines');
        exit;
    }

    // =====================
    // COMMUNITIES MANAGEMENT (Katalog Komunitas)
    // =====================

    public function communities()
    {
        $search = $_GET['q'] ?? null;
        $communityModel = new Community();
        $communities = $communityModel->all($search);
        return $this->view('admin/communities/index', [
            'communities' => $communities,
            'search' => $search
        ]);
    }

    public function communityCreate()
    {
        return $this->view('admin/communities/form', ['community' => null]);
    }

    public function communityStore()
    {
        $communityModel = new Community();
        
        // Handle image upload
        $imagePath = null;
        if (!empty($_FILES['image']['name'])) {
            $uploadDir = dirname(__DIR__, 2) . '/public/uploads/communities/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $imageName = time() . '_' . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName);
            $imagePath = 'uploads/communities/' . $imageName;
        }

        $communityModel->create([
            'name' => $_POST['name'],
            'description' => $_POST['description'] ?? null,
            'image' => $imagePath,
            'location' => $_POST['location'] ?? null,
            'contact' => $_POST['contact'] ?? null,
            'website' => $_POST['website'] ?? null,
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ]);
        
        header('Location: ' . BASE_URL . '/admin/communities');
        exit;
    }

    public function communityEdit($id)
    {
        $communityModel = new Community();
        $community = $communityModel->find($id);
        
        if (!$community) {
            header('Location: ' . BASE_URL . '/admin/communities');
            exit;
        }
        
        return $this->view('admin/communities/form', ['community' => $community]);
    }

    public function communityUpdate($id)
    {
        $communityModel = new Community();
        
        $data = [
            'name' => $_POST['name'],
            'description' => $_POST['description'] ?? null,
            'location' => $_POST['location'] ?? null,
            'contact' => $_POST['contact'] ?? null,
            'website' => $_POST['website'] ?? null,
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];
        
        // Handle image upload
        if (!empty($_FILES['image']['name'])) {
            $uploadDir = dirname(__DIR__, 2) . '/public/uploads/communities/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $imageName = time() . '_' . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName);
            $data['image'] = 'uploads/communities/' . $imageName;
        }

        $communityModel->update($id, $data);
        
        header('Location: ' . BASE_URL . '/admin/communities');
        exit;
    }

    public function communityDelete($id)
    {
        $communityModel = new Community();
        $communityModel->delete($id);
        
        header('Location: ' . BASE_URL . '/admin/communities');
        exit;
    }

    // =====================
    // PRODUCTS MANAGEMENT (Merch)
    // =====================

    public function products()
    {
        $search = $_GET['q'] ?? null;
        $productModel = new Product();
        $products = $productModel->getAll(null, 0, false, $search); // Get all including inactive with search
        return $this->view('admin/products/index', ['products' => $products, 'search' => $search]);
    }

    public function productCreate()
    {
        $productModel = new Product();
        $categories = $productModel->getCategories();
        return $this->view('admin/products/form', ['product' => null, 'categories' => $categories]);
    }

    public function productStore()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/products');
            exit;
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'category_id' => $_POST['category_id'] ?? null,
            'description' => trim($_POST['description'] ?? ''),
            'price' => intval($_POST['price'] ?? 0),
            'stock' => intval($_POST['stock'] ?? 0),
            'whatsapp_number' => trim($_POST['whatsapp_number'] ?? '6283895189649'),
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];

        // Handle cover image upload
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = dirname(__DIR__, 2) . '/public/uploads/products/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $ext = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['cover_image']['tmp_name'], $uploadDir . $filename);
            $data['cover_image'] = 'uploads/products/' . $filename;
        }

        $productModel = new Product();
        $productModel->create($data);
        setFlash('success', 'Produk berhasil ditambahkan');
        header('Location: ' . BASE_URL . '/admin/products');
        exit;
    }

    public function productEdit($id)
    {
        $productModel = new Product();
        $product = $productModel->find($id);

        if (!$product) {
            setFlash('error', 'Produk tidak ditemukan');
            header('Location: ' . BASE_URL . '/admin/products');
            exit;
        }
        
        $categories = $productModel->getCategories();
        return $this->view('admin/products/form', ['product' => $product, 'categories' => $categories]);
    }

    public function productUpdate($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/products');
            exit;
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'category_id' => $_POST['category_id'] ?? null,
            'description' => trim($_POST['description'] ?? ''),
            'price' => intval($_POST['price'] ?? 0),
            'stock' => intval($_POST['stock'] ?? 0),
            'whatsapp_number' => trim($_POST['whatsapp_number'] ?? '6283895189649'),
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];

        // Handle cover image upload
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = dirname(__DIR__, 2) . '/public/uploads/products/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $ext = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['cover_image']['tmp_name'], $uploadDir . $filename);
            $data['cover_image'] = 'uploads/products/' . $filename;
        }

        $productModel = new Product();
        $productModel->update($id, $data);
        setFlash('success', 'Produk berhasil diupdate');
        header('Location: ' . BASE_URL . '/admin/products');
        exit;
    }

    public function productDelete($id)
    {
        $productModel = new Product();
        $productModel->delete($id);
        setFlash('success', 'Produk berhasil dihapus');
        header('Location: ' . BASE_URL . '/admin/products');
        exit;
    }

    // =====================
    // EVENTS MANAGEMENT (Pentas)
    // =====================

    public function events()
    {
        $search = $_GET['q'] ?? null;
        $eventModel = new Event();
        $events = $eventModel->getAll($search);
        return $this->view('admin/events/index', ['events' => $events, 'search' => $search]);
    }

    public function eventCreate()
    {
        return $this->view('admin/events/form', ['event' => null]);
    }

    public function eventStore()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/events');
            exit;
        }

        // Generate slug from title
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $_POST['title'] ?? '')));
        $slug = preg_replace('/-+/', '-', $slug); // Remove multiple dashes
        $slug = trim($slug, '-'); // Remove leading/trailing dashes
        
        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'slug' => $slug,
            'description' => trim($_POST['description'] ?? ''),
            'venue' => trim($_POST['venue'] ?? ''),
            'venue_address' => trim($_POST['venue_address'] ?? ''),
            'event_date' => $_POST['event_date'] ?? null,
            'end_date' => $_POST['event_end_date'] ?: null,
            'ticket_price' => intval($_POST['ticket_price'] ?? 0),
            'ticket_quota' => intval($_POST['ticket_quota'] ?? 100),
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];

        // Handle cover image upload
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = dirname(__DIR__, 2) . '/public/uploads/events/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $ext = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['cover_image']['tmp_name'], $uploadDir . $filename);
            $data['cover_image'] = 'uploads/events/' . $filename;
        }

        $eventModel = new Event();
        $eventModel->create($data);
        setFlash('success', 'Event berhasil ditambahkan');
        header('Location: ' . BASE_URL . '/admin/events');
        exit;
    }

    public function eventEdit($id)
    {
        $eventModel = new Event();
        $event = $eventModel->find($id);

        if (!$event) {
            setFlash('error', 'Event tidak ditemukan');
            header('Location: ' . BASE_URL . '/admin/events');
            exit;
        }

        return $this->view('admin/events/form', ['event' => $event]);
    }

    public function eventUpdate($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/events');
            exit;
        }

        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'venue' => trim($_POST['venue'] ?? ''),
            'venue_address' => trim($_POST['venue_address'] ?? ''),
            'event_date' => $_POST['event_date'] ?? null,
            'end_date' => $_POST['event_end_date'] ?: null,
            'ticket_price' => intval($_POST['ticket_price'] ?? 0),
            'ticket_quota' => intval($_POST['ticket_quota'] ?? 100),
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];

        // Handle cover image upload
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = dirname(__DIR__, 2) . '/public/uploads/events/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $ext = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['cover_image']['tmp_name'], $uploadDir . $filename);
            $data['cover_image'] = 'uploads/events/' . $filename;
        }

        $eventModel = new Event();
        $eventModel->update($id, $data);
        setFlash('success', 'Event berhasil diupdate');
        header('Location: ' . BASE_URL . '/admin/events');
        exit;
    }

    public function eventDelete($id)
    {
        $eventModel = new Event();
        $eventModel->delete($id);
        setFlash('success', 'Event berhasil dihapus');
        header('Location: ' . BASE_URL . '/admin/events');
        exit;
    }

    public function zineDelete($id)
    {
        $zineModel = new Zine();
        $zine = $zineModel->find($id);

        if ($zine) {
            // Delete cover image
            if (!empty($zine['cover_image'])) {
                $coverPath = dirname(__DIR__, 2) . '/public/' . $zine['cover_image'];
                if (file_exists($coverPath)) {
                    unlink($coverPath);
                }
            }

            // Delete PDF file
            if (!empty($zine['pdf_file'])) {
                $pdfPath = dirname(__DIR__, 2) . '/public/' . $zine['pdf_file'];
                if (file_exists($pdfPath)) {
                    unlink($pdfPath);
                }
            }

            $zineModel->delete($id);
            setFlash('success', 'Buletin berhasil dihapus');
        } else {
            setFlash('error', 'Buletin tidak ditemukan');
        }

        header('Location: ' . BASE_URL . '/admin/zines');
        exit;
    }

    // =====================
    // SETTINGS MANAGEMENT
    // =====================

    public function settings()
    {
        $userId = $_SESSION['user_id'];
        $userModel = new User();
        $user = $userModel->find($userId);
        
        return $this->view('admin/settings', ['user' => $user]);
    }

    public function settingsUpdate()
    {
        $userId = $_SESSION['user_id'];
        $userModel = new User();
        
        $data = [
            'name' => trim($_POST['name']),
            'email' => trim($_POST['email'])
        ];

        // Handle password change
        if (!empty($_POST['password'])) {
            if ($_POST['password'] !== $_POST['password_confirm']) {
                setFlash('error', 'Konfirmasi password tidak cocok');
                header('Location: ' . BASE_URL . '/admin/settings');
                exit;
            }
            $data['password'] = $_POST['password'];
        }

        // Handle avatar upload
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = dirname(__DIR__, 2) . '/public/uploads/avatars/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $filename = 'avatar_' . $userId . '_' . time() . '.' . $ext;
            
            move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadDir . $filename);
            $data['avatar'] = 'uploads/avatars/' . $filename;
        }

        $userModel->update($userId, $data);
        
        // Update session name if changed
        $_SESSION['user_name'] = $data['name'];
        
        setFlash('success', 'Profil berhasil diperbarui');
        header('Location: ' . BASE_URL . '/admin/settings');
        exit;
    }

    // =====================
    // TICKETS MANAGEMENT
    // =====================

    public function tickets()
    {
        $ticketModel = new Ticket();
        $eventModel = new Event();
        
        $eventId = isset($_GET['event_id']) ? intval($_GET['event_id']) : null;
        $status = isset($_GET['status']) ? $_GET['status'] : null;
        
        if ($eventId) {
            $tickets = $ticketModel->getByEvent($eventId);
            $currentEvent = $eventModel->find($eventId);
        } else {
            $tickets = $ticketModel->getAll();
            $currentEvent = null;
        }
        
        $events = $eventModel->getAll();
        
        return $this->view('admin/tickets/index', [
            'tickets' => $tickets,
            'events' => $events,
            'currentEvent' => $currentEvent,
            'currentStatus' => $status
        ]);
    }

    public function ticketConfirm($id)
    {
        $ticketModel = new Ticket();
        $ticket = $ticketModel->find($id);
        
        if ($ticket) {
            $ticketModel->updateStatus($id, 'confirmed');
            setFlash('success', 'Tiket berhasil dikonfirmasi');
        }
        
        $redirect = BASE_URL . '/admin/tickets';
        if (isset($_GET['event_id'])) {
            $redirect .= '?event_id=' . intval($_GET['event_id']);
        }
        header('Location: ' . $redirect);
        exit;
    }

    public function ticketCancel($id)
    {
        $ticketModel = new Ticket();
        $ticket = $ticketModel->find($id);
        
        if ($ticket) {
            $ticketModel->updateStatus($id, 'cancelled');
            setFlash('success', 'Tiket dibatalkan');
        }
        
        $redirect = BASE_URL . '/admin/tickets';
        if (isset($_GET['event_id'])) {
            $redirect .= '?event_id=' . intval($_GET['event_id']);
        }
        header('Location: ' . $redirect);
        exit;
    }

    public function ticketDelete($id)
    {
        $ticketModel = new Ticket();
        $ticketModel->delete($id);
        setFlash('success', 'Tiket berhasil dihapus');
        
        $redirect = BASE_URL . '/admin/tickets';
        if (isset($_GET['event_id'])) {
            $redirect .= '?event_id=' . intval($_GET['event_id']);
        }
        header('Location: ' . $redirect);
        exit;
    }

    public function ticketDetail($id)
    {
        $ticketModel = new Ticket();
        $ticket = $ticketModel->find($id);
        
        if (!$ticket) {
            setFlash('error', 'Tiket tidak ditemukan');
            header('Location: ' . BASE_URL . '/admin/tickets');
            exit;
        }
        
        return $this->view('admin/tickets/detail', ['ticket' => $ticket]);
    }

    // ========== KOLABORASI CRUD ==========

    public function collaborations()
    {
        $search = $_GET['q'] ?? null;
        $collaborations = $this->collaborationModel->all($search);
        
        return $this->view('admin/collaborations/index', [
            'collaborations' => $collaborations,
            'search' => $search
        ]);
    }

    public function collaborationCreate()
    {
        $contributors = $this->userModel->all(); // Show all users
        
        return $this->view('admin/collaborations/form', [
            'collaboration' => null,
            'contributors' => $contributors,
            'selectedContributors' => []
        ]);
    }

    public function collaborationStore()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/collaborations');
            exit;
        }

        $title = trim($_POST['title']);
        $slug = $this->collaborationModel->generateSlug($title);
        
        $data = [
            'title' => $title,
            'slug' => $slug,
            'description' => $_POST['description'] ?? '',
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'social_media' => [
                'instagram' => $_POST['instagram'] ?? '',
                'twitter' => $_POST['twitter'] ?? '',
                'facebook' => $_POST['facebook'] ?? '',
                'website' => $_POST['website'] ?? ''
            ]
        ];

        // Handle Image Upload
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = dirname(__DIR__, 2) . '/public/uploads/collaborations/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $ext = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
            $filename = $slug . '-' . uniqid() . '.' . $ext;
            
            if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $uploadDir . $filename)) {
                $data['cover_image'] = 'uploads/collaborations/' . $filename;
            }
        }

        $collaborationId = $this->collaborationModel->create($data);

        // Handle Contributors
        if (!empty($_POST['contributors'])) {
            $this->collaborationModel->syncContributors($collaborationId, $_POST['contributors']);
        }

        setFlash('success', 'Kolaborasi berhasil ditambahkan');
        header('Location: ' . BASE_URL . '/admin/collaborations');
        exit;
    }

    public function collaborationEdit($id)
    {
        $collaboration = $this->collaborationModel->find($id);
        if (!$collaboration) {
            setFlash('danger', 'Kolaborasi tidak ditemukan');
            header('Location: ' . BASE_URL . '/admin/collaborations');
            exit;
        }

        $contributors = $this->userModel->all(); // Show all users
        $currentContributors = $this->collaborationModel->getContributors($id);
        $selectedContributors = array_column($currentContributors, 'id');

        return $this->view('admin/collaborations/form', [
            'collaboration' => $collaboration,
            'contributors' => $contributors,
            'selectedContributors' => $selectedContributors
        ]);
    }

    public function collaborationUpdate($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/collaborations');
            exit;
        }

        $collaboration = $this->collaborationModel->find($id);
        if (!$collaboration) {
            header('Location: ' . BASE_URL . '/admin/collaborations');
            exit;
        }

        $data = [
            'title' => trim($_POST['title']),
            'description' => $_POST['description'] ?? '',
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'social_media' => [
                'instagram' => $_POST['instagram'] ?? '',
                'twitter' => $_POST['twitter'] ?? '',
                'facebook' => $_POST['facebook'] ?? '',
                'website' => $_POST['website'] ?? ''
            ]
        ];

        // Handle Image Upload
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = dirname(__DIR__, 2) . '/public/uploads/collaborations/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Delete old image
            if (!empty($collaboration['cover_image'])) {
                $oldFile = dirname(__DIR__, 2) . '/public/' . $collaboration['cover_image'];
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            $ext = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
            $filename = $collaboration['slug'] . '-' . uniqid() . '.' . $ext;
            
            if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $uploadDir . $filename)) {
                $data['cover_image'] = 'uploads/collaborations/' . $filename;
            }
        }

        $this->collaborationModel->update($id, $data);

        // Handle Contributors
        $contributorIds = $_POST['contributors'] ?? [];
        $this->collaborationModel->syncContributors($id, $contributorIds);

        setFlash('success', 'Kolaborasi berhasil diperbarui');
        header('Location: ' . BASE_URL . '/admin/collaborations');
        exit;
    }

    public function collaborationDelete($id)
    {
        $collaboration = $this->collaborationModel->find($id);
        if ($collaboration) {
            // Delete image
            if (!empty($collaboration['cover_image'])) {
                $oldFile = dirname(__DIR__, 2) . '/public/' . $collaboration['cover_image'];
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }
            
            $this->collaborationModel->delete($id);
            setFlash('success', 'Kolaborasi berhasil dihapus');
        }

        header('Location: ' . BASE_URL . '/admin/collaborations');
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

        // 3. Stopwords (Indonesian - kata sambung, preposisi, kata ganti, kata bantu)
        $stopwords = [
            // Kata sambung (konjungsi)
            'dan', 'atau', 'tetapi', 'tapi', 'namun', 'melainkan', 'sedangkan', 'padahal', 
            'serta', 'lalu', 'kemudian', 'lantas', 'lagi', 'sebab', 'karena', 'oleh', 
            'maka', 'sehingga', 'agar', 'supaya', 'kalau', 'jika', 'jikalau', 'bila', 
            'apabila', 'asal', 'asalkan', 'meski', 'meskipun', 'walau', 'walaupun', 
            'sekalipun', 'biarpun', 'kendati', 'seakan', 'seolah', 'seperti', 'bagaikan',
            'laksana', 'ibarat', 'daripada', 'alih', 'ketimbang', 'bahwa', 'yakni', 'yaitu',
            
            // Preposisi (kata depan)
            'di', 'ke', 'dari', 'pada', 'dalam', 'dengan', 'untuk', 'bagi', 'demi', 
            'tanpa', 'sampai', 'hingga', 'sejak', 'selama', 'sepanjang', 'tentang', 
            'mengenai', 'terhadap', 'akan', 'atas', 'bawah', 'depan', 'belakang', 
            'samping', 'antara', 'sekitar', 'seputar', 'lewat', 'melalui', 'oleh',
            
            // Kata ganti (pronomina)
            'saya', 'aku', 'kamu', 'anda', 'engkau', 'dia', 'ia', 'beliau', 'mereka', 
            'kami', 'kita', 'ini', 'itu', 'tersebut', 'yang', 'siapa', 'apa', 'mana',
            
            // Kata bantu / partikel
            'adalah', 'ialah', 'merupakan', 'yaitu', 'yakni', 'bisa', 'dapat', 'mampu',
            'boleh', 'harus', 'wajib', 'perlu', 'mesti', 'hendak', 'hendaknya', 'ingin',
            'mau', 'akan', 'sudah', 'telah', 'pernah', 'belum', 'sedang', 'masih', 'lagi',
            'tidak', 'tak', 'bukan', 'jangan', 'belum', 'tiada',
            
            // Kata keterangan umum
            'sangat', 'amat', 'sekali', 'paling', 'lebih', 'kurang', 'agak', 'cukup',
            'terlalu', 'begitu', 'demikian', 'bagaimana', 'mengapa', 'kenapa', 'kapan',
            'dimana', 'kemana', 'darimana', 'bilamana', 'berapa', 'seberapa',
            
            // Kata umum lainnya
            'hal', 'sebuah', 'suatu', 'satu', 'dua', 'tiga', 'empat', 'lima', 
            'banyak', 'sedikit', 'beberapa', 'semua', 'seluruh', 'tiap', 'setiap', 
            'para', 'berbagai', 'macam', 'jenis', 'lain', 'sama', 'sendiri',
            'saja', 'hanya', 'justru', 'malah', 'bahkan', 'pula', 'pun', 'juga',
            'lalu', 'kemudian', 'setelah', 'sebelum', 'ketika', 'saat', 'waktu',
            'hari', 'bulan', 'tahun', 'minggu', 'jam', 'menit', 'detik',
            'cara', 'tempat', 'orang', 'kali', 'kala', 'masa', 'zaman',
            'menjadi', 'membuat', 'melakukan', 'memberikan', 'mendapatkan', 
            'secara', 'menurut', 'berdasarkan', 'sesuai', 'terkait', 'sehubungan',
            
            // Kata tambahan (berdasarkan feedback)
            'kini', 'akhir', 'awal', 'sekian', 'sekedar', 'semoga', 'berharap',
            'tinggal', 'berusia', 'hidupnya', 'bukanlah', 'tidaklah', 'ataupun',
            'kecil', 'besar', 'lama', 'baru', 'luas', 'tinggi', 'rendah',
            'tahunan', 'bulanan', 'harian', 'mingguan',
            'ditelan', 'dijaganya', 'diwarisi', 'diterima', 'dipanggil', 'digambarkan',
            'mata', 'tangan', 'kaki', 'kepala', 'badan', 'tubuh',
            'desa', 'kota', 'dukuh', 'dusun', 'kampung', 'negeri',
            'ayahnya', 'ibunya', 'anaknya', 'kakeknya', 'neneknya',
            'energinya', 'hidupnya', 'ceritanya', 'kisahnya',
            'klasik', 'modern', 'kuno', 'tradisi', 'kesenian',
            'menggunakan', 'dimengerti', 'menggantungkan', 'mewariskan', 'melestarikan',
            'pencarian', 'pertunjukkan', 'kesederhanaan', 'kehidupan', 'penokohan',
            'berdiri', 'terasing', 'dinegerinya', 'tumbang', 'hilang'
        ];

        // 4. Filter & Count (minimum 4 karakter untuk kata yang bermakna)
        $keywords = [];
        foreach ($words as $word) {
            // Filter: panjang > 4, bukan stopword, bukan angka, bukan kata dengan akhiran 'nya'
            if (strlen($word) > 4 && 
                !in_array($word, $stopwords) && 
                !is_numeric($word) &&
                !preg_match('/^\d+an$/', $word) && // Filter tahun seperti 1960an
                !preg_match('/nya$/', $word)) { // Filter kata berakhiran -nya
                if (!isset($keywords[$word])) {
                    $keywords[$word] = 0;
                }
                $keywords[$word]++;
            }
        }

        // 5. Sort by Frequency
        arsort($keywords);

        // 6. Get Top 30 Keywords (yang paling sering muncul = paling penting)
        $topKeywords = array_keys(array_slice($keywords, 0, 30));
        
        // Return structured data like tag objects
        $results = [];
        foreach ($topKeywords as $word) {
            $results[] = ['name' => $word];
        }

        echo json_encode($results);
        exit;
    }
}
