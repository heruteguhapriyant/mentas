<?php require_once __DIR__ . '/layout/header.php'; ?>

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
                
                <!-- Quill Editor Container -->
                <div id="quill-editor"></div>
                <input type="hidden" name="body" id="body-input">
            </div>

            <style>
                /* Quill Editor Container */
                #quill-editor {
                    height: 400px;
                    background: #fff;
                    margin-bottom: 20px;
                }
                /* Quill Editor Styling */
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
                .ql-snow .ql-picker.ql-header .ql-picker-label::before,
                .ql-snow .ql-picker.ql-header .ql-picker-item::before {
                    content: 'Normal';
                }
                .ql-snow .ql-picker.ql-header .ql-picker-label[data-value="1"]::before,
                .ql-snow .ql-picker.ql-header .ql-picker-item[data-value="1"]::before {
                    content: 'Heading 1';
                }
                .ql-snow .ql-picker.ql-header .ql-picker-label[data-value="2"]::before,
                .ql-snow .ql-picker.ql-header .ql-picker-item[data-value="2"]::before {
                    content: 'Heading 2';
                }
                .ql-snow .ql-picker.ql-header .ql-picker-label[data-value="3"]::before,
                .ql-snow .ql-picker.ql-header .ql-picker-item[data-value="3"]::before {
                    content: 'Heading 3';
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
                <label>Tags</label>
                <div class="tags-container">
                    <?php foreach ($allTags as $tag): ?>
                        <label class="tag-checkbox">
                            <input type="checkbox" name="tags[]" value="<?= $tag['id'] ?>" 
                                   <?= in_array($tag['id'], $postTagIds) ? 'checked' : '' ?>>
                            <span><?= htmlspecialchars($tag['name']) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
                </div>
                <div class="manual-tags-wrapper">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                        <label style="margin-bottom: 0;">Tambah Tag Lainnya:</label>
                        <button type="button" class="btn-sm btn-secondary" onclick="autoGenerateTags()" style="background: #28a745; border: none; font-size: 0.8rem; display: flex; align-items: center; gap: 5px;">
                            <i class="fa-solid fa-wand-magic-sparkles"></i> Generate Tags Otomatis
                        </button>
                    </div>
                    <div class="tag-input-container">
                        <div id="selected-tags-container"></div>
                        <input type="text" id="tag-input" class="tag-input-field" placeholder="Ketik tag..." autocomplete="off">
                    </div>
                    <ul id="tag-suggestions" class="tag-suggestions" style="display: none;"></ul>
                    <input type="hidden" name="manual_tags" id="manual_tags_input">
                </div>
                <small>Ketik untuk mencari tag yang sudah ada atau tekan Enter untuk membuat tag baru.</small>
            </div>
            <?php endif; ?>

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



<script>
document.addEventListener('DOMContentLoaded', function() {
    const tagInput = document.getElementById('tag-input');
    const suggestionsList = document.getElementById('tag-suggestions');
    const selectedTagsContainer = document.getElementById('selected-tags-container');
    const manualTagsInput = document.getElementById('manual_tags_input');
    
    let selectedTags = [];
    let debounceTimer;

    // Load initial manual tags if editing (optional, strictly user asked for adding)
    // For now we start empty or parse from existing manual inputs if we had them

    tagInput.addEventListener('input', function() {
        const query = this.value.trim();
        clearTimeout(debounceTimer);

        if (query.length < 1) {
            suggestionsList.style.display = 'none';
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch(`<?= BASE_URL ?>/contributor/searchTags?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(tags => {
                    suggestionsList.innerHTML = '';
                    if (tags.length > 0) {
                        tags.forEach(tag => {
                            const li = document.createElement('li');
                            li.className = 'suggestion-item';
                            li.textContent = tag.name;
                            li.onclick = () => addTag(tag.name);
                            suggestionsList.appendChild(li);
                        });
                        suggestionsList.style.display = 'block';
                    } else {
                        suggestionsList.style.display = 'none'; // Or show "Create new tag..."
                    }
                });
        }, 300);
    });

    tagInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const value = this.value.trim();
            if (value) {
                addTag(value);
            }
        }
    });

    // Close suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.manual-tags-wrapper')) {
            suggestionsList.style.display = 'none';
        }
    });

    window.addTag = function(name) {
        // Prevent duplicates
        if (selectedTags.includes(name)) {
            tagInput.value = '';
            suggestionsList.style.display = 'none';
            return;
        }

        selectedTags.push(name);
        renderTags();
        updateHiddenInput();
        
        tagInput.value = '';
        suggestionsList.style.display = 'none';
        tagInput.focus();
    }

    window.removeTag = function(name) {
        selectedTags = selectedTags.filter(tag => tag !== name);
        renderTags();
        updateHiddenInput();
    }

    // Expose for auto-generation
    window.autoGenerateTags = function() {
        const title = document.getElementById('title').value;
        const body = document.getElementById('body').value;
        
        if (!title && !body) {
            alert('Mohon isi Judul atau Konten Artikel terlebih dahulu.');
            return;
        }

        const btn = document.querySelector('button[onclick="autoGenerateTags()"]');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Generating...';
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
                tags.forEach(tag => {
                    addTag(tag.name);
                });
                alert('Tags berhasil digenerate: ' + tags.map(t => t.name).join(', '));
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
    };

    function renderTags() {
        selectedTagsContainer.innerHTML = '';
        selectedTags.forEach(tag => {
            const chip = document.createElement('div');
            chip.className = 'tag-chip';
            chip.innerHTML = `${tag} <span class="remove-tag" onclick="removeTag('${tag}')">&times;</span>`;
            selectedTagsContainer.appendChild(chip);
        });
    }

    function updateHiddenInput() {
        manualTagsInput.value = selectedTags.join(',');
    }
});
</script>

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

<?php require_once __DIR__ . '/layout/footer.php'; ?>
