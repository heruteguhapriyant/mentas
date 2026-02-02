<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="page-header">
    <h1><?= $category ? 'Edit Kategori' : 'Tambah Kategori' ?></h1>
    <a href="<?= BASE_URL ?>/admin/categories" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card" style="max-width: 600px;">
    <form action="<?= BASE_URL ?>/admin/<?= $category ? 'categoryUpdate/' . $category['id'] : 'categoryStore' ?>" method="POST">
        <div class="form-group">
            <label for="name">Nama Kategori *</label>
            <input type="text" id="name" name="name" class="form-control" required value="<?= htmlspecialchars($category['name'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="type">Tipe Kategori *</label>
            <?php 
            // Get type from category (edit) or query param (create)
            $selectedType = $category['type'] ?? ($_GET['type'] ?? 'blog'); 
            ?>
            <select id="type" name="type" class="form-control" required>
                <option value="blog" <?= $selectedType == 'blog' ? 'selected' : '' ?>>Blog</option>
                <option value="zine" <?= $selectedType == 'zine' ? 'selected' : '' ?>>Bulletin Sastra (Zine)</option>
                <option value="merch" <?= $selectedType == 'merch' ? 'selected' : '' ?>>Merchandise</option>
            </select>
        </div>

        <div class="form-group">
            <label for="description">Deskripsi</label>
            <textarea id="description" name="description" class="form-control" rows="3"><?= htmlspecialchars($category['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="sort_order">Urutan</label>
            <input type="number" id="sort_order" name="sort_order" class="form-control" value="<?= $category['sort_order'] ?? 0 ?>" min="0">
            <small style="color: #666;">Urutan tampil di menu (angka kecil = tampil duluan)</small>
        </div>

        <div class="form-group">
            <label class="form-check">
                <input type="checkbox" name="is_active" <?= ($category['is_active'] ?? 1) ? 'checked' : '' ?>>
                Aktif (tampil di menu)
            </label>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan
        </button>
    </form>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
