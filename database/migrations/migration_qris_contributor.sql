-- Migration: Add QRIS image field to users table for contributor support
-- Run this migration in MySQL/phpMyAdmin

-- Add qris_image column to users table
ALTER TABLE users
ADD COLUMN qris_image VARCHAR(255) NULL AFTER social_media;

-- Add index for faster lookups (optional)
CREATE INDEX idx_users_qris ON users(qris_image);
