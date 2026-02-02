<?php

/**
 * Category Model - Blog Menu (Dynamic)
 */
class Category
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Get all categories, optionally filtered by type
     */
    public function all($activeOnly = true, $type = null)
    {
        $sql = "SELECT * FROM categories";
        $params = [];
        $conditions = [];

        if ($activeOnly) {
            $conditions[] = "is_active = 1";
        }

        if ($type) {
            $conditions[] = "type = ?";
            $params[] = $type;
        }
        
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }
        
        $sql .= " ORDER BY sort_order ASC, name ASC";

        return $this->db->query($sql, $params);
    }

    /**
     * Get categories by type
     */
    public function getByType($type)
    {
        return $this->all(true, $type);
    }

    /**
     * Find category by ID
     */
    public function find($id)
    {
        return $this->db->queryOne(
            "SELECT * FROM categories WHERE id = ?",
            [$id]
        );
    }

    /**
     * Find category by slug
     */
    public function findBySlug($slug)
    {
        return $this->db->queryOne(
            "SELECT * FROM categories WHERE slug = ?",
            [$slug]
        );
    }

    /**
     * Create new category
     */
    public function create($data)
    {
        $sql = "INSERT INTO categories (name, slug, type, description, is_active, sort_order) 
                VALUES (?, ?, ?, ?, ?, ?)";

        $this->db->execute($sql, [
            $data['name'],
            $this->generateSlug($data['name']),
            $data['type'] ?? 'blog', // Default to blog
            $data['description'] ?? null,
            $data['is_active'] ?? 1,
            $data['sort_order'] ?? 0
        ]);

        return $this->db->lastInsertId();
    }

    /**
     * Update category
     */
    public function update($id, $data)
    {
        $fields = [];
        $params = [];

        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $params[] = $value;
        }

        // Regenerate slug if name changed
        if (isset($data['name']) && !isset($data['slug'])) {
            $fields[] = "slug = ?";
            $params[] = $this->generateSlug($data['name']);
        }

        $params[] = $id;
        $sql = "UPDATE categories SET " . implode(', ', $fields) . " WHERE id = ?";

        return $this->db->execute($sql, $params);
    }

    /**
     * Delete category
     */
    public function delete($id)
    {
        return $this->db->execute("DELETE FROM categories WHERE id = ?", [$id]);
    }

    /**
     * Toggle active status
     */
    public function toggleActive($id)
    {
        return $this->db->execute(
            "UPDATE categories SET is_active = NOT is_active WHERE id = ?",
            [$id]
        );
    }

    /**
     * Get post count per category (BLOG Only)
     */
    public function getWithPostCount()
    {
        return $this->db->query(
            "SELECT c.*, COUNT(p.id) as post_count 
             FROM categories c 
             LEFT JOIN posts p ON c.id = p.category_id AND p.status = 'published'
             WHERE c.is_active = 1 AND c.type = 'blog'
             GROUP BY c.id
             ORDER BY c.sort_order ASC"
        );
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
