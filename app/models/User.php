<?php

/**
 * User Model
 */
class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Get all users
     */
    public function all($role = null, $status = null, $orderBy = 'created_at DESC', $search = null)
    {
        $sql = "SELECT * FROM users WHERE 1=1";
        $params = [];

        if ($role) {
            $sql .= " AND role = ?";
            $params[] = $role;
        }

        if ($status) {
            $sql .= " AND status = ?";
            $params[] = $status;
        }

        if ($search) {
            $sql .= " AND (name LIKE ? OR email LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        $sql .= " ORDER BY " . $orderBy;

        return $this->db->query($sql, $params);
    }



    /**
     * Find user by ID
     */
    public function find($id)
    {
        return $this->db->queryOne(
            "SELECT * FROM users WHERE id = ?",
            [$id]
        );
    }

    /**
     * Find user by email
     */
    public function findByEmail($email)
    {
        return $this->db->queryOne(
            "SELECT * FROM users WHERE email = ?",
            [$email]
        );
    }

    /**
     * Create new user
     */
    public function create($data)
    {
        $sql = "INSERT INTO users (name, email, password, phone, bio, avatar, address, social_media, role, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $this->db->execute($sql, [
            $data['name'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['phone'] ?? null,
            $data['bio'] ?? null,
            $data['avatar'] ?? null,
            $data['address'] ?? null,
            json_encode($data['social_media'] ?? []),
            $data['role'] ?? 'contributor',
            $data['status'] ?? 'pending'
        ]);

        return $this->db->lastInsertId();
    }

    /**
     * Update user
     */
    public function update($id, $data)
    {
        $fields = [];
        $params = [];

        foreach ($data as $key => $value) {
            if ($key === 'password') {
                $fields[] = "password = ?";
                $params[] = password_hash($value, PASSWORD_DEFAULT);
            } elseif ($key === 'social_media') {
                $fields[] = "social_media = ?";
                $params[] = json_encode($value);
            } else {
                $fields[] = "$key = ?";
                $params[] = $value;
            }
        }

        $params[] = $id;
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";

        return $this->db->execute($sql, $params);
    }

    /**
     * Delete user
     */
    public function delete($id)
    {
        return $this->db->execute("DELETE FROM users WHERE id = ?", [$id]);
    }

    /**
     * Verify password
     */
    public function verifyPassword($email, $password)
    {
        $user = $this->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    /**
     * Update status (approve/reject contributor)
     */
    public function updateStatus($id, $status)
    {
        return $this->update($id, ['status' => $status]);
    }

    /**
     * Get pending contributors
     */
    public function getPendingContributors()
    {
        return $this->all('contributor', 'pending');
    }

    /**
     * Get active contributors
     */
    public function getActiveContributors($sortByName = false)
    {
        $orderBy = $sortByName ? 'name ASC' : 'created_at DESC';
        return $this->all('contributor', 'active', $orderBy);
    }
}
