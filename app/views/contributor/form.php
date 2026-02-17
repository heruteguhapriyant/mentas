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
                        <div id="cover-image-preview" style="margin-bottom: 0.5rem; display: flex; align-items: flex-start; gap: 10px;">
                            <img src="<?= BASE_URL ?>/<?= $post['cover_image'] ?>" style="max-width: 150px; border-radius: 4px;">
                            <button type="button" onclick="removeCoverImage()" class="btn btn-danger" style="padding: 4px 10px; font-size: 12px; background: #dc3545; color: #fff; border: none; border-radius: 4px; cursor: pointer;">
                                <i class="fa-solid fa-trash"></i> Hapus
                            </button>
                        </div>
                        <input type="hidden" name="remove_cover_image" id="remove_cover_image" value="0">
                    <?php endif; ?>
                    <div id="new-cover-preview" style="margin-bottom: 0.5rem; display: none;">
                        <img id="cover-preview-img" src="" style="max-width: 200px; border-radius: 4px;">
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="file" id="cover_image" name="cover_image" class="form-control" accept="image/*" style="flex: 1;" onchange="previewCover(this)">
                        <button type="button" id="clear-file-btn" onclick="clearCoverFile()" style="display: none; padding: 6px 12px; background: #6c757d; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; white-space: nowrap;">
                            <i class="fa-solid fa-times"></i> Clear
                        </button>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="published_at">Rencana Tanggal Publish (Opsional)</label>
                <input type="datetime-local" id="published_at" name="published_at" class="form-control" 
                       value="<?= !empty($post['published_at']) ? date('Y-m-d\TH:i', strtotime($post['published_at'])) : '' ?>">
                <small class="text-muted" style="color: #6c757d; font-size: 0.85em;">Jika dikosongkan, akan dipublish segera saat disetujui admin.</small>
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

            <!-- Manual Tags Input (Hidden/Commented by Request) -->
            <!--
            <div class="form-group">
                <label for="tag-input">Tags Tambahan (Manual / Auto)</label>
                <div class="manual-tags-wrapper" style="position: relative;">
                    <div class="tag-input-container" id="selected-tags-container" style="display: flex; flex-wrap: wrap; gap: 5px; padding: 8px; border: 1px solid #ddd; border-radius: 4px; background: #fff; min-height: 45px;">
                        <!-- Tags rendered here -->
                    </div>
                    <input type="text" id="tag-input" class="tag-input-field" placeholder="Ketik tag dan tekan Enter..." style="border: none; outline: none; flex: 1; min-width: 150px; padding: 4px; font-size: 1rem; background: transparent;" autocomplete="off">
                </div>
                
                <!-- Suggestions List -->
                <ul id="tag-suggestions" class="tag-suggestions" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: #fff; border: 1px solid #ddd; border-top: none; max-height: 200px; overflow-y: auto; z-index: 1000; list-style: none; padding: 0; margin: 0; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"></ul>

                <div style="margin-top: 5px; display: flex; gap: 10px;">
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="autoGenerateTags()">
                        <i class="fa-solid fa-wand-magic-sparkles"></i> Generate Tags
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearGeneratedTags()">
                        Clear Tags
                    </button>
                </div>
                <input type="hidden" name="manual_tags" id="manual_tags_input">
                <small class="form-text text-muted">Ketik tag lalu tekan <b>Enter</b>, atau gunakan tombol Generate.</small>
            </div>
            -->

            <style>
                /* Quill Editor Container */
                #quill-editor {
                    height: 400px;
                    background: #fff;
                    margin-bottom: 20px;
                }
                /* Quill image delete overlay */
                .ql-editor img {
                    cursor: pointer;
                    transition: outline 0.15s;
                }
                .ql-editor img.selected-image {
                    outline: 3px solid #dc3545;
                    outline-offset: 2px;
                }
                .image-delete-tooltip {
                    position: fixed;
                    background: #dc3545;
                    color: #fff;
                    padding: 6px 14px;
                    border-radius: 4px;
                    font-size: 13px;
                    cursor: pointer;
                    z-index: 10000;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
                    display: none;
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
function removeCoverImage() {
    if (confirm('Hapus gambar cover?')) {
        document.getElementById('cover-image-preview').style.display = 'none';
        document.getElementById('remove_cover_image').value = '1';
    }
}

// Preview cover image when file selected
function previewCover(input) {
    var preview = document.getElementById('new-cover-preview');
    var previewImg = document.getElementById('cover-preview-img');
    var clearBtn = document.getElementById('clear-file-btn');
    
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
        clearBtn.style.display = 'inline-block';
    } else {
        preview.style.display = 'none';
        clearBtn.style.display = 'none';
    }
}

