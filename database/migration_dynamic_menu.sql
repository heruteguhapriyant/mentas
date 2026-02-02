-- 1. Add type column to categories
ALTER TABLE categories ADD COLUMN type ENUM('blog', 'zine', 'merch') NOT NULL DEFAULT 'blog' AFTER name;

-- 2. Insert Merch Categories
INSERT INTO categories (name, slug, type, description) VALUES
('Merchandise', 'merchandise', 'merch', 'Aksesoris dan Pakaian Official'),
('Buku', 'buku', 'merch', 'Buku dan Terbitan');

-- 3. Insert Zine Categories
-- Note: 'esai' might collide with existing blog 'esai', so we use unique slugs if needed
INSERT INTO categories (name, slug, type, description) VALUES
('Esai', 'esai-sastra', 'zine', 'Esai Sastra'),
('Prosa', 'prosa', 'zine', 'Prosa Sastra'),
('Puisi', 'puisi', 'zine', 'Puisi Sastra'),
('Cerpen', 'cerpen', 'zine', 'Cerita Pendek'),
('Seni Rupa', 'seni-rupa', 'zine', 'Seni Rupa dan Visual'),
('Zine', 'zine-digital', 'zine', 'Zine Digital');

-- 4. Add category_id to zines
ALTER TABLE zines ADD COLUMN category_id INT DEFAULT NULL AFTER cover_image;
ALTER TABLE zines ADD CONSTRAINT fk_zines_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL;

-- 5. Add category_id to products
ALTER TABLE products ADD COLUMN category_id INT DEFAULT NULL AFTER slug;
ALTER TABLE products ADD CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL;

-- 6. Migrate existing data for Zines
-- Mapping ENUM values to new category IDs
UPDATE zines SET category_id = (SELECT id FROM categories WHERE type='zine' AND slug='esai-sastra') WHERE category = 'esai';
UPDATE zines SET category_id = (SELECT id FROM categories WHERE type='zine' AND slug='prosa') WHERE category = 'prosa';
UPDATE zines SET category_id = (SELECT id FROM categories WHERE type='zine' AND slug='puisi') WHERE category = 'puisi';
UPDATE zines SET category_id = (SELECT id FROM categories WHERE type='zine' AND slug='cerpen') WHERE category = 'cerpen';
UPDATE zines SET category_id = (SELECT id FROM categories WHERE type='zine' AND slug='seni-rupa') WHERE category = 'rupa';
UPDATE zines SET category_id = (SELECT id FROM categories WHERE type='zine' AND slug='zine-digital') WHERE category = 'zine';

-- 7. Migrate existing data for Products
UPDATE products SET category_id = (SELECT id FROM categories WHERE type='merch' AND slug='merchandise') WHERE category = 'merchandise';
UPDATE products SET category_id = (SELECT id FROM categories WHERE type='merch' AND slug='buku') WHERE category = 'buku';
