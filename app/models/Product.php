<?php
// Product Model for Merch Feature (Updated dengan Pagination)

class Product {
    private $db;
    private $table = 'products';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Get active merch categories
     */
    public function getCategories() {
        $categoryModel = new Category();
        return $categoryModel->getByType('merch');
    }

    /**
     * Get all active products (Updated dengan pagination support)
     */
    public function getAll($limit = null, $offset = 0, $activeOnly = true) {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.id";
        
        $params = [];
        
        if ($activeOnly) {
            $sql .= " WHERE p.is_active = 1";
        }
        
        $sql .= " ORDER BY p.created_at DESC";
        
        // Add pagination
        if ($limit !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }
        
        $stmt = $this->db->prepare($sql);
        
        if ($limit !== null) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get product by ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.name as category_name, c.id as category_id
             FROM {$this->table} p
             LEFT JOIN categories c ON p.category_id = c.id
             WHERE p.id = :id"
        );
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Find product by ID (alias for getById)
     */
    public function find($id) {
        return $this->getById($id);
    }

    /**
     * Get product by slug
     */
    public function getBySlug($slug) {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.name as category_name, c.slug as category_slug
             FROM {$this->table} p
             LEFT JOIN categories c ON p.category_id = c.id
             WHERE p.slug = :slug"
        );
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get products by category slug (Updated dengan pagination support)
     */
    public function getByCategory($categorySlug, $limit = null, $offset = 0) {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                FROM {$this->table} p
                JOIN categories c ON p.category_id = c.id
                WHERE c.slug = :slug AND p.is_active = 1 
                ORDER BY p.created_at DESC";
        
        // Add pagination
        if ($limit !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':slug', $categorySlug);
        
        if ($limit !== null) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Count all products (BARU - untuk pagination)
     * 
     * @param string|null $categorySlug Filter by category
     * @param bool $activeOnly Only active products
     * @return int
     */
    public function countAll($categorySlug = null, $activeOnly = true) {
        if ($categorySlug) {
            $sql = "SELECT COUNT(*) as total 
                    FROM {$this->table} p
                    JOIN categories c ON p.category_id = c.id
                    WHERE c.slug = :slug";
            
            if ($activeOnly) {
                $sql .= " AND p.is_active = 1";
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':slug', $categorySlug);
        } else {
            $sql = "SELECT COUNT(*) as total FROM {$this->table}";
            
            if ($activeOnly) {
                $sql .= " WHERE is_active = 1";
            }
            
            $stmt = $this->db->prepare($sql);
        }
        
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['total'] ?? 0);
    }

    /**
     * Create new product
     */
    public function create($data) {
        $data['slug'] = $data['slug'] ?? self::generateSlug($data['name']);
        
        $sql = "INSERT INTO {$this->table} 
                (name, slug, category_id, description, price, stock, cover_image, images, whatsapp_number, is_active) 
                VALUES 
                (:name, :slug, :category_id, :description, :price, :stock, :cover_image, :images, :whatsapp_number, :is_active)";
        
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':slug', $data['slug']);
        $stmt->bindParam(':category_id', $data['category_id']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':stock', $data['stock']);
        $stmt->bindParam(':cover_image', $data['cover_image']);
        $stmt->bindParam(':images', $data['images']);
        $stmt->bindParam(':whatsapp_number', $data['whatsapp_number']);
        $stmt->bindParam(':is_active', $data['is_active']);
        
        return $stmt->execute();
    }

    /**
     * Update product
     */
    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];
        
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
            $params[":$key"] = $value;
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute($params);
    }

    /**
     * Delete product
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    /**
     * Update stock quantity (UPDATED - untuk set stock langsung)
     */
    public function updateStock($id, $quantity) {
        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET stock = :quantity WHERE id = :id"
        );
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Decrement stock (BARU - untuk mengurangi stock setelah order)
     */
    public function decrementStock($id, $amount = 1) {
        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET stock = stock - :amount WHERE id = :id AND stock >= :amount"
        );
        $stmt->bindParam(':id', $id);
        $stmt->bindValue(':amount', (int)$amount, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Generate slug from name
     */
    public static function generateSlug($name) {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return trim($slug, '-');
    }

    /**
     * Count products by category (kept for backward compatibility)
     */
    public function countByCategory($categorySlug) {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) as total 
             FROM {$this->table} p
             JOIN categories c ON p.category_id = c.id
             WHERE c.slug = :slug AND p.is_active = 1"
        );
        $stmt->bindParam(':slug', $categorySlug);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    /**
     * Get featured products (BARU - untuk homepage/banner)
     * 
     * @param int $limit Number of products to return
     * @return array
     */
    public function getFeatured($limit = 4) {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.is_active = 1 AND p.is_featured = 1
                ORDER BY p.created_at DESC
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get new arrivals (BARU - produk terbaru)
     * 
     * @param int $limit Number of products to return
     * @return array
     */
    public function getNewArrivals($limit = 8) {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.is_active = 1
                ORDER BY p.created_at DESC
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Search products (BARU - untuk fitur search)
     * 
     * @param string $keyword Search keyword
     * @param int $limit Items per page
     * @param int $offset Offset
     * @return array
     */
    public function search($keyword, $limit = 12, $offset = 0) {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.is_active = 1 
                AND (p.name LIKE :keyword OR p.description LIKE :keyword)
                ORDER BY p.created_at DESC
                LIMIT :limit OFFSET :offset";
        
        $searchTerm = "%$keyword%";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':keyword', $searchTerm);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Count search results (BARU - untuk pagination search)
     * 
     * @param string $keyword Search keyword
     * @return int
     */
    public function countSearch($keyword) {
        $sql = "SELECT COUNT(*) as total 
                FROM {$this->table} p
                WHERE p.is_active = 1 
                AND (p.name LIKE :keyword OR p.description LIKE :keyword)";
        
        $searchTerm = "%$keyword%";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':keyword', $searchTerm);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['total'] ?? 0);
    }

    /**
     * Get low stock products (BARU - untuk admin notification)
     * 
     * @param int $threshold Stock threshold
     * @param int $limit Number of products
     * @return array
     */
    public function getLowStock($threshold = 10, $limit = 10) {
        $sql = "SELECT p.*, c.name as category_name
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.is_active = 1 AND p.stock <= :threshold AND p.stock > 0
                ORDER BY p.stock ASC
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':threshold', (int)$threshold, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get out of stock products (BARU - untuk admin)
     * 
     * @return array
     */
    public function getOutOfStock() {
        $sql = "SELECT p.*, c.name as category_name
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.is_active = 1 AND p.stock = 0
                ORDER BY p.updated_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}