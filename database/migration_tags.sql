-- =============================================
-- MIGRATION: Tags System
-- =============================================

-- Tabel tags
CREATE TABLE IF NOT EXISTS tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel pivot post_tags (many-to-many)
CREATE TABLE IF NOT EXISTS post_tags (
    post_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (post_id, tag_id),
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Index untuk performa
CREATE INDEX idx_post_tags_post ON post_tags(post_id);
CREATE INDEX idx_post_tags_tag ON post_tags(tag_id);
CREATE INDEX idx_tags_slug ON tags(slug);

-- =============================================
-- SAMPLE DATA: Tags
-- =============================================
INSERT INTO tags (name, slug) VALUES
('Seni Rupa', 'seni-rupa'),
('Teater', 'teater'),
('Musik', 'musik'),
('Sastra', 'sastra'),
('Tari', 'tari'),
('Film', 'film'),
('Fotografi', 'fotografi'),
('Budaya Lokal', 'budaya-lokal'),
('Festival', 'festival'),
('Workshop', 'workshop');
