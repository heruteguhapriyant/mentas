<?php

/**
 * Post Model - Blog Articles (Updated with Pagination)
 */
class Post
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Get all published posts (Updated dengan pagination support)
     */
    public function all($limit = null, $offset = 0, $status = 'published')
    {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug, u.name as author_name
                FROM posts p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN users u ON p.author_id = u.id
                WHERE p.status = ?
                ORDER BY p.published_at DESC, p.created_at DESC";

        $params = [$status];

        if ($limit) {
            $sql .= " LIMIT ? OFFSET ?";
            $params[] = (int)$limit;
            $params[] = (int)$offset;
        }

        return $this->db->query($sql, $params);
    }

    /**
     * Get posts by category (Updated dengan pagination support)
     */
    public function getByCategory($categorySlug, $limit = null, $offset = 0)
    {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug, u.name as author_name
                FROM posts p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN users u ON p.author_id = u.id
                WHERE p.status = 'published' AND c.slug = ?
                ORDER BY p.published_at DESC, p.created_at DESC";

        $params = [$categorySlug];

        if ($limit) {
            $sql .= " LIMIT ? OFFSET ?";
            $params[] = (int)$limit;
            $params[] = (int)$offset;
        }

        return $this->db->query($sql, $params);
    }

    /**
     * Get posts by author (Updated dengan pagination support)
     */
    public function getByAuthor($authorId, $status = 'published', $limit = null, $offset = 0)
    {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug
                FROM posts p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.author_id = ?";

        $params = [$authorId];

        if ($status) {
            $sql .= " AND p.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY p.created_at DESC";

        if ($limit) {
            $sql .= " LIMIT ? OFFSET ?";
            $params[] = (int)$limit;
            $params[] = (int)$offset;
        }

        return $this->db->query($sql, $params);
    }

    /**
     * Count total posts (BARU - untuk pagination)
     * 
     * @param string|null $categorySlug Filter by category
     * @param int|null $authorId Filter by author
     * @param string $status Status filter
     * @return int Total count
     */
    public function countAll($categorySlug = null, $authorId = null, $status = 'published')
    {
        $sql = "SELECT COUNT(DISTINCT p.id) as total 
                FROM posts p";
        
        $params = [];
        $conditions = ["p.status = ?"];
        $params[] = $status;

        // Join category jika perlu filter
        if ($categorySlug) {
            $sql .= " LEFT JOIN categories c ON p.category_id = c.id";
            $conditions[] = "c.slug = ?";
            $params[] = $categorySlug;
        }

        // Filter by author
        if ($authorId) {
            $conditions[] = "p.author_id = ?";
            $params[] = $authorId;
        }

        $sql .= " WHERE " . implode(' AND ', $conditions);

        $result = $this->db->queryOne($sql, $params);
        return (int)($result['total'] ?? 0);
    }

    /**
     * Get paginated posts (BARU - method khusus untuk pagination)
     * 
     * @param int $limit Items per page
     * @param int $offset Offset
     * @param string|null $categorySlug Filter by category
     * @param int|null $authorId Filter by author
     * @param string $orderBy Sort column
     * @param string $orderDir Sort direction
     * @return array Posts
     */
    public function getPaginated($limit = 9, $offset = 0, $categorySlug = null, $authorId = null, $orderBy = 'published_at', $orderDir = 'DESC')
    {
        $sql = "SELECT p.*, 
                       c.name as category_name, 
                       c.slug as category_slug, 
                       u.name as author_name
                FROM posts p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN users u ON p.author_id = u.id
                WHERE p.status = 'published'";
        
        $params = [];

        // Filter by category
        if ($categorySlug) {
            $sql .= " AND c.slug = ?";
            $params[] = $categorySlug;
        }

        // Filter by author
        if ($authorId) {
            $sql .= " AND p.author_id = ?";
            $params[] = $authorId;
        }

        // Order
        $allowedOrderColumns = ['published_at', 'created_at', 'title', 'views'];
        $orderBy = in_array($orderBy, $allowedOrderColumns) ? $orderBy : 'published_at';
        $orderDir = strtoupper($orderDir) === 'ASC' ? 'ASC' : 'DESC';
        
        $sql .= " ORDER BY p.$orderBy $orderDir, p.created_at DESC";
        
        // Limit and offset
        $sql .= " LIMIT ? OFFSET ?";
        $params[] = (int)$limit;
        $params[] = (int)$offset;

        return $this->db->query($sql, $params);
    }

    /**
     * Find post by ID
     */
    public function find($id)
    {
        return $this->db->queryOne(
            "SELECT p.*, c.name as category_name, c.slug as category_slug, u.name as author_name, u.bio as author_bio, u.avatar as author_avatar, u.social_media as author_social
             FROM posts p
             LEFT JOIN categories c ON p.category_id = c.id
             LEFT JOIN users u ON p.author_id = u.id
             WHERE p.id = ?",
            [$id]
        );
    }

    /**
     * Find post by slug
     */
    public function findBySlug($slug)
    {
        return $this->db->queryOne(
            "SELECT p.*, c.name as category_name, c.slug as category_slug, u.name as author_name, u.bio as author_bio, u.avatar as author_avatar, u.social_media as author_social
             FROM posts p
             LEFT JOIN categories c ON p.category_id = c.id
             LEFT JOIN users u ON p.author_id = u.id
             WHERE p.slug = ?",
            [$slug]
        );
    }

    /**
     * Create new post
     */
    public function create($data)
    {
        $sql = "INSERT INTO posts (title, slug, excerpt, body, cover_image, category_id, author_id, status, published_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $publishedAt = ($data['status'] ?? 'draft') === 'published' ? date('Y-m-d H:i:s') : null;

        $this->db->execute($sql, [
            $data['title'],
            $this->generateSlug($data['title']),
            $data['excerpt'] ?? null,
            $data['body'] ?? null,
            $data['cover_image'] ?? null,
            $data['category_id'] ?? null,
            $data['author_id'],
            $data['status'] ?? 'draft',
            $publishedAt
        ]);

        return $this->db->lastInsertId();
    }

    /**
     * Update post
     */
    public function update($id, $data)
    {
        $fields = [];
        $params = [];

        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $params[] = $value;
        }

        // Set published_at if status changed to published
        if (isset($data['status']) && $data['status'] === 'published') {
            $post = $this->find($id);
            if (!$post['published_at']) {
                $fields[] = "published_at = ?";
                $params[] = date('Y-m-d H:i:s');
            }
        }

        // Regenerate slug if title changed
        if (isset($data['title']) && !isset($data['slug'])) {
            $fields[] = "slug = ?";
            $params[] = $this->generateSlug($data['title']);
        }

        $params[] = $id;
        $sql = "UPDATE posts SET " . implode(', ', $fields) . " WHERE id = ?";

        return $this->db->execute($sql, $params);
    }

    /**
     * Delete post
     */
    public function delete($id)
    {
        return $this->db->execute("DELETE FROM posts WHERE id = ?", [$id]);
    }

    /**
     * Increment view count
     */
    public function incrementViews($id)
    {
        return $this->db->execute(
            "UPDATE posts SET views = views + 1 WHERE id = ?",
            [$id]
        );
    }

    /**
     * Get recent posts
     */
    public function getRecent($limit = 5)
    {
        return $this->all($limit);
    }

    /**
     * Count posts (Existing method - tetap dipertahankan untuk backward compatibility)
     */
    public function count($status = 'published')
    {
        $result = $this->db->queryOne(
            "SELECT COUNT(*) as total FROM posts WHERE status = ?",
            [$status]
        );
        return $result['total'];
    }

    /**
     * Generate URL-friendly slug
     */
    private function generateSlug($string)
    {
        $slug = strtolower(trim($string));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');

        // Check for duplicate
        $original = $slug;
        $counter = 1;
        while ($this->findBySlug($slug)) {
            $slug = $original . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Search posts (BONUS - untuk fitur search)
     * 
     * @param string $keyword Search keyword
     * @param int $limit Limit results
     * @param int $offset Offset
     * @return array
     */
    public function search($keyword, $limit = 9, $offset = 0)
    {
        $sql = "SELECT p.*, 
                       c.name as category_name, 
                       c.slug as category_slug, 
                       u.name as author_name
                FROM posts p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN users u ON p.author_id = u.id
                WHERE p.status = 'published' 
                AND (p.title LIKE ? OR p.excerpt LIKE ? OR p.body LIKE ?)
                ORDER BY p.published_at DESC
                LIMIT ? OFFSET ?";

        $searchTerm = "%$keyword%";
        return $this->db->query($sql, [
            $searchTerm, 
            $searchTerm, 
            $searchTerm,
            (int)$limit,
            (int)$offset
        ]);
    }

    /**
     * Count search results (BONUS - untuk pagination search)
     * 
     * @param string $keyword Search keyword
     * @return int
     */
    public function countSearch($keyword)
    {
        $sql = "SELECT COUNT(*) as total 
                FROM posts p
                WHERE p.status = 'published' 
                AND (p.title LIKE ? OR p.excerpt LIKE ? OR p.body LIKE ?)";

        $searchTerm = "%$keyword%";
        $result = $this->db->queryOne($sql, [$searchTerm, $searchTerm, $searchTerm]);
        return (int)($result['total'] ?? 0);
    }
}