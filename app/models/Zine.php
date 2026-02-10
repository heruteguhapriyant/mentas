<?php

/**
 * Zine Model - Bulletin Sastra (Updated dengan Pagination)
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
     * Get all active zines with category info (Updated dengan pagination support)
     */
    public function all($limit = null, $offset = 0, $activeOnly = true, $search = null)
    {
        $sql = "SELECT z.*, c.name as category_name, c.slug as category_slug 
                FROM zines z
                LEFT JOIN categories c ON z.category_id = c.id";
        
        $params = [];
        $conditions = [];
        
        if ($activeOnly) {
            $conditions[] = "z.is_active = 1";
        }

        if ($search) {
            $conditions[] = "(z.title LIKE ? OR z.content LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }
        
        $sql .= " ORDER BY z.created_at DESC";

        // Add pagination
        if ($limit !== null) {
            $sql .= " LIMIT ? OFFSET ?";
            $params[] = (int)$limit;
            $params[] = (int)$offset;
        }

        return $this->db->query($sql, $params);
    }

    /**
     * Get zines by category slug (Updated dengan pagination support)
     */
    public function getByCategory($categorySlug, $limit = null, $offset = 0)
    {
        $sql = "SELECT z.*, c.name as category_name, c.slug as category_slug 
                FROM zines z
                JOIN categories c ON z.category_id = c.id
                WHERE c.slug = ? AND z.is_active = 1 
                ORDER BY z.created_at DESC";
        
        $params = [$categorySlug];
        
        // Add pagination
        if ($limit !== null) {
            $sql .= " LIMIT ? OFFSET ?";
            $params[] = (int)$limit;
            $params[] = (int)$offset;
        }

        return $this->db->query($sql, $params);
    }

    /**
     * Count all zines (BARU - untuk pagination)
     * 
     * @param string|null $categorySlug Filter by category
     * @param bool $activeOnly Only active zines
     * @return int
     */
    public function countAll($categorySlug = null, $activeOnly = true)
    {
        if ($categorySlug) {
            $sql = "SELECT COUNT(*) as total 
                    FROM zines z
                    INNER JOIN categories c ON z.category_id = c.id
                    WHERE c.slug = ?";
            
            $params = [$categorySlug];
            
            if ($activeOnly) {
                $sql .= " AND z.is_active = 1";
            }
            
            $result = $this->db->queryOne($sql, $params);
        } else {
            $sql = "SELECT COUNT(*) as total FROM zines";
            
            if ($activeOnly) {
                $sql .= " WHERE is_active = 1";
            }
            
            $result = $this->db->queryOne($sql);
        }
        
        return (int)($result['total'] ?? 0);
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
        $sql = "INSERT INTO zines (title, slug, excerpt, content, cover_image, category_id, pdf_link, is_active) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $this->db->execute($sql, [
            $data['title'],
            $this->generateSlug($data['title']),
            $data['excerpt'] ?? null,
            $data['content'] ?? null,
            $data['cover_image'] ?? null,
            $data['category_id'] ?? null,
            $data['pdf_link'] ?? null,
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
     * Increment download count (BARU - untuk tracking downloads)
     * 
     * @param int $id Zine ID
     * @return bool
     */
    public function incrementDownloads($id)
    {
        $sql = "UPDATE zines SET downloads = downloads + 1 WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }

    /**
     * Get recent zines (BARU - untuk sidebar/widget)
     * 
     * @param int $limit Number of zines to return
     * @return array
     */
    public function getRecent($limit = 5)
    {
        return $this->all($limit, 0);
    }

    /**
     * Get popular zines (BARU - berdasarkan download count)
     * 
     * @param int $limit Number of zines to return
     * @return array
     */
    public function getPopular($limit = 5)
    {
        $sql = "SELECT z.*, c.name as category_name, c.slug as category_slug
                FROM zines z
                LEFT JOIN categories c ON z.category_id = c.id
                WHERE z.is_active = 1
                ORDER BY z.downloads DESC, z.created_at DESC
                LIMIT ?";
        
        return $this->db->query($sql, [(int)$limit]);
    }

    /**
     * Get zines by issue number (BARU - jika ada sistem numbering)
     * 
     * @param int $issueNumber Issue/edisi number
     * @return array|null
     */
    public function getByIssue($issueNumber)
    {
        $sql = "SELECT z.*, c.name as category_name, c.slug as category_slug
                FROM zines z
                LEFT JOIN categories c ON z.category_id = c.id
                WHERE z.issue_number = ? AND z.is_active = 1";
        
        return $this->db->queryOne($sql, [$issueNumber]);
    }

    /**
     * Get latest issue (BARU)
     * 
     * @return array|null
     */
    public function getLatestIssue()
    {
        $sql = "SELECT z.*, c.name as category_name, c.slug as category_slug
                FROM zines z
                LEFT JOIN categories c ON z.category_id = c.id
                WHERE z.is_active = 1
                ORDER BY z.issue_number DESC, z.created_at DESC
                LIMIT 1";
        
        return $this->db->queryOne($sql);
    }

    /**
     * Search zines (BARU - untuk fitur search)
     * 
     * @param string $keyword Search keyword
     * @param int $limit Items per page
     * @param int $offset Offset
     * @return array
     */
    public function search($keyword, $limit = 12, $offset = 0)
    {
        $sql = "SELECT z.*, c.name as category_name, c.slug as category_slug
                FROM zines z
                LEFT JOIN categories c ON z.category_id = c.id
                WHERE z.is_active = 1 
                AND (z.title LIKE ? OR z.excerpt LIKE ? OR z.content LIKE ?)
                ORDER BY z.created_at DESC
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
     * Count search results (BARU - untuk pagination search)
     * 
     * @param string $keyword Search keyword
     * @return int
     */
    public function countSearch($keyword)
    {
        $sql = "SELECT COUNT(*) as total 
                FROM zines z
                WHERE z.is_active = 1 
                AND (z.title LIKE ? OR z.excerpt LIKE ? OR z.content LIKE ?)";
        
        $searchTerm = "%$keyword%";
        $result = $this->db->queryOne($sql, [$searchTerm, $searchTerm, $searchTerm]);
        return (int)($result['total'] ?? 0);
    }

    /**
     * Get zines by year (BARU - untuk archive)
     * 
     * @param int $year Year
     * @param int $limit Items per page
     * @param int $offset Offset
     * @return array
     */
    public function getByYear($year, $limit = null, $offset = 0)
    {
        $sql = "SELECT z.*, c.name as category_name, c.slug as category_slug
                FROM zines z
                LEFT JOIN categories c ON z.category_id = c.id
                WHERE z.is_active = 1 
                AND YEAR(z.created_at) = ?
                ORDER BY z.created_at DESC";
        
        $params = [(int)$year];
        
        if ($limit !== null) {
            $sql .= " LIMIT ? OFFSET ?";
            $params[] = (int)$limit;
            $params[] = (int)$offset;
        }
        
        return $this->db->query($sql, $params);
    }

    /**
     * Get archive years (BARU - untuk dropdown/filter archive)
     * 
     * @return array Array of years
     */
    public function getArchiveYears()
    {
        $sql = "SELECT DISTINCT YEAR(created_at) as year 
                FROM zines 
                WHERE is_active = 1
                ORDER BY year DESC";
        
        $results = $this->db->query($sql);
        return array_column($results, 'year');
    }

    /**
     * Count zines by year (BARU)
     * 
     * @param int $year Year
     * @return int
     */
    public function countByYear($year)
    {
        $sql = "SELECT COUNT(*) as total 
                FROM zines 
                WHERE is_active = 1 AND YEAR(created_at) = ?";
        
        $result = $this->db->queryOne($sql, [(int)$year]);
        return (int)($result['total'] ?? 0);
    }

    /**
     * Generate URL-friendly slug
     */
    /**
     * Generate URL-friendly slug
     */
    private function generateSlug($string)
    {
        $slug = strtolower(trim($string));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        
        // Ensure uniqueness
        $originalSlug = $slug;
        $count = 1;
        
        while ($this->findBySlug($slug)) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        
        return $slug;
    }
}