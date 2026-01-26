<?php

/**
 * Tag Model - Post Tags
 */
class Tag
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Get all tags
     */
    public function all()
    {
        return $this->db->query("SELECT * FROM tags ORDER BY name ASC");
    }

    /**
     * Find tag by ID
     */
    public function find($id)
    {
        return $this->db->queryOne(
            "SELECT * FROM tags WHERE id = ?",
            [$id]
        );
    }

    /**
     * Find tag by slug
     */
    public function findBySlug($slug)
    {
        return $this->db->queryOne(
            "SELECT * FROM tags WHERE slug = ?",
            [$slug]
        );
    }

    /**
     * Get tags for a specific post
     */
    public function getByPost($postId)
    {
        return $this->db->query(
            "SELECT t.* FROM tags t
             INNER JOIN post_tags pt ON t.id = pt.tag_id
             WHERE pt.post_id = ?
             ORDER BY t.name ASC",
            [$postId]
        );
    }

    /**
     * Get popular tags (most used)
     */
    public function getPopular($limit = 10)
    {
        return $this->db->query(
            "SELECT t.*, COUNT(pt.post_id) as post_count
             FROM tags t
             LEFT JOIN post_tags pt ON t.id = pt.tag_id
             GROUP BY t.id
             ORDER BY post_count DESC, t.name ASC
             LIMIT ?",
            [$limit]
        );
    }

    /**
     * Create new tag
     */
    public function create($data)
    {
        $sql = "INSERT INTO tags (name, slug) VALUES (?, ?)";

        $this->db->execute($sql, [
            $data['name'],
            $this->generateSlug($data['name'])
        ]);

        return $this->db->lastInsertId();
    }

    /**
     * Find or Create tag by name
     */
    public function findOrCreate($name)
    {
        $name = trim($name);
        if (empty($name)) return false;

        // Try to find by name first
        $tag = $this->db->queryOne("SELECT * FROM tags WHERE name = ?", [$name]);
        if ($tag) {
            return $tag['id'];
        }

        // Create if not exists
        return $this->create(['name' => $name]);
    }

    /**
     * Search tags by name
     */
    public function search($query, $limit = 10)
    {
        return $this->db->query(
            "SELECT * FROM tags WHERE name LIKE ? ORDER BY name ASC LIMIT ?",
            ["%$query%", $limit]
        );
    }

    /**
     * Attach tags to a post
     */
    public function attachToPost($postId, $tagIds)
    {
        // First, remove existing tags
        $this->db->execute("DELETE FROM post_tags WHERE post_id = ?", [$postId]);

        // Then, attach new tags
        foreach ($tagIds as $tagId) {
            $this->db->execute(
                "INSERT INTO post_tags (post_id, tag_id) VALUES (?, ?)",
                [$postId, $tagId]
            );
        }
    }

    /**
     * Update tag
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
        $sql = "UPDATE tags SET " . implode(', ', $fields) . " WHERE id = ?";

        return $this->db->execute($sql, $params);
    }

    /**
     * Delete tag
     */
    public function delete($id)
    {
        // First remove from post_tags
        $this->db->execute("DELETE FROM post_tags WHERE tag_id = ?", [$id]);
        // Then remove tag
        return $this->db->execute("DELETE FROM tags WHERE id = ?", [$id]);
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

        return $slug;
    }
}
