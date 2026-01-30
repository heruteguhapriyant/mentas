-- Migration: Products Table for Merch Feature
-- Run this SQL in phpMyAdmin or MySQL client

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE,
    category ENUM('merchandise', 'buku') NOT NULL DEFAULT 'merchandise',
    description TEXT,
    price DECIMAL(12,2) NOT NULL DEFAULT 0,
    stock INT DEFAULT 0,
    cover_image VARCHAR(255),
    images TEXT,  -- JSON array of additional image paths
    whatsapp_number VARCHAR(20) DEFAULT '6283895189649',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Index for faster queries
CREATE INDEX idx_products_category ON products(category);
CREATE INDEX idx_products_is_active ON products(is_active);
CREATE INDEX idx_products_slug ON products(slug);
