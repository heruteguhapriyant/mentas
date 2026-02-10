-- =============================================
-- MENTAS.ID DATABASE SCHEMA
-- =============================================

-- Buat database (jalankan manual jika belum ada)
-- CREATE DATABASE mentas_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE mentas_db;

-- =============================================
-- TABEL USERS (Admin & Contributor)
-- =============================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    bio TEXT,
    avatar VARCHAR(255),
    address TEXT,
    social_media JSON,
    role ENUM('admin', 'contributor') DEFAULT 'contributor',
    status ENUM('pending', 'active', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABEL CATEGORIES (Menu Blog - Dinamis)
-- =============================================
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type ENUM('blog', 'zine', 'merch') DEFAULT 'blog',
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABEL POSTS (Artikel Blog)
-- =============================================
CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    excerpt TEXT,
    body LONGTEXT,
    cover_image VARCHAR(255),
    category_id INT,
    author_id INT,
    status ENUM('draft', 'published') DEFAULT 'draft',
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    published_at TIMESTAMP NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABEL ZINES (Bulletin Sastra - Statis)
-- =============================================
CREATE TABLE IF NOT EXISTS zines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content LONGTEXT,
    cover_image VARCHAR(255),
    pdf_link VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABEL COMMUNITIES (Katalog/Index - Statis)
-- =============================================
CREATE TABLE IF NOT EXISTS communities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    image VARCHAR(255),
    location VARCHAR(255),
    contact VARCHAR(255),
    website VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABEL EVENTS (Agenda)
-- =============================================
CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) DEFAULT NULL,
    description TEXT,
    venue VARCHAR(255),
    venue_address TEXT,
    event_date DATETIME NOT NULL,
    end_date DATETIME,
    cover_image VARCHAR(255),
    ticket_price DECIMAL(12,2) DEFAULT 0.00,
    ticket_quota INT DEFAULT 0,
    tickets_sold INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABEL PAYMENT SETTINGS (Rekening Bank/QRIS)
-- =============================================
CREATE TABLE IF NOT EXISTS payment_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bank_name VARCHAR(100) NOT NULL DEFAULT 'Mandiri',
    account_number VARCHAR(50) NOT NULL DEFAULT '1840005061294',
    account_name VARCHAR(255) NOT NULL DEFAULT 'Abimanyu Ianocta Per',
    qris_image VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABEL COMMENTS
-- =============================================
CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    body TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- DATA AWAL: Admin User
-- Password: admin123 (bcrypt hash)
-- =============================================
INSERT INTO users (name, email, password, role, status) VALUES
('Admin Mentas', 'admin@mentas.id', '$2y$10$CrNLJmt0PsTeNU2Y/1XQv.vcOBXS4/bC24wT.me0qo8njwBtFhFv', 'admin', 'active');

-- =============================================
-- DATA AWAL: Kategori Blog (Opsi B)
-- =============================================
INSERT INTO categories (name, slug, description, sort_order) VALUES
('Berita', 'berita', 'Berita terkini seputar seni dan budaya', 1),
('Esai', 'esai', 'Esai dan opini tentang kesenian', 2),
('Komunitas', 'komunitas', 'Cerita dan kegiatan komunitas seni', 3),
('Tradisi', 'tradisi', 'Warisan tradisi dan budaya nusantara', 4),
('Ekosistem', 'ekosistem', 'Ekosistem seni dan budaya Indonesia', 5);

-- =============================================
-- INDEXES
-- =============================================
CREATE INDEX idx_posts_category ON posts(category_id);
CREATE INDEX idx_posts_author ON posts(author_id);
CREATE INDEX idx_posts_status ON posts(status);
CREATE INDEX idx_posts_slug ON posts(slug);
CREATE INDEX idx_categories_slug ON categories(slug);
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_status ON users(status);
