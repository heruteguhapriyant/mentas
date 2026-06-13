<?php

/**
 * ContentController - Updated dengan Pagination
 * Handles: /blog, /blog/{category}, /blog/{post-slug}
 */
class ContentController extends Controller
{
    private $postModel;
    private $categoryModel;
    private $tagModel;
    private $commentModel;
    private $itemsPerPage = 8; // Jumlah artikel per halaman

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
        $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

        // Handle 'blog', 'author', 'contributors' types
        if (!in_array($type, ['blog', 'author', 'contributors'])) {
            return $this->view('errors/404');
        }

        // Get only BLOG categories for sidebar/filter
        $categories = $this->categoryModel->all(true, 'blog');

        // 0️⃣ HALAMAN AUTHOR (/author/{id})
        if ($type === 'author' && $slug) {
            $userModel = new User();
            $author = $userModel->find($slug); // $slug here is the ID actually

            if (!$author) {
                return $this->view('errors/404');
            }

            // Count total posts by author
            $totalPosts = $this->postModel->countAll(null, $author['id']);
            
            // 1. Get Posts
            $latestPosts = $this->postModel->getByAuthor($author['id'], 'published', 20, 0);

            // 2. Get Zines
            $zineModel = new Zine();
            $zines = $zineModel->getByAuthor($author['id']);

            // 3. Get Collaborations
            $collabModel = new Collaboration();
            $collabs = $collabModel->getByUser($author['id']);

            // 4. Get Pentas (Events)
            $eventModel = new Event();
            $pentas = $eventModel->getEventsByContributor($author['id']);

            // 5. Get Produk (Merch)
            $productModel = new Product();
            $produk = $productModel->getByCreator($author['id']);

            // 6. Merge and Normalize
            $feed = [];

            foreach ($latestPosts as $p) {
                $feed[] = [
                    'type'          => 'blog',
                    'title'         => $p['title'],
                    'slug'          => $p['slug'],
                    'cover_image'   => $p['cover_image'],
                    'category_name' => $p['category_name'] ?? 'Artikel',
                    'created_at'    => $p['published_at'],
                    'excerpt'       => $p['excerpt'] ?? substr(strip_tags($p['body']), 0, 100) . '...',
                    'url'           => BASE_URL . '/blog/' . $p['slug']
                ];
            }

            foreach ($zines as $z) {
                $feed[] = [
                    'type'          => 'bulletin',
                    'title'         => $z['title'],
                    'slug'          => $z['slug'],
                    'cover_image'   => $z['cover_image'],
                    'category_name' => $z['category_name'] ?? 'Buletin',
                    'created_at'    => $z['created_at'],
                    'excerpt'       => $z['excerpt'] ?? 'Buletin Sastra',
                    'url'           => BASE_URL . '/bulletin/' . ($z['slug'] ?? '#')
                ];
            }

            foreach ($collabs as $c) {
                $feed[] = [
                    'type'          => 'kolaborasi',
                    'title'         => $c['title'],
                    'slug'          => $c['slug'],
                    'cover_image'   => $c['cover_image'],
                    'category_name' => 'Kolaborasi',
                    'created_at'    => $c['created_at'],
                    'excerpt'       => $c['description'] ?? 'Kolaborasi',
                    'url'           => BASE_URL . '/kolaborasi/' . $c['slug']
                ];
            }

            // 6. Sort by Date DESC
            usort($feed, function ($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });

            // Counts for sidebar
            $counts = [
                'blog'       => $totalPosts,
                'bulletin'   => count($zines),
                'kolaborasi' => count($collabs),
                'merch'      => count($produk),
                'pentas'     => count($pentas),
            ];
            
            $contentItems = [
                'blog'       => $latestPosts,
                'bulletin'   => $zines,
                'kolaborasi' => $collabs,
                'pentas'     => $pentas,
                'merch'      => $produk,
            ];
            
            return $this->view('content/detail/author', [
                'type'          => ['name' => 'Penulis', 'slug' => 'author'],
                'content'       => $author,
                'latestContent' => $feed,
                'posts'         => $latestPosts,
                'categories'    => $categories,
                'counts'        => $counts,
                'contentItems'  => $contentItems,
                'pagination'    => null
            ]);
        }

