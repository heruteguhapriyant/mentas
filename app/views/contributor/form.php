<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<main class="container contributor-form-page">
    <div class="page-header">
        <h1><?= $post ? 'Edit Artikel' : 'Tulis Artikel Baru' ?></h1>
        <a href="<?= BASE_URL ?>/contributor" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    <?php $flash = getFlash(); ?>
    <?php if ($flash): ?>
        <div class="alert alert-<?= $flash['type'] ?>">
            <?= $flash['message'] ?>
        </div>
    <?php endif; ?>

    <div class="form-card">
        <form action="<?= BASE_URL ?>/contributor/<?= $post ? 'update/' . $post['id'] : 'store' ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Judul Artikel *</label>
                <input type="text" id="title" name="title" class="form-control" required value="<?= htmlspecialchars($post['title'] ?? '') ?>">
            </div>

            <div class="form-row">
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
                    <label for="cover_image">Cover Image</label>
                    <?php if (!empty($post['cover_image'])): ?>
                        <div style="margin-bottom: 0.5rem;">
                            <img src="<?= BASE_URL ?>/<?= $post['cover_image'] ?>" style="max-width: 150px; border-radius: 4px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" id="cover_image" name="cover_image" class="form-control" accept="image/*">
                </div>
            </div>

            <div class="form-group">
                <label for="excerpt">Ringkasan</label>
                <textarea id="excerpt" name="excerpt" class="form-control" rows="2" placeholder="Ringkasan singkat artikel..."><?= htmlspecialchars($post['excerpt'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label for="body">Isi Artikel *</label>
                <textarea id="body" name="body" class="form-control" rows="15" required placeholder="Tulis isi artikel di sini..."><?= htmlspecialchars($post['body'] ?? '') ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" name="status" value="draft" class="btn btn-secondary">
                    <i class="fa-solid fa-save"></i> Simpan Draft
                </button>
                <button type="submit" name="status" value="published" class="btn btn-primary">
                    <i class="fa-solid fa-paper-plane"></i> Publish
                </button>
            </div>
        </form>
    </div>
</main>

<style>
.contributor-form-page { padding: 2rem 0; }
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
.form-card { background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.form-group { margin-bottom: 1rem; }
.form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; }
.form-control { width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; font-family: inherit; }
.form-control:focus { outline: none; border-color: #007bff; }
textarea.form-control { resize: vertical; min-height: 100px; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.form-actions { display: flex; gap: 1rem; margin-top: 1.5rem; }
.btn { display: inline-block; padding: 0.75rem 1.5rem; border: none; border-radius: 4px; text-decoration: none; cursor: pointer; font-size: 1rem; }
.btn-primary { background: #007bff; color: #fff; }
.btn-secondary { background: #6c757d; color: #fff; }
.alert { padding: 1rem; border-radius: 4px; margin-bottom: 1rem; }
.alert-error { background: #f8d7da; color: #721c24; }

@media (max-width: 768px) {
    .form-row { grid-template-columns: 1fr; }
}
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
