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
            <label for="published_at">Tanggal Publish (Opsional)</label>
            <input type="datetime-local" id="published_at" name="published_at" class="form-control" 
                   value="<?= !empty($post['published_at']) ? date('Y-m-d\TH:i', strtotime($post['published_at'])) : '' ?>">
            <small class="text-muted" style="color: #6c757d; font-size: 0.85em;">
                Jika status <b>Published</b> dan tanggal ini diisi masa depan, artikel akan "Terjadwal".<br>
                Jika kosong, menggunakan waktu sekarang.
            </small>
        </div>

        <div class="form-group">
            <label for="cover_image">Cover Image</label>
            <?php if (!empty($post['cover_image'])): ?>
                <div id="cover-image-preview" style="margin-bottom: 0.5rem; display: flex; align-items: flex-start; gap: 10px;">
                    <img src="<?= BASE_URL ?>/<?= $post['cover_image'] ?>" style="max-width: 200px; border-radius: 4px;">
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
                <input type="file" id="cover_image" name="cover_image" class="form-control" accept="image/*" onchange="previewCover(this)">
                <button type="button" id="clear-file-btn" onclick="clearCoverFile()" style="display: none; padding: 6px 12px; background: #6c757d; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; white-space: nowrap;">
                    <i class="fa-solid fa-times"></i> Clear
                </button>
            </div>
        </div>

        <div class="form-group">
            <label for="excerpt">Ringkasan</label>
            <textarea id="excerpt" name="excerpt" class="form-control" rows="2" placeholder="Ringkasan singkat artikel..."><?= htmlspecialchars($post['excerpt'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="body">Isi Artikel *</label>
            <!-- Quill Editor Container -->
            <div id="quill-wrapper" style="position: relative;">
                <div id="quill-editor" style="min-height: 300px; background: white;"></div>
            </div>
            <input type="hidden" name="body" id="body" required value="<?= htmlspecialchars($post['body'] ?? '') ?>">
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
        <!-- Manual & Generated Tags -->
        <div class="form-group">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <label for="tag-input">Tags Tambahan (Manual / Auto)</label>
                <div style="display: flex; gap: 8px;">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearAllTags()" style="font-size: 0.85rem;">
                        <i class="fas fa-times"></i> Clear All
                    </button>
                    <button type="button" class="btn btn-sm btn-success" onclick="autoGenerateTags()" style="font-size: 0.85rem;">
                        <i class="fas fa-wand-magic-sparkles"></i> Generate Tags
                    </button>
                </div>
            </div>

            <!-- Tag Input Wrapper -->
            <div class="manual-tags-wrapper" style="position: relative;">
                <div class="tag-input-container" style="display: flex; flex-wrap: wrap; gap: 5px; padding: 8px; border: 1px solid #ddd; border-radius: 4px; background: #fff; min-height: 45px;">
                    <div id="active-tags-container" style="display: contents;">
                        <!-- Tags rendered here -->
                    </div>
                    <input type="text" id="tag-input" class="tag-input-field" placeholder="Ketik tag dan tekan Enter..." style="border: none; outline: none; flex: 1; min-width: 150px; padding: 4px; font-size: 1rem; background: transparent;" autocomplete="off">
                </div>
                <!-- Autocomplete Suggestions -->
                <ul id="tag-suggestions" class="tag-suggestions" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: #fff; border: 1px solid #ddd; border-top: none; max-height: 200px; overflow-y: auto; z-index: 1000; list-style: none; padding: 0; margin: 0; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"></ul>
            </div>
            
            <input type="hidden" name="manual_tags" id="manual_tags_input">
            <small class="form-text text-muted">Ketik tag lalu tekan <b>Enter</b>, atau gunakan tombol Generate.</small>
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

<!-- Quill Styles -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
.active-tag-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    background: #e9ecef;
    border-radius: 16px;
    font-size: 0.9rem;
    color: #333;
    margin-right: 5px;
    margin-bottom: 5px;
}
.active-tag-chip .remove-tag {
    cursor: pointer;
    font-weight: bold;
    color: #666;
    margin-left: 5px;
}
.active-tag-chip .remove-tag:hover {
    color: #dc3545;
}
.suggestion-item {
    padding: 8px 12px;
    cursor: pointer;
    border-bottom: 1px solid #f0f0f0;
}
.suggestion-item:hover {
    background: #f8f9fa;
    color: #007bff;
}

/* Quill Overrides */
.ql-toolbar.ql-snow {
    border-radius: 4px 4px 0 0;
    margin-top: 0.5rem;
}
.ql-container.ql-snow {
    border-radius: 0 0 4px 4px;
    font-family: inherit;
    font-size: 1rem;
}

/* Quill Image Delete */
.ql-editor img.selected-image {
    outline: 3px solid #dc3545;
    cursor: pointer;
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
</style>

<script>
// Cover image management
function removeCoverImage() {
    if (confirm('Hapus gambar cover?')) {
        document.getElementById('cover-image-preview').style.display = 'none';
        document.getElementById('remove_cover_image').value = '1';
    }
}

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

function clearCoverFile() {
    var fileInput = document.getElementById('cover_image');
    fileInput.value = '';
    document.getElementById('new-cover-preview').style.display = 'none';
    document.getElementById('clear-file-btn').style.display = 'none';
}

// Unified Tag Logic
let activeTags = [];
const tagInput = document.getElementById('tag-input');
const suggestionsList = document.getElementById('tag-suggestions');
const activeTagsContainer = document.getElementById('active-tags-container');
const manualTagsInput = document.getElementById('manual_tags_input');
let debounceTimer;

document.addEventListener('DOMContentLoaded', function() {
    // Init from hidden input (if editing or validation error)
    if (manualTagsInput.value) {
        activeTags = manualTagsInput.value.split(',').filter(t => t.trim() !== '');
        renderActiveTags();
    }
});

// Input Event
tagInput.addEventListener('input', function() {
    const query = this.value.trim();
    clearTimeout(debounceTimer);

    if (query.length < 1) {
        suggestionsList.style.display = 'none';
        return;
    }

    debounceTimer = setTimeout(() => {
        fetch(`<?= BASE_URL ?>/admin/searchTags?q=${encodeURIComponent(query)}`)
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
                    suggestionsList.style.display = 'none';
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

document.addEventListener('click', function(e) {
    if (!e.target.closest('.manual-tags-wrapper')) {
        suggestionsList.style.display = 'none';
    }
});

function addTag(name) {
    // Check if already in activeTags
    if (activeTags.includes(name)) {
        tagInput.value = '';
        suggestionsList.style.display = 'none';
        return;
    }
    
    activeTags.push(name);
    renderActiveTags();
    updateHiddenInput();
    
    tagInput.value = '';
    suggestionsList.style.display = 'none';
    tagInput.focus();
}

function removeActiveTag(name) {
    activeTags = activeTags.filter(t => t !== name);
    renderActiveTags();
    updateHiddenInput();
}

function renderActiveTags() {
    // activeTagsContainer might be null if script runs before DOM? 
    // But we are at bottom of body.
    if (!activeTagsContainer) return;

    activeTagsContainer.innerHTML = '';
    activeTags.forEach(tag => {
        const chip = document.createElement('div');
        chip.className = 'active-tag-chip';
        chip.innerHTML = `${tag} <span class="remove-tag" onclick="removeActiveTag('${tag}')">&times;</span>`;
        activeTagsContainer.appendChild(chip);
    });
}

function updateHiddenInput() {
    manualTagsInput.value = activeTags.join(',');
}

function clearAllTags() {
    activeTags = [];
    renderActiveTags();
    updateHiddenInput();
}

function autoGenerateTags() {
    const title = document.getElementById('title').value;
    // Quill editor might not update textarea immediately if not triggered
    // But here we access #body input which is synced
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

    fetch('<?= BASE_URL ?>/admin/generateTags', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(tags => {
        if (tags.length > 0) {
            let count = 0;
            tags.forEach(tag => {
                // Capitalize first letter
                const tagName = tag.name.charAt(0).toUpperCase() + tag.name.slice(1);
                 if (!activeTags.includes(tagName)) {
                     activeTags.push(tagName); 
                     count++;
                 }
            });
            
            renderActiveTags();
            updateHiddenInput();
            
            alert(`Berhasil mengekstrak ${tags.length} kata kunci! (${count} baru)`);
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

// Clear all database tags (uncheck all checkboxes)
function clearDatabaseTags() {
    document.querySelectorAll('input[name="tags[]"]').forEach(checkbox => {
        checkbox.checked = false;
    });
}
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
        // Use clipboard to paste HTML correctly
        quill.clipboard.dangerouslyPasteHTML(<?= json_encode($post['body']) ?>);
        <?php endif; ?>

        // Sync content into hidden input on change
        quill.on('text-change', function() {
            document.getElementById('body').value = quill.root.innerHTML;
        });
        
        // Initial sync ensures field has value if untouched
        document.getElementById('body').value = quill.root.innerHTML;

        // Image click-to-delete in Quill
        var deleteTooltip = document.createElement('div');
        deleteTooltip.className = 'image-delete-tooltip';
        deleteTooltip.innerHTML = '<i class="fa-solid fa-trash"></i> Hapus Gambar';
        document.body.appendChild(deleteTooltip);
        var selectedImg = null;

        quill.root.addEventListener('click', function(e) {
            if (e.target.tagName === 'IMG') {
                if (selectedImg) selectedImg.classList.remove('selected-image');
                selectedImg = e.target;
                selectedImg.classList.add('selected-image');
                var rect = selectedImg.getBoundingClientRect();
                deleteTooltip.style.top = (rect.top - 36) + 'px';
                deleteTooltip.style.left = (rect.left + rect.width / 2 - 55) + 'px';
                deleteTooltip.style.display = 'block';
            } else {
                if (selectedImg) selectedImg.classList.remove('selected-image');
                selectedImg = null;
                deleteTooltip.style.display = 'none';
            }
        });

        deleteTooltip.addEventListener('click', function(ev) {
            ev.stopPropagation();
            if (selectedImg && confirm('Hapus gambar ini dari artikel?')) {
                selectedImg.remove();
                selectedImg = null;
                deleteTooltip.style.display = 'none';
                document.getElementById('body').value = quill.root.innerHTML;
            }
        });

        document.addEventListener('click', function(ev) {
            if (!ev.target.closest('#quill-wrapper') && !ev.target.closest('.image-delete-tooltip')) {
                if (selectedImg) selectedImg.classList.remove('selected-image');
                selectedImg = null;
                deleteTooltip.style.display = 'none';
            }
        });
    });
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

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
                        aspectRatio: 16 / 9, // Blog posts usually 16:9
                        viewMode: 1,
                        autoCropArea: 1,
                    });
                };
                reader.readAsDataURL(file);
                
                // Reset value to allow re-selecting same file if cancelled, 
                // but here implementation is slightly different than Zine. 
                // We'll handle input reset in cancel.
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

            // Trigger preview update (existing function)
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
        input.value = ''; // Clear input
        if (typeof clearCoverFile === 'function') {
            clearCoverFile();
        }
    });

    // Handle existing clear button interaction if needed
});
</script>
