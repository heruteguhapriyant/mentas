<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="page-header">
    <h1><?= $product ? 'Edit Produk' : 'Tambah Produk' ?></h1>
    <a href="<?= BASE_URL ?>/admin/products" class="btn btn-secondary">Kembali</a>
</div>

<div class="card">
    <form action="<?= BASE_URL ?>/admin/<?= $product ? 'productUpdate/' . $product['id'] : 'productStore' ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Nama Produk *</label>
            <input type="text" name="name" class="form-control" value="<?= $product['name'] ?? '' ?>" required>
        </div>

        <div class="form-group">
            <label>Kategori *</label>
            <select name="category_id" class="form-control" required>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($product['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-row">
            <div class="form-group" style="flex: 1;">
                <label>Harga (Rp) *</label>
                <input type="number" name="price" class="form-control" value="<?= $product['price'] ?? 0 ?>" min="0" required>
            </div>
            <div class="form-group" style="flex: 1;">
                <label>Stok *</label>
                <input type="number" name="stock" class="form-control" value="<?= $product['stock'] ?? 0 ?>" min="0" required>
            </div>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="description" class="form-control" rows="5"><?= $product['description'] ?? '' ?></textarea>
        </div>

        <div class="form-group">
            <label>Cover Image</label>
            <?php if (!empty($product['cover_image'])): ?>
                <div style="margin-bottom: 10px;">
                    <img src="<?= BASE_URL ?>/<?= $product['cover_image'] ?>" alt="" style="max-width: 150px; border-radius: 8px;">
                </div>
            <?php endif; ?>
            <input type="file" name="cover_image" class="form-control" accept="image/*">
            <small class="form-text">Format: JPG, PNG. Max 2MB</small>
        </div>

        <div class="form-group">
            <label>Nomor WhatsApp</label>
            <input type="text" name="whatsapp_number" class="form-control" value="<?= $product['whatsapp_number'] ?? '6283895189649' ?>" placeholder="6281234567890">
            <small class="form-text">Format internasional tanpa +, contoh: 6281234567890</small>
        </div>

        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="is_active" <?= ($product['is_active'] ?? 1) ? 'checked' : '' ?>>
                Aktif (tampil di website)
            </label>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?= $product ? 'Update' : 'Simpan' ?></button>
            <a href="<?= BASE_URL ?>/admin/products" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<style>
.form-row { display: flex; gap: 20px; }
@media (max-width: 768px) { .form-row { flex-direction: column; gap: 0; } }
</style>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
