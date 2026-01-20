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
            <label>Cover Image</label>
            <?php if ($zine && $zine['cover_image']): ?>
                <div style="margin-bottom: 0.5rem;">
                    <img src="<?= BASE_URL ?>/<?= $zine['cover_image'] ?>" alt="" style="max-width: 200px; border-radius: 4px;">
                </div>
            <?php endif; ?>
            <input type="file" name="cover_image" class="form-control" accept="image/*">
        </div>

        <div class="form-group">
            <label>Konten</label>
            <textarea name="content" class="form-control" rows="10"><?= htmlspecialchars($zine['content'] ?? '') ?></textarea>
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
