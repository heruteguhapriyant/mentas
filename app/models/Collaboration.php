<?php

/**
 * Collaboration Model - Kolaborasi
 */
class Collaboration
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Get all collaborations
     */
    public function all($search = null)
    {
        $sql = "SELECT c.*, GROUP_CONCAT(u.name SEPARATOR ', ') as contributor_names 
                FROM collaborations c 
                LEFT JOIN collaboration_contributors cc ON c.id = cc.collaboration_id 
                LEFT JOIN users u ON cc.user_id = u.id 
                WHERE 1=1";
        $params = [];

        if ($search) {
            $sql .= " AND (c.title LIKE ? OR c.description LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        $sql .= " GROUP BY c.id ORDER BY c.created_at DESC";

        return $this->db->query($sql, $params);
    }

    /**
     * Get active collaborations
     */
    public function getActive()
    {
        return $this->db->query(
            "SELECT c.*, GROUP_CONCAT(u.name SEPARATOR ', ') as contributor_names 
             FROM collaborations c 
             LEFT JOIN collaboration_contributors cc ON c.id = cc.collaboration_id 
             LEFT JOIN users u ON cc.user_id = u.id 
             WHERE c.is_active = 1 
             GROUP BY c.id 
             ORDER BY c.created_at DESC"
        );
    }

    /**
     * Find by ID
     */
    public function find($id)
    {
        return $this->db->queryOne(
            "SELECT * FROM collaborations WHERE id = ?",
            [$id]
        );
    }

    /**
     * Find by slug
     */
    public function findBySlug($slug)
    {
        return $this->db->queryOne(
            "SELECT * FROM collaborations WHERE slug = ?",
            [$slug]
        );
    }

    /**
     * Create new collaboration
     */
    public function create($data)
    {
        $sql = "INSERT INTO collaborations (title, slug, cover_image, description, social_media, is_active) 
                VALUES (?, ?, ?, ?, ?, ?)";

        $this->db->execute($sql, [
            $data['title'],
            $data['slug'],
            $data['cover_image'] ?? null,
            $data['description'] ?? null,
            json_encode($data['social_media'] ?? []),
            $data['is_active'] ?? 1
        ]);

        return $this->db->lastInsertId();
    }

    /**
     * Update collaboration
     */
    public function update($id, $data)
    {
        $fields = [];
        $params = [];

        foreach ($data as $key => $value) {
            if ($key === 'social_media') {
                $fields[] = "social_media = ?";
                $params[] = json_encode($value);
            } else {
                $fields[] = "$key = ?";
                $params[] = $value;
            }
        }

        $params[] = $id;
        $sql = "UPDATE collaborations SET " . implode(', ', $fields) . " WHERE id = ?";

        return $this->db->execute($sql, $params);
    }

    /**
     * Delete collaboration
     */
    public function delete($id)
    {
        // Delete contributors first
        $this->db->execute("DELETE FROM collaboration_contributors WHERE collaboration_id = ?", [$id]);
        return $this->db->execute("DELETE FROM collaborations WHERE id = ?", [$id]);
    }

    /**
     * Count collaborations by user (contributor)
     */
    public function countByUser($userId)
    {
        $sql = "SELECT COUNT(DISTINCT c.id) as total 
                FROM collaborations c 
                JOIN collaboration_contributors cc ON c.id = cc.collaboration_id 
                WHERE cc.user_id = ? AND c.is_active = 1";
        $result = $this->db->queryOne($sql, [$userId]);
        return (int)($result['total'] ?? 0);
    }

    public function getByUser($userId)
    {
        $sql = "SELECT c.*
                FROM collaborations c 
                JOIN collaboration_contributors cc ON c.id = cc.collaboration_id 
                WHERE cc.user_id = ? AND c.is_active = 1 
                ORDER BY c.created_at DESC";
        return $this->db->query($sql, [$userId]);
    }

    /**
     * Get contributors for a collaboration
     */
    public function getContributors($collaborationId)
    {
        return $this->db->query(
            "SELECT u.* FROM users u 
             JOIN collaboration_contributors cc ON u.id = cc.user_id 
             WHERE cc.collaboration_id = ? 
             ORDER BY u.name",
            [$collaborationId]
        );
    }

    /**
     * Sync contributors (remove all, then add new ones)
     */
    public function syncContributors($collaborationId, $userIds = [])
    {
        // Remove existing
        $this->db->execute(
            "DELETE FROM collaboration_contributors WHERE collaboration_id = ?",
            [$collaborationId]
        );

        // Add new
        foreach ($userIds as $userId) {
            $this->db->execute(
                "INSERT INTO collaboration_contributors (collaboration_id, user_id) VALUES (?, ?)",
                [$collaborationId, $userId]
            );
        }
    }

    /**
     * Generate slug
     */
    public function generateSlug($string)
    {
        $slug = strtolower(trim($string));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');

        // Check uniqueness
        $original = $slug;
        $counter = 1;
        while ($this->findBySlug($slug)) {
            $slug = $original . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