// Clear file input and hide preview
function clearCoverFile() {
    var fileInput = document.getElementById('cover_image');
    fileInput.value = '';
    document.getElementById('new-cover-preview').style.display = 'none';
    document.getElementById('clear-file-btn').style.display = 'none';
}
</script>

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
        const body = document.getElementById('body-input').value;
        
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

    // Clear all database tags (uncheck all checkboxes)
    window.clearDatabaseTags = function() {
        document.querySelectorAll('input[name="tags[]"]').forEach(checkbox => {
            checkbox.checked = false;
        });
    };

    // Clear all generated/manual tags
    window.clearGeneratedTags = function() {
        selectedTags = [];
        renderTags();
        updateHiddenInput();
    };
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

        // --- Image delete on click ---
        var deleteTooltip = document.createElement('div');
        deleteTooltip.className = 'image-delete-tooltip';
        deleteTooltip.innerHTML = '<i class="fa-solid fa-trash"></i> Hapus Gambar';
        document.body.appendChild(deleteTooltip);
        var selectedImg = null;

        quill.root.addEventListener('click', function(e) {
            if (e.target.tagName === 'IMG') {
                // Remove previous selection
                if (selectedImg) selectedImg.classList.remove('selected-image');
                
                selectedImg = e.target;
                selectedImg.classList.add('selected-image');

                // Position tooltip above image (fixed position)
                var rect = selectedImg.getBoundingClientRect();
                deleteTooltip.style.top = (rect.top - 36) + 'px';
                deleteTooltip.style.left = (rect.left + rect.width / 2 - 55) + 'px';
                deleteTooltip.style.display = 'block';
            } else {
                // Clicked non-image area — dismiss
                if (selectedImg) selectedImg.classList.remove('selected-image');
                selectedImg = null;
                deleteTooltip.style.display = 'none';
            }
        });

        deleteTooltip.addEventListener('click', function() {
            if (selectedImg) {
                var blot = Quill.find(selectedImg);
                if (blot) {
                    blot.remove();
                } else {
                    selectedImg.remove();
                }
                selectedImg = null;
                deleteTooltip.style.display = 'none';
                // Sync
                document.getElementById('body-input').value = quill.root.innerHTML;
            }
        });

        // Hide tooltip on scroll or click outside editor
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#quill-editor') && !e.target.closest('.image-delete-tooltip')) {
                if (selectedImg) selectedImg.classList.remove('selected-image');
                selectedImg = null;
                deleteTooltip.style.display = 'none';
            }
        });
    });
</script>

<?php require_once __DIR__ . '/layout/footer.php'; ?>

<!-- Cropping Modal -->
<div id="cropModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.9);">
    <div style="position: relative; margin: 2% auto; padding: 0; width: 90%; max-width: 800px; height: 90%;">
        <div style="background: #fff; padding: 10px; border-radius: 4px; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0;">Crop Gambar</h3>
            <div>
                <button type="button" id="btnCrop" class="btn btn-primary">Potong & Simpan</button>
                <button type="button" id="btnCancelCrop" class="btn btn-secondary">Batal</button>
            </div>
        </div>
        <div style="height: 500px; background: #333; margin-top: 10px; display: flex; align-items: center; justify-content: center;">
            <img id="imageToCrop" src="" style="max-width: 100%; max-height: 100%; display: block;">
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.querySelector('input[name="cover_image"]');
    const modal = document.getElementById('cropModal');
    const image = document.getElementById('imageToCrop');
    const btnCrop = document.getElementById('btnCrop');
    const btnCancel = document.getElementById('btnCancelCrop');
    let cropper;

    if (input) {
        input.addEventListener('change', function(e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                const file = files[0];
                if (!file.type.startsWith('image/')) return;

                const reader = new FileReader();
                reader.onload = function(e) {
                    image.src = e.target.result;
                    modal.style.display = 'block';

                    if (cropper) {
                        cropper.destroy();
                    }

                    cropper = new Cropper(image, {
                        aspectRatio: 16 / 9, // 16:9 for articles
                        viewMode: 1,
                        autoCropArea: 1,
                    });
                };
                reader.readAsDataURL(file);
            }
        });
    }

    btnCrop.addEventListener('click', function() {
        if (!cropper) return;

        const canvas = cropper.getCroppedCanvas({
            width: 800,
            height: 450,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        });

        canvas.toBlob(function(blob) {
            const fileName = input.files[0] ? input.files[0].name : "cropped.jpg";
            const newFile = new File([blob], fileName, { type: 'image/jpeg', lastModified: new Date().getTime() });

            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(newFile);
            input.files = dataTransfer.files;

            // Trigger preview update
            if (typeof previewCover === 'function') {
                previewCover(input);
            }

            modal.style.display = 'none';
            cropper.destroy();
            cropper = null;
        }, 'image/jpeg', 0.9);
    });

    btnCancel.addEventListener('click', function() {
        modal.style.display = 'none';
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        input.value = ''; 
        if (typeof clearCoverFile === 'function') {
            clearCoverFile();
        }
    });
});
</script>
