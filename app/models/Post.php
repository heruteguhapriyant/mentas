<?php

/**
 * Post Model - Blog Articles
 */
class Post
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Get all published posts
     */
    public function all($limit = null, $offset = 0)
    {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug, u.name as author_name
                FROM posts p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN users u ON p.author_id = u.id
                WHERE p.status = 'published'
                ORDER BY p.published_at DESC, p.created_at DESC";

        if ($limit) {
            $sql .= " LIMIT $limit OFFSET $offset";
        }

        return $this->db->query($sql);
    }

    /**
     * Get posts by category
     */
    public function getByCategory($categorySlug, $limit = null)
    {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug, u.name as author_name
                FROM posts p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN users u ON p.author_id = u.id
                WHERE p.status = 'published' AND c.slug = ?
                ORDER BY p.published_at DESC, p.created_at DESC";

        if ($limit) {
            $sql .= " LIMIT $limit";
        }

        return $this->db->query($sql, [$categorySlug]);
    }

    /**
     * Get posts by author
     */
    public function getByAuthor($authorId, $status = null)
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
     * Count posts
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
}
