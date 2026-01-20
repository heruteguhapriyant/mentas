<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="page-header">
    <h1><?= $community ? 'Edit Komunitas' : 'Tambah Komunitas' ?></h1>
    <a href="<?= BASE_URL ?>/admin/communities" class="btn btn-secondary">← Kembali</a>
</div>

<div class="card">
    <form action="<?= BASE_URL ?>/admin/<?= $community ? 'communityUpdate/'.$community['id'] : 'communityStore' ?>" method="POST" enctype="multipart/form-data">
        
        <div class="form-group">
            <label>Nama Komunitas *</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($community['name'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label>Logo/Gambar</label>
            <?php if ($community && $community['image']): ?>
                <div style="margin-bottom: 0.5rem;">
                    <img src="<?= BASE_URL ?>/<?= $community['image'] ?>" alt="" style="max-width: 150px; border-radius: 4px;">
                </div>
            <?php endif; ?>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="description" class="form-control" rows="5"><?= htmlspecialchars($community['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label>Lokasi</label>
            <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($community['location'] ?? '') ?>" placeholder="Contoh: Jakarta, Indonesia">
        </div>

        <div class="form-group">
            <label>Kontak</label>
            <input type="text" name="contact" class="form-control" value="<?= htmlspecialchars($community['contact'] ?? '') ?>" placeholder="Email atau nomor telepon">
        </div>

        <div class="form-group">
            <label>Website</label>
            <input type="url" name="website" class="form-control" value="<?= htmlspecialchars($community['website'] ?? '') ?>" placeholder="https://...">
        </div>

        <div class="form-group">
            <label class="form-check">
                <input type="checkbox" name="is_active" value="1" <?= ($community['is_active'] ?? 1) ? 'checked' : '' ?>>
                <span>Aktif (tampil di website)</span>
            </label>
        </div>

        <button type="submit" class="btn btn-primary"><?= $community ? 'Update' : 'Simpan' ?></button>
    </form>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
