<?php
// Product Model for Merch Feature

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
     * Get all active products
     */
    public function getAll($activeOnly = true) {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.id";
        
        if ($activeOnly) {
            $sql .= " WHERE p.is_active = 1";
        }
        
        $sql .= " ORDER BY p.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
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
     * Get products by category slug
     */
    public function getByCategory($categorySlug) {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.name as category_name, c.slug as category_slug 
             FROM {$this->table} p
             JOIN categories c ON p.category_id = c.id
             WHERE c.slug = :slug AND p.is_active = 1 
             ORDER BY p.created_at DESC"
        );
        $stmt->bindParam(':slug', $categorySlug);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
     * Update stock
     */
    public function updateStock($id, $quantity) {
        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET stock = stock - :quantity WHERE id = :id AND stock >= :quantity"
        );
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':quantity', $quantity);
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
     * Count products by category
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
}
