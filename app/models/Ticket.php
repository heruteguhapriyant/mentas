<?php
// Ticket Model for Pentas Feature

class Ticket {
    private $db;
    private $table = 'tickets';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Get all tickets
     */
    public function getAll() {
        $stmt = $this->db->prepare(
            "SELECT t.*, e.title as event_title, e.event_date 
             FROM {$this->table} t 
             JOIN events e ON t.event_id = e.id 
             ORDER BY t.created_at DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get ticket by ID (alias for getById)
     */
    public function find($id) {
        return $this->getById($id);
    }

    /**
     * Get all tickets for an event (alias for getByEventId)
     */
    public function getByEvent($eventId) {
        $stmt = $this->db->prepare(
            "SELECT t.*, e.title as event_title, e.event_date 
             FROM {$this->table} t 
             JOIN events e ON t.event_id = e.id 
             WHERE t.event_id = :event_id 
             ORDER BY t.created_at DESC"
        );
        $stmt->bindParam(':event_id', $eventId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all tickets for an event (legacy method)
     */
    public function getByEventId($eventId) {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE event_id = :event_id ORDER BY created_at DESC"
        );
        $stmt->bindParam(':event_id', $eventId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get ticket by ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get ticket by code
     */
    public function getByCode($code) {
        $stmt = $this->db->prepare(
            "SELECT t.*, e.title as event_title, e.event_date, e.venue 
             FROM {$this->table} t 
             JOIN events e ON t.event_id = e.id 
             WHERE t.ticket_code = :code"
        );
        $stmt->bindParam(':code', $code);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get tickets by email
     */
    public function getByEmail($email) {
        $stmt = $this->db->prepare(
            "SELECT t.*, e.title as event_title, e.event_date, e.venue 
             FROM {$this->table} t 
             JOIN events e ON t.event_id = e.id 
             WHERE t.email = :email 
             ORDER BY t.created_at DESC"
        );
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Create new ticket
     */
    public function create($data) {
        $ticketCode = $this->generateTicketCode();
        
        $sql = "INSERT INTO {$this->table} 
                (ticket_code, event_id, name, email, phone, quantity, total_price, payment_proof, status, notes) 
                VALUES 
                (:ticket_code, :event_id, :name, :email, :phone, :quantity, :total_price, :payment_proof, :status, :notes)";
        
        $stmt = $this->db->prepare($sql);
        
        $status = $data['status'] ?? 'pending';
        $paymentProof = $data['payment_proof'] ?? null;
        $notes = $data['notes'] ?? null;
        
        $stmt->bindParam(':ticket_code', $ticketCode);
        $stmt->bindParam(':event_id', $data['event_id']);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':phone', $data['phone']);
        $stmt->bindParam(':quantity', $data['quantity']);
        $stmt->bindParam(':total_price', $data['total_price']);
        $stmt->bindParam(':payment_proof', $paymentProof);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':notes', $notes);
        
        if ($stmt->execute()) {
            return $ticketCode;
        }
        return false;
    }

    /**
     * Update ticket
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
     * Confirm ticket payment
     */
    public function confirm($id) {
        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET status = 'confirmed', confirmed_at = NOW() WHERE id = :id"
        );
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    /**
     * Cancel ticket
     */
    public function cancel($id) {
        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET status = 'cancelled' WHERE id = :id"
        );
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    /**
     * Update ticket status
     */
    public function updateStatus($id, $status) {
        $sql = "UPDATE {$this->table} SET status = :status";
        if ($status === 'confirmed') {
            $sql .= ", confirmed_at = NOW()";
        }
        $sql .= " WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':status', $status);
        return $stmt->execute();
    }

    /**
     * Check in ticket
     */
    public function checkIn($ticketCode) {
        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET status = 'checked_in', checked_in_at = NOW() WHERE ticket_code = :code AND status = 'confirmed'"
        );
        $stmt->bindParam(':code', $ticketCode);
        return $stmt->execute();
    }

    /**
     * Upload payment proof
     */
    public function uploadPaymentProof($id, $proofPath) {
        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET payment_proof = :proof WHERE id = :id"
        );
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':proof', $proofPath);
        return $stmt->execute();
    }

    /**
     * Delete ticket
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    /**
     * Generate unique ticket code
     */
    private function generateTicketCode() {
        $code = 'PENTAS-' . strtoupper(substr(uniqid(), -6));
        
        // Check if code exists
        $stmt = $this->db->prepare("SELECT id FROM {$this->table} WHERE ticket_code = :code");
        $stmt->bindParam(':code', $code);
        $stmt->execute();
        
        if ($stmt->fetch()) {
            return $this->generateTicketCode(); // Recursive call if exists
        }
        
        return $code;
    }

    /**
     * Get tickets by status
     */
    public function getByStatus($status) {
        $stmt = $this->db->prepare(
            "SELECT t.*, e.title as event_title, e.event_date 
             FROM {$this->table} t 
             JOIN events e ON t.event_id = e.id 
             WHERE t.status = :status 
             ORDER BY t.created_at DESC"
        );
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Count tickets by status for an event
     */
    public function countByStatusForEvent($eventId, $status) {
        $stmt = $this->db->prepare(
            "SELECT SUM(quantity) as total FROM {$this->table} WHERE event_id = :event_id AND status = :status"
        );
        $stmt->bindParam(':event_id', $eventId);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Get payment settings
     */
    public function getPaymentSettings() {
        $stmt = $this->db->prepare(
            "SELECT * FROM payment_settings WHERE is_active = 1 LIMIT 1"
        );
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