        // 🔍 SEARCH FUNCTIONALITY (/blog?q=keyword)
        $searchQuery = trim($_GET['q'] ?? '');
        if (!empty($searchQuery) && !$slug) {
            $totalPosts = $this->postModel->countSearch($searchQuery);
            $pagination = paginate($totalPosts, $currentPage, $this->itemsPerPage);
            $posts = $this->postModel->search(
                $searchQuery,
                $pagination['items_per_page'],
                $pagination['offset']
            );
            
            return $this->view('content/list/article', [
                'type'          => ['name' => 'Hasil Pencarian', 'slug' => 'blog'],
                'contents'      => $posts,
                'categories'    => $categories,
                'activeCategory'=> null,
                'searchQuery'   => $searchQuery,
                'pagination'    => $pagination
            ]);
        }

        // 🏷️ TAG FILTERING (/blog?tag=tag-slug)
        $tagSlug = trim($_GET['tag'] ?? '');
        if (!empty($tagSlug) && !$slug) {
            $tagModel = new Tag();
            $tag = $tagModel->findBySlug($tagSlug);
            
            if (!$tag) {
                return $this->view('errors/404');
            }
            
            $totalPosts = $this->postModel->countByTag($tagSlug);
            $pagination = paginate($totalPosts, $currentPage, $this->itemsPerPage);
            $posts = $this->postModel->getByTag(
                $tagSlug,
                $pagination['items_per_page'],
                $pagination['offset']
            );
            
            return $this->view('content/list/article', [
                'type'          => ['name' => 'Tag: ' . $tag['name'], 'slug' => 'blog'],
                'contents'      => $posts,
                'categories'    => $categories,
                'activeCategory'=> null,
                'activeTag'     => $tag,
                'pagination'    => $pagination
            ]);
        }

        // 1️⃣ HALAMAN LIST SEMUA POST (/blog)
        if (!$slug) {
            $totalPosts = $this->postModel->countAll();
            $pagination = paginate($totalPosts, $currentPage, $this->itemsPerPage);
            $posts = $this->postModel->getPaginated(
                $pagination['items_per_page'],
                $pagination['offset']
            );
            
            return $this->view('content/list/article', [
                'type'          => ['name' => 'Blog', 'slug' => 'blog'],
                'contents'      => $posts,
                'categories'    => $categories,
                'activeCategory'=> null,
                'pagination'    => $pagination
            ]);
        }

        // 2️⃣ CEK APAKAH SLUG = KATEGORI (/blog/tradisi)
        $category = $this->categoryModel->findBySlug($slug);
        
        if ($category) {
            $totalPosts = $this->postModel->countAll($slug);
            $pagination = paginate($totalPosts, $currentPage, $this->itemsPerPage);
            $posts = $this->postModel->getByCategory(
                $slug,
                $pagination['items_per_page'],
                $pagination['offset']
            );
            
            return $this->view('content/list/article', [
                'type'          => ['name' => 'Blog', 'slug' => 'blog'],
                'contents'      => $posts,
                'categories'    => $categories,
                'activeCategory'=> $slug,
                'categoryName'  => $category['name'],
                'pagination'    => $pagination
            ]);
        }

        // 3️⃣ SLUG = POST DETAIL (/blog/judul-artikel)
        $post = $this->postModel->findBySlug($slug);
        
        $isPublished = $post && $post['status'] === 'published';
        $canView = $isPublished || isAdmin() || (isLoggedIn() && $post['author_id'] == $_SESSION['user_id']);

        if ($post && $canView) {
            $this->postModel->incrementViews($post['id']);
            
            $recentPosts = $this->postModel->getRecent(5);
            $categoriesWithCount = $this->categoryModel->getWithPostCount();
            $postTags = $this->tagModel->getByPost($post['id']);
            $comments = $this->commentModel->getByPost($post['id']);
            
            return $this->view('content/detail/article', [
                'type'               => ['name' => 'Blog', 'slug' => 'blog'],
                'content'            => $post,
                'categories'         => $categories,
                'recentPosts'        => $recentPosts,
                'categoriesWithCount'=> $categoriesWithCount,
                'postTags'           => $postTags,
                'comments'           => $comments
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