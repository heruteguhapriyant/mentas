<?php

/**
 * Zine Model - Bulletin Sastra (Static View)
 */
class Zine
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Get all active zines
     */
    public function all()
    {
        return $this->db->query(
            "SELECT * FROM zines WHERE is_active = 1 ORDER BY created_at DESC"
        );
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
        $sql = "INSERT INTO zines (title, slug, content, cover_image, is_active) VALUES (?, ?, ?, ?, ?)";

        $this->db->execute($sql, [
            $data['title'],
            $this->generateSlug($data['title']),
            $data['content'] ?? null,
            $data['cover_image'] ?? null,
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
