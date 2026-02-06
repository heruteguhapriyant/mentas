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
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <label>Tags dari Database</label>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearDatabaseTags()" style="font-size: 0.85rem;">
                    <i class="fas fa-times"></i> Clear Tags
                </button>
            </div>
            <div class="tags-checkbox-container" style="display: flex; flex-wrap: wrap; gap: 10px; padding: 15px; background: #f8f9fa; border-radius: 8px; border: 1px solid #ddd; max-height: 200px; overflow-y: auto;">
                <?php foreach ($allTags as $tag): ?>
                    <label class="tag-label" style="display: flex; align-items: center; gap: 5px; cursor: pointer; padding: 5px 12px; background: #fff; border-radius: 20px; border: 1px solid #ddd; transition: all 0.2s;">
                        <input type="checkbox" name="tags[]" value="<?= $tag['id'] ?>" 
                               data-tag-name="<?= strtolower($tag['name']) ?>"
                               <?= in_array($tag['id'], $postTagIds) ? 'checked' : '' ?>
                               style="margin: 0;">
                        <span><?= htmlspecialchars($tag['name']) ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
            <small class="form-text">Pilih tags yang sudah ada</small>
        </div>
        <?php endif; ?>

        <!-- Manual Tags / Generated Tags -->
        <div class="form-group">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <label>Tags Baru (dari Artikel)</label>
                <div style="display: flex; gap: 8px;">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearGeneratedTags()" style="font-size: 0.85rem;">
                        <i class="fas fa-times"></i> Clear
                    </button>
                    <button type="button" class="btn btn-sm btn-success" onclick="autoGenerateTags()" style="font-size: 0.85rem;">
                        <i class="fas fa-wand-magic-sparkles"></i> Generate Tags
                    </button>
                </div>
            </div>
            <div id="generated-tags-container" style="display: flex; flex-wrap: wrap; gap: 8px; padding: 15px; background: #e8f5e9; border-radius: 8px; border: 1px solid #c8e6c9; min-height: 50px;">
                <span id="no-tags-placeholder" style="color: #666; font-size: 0.9rem;">Klik "Generate Tags" untuk mengekstrak kata kunci dari artikel</span>
            </div>
            <input type="hidden" name="manual_tags" id="manual_tags_input">
            <small class="form-text">Tags baru akan dibuat otomatis dari kata kunci artikel</small>
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

<style>
.generated-tag-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: #28a745;
    color: white;
    border-radius: 20px;
    font-size: 0.85rem;
}
.generated-tag-chip .remove-tag {
    cursor: pointer;
    font-weight: bold;
    font-size: 1.1rem;
    line-height: 1;
}
.generated-tag-chip .remove-tag:hover {
    color: #ffcdd2;
}
</style>

<script>
let generatedTags = [];

function autoGenerateTags() {
    const title = document.getElementById('title').value;
    const body = document.getElementById('body').value;
    
    if (!title && !body) {
        alert('Mohon isi Judul atau Konten Artikel terlebih dahulu.');
        return;
    }

    const btn = document.querySelector('button[onclick="autoGenerateTags()"]');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';
    btn.disabled = true;

    const formData = new FormData();
    formData.append('title', title);
    formData.append('body', body);

    fetch('<?= BASE_URL ?>/contributor/generateTags', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(tags => {
        if (tags.length > 0) {
            // Add generated tags (avoid duplicates)
            tags.forEach(tag => {
                const tagName = tag.name.charAt(0).toUpperCase() + tag.name.slice(1); // Capitalize
                if (!generatedTags.includes(tagName)) {
                    generatedTags.push(tagName);
                }
            });
            
            renderGeneratedTags();
            updateManualTagsInput();
            
            alert(`Berhasil mengekstrak ${tags.length} kata kunci dari artikel!`);
        } else {
            alert('Tidak dapat menemukan kata kunci yang cocok.');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Terjadi kesalahan saat generate tags.');
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}

function renderGeneratedTags() {
    const container = document.getElementById('generated-tags-container');
    const placeholder = document.getElementById('no-tags-placeholder');
    
    if (generatedTags.length > 0) {
        if (placeholder) placeholder.style.display = 'none';
        
        // Clear existing chips (except placeholder)
        container.querySelectorAll('.generated-tag-chip').forEach(el => el.remove());
        
        generatedTags.forEach(tag => {
            const chip = document.createElement('span');
            chip.className = 'generated-tag-chip';
            chip.innerHTML = `${tag} <span class="remove-tag" onclick="removeGeneratedTag('${tag}')">&times;</span>`;
            container.appendChild(chip);
        });
    } else {
        if (placeholder) placeholder.style.display = 'inline';
        container.querySelectorAll('.generated-tag-chip').forEach(el => el.remove());
    }
}

function removeGeneratedTag(tagName) {
    generatedTags = generatedTags.filter(t => t !== tagName);
    renderGeneratedTags();
    updateManualTagsInput();
}

function updateManualTagsInput() {
    document.getElementById('manual_tags_input').value = generatedTags.join(',');
}

// Clear all database tags (uncheck all checkboxes)
function clearDatabaseTags() {
    document.querySelectorAll('input[name="tags[]"]').forEach(checkbox => {
        checkbox.checked = false;
    });
}

// Clear all generated tags
function clearGeneratedTags() {
    generatedTags = [];
    renderGeneratedTags();
    updateManualTagsInput();
}
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
