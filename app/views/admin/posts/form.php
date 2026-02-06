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
            
            <!-- Quill Editor Container -->
            <div id="quill-editor"></div>
            <input type="hidden" name="body" id="body-input">
        </div>

        <style>
            /* Quill Editor Styling */
            #quill-editor {
                height: 400px;
                background: #fff;
                margin-bottom: 20px;
            }
            .ql-toolbar.ql-snow {
                border-radius: 8px 8px 0 0;
                background: #f8f9fa;
                border: 1px solid #ddd;
            }
            .ql-container.ql-snow {
                border-radius: 0 0 8px 8px;
                border: 1px solid #ddd;
                border-top: none;
                font-size: 16px;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            }
            .ql-editor {
                min-height: 350px;
                line-height: 1.8;
            }
            .ql-editor h1, .ql-editor h2, .ql-editor h3 {
                margin-top: 1.5rem;
                margin-bottom: 0.5rem;
            }
            .ql-editor blockquote {
                border-left: 4px solid #d52c2c;
                padding-left: 1rem;
                color: #555;
            }
            .ql-editor a {
                color: #d52c2c;
            }
        </style>

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
                <label>Tags</label>
                <button type="button" class="btn btn-sm btn-success" onclick="autoGenerateTags()" style="font-size: 0.85rem;">
                    <i class="fas fa-wand-magic-sparkles"></i> Generate Tags Otomatis
                </button>
            </div>
            <div class="tags-checkbox-container" style="display: flex; flex-wrap: wrap; gap: 10px; padding: 15px; background: #f8f9fa; border-radius: 8px; border: 1px solid #ddd; max-height: 300px; overflow-y: auto;">
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

<script>
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

    // Use Contributor endpoint since valid for Admin too
    fetch('<?= BASE_URL ?>/contributor/generateTags', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(tags => {
        if (tags.length > 0) {
            let matchCount = 0;
            const checkboxes = document.querySelectorAll('input[name="tags[]"]');
            
            tags.forEach(generatedTag => {
                const searchName = generatedTag.name.toLowerCase();
                
                checkboxes.forEach(cb => {
                    const tagName = cb.getAttribute('data-tag-name');
                    if (tagName === searchName || tagName.includes(searchName) || searchName.includes(tagName)) {
                        if (!cb.checked) {
                            cb.checked = true;
                            cb.parentNode.style.borderColor = '#28a745'; // Highlight match
                            matchCount++;
                        }
                    }
                });
            });

            if (matchCount > 0) {
                alert(`Berhasil menemukan ${matchCount} tags yang cocok dari database!`);
            } else {
                alert('Tags digenerate, tapi tidak ada yang cocok dengan database tags saat ini.');
                console.log('Generated:', tags);
            }
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
</script>

<!-- Quill CSS -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">

<!-- Quill JS -->
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
    // Initialize Quill Editor after DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        var quill = new Quill('#quill-editor', {
            theme: 'snow',
            placeholder: 'Tulis isi artikel di sini...',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'align': [] }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    ['blockquote', 'code-block'],
                    ['link', 'image', 'video'],
                    ['clean']
                ]
            }
        });

        // Load existing content if editing
        <?php if (!empty($post['body'])): ?>
        quill.root.innerHTML = <?= json_encode($post['body']) ?>;
        <?php endif; ?>

        // Sync content to hidden input on form submit
        document.querySelector('form').addEventListener('submit', function() {
            document.getElementById('body-input').value = quill.root.innerHTML;
        });

        // Also sync on any change for draft saving
        quill.on('text-change', function() {
            document.getElementById('body-input').value = quill.root.innerHTML;
        });
    });
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
