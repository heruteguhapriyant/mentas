<?php
// Product Model for Merch Feature

class Product {
    private $db;
    private $table = 'products';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Get all active products
     */
    public function getAll($category = null) {
        $sql = "SELECT * FROM {$this->table} WHERE is_active = 1";
        
        if ($category) {
            $sql .= " AND category = :category";
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        
        if ($category) {
            $stmt->bindParam(':category', $category);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get product by ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
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
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE slug = :slug");
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get products by category
     */
    public function getByCategory($category) {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE category = :category AND is_active = 1 ORDER BY created_at DESC"
        );
        $stmt->bindParam(':category', $category);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Create new product
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} 
                (name, slug, category, description, price, stock, cover_image, images, whatsapp_number, is_active) 
                VALUES 
                (:name, :slug, :category, :description, :price, :stock, :cover_image, :images, :whatsapp_number, :is_active)";
        
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':slug', $data['slug']);
        $stmt->bindParam(':category', $data['category']);
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
    public function countByCategory($category) {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) as total FROM {$this->table} WHERE category = :category AND is_active = 1"
        );
        $stmt->bindParam(':category', $category);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
}
