<?php

/**
 * ContentController - Blog Content Handler
 * Handles: /blog, /blog/{category}, /blog/{post-slug}
 */
class ContentController extends Controller
{
    private $postModel;
    private $categoryModel;
    private $tagModel;

    private $commentModel;

    public function __construct()
    {
        $this->postModel = new Post();
        $this->categoryModel = new Category();
        $this->tagModel = new Tag();
        $this->commentModel = new Comment();
    }

    /**
     * Handle blog routes
     */
    public function handle()
    {
        $type = $_GET['type'] ?? null;
        $slug = $_GET['slug'] ?? null;

        // Handle 'blog', 'author', 'contributors' types
        if (!in_array($type, ['blog', 'author', 'contributors'])) {
            return $this->view('errors/404');
        }

        // Get all categories for sidebar/filter
        $categories = $this->categoryModel->all(true);

        // 0️⃣ HALAMAN AUTHOR (/author/{id})
        if ($type === 'author' && $slug) {
            $userModel = new User();
            $author = $userModel->find($slug); // $slug here is the ID actually

            if (!$author) {
                return $this->view('errors/404');
            }

            $posts = $this->postModel->getByAuthor($author['id'], 'published');
            
            return $this->view('content/detail/author', [ // Use dedicated author detail view
                'type' => ['name' => 'Penulis', 'slug' => 'author'],
                'content' => $author, // Pass author data as main content
                'posts' => $posts,
                'categories' => $categories
            ]);
        }



        // Get all categories for sidebar/filter
        $categories = $this->categoryModel->all(true);

        // 1️⃣ HALAMAN LIST SEMUA POST (/blog)
        if (!$slug) {
            $posts = $this->postModel->all();
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
            
            // Get sidebar data
            $recentPosts = $this->postModel->getRecent(5);
            $categoriesWithCount = $this->categoryModel->getWithPostCount();
            $postTags = $this->tagModel->getByPost($post['id']);
            $comments = $this->commentModel->getByPost($post['id']);
            
            return $this->view('content/detail/article', [
                'type' => ['name' => 'Blog', 'slug' => 'blog'],
                'content' => $post,
                'categories' => $categories,
                'recentPosts' => $recentPosts,
                'categoriesWithCount' => $categoriesWithCount,
                'postTags' => $postTags,
                'comments' => $comments
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

