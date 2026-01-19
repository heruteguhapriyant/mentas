<?php

/**
 * ContentController - Blog Content Handler
 * Handles: /blog, /blog/{category}, /blog/{post-slug}
 */
class ContentController extends Controller
{
    private $postModel;
    private $categoryModel;

    public function __construct()
    {
        $this->postModel = new Post();
        $this->categoryModel = new Category();
    }

    /**
     * Handle blog routes
     */
    public function handle()
    {
        $type = $_GET['type'] ?? null;
        $slug = $_GET['slug'] ?? null;

        // Only handle 'blog' type for now
        if ($type !== 'blog') {
            return $this->view('errors/404');
        }

        // Get all categories for sidebar/filter
        $categories = $this->categoryModel->all(true);

        // 1️⃣ HALAMAN LIST SEMUA POST (/blog)
        if (!$slug) {
            $posts = $this->postModel->all(20);
            return $this->view('content/list/article', [
                'type' => ['name' => 'Blog', 'slug' => 'blog'],
                'contents' => $posts,
                'categories' => $categories,
                'activeCategory' => null
            ]);
        }

        // 2️⃣ CEK APAKAH SLUG = KATEGORI (/blog/tradisi)
        $category = $this->categoryModel->findBySlug($slug);
        
        if ($category) {
            $posts = $this->postModel->getByCategory($slug);
            return $this->view('content/list/article', [
                'type' => ['name' => 'Blog', 'slug' => 'blog'],
                'contents' => $posts,
                'categories' => $categories,
                'activeCategory' => $slug,
                'categoryName' => $category['name']
            ]);
        }

        // 3️⃣ SLUG = POST DETAIL (/blog/judul-artikel)
        $post = $this->postModel->findBySlug($slug);
        
        if ($post && $post['status'] === 'published') {
            // Increment view count
            $this->postModel->incrementViews($post['id']);
            
            return $this->view('content/detail/article', [
                'type' => ['name' => 'Blog', 'slug' => 'blog'],
                'content' => $post,
                'categories' => $categories
            ]);
        }

        return $this->view('errors/404');
    }

    /**
     * Index method (default)
     */
    public function index()
    {
        $_GET['type'] = 'blog';
        $_GET['slug'] = null;
        return $this->handle();
    }
}

