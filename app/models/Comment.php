<?php

class Comment
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function create($data)
    {
        $sql = 'INSERT INTO comments (post_id, user_id, name, email, body) VALUES (?, ?, ?, ?, ?)';
        $params = [
            $data['post_id'],
            $data['user_id'] ?? null,
            $data['name'],
            $data['email'],
            $data['body']
        ];
        return $this->db->execute($sql, $params);
    }

    public function getByPost($postId)
    {
        $sql = 'SELECT * FROM comments WHERE post_id = ? ORDER BY created_at DESC';
        return $this->db->query($sql, [$postId]);
    }
}
