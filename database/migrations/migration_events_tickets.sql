-- Migration: Events & Tickets Tables for Pentas Feature
-- Run this SQL in phpMyAdmin or MySQL client

-- Events table
CREATE TABLE IF NOT EXISTS events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE,
    description TEXT,
    venue VARCHAR(255),
    venue_address TEXT,
    event_date DATETIME NOT NULL,
    end_date DATETIME,
    cover_image VARCHAR(255),
    ticket_price DECIMAL(12,2) DEFAULT 0,  -- 0 = gratis
    ticket_quota INT DEFAULT 0,  -- 0 = unlimited
    tickets_sold INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tickets/Registrations table
CREATE TABLE IF NOT EXISTS tickets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ticket_code VARCHAR(50) UNIQUE NOT NULL,  -- Format: PENTAS-XXXXXX
    event_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    quantity INT DEFAULT 1,
    total_price DECIMAL(12,2) DEFAULT 0,
    payment_proof VARCHAR(255),  -- Upload bukti transfer
    status ENUM('pending', 'confirmed', 'cancelled', 'checked_in') DEFAULT 'pending',
    notes TEXT,
    checked_in_at TIMESTAMP NULL,
    confirmed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Indexes for faster queries
CREATE INDEX idx_events_is_active ON events(is_active);
CREATE INDEX idx_events_event_date ON events(event_date);
CREATE INDEX idx_events_slug ON events(slug);

CREATE INDEX idx_tickets_event_id ON tickets(event_id);
CREATE INDEX idx_tickets_status ON tickets(status);
CREATE INDEX idx_tickets_ticket_code ON tickets(ticket_code);
CREATE INDEX idx_tickets_email ON tickets(email);

-- Payment settings table (for bank info)
CREATE TABLE IF NOT EXISTS payment_settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    bank_name VARCHAR(100) NOT NULL DEFAULT 'Mandiri',
    account_number VARCHAR(50) NOT NULL DEFAULT '1840005061294',
    account_name VARCHAR(255) NOT NULL DEFAULT 'Abimanyu Ianocta Per',
    qris_image VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default payment setting
INSERT INTO payment_settings (bank_name, account_number, account_name) 
VALUES ('Mandiri', '1840005061294', 'Abimanyu Ianocta Per');
