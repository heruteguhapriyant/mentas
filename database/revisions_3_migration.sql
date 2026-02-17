-- Revisions 3.0 Database Migration
-- Run this SQL to add required columns

-- 1. Add plain_password column to users table for admin password recovery
ALTER TABLE users ADD COLUMN plain_password VARCHAR(255) NULL AFTER password;

-- 2. Add image_position column to users table for avatar crop control
ALTER TABLE users ADD COLUMN image_position VARCHAR(20) DEFAULT 'center' AFTER avatar;

-- 3. Add image_position column to zines table for cover crop control
ALTER TABLE zines ADD COLUMN image_position VARCHAR(20) DEFAULT 'center' AFTER cover_image;

-- 4. Add author_id column to zines table for bulletin author/collaborator
ALTER TABLE zines ADD COLUMN author_id INT UNSIGNED NULL AFTER category_id;

-- 5. Create collaborations table for Kolaborasi feature
CREATE TABLE IF NOT EXISTS collaborations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    cover_image VARCHAR(255) NULL,
    description TEXT NULL,
    social_media JSON NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Create collaboration_contributors pivot table
CREATE TABLE IF NOT EXISTS collaboration_contributors (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    collaboration_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_collab_user (collaboration_id, user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
