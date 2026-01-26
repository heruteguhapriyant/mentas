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

        <?php 
        // Get array of current post tag IDs
        $postTagIds = [];
        if (!empty($postTags)) {
            foreach ($postTags as $pt) {
                $postTagIds[] = $pt['id'];
            }
        }
        ?>

        <?php if (!empty($allTags)): ?>
        <div class="form-group">
            <label>Tags</label>
            <div style="display: flex; flex-wrap: wrap; gap: 10px; padding: 15px; background: #f8f9fa; border-radius: 8px; border: 1px solid #ddd;">
                <?php foreach ($allTags as $tag): ?>
                    <label style="display: flex; align-items: center; gap: 5px; cursor: pointer; padding: 5px 12px; background: #fff; border-radius: 20px; border: 1px solid #ddd; transition: all 0.2s;">
                        <input type="checkbox" name="tags[]" value="<?= $tag['id'] ?>" 
                               <?= in_array($tag['id'], $postTagIds) ? 'checked' : '' ?>
                               style="margin: 0;">
                        <span><?= htmlspecialchars($tag['name']) ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
            <small class="form-text">Pilih tags yang relevan dengan artikel</small>
        </div>
        <?php endif; ?>

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
