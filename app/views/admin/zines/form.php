<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="page-header">
    <h1><?= $zine ? 'Edit Buletin' : 'Tambah Buletin' ?></h1>
    <a href="<?= BASE_URL ?>/admin/zines" class="btn btn-secondary">← Kembali</a>
</div>

<div class="card">
    <form action="<?= BASE_URL ?>/admin/<?= $zine ? 'zineUpdate/'.$zine['id'] : 'zineStore' ?>" method="POST" enctype="multipart/form-data">
        
        <div class="form-group">
            <label>Judul Buletin *</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($zine['title'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label>Kategori *</label>
            <select name="category_id" class="form-control" required>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($zine['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Deskripsi Singkat (Excerpt)</label>
            <textarea name="excerpt" class="form-control" rows="3" placeholder="Deskripsi singkat yang akan ditampilkan di card..."><?= htmlspecialchars($zine['excerpt'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label>Cover Image</label>
            <?php if ($zine && $zine['cover_image']): ?>
                <div style="margin-bottom: 0.5rem;">
                    <img src="<?= BASE_URL ?>/<?= $zine['cover_image'] ?>" alt="" style="max-width: 200px; border-radius: 4px;">
                </div>
            <?php endif; ?>
            <input type="file" name="cover_image" class="form-control" accept="image/*">
            <small class="form-text text-muted">Format: JPG, PNG, GIF. Ukuran maksimal: 2MB</small>
        </div>

        <div class="form-group">
            <label>Link Google Drive (PDF) *</label>
            <input type="text" name="pdf_link" class="form-control" value="<?= htmlspecialchars($zine['pdf_link'] ?? '') ?>" placeholder="https://drive.google.com/..." required>
            <small class="form-text text-muted">Masukkan link sharing Google Drive untuk file PDF buletin.</small>
        </div>

        <div class="form-group">
            <label class="form-check">
                <input type="checkbox" name="is_active" value="1" <?= ($zine['is_active'] ?? 1) ? 'checked' : '' ?>>
                <span>Aktif (tampil di website)</span>
            </label>
        </div>

        <button type="submit" class="btn btn-primary"><?= $zine ? 'Update' : 'Simpan' ?></button>
    </form>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
