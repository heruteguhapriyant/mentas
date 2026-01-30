-- =============================================
-- MIGRATION: Bulletin Sastra PDF Support
-- Date: 2026-01-30
-- Description: Add PDF file support and categories to zines table
-- =============================================

-- Add new columns to zines table
ALTER TABLE zines 
  ADD COLUMN category ENUM('esai', 'prosa', 'puisi', 'cerpen', 'rupa', 'zine') NOT NULL DEFAULT 'esai' AFTER cover_image,
  ADD COLUMN pdf_file VARCHAR(255) NULL AFTER category,
  ADD COLUMN excerpt TEXT NULL AFTER pdf_file;

-- Add index for category filtering
CREATE INDEX idx_zines_category ON zines(category);

-- Note: The 'content' column is kept for backward compatibility
-- It can be removed in a future migration if all data has been migrated to PDF
