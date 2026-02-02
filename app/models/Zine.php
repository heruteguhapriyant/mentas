<?php

/**
 * Zine Model - Bulletin Sastra (PDF Support)
 */
class Zine
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Get all active zine categories from DB
     */
    public function getCategories()
    {
        $categoryModel = new Category();
        return $categoryModel->getByType('zine');
    }

    /**
     * Get all active zines with category info
     */
    public function all($activeOnly = true)
    {
        $sql = "SELECT z.*, c.name as category_name, c.slug as category_slug 
                FROM zines z
                LEFT JOIN categories c ON z.category_id = c.id
                ";
        
        if ($activeOnly) {
            $sql .= " WHERE z.is_active = 1";
        }
        
        $sql .= " ORDER BY z.created_at DESC";

        return $this->db->query($sql);
    }

    /**
     * Get zines by category slug
     */
    public function getByCategory($categorySlug)
    {
        return $this->db->query(
            "SELECT z.*, c.name as category_name, c.slug as category_slug 
             FROM zines z
             JOIN categories c ON z.category_id = c.id
             WHERE c.slug = ? AND z.is_active = 1 
             ORDER BY z.created_at DESC",
            [$categorySlug]
        );
    }

    /**
     * Get zines grouped by category (for potential use)
     */
    public function getAllGroupedByCategory()
    {
        // Re-implement if needed based on dynamic cats
        // For now, simpler to just get all
        return $this->all();
    }

    /**
     * Find zine by ID
     */
    public function find($id)
    {
        return $this->db->queryOne(
            "SELECT z.*, c.name as category_name, c.id as category_id
             FROM zines z
             LEFT JOIN categories c ON z.category_id = c.id
             WHERE z.id = ?",
            [$id]
        );
    }

    /**
     * Find zine by slug
     */
    public function findBySlug($slug)
    {
        return $this->db->queryOne(
            "SELECT z.*, c.name as category_name, c.slug as category_slug
             FROM zines z
             LEFT JOIN categories c ON z.category_id = c.id
             WHERE z.slug = ?",
            [$slug]
        );
    }

    /**
     * Create new zine (Admin only)
     */
    public function create($data)
    {
        $sql = "INSERT INTO zines (title, slug, excerpt, content, cover_image, category_id, pdf_file, is_active) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $this->db->execute($sql, [
            $data['title'],
            $this->generateSlug($data['title']),
            $data['excerpt'] ?? null,
            $data['content'] ?? null,
            $data['cover_image'] ?? null,
            $data['category_id'] ?? null,
            $data['pdf_file'] ?? null,
            $data['is_active'] ?? 1
        ]);

        return $this->db->lastInsertId();
    }

    /**
     * Update zine
     */
    public function update($id, $data)
    {
        $fields = [];
        $params = [];

        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $params[] = $value;
        }

        $params[] = $id;
        $sql = "UPDATE zines SET " . implode(', ', $fields) . " WHERE id = ?";

        return $this->db->execute($sql, $params);
    }

    /**
     * Delete zine
     */
    public function delete($id)
    {
        return $this->db->execute("DELETE FROM zines WHERE id = ?", [$id]);
    }

    /**
     * Generate URL-friendly slug
     */
    private function generateSlug($string)
    {
        $slug = strtolower(trim($string));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return trim($slug, '-');
    }
}
