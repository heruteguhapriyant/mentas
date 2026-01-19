<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="page-header">
    <h1><?= $post ? 'Edit Artikel' : 'Tulis Artikel Baru' ?></h1>
    <a href="<?= BASE_URL ?>/admin/posts" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <form action="<?= BASE_URL ?>/admin/<?= $post ? 'postUpdate/' . $post['id'] : 'postStore' ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Judul Artikel *</label>
            <input type="text" id="title" name="title" class="form-control" required value="<?= htmlspecialchars($post['title'] ?? '') ?>">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label for="category_id">Kategori</label>
                <select id="category_id" name="category_id" class="form-control">
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($post['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-control">
                    <option value="draft" <?= ($post['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="published" <?= ($post['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="cover_image">Cover Image</label>
            <?php if (!empty($post['cover_image'])): ?>
                <div style="margin-bottom: 0.5rem;">
                    <img src="<?= BASE_URL ?>/<?= $post['cover_image'] ?>" style="max-width: 200px; border-radius: 4px;">
                </div>
            <?php endif; ?>
            <input type="file" id="cover_image" name="cover_image" class="form-control" accept="image/*">
        </div>

        <div class="form-group">
            <label for="excerpt">Ringkasan</label>
            <textarea id="excerpt" name="excerpt" class="form-control" rows="2" placeholder="Ringkasan singkat artikel..."><?= htmlspecialchars($post['excerpt'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="body">Isi Artikel *</label>
            <textarea id="body" name="body" class="form-control" rows="15" required placeholder="Tulis isi artikel di sini..."><?= htmlspecialchars($post['body'] ?? '') ?></textarea>
        </div>

        <div style="display: flex; gap: 1rem;">
            <button type="submit" name="status" value="draft" class="btn btn-secondary">
                <i class="fas fa-save"></i> Simpan Draft
            </button>
            <button type="submit" name="status" value="published" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i> Publish
            </button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
