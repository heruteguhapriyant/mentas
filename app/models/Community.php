<?php

/**
 * Community Model - Katalog/Index (Static View)
 */
class Community
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Get all active communities
     */
    public function all()
    {
        return $this->db->query(
            "SELECT * FROM communities WHERE is_active = 1 ORDER BY name ASC"
        );
    }

    /**
     * Find community by ID
     */
    public function find($id)
    {
        return $this->db->queryOne(
            "SELECT * FROM communities WHERE id = ?",
            [$id]
        );
    }

    /**
     * Find community by slug
     */
    public function findBySlug($slug)
    {
        return $this->db->queryOne(
            "SELECT * FROM communities WHERE slug = ?",
            [$slug]
        );
    }

    /**
     * Create new community (Admin only)
     */
    public function create($data)
    {
        $sql = "INSERT INTO communities (name, slug, description, image, location, contact, website, is_active) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $this->db->execute($sql, [
            $data['name'],
            $this->generateSlug($data['name']),
            $data['description'] ?? null,
            $data['image'] ?? null,
            $data['location'] ?? null,
            $data['contact'] ?? null,
            $data['website'] ?? null,
            $data['is_active'] ?? 1
        ]);

        return $this->db->lastInsertId();
    }

    /**
     * Update community
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
        $sql = "UPDATE communities SET " . implode(', ', $fields) . " WHERE id = ?";

        return $this->db->execute($sql, $params);
    }

    /**
     * Delete community
     */
    public function delete($id)
    {
        return $this->db->execute("DELETE FROM communities WHERE id = ?", [$id]);
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
