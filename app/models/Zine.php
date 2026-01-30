<?php

/**
 * Zine Model - Bulletin Sastra (PDF Support)
 */
class Zine
{
    private $db;

    // Available categories
    const CATEGORIES = [
        'esai' => 'Esai',
        'prosa' => 'Prosa',
        'puisi' => 'Puisi',
        'cerpen' => 'Cerpen',
        'rupa' => 'Rupa',
        'zine' => 'Zine'
    ];

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Get all categories
     */
    public static function getCategories()
    {
        return self::CATEGORIES;
    }

    /**
     * Get category label
     */
    public static function getCategoryLabel($category)
    {
        return self::CATEGORIES[$category] ?? ucfirst($category);
    }

    /**
     * Get all active zines
     */
    public function all($activeOnly = true)
    {
        if ($activeOnly) {
            return $this->db->query(
                "SELECT * FROM zines WHERE is_active = 1 ORDER BY created_at DESC"
            );
        }
        return $this->db->query(
            "SELECT * FROM zines ORDER BY created_at DESC"
        );
    }

    /**
     * Get zines by category
     */
    public function getByCategory($category)
    {
        return $this->db->query(
            "SELECT * FROM zines WHERE category = ? AND is_active = 1 ORDER BY created_at DESC",
            [$category]
        );
    }

    /**
     * Get zines grouped by category
     */
    public function getAllGroupedByCategory()
    {
        $zines = $this->all();
        $grouped = [];
        
        foreach (self::CATEGORIES as $key => $label) {
            $grouped[$key] = [];
        }
        
        foreach ($zines as $zine) {
            $category = $zine['category'] ?? 'esai';
            if (isset($grouped[$category])) {
                $grouped[$category][] = $zine;
            }
        }
        
        return $grouped;
    }

    /**
     * Find zine by ID
     */
    public function find($id)
    {
        return $this->db->queryOne(
            "SELECT * FROM zines WHERE id = ?",
            [$id]
        );
    }

    /**
     * Find zine by slug
     */
    public function findBySlug($slug)
    {
        return $this->db->queryOne(
            "SELECT * FROM zines WHERE slug = ?",
            [$slug]
        );
    }

    /**
     * Create new zine (Admin only)
     */
    public function create($data)
    {
        $sql = "INSERT INTO zines (title, slug, excerpt, content, cover_image, category, pdf_file, is_active) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $this->db->execute($sql, [
            $data['title'],
            $this->generateSlug($data['title']),
            $data['excerpt'] ?? null,
            $data['content'] ?? null,
            $data['cover_image'] ?? null,
            $data['category'] ?? 'esai',
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
     * Count zines by category
     */
    public function countByCategory($category = null)
    {
        if ($category) {
            $result = $this->db->queryOne(
                "SELECT COUNT(*) as count FROM zines WHERE category = ? AND is_active = 1",
                [$category]
            );
        } else {
            $result = $this->db->queryOne(
                "SELECT COUNT(*) as count FROM zines WHERE is_active = 1"
            );
        }
        return $result['count'] ?? 0;
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
