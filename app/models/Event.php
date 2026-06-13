<?php
// Event Model for Pentas Feature

class Event {
    private $db;
    private $table = 'events';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Get all active events
     */
    public function getAll($search = null) {
        $sql = "SELECT * FROM {$this->table} WHERE is_active = 1";
        $params = [];

        if ($search) {
            $sql .= " AND (title LIKE :search OR description LIKE :search2 OR venue LIKE :search3)";
            $params[':search'] = "%$search%";
            $params[':search2'] = "%$search%";
            $params[':search3'] = "%$search%";
        }

        $sql .= " ORDER BY event_date ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get upcoming events with search & pagination
     */
    public function getUpcoming($search = '', $offset = 0, $limit = 9) {
        // Jika dipanggil dengan cara lama getUpcoming(20), tangani gracefully
        if (is_numeric($search)) {
            $limit  = (int)$search;
            $search = '';
            $offset = 0;
        }

        $sql = "SELECT * FROM {$this->table} WHERE is_active = 1 AND event_date >= NOW()";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (title LIKE :search OR description LIKE :search2 OR venue LIKE :search3)";
            $params[':search']  = "%$search%";
            $params[':search2'] = "%$search%";
            $params[':search3'] = "%$search%";
        }

        $sql .= " ORDER BY event_date ASC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * Get past events with pagination
     */
    public function getPast($offset = 0, $limit = 9, $search = '') {
        // Graceful handling jika dipanggil cara lama getPast(10)
        if (func_num_args() === 1 && $offset <= 50) {
            $limit  = $offset;
            $offset = 0;
        }

        $sql    = "SELECT * FROM {$this->table} WHERE is_active = 1 AND event_date < NOW()";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (title LIKE :search OR description LIKE :search2 OR venue LIKE :search3)";
            $params[':search']  = "%$search%";
            $params[':search2'] = "%$search%";
            $params[':search3'] = "%$search%";
        }

        $sql .= " ORDER BY event_date DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * Count upcoming events (for pagination)
     */
    public function countUpcoming($search = '') {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE is_active = 1 AND event_date >= NOW()";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (title LIKE :search OR description LIKE :search2 OR venue LIKE :search3)";
            $params[':search']  = "%$search%";
            $params[':search2'] = "%$search%";
            $params[':search3'] = "%$search%";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Count past events (for pagination)
     */
    public function countPast($search = '') {
        $sql    = "SELECT COUNT(*) FROM {$this->table} WHERE is_active = 1 AND event_date < NOW()";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (title LIKE :search OR description LIKE :search2 OR venue LIKE :search3)";
            $params[':search']  = "%$search%";
            $params[':search2'] = "%$search%";
            $params[':search3'] = "%$search%";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Get event by ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Find event by ID (alias for getById)
     */
    public function find($id) {
        return $this->getById($id);
    }

    /**
     * Get event by slug
     */
    public function getBySlug($slug) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE slug = :slug");
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create new event
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} 
                (title, slug, description, venue, venue_address, event_date, end_date, cover_image, ticket_price, ticket_quota, is_active) 
                VALUES 
                (:title, :slug, :description, :venue, :venue_address, :event_date, :end_date, :cover_image, :ticket_price, :ticket_quota, :is_active)";
        
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':slug', $data['slug']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':venue', $data['venue']);
        $stmt->bindParam(':venue_address', $data['venue_address']);
        $stmt->bindParam(':event_date', $data['event_date']);
        $stmt->bindParam(':end_date', $data['end_date']);
        $stmt->bindParam(':cover_image', $data['cover_image']);
        $stmt->bindParam(':ticket_price', $data['ticket_price']);
        $stmt->bindParam(':ticket_quota', $data['ticket_quota']);
        $stmt->bindParam(':is_active', $data['is_active']);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId(); // return ID untuk sync kontributor
        }
        return false;
    }

    /**
     * Update event
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
     * Delete event
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    /**
     * Increment tickets sold
     */
    public function incrementTicketsSold($id, $quantity = 1) {
        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET tickets_sold = tickets_sold + :quantity WHERE id = :id"
        );
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':quantity', $quantity);
        return $stmt->execute();
    }

    /**
     * Check if tickets available
     */
    public function hasAvailableTickets($id) {
        $event = $this->getById($id);
        if (!$event) return false;
        
        // If quota is 0, it's unlimited
        if ($event['ticket_quota'] == 0) return true;
        
        return $event['tickets_sold'] < $event['ticket_quota'];
    }

    /**
     * Get available tickets count
     */
    public function getAvailableTickets($id) {
        $event = $this->getById($id);
        if (!$event) return 0;
        
        // If quota is 0, return unlimited indicator
        if ($event['ticket_quota'] == 0) return -1; // -1 means unlimited
        
        return max(0, $event['ticket_quota'] - $event['tickets_sold']);
    }

    /**
     * Generate slug from title
     */
    public static function generateSlug($title) {
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return trim($slug, '-');
    }

    /**
     * Check if event is free
     */
    public function isFree($id) {
        $event = $this->getById($id);
        return $event && $event['ticket_price'] == 0;
    }

    // =========================================================
    // CONTRIBUTOR METHODS
    // =========================================================

    /**
     * Ambil semua kontributor aktif (untuk form admin)
     */
    public function getActiveContributors() {
        $stmt = $this->db->query(
            "SELECT id, name FROM users 
             WHERE role = 'contributor' AND status = 'active' 
             ORDER BY name ASC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ambil user_id kontributor yang sudah dipilih untuk event tertentu
     * (digunakan untuk pre-check checkbox di form edit)
     */
    public function getEventContributors($eventId) {
        $stmt = $this->db->prepare(
            "SELECT user_id FROM event_contributors WHERE event_id = ?"
        );
        $stmt->execute([$eventId]);
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'user_id');
    }

    /**
     * Ambil data kontributor lengkap (id + name) untuk halaman detail publik
     */
    public function getEventContributorsDetail($eventId) {
        $stmt = $this->db->prepare(
            "SELECT u.id, u.name
             FROM event_contributors ec
             JOIN users u ON ec.user_id = u.id
             WHERE ec.event_id = ?
             ORDER BY u.name ASC"
        );
        $stmt->execute([$eventId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Sync kontributor event (hapus lama, insert baru)
     * Dipanggil saat store maupun update event
     */
    public function syncEventContributors($eventId, array $userIds) {
        // Hapus semua relasi lama
        $stmt = $this->db->prepare(
            "DELETE FROM event_contributors WHERE event_id = ?"
        );
        $stmt->execute([$eventId]);

        // Insert relasi baru
        if (!empty($userIds)) {
            $insert = $this->db->prepare(
                "INSERT INTO event_contributors (event_id, user_id) VALUES (?, ?)"
            );
            foreach ($userIds as $userId) {
                $insert->execute([$eventId, (int)$userId]);
            }
        }
    }

    /**
     * Ambil event yang melibatkan user tertentu (untuk halaman profil author)
     */
    public function getEventsByContributor($userId) {
        $stmt = $this->db->prepare(
            "SELECT e.id, e.title, e.slug, e.event_date
             FROM event_contributors ec
             JOIN events e ON ec.event_id = e.id
             WHERE ec.user_id = ? AND e.is_active = 1
             ORDER BY e.event_date DESC"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}