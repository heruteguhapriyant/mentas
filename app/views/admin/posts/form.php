<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="page-header">
    <h1><?= $post ? 'Edit Artikel' : 'Tulis Artikel Baru' ?></h1>
    <a href="<?= BASE_URL ?>/admin/posts" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<?php $flash = getFlash(); ?>
<?php if ($flash): ?>
    <div class="alert alert-<?= $flash['type'] ?>">
        <?= $flash['message'] ?>
    </div>
<?php endif; ?>

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

        <div class="form-group">
            <label for="excerpt">Ringkasan</label>
            <textarea id="excerpt" name="excerpt" class="form-control" rows="2" placeholder="Ringkasan singkat artikel..."><?= htmlspecialchars($post['excerpt'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="body">Isi Artikel *</label>
            <div id="quill-wrapper" style="position: relative;">
                <div id="quill-editor"></div>
            </div>
            <input type="hidden" name="body" id="body" required>
        </div>

        <?php
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
            <div class="tags-checkbox-wrapper" id="tags-checkbox-list">
                <?php foreach ($allTags as $tag): ?>
                    <label class="tag-checkbox-item" data-name="<?= strtolower(htmlspecialchars($tag['name'])) ?>">
                        <input
                            type="checkbox"
                            name="tags[]"
                            value="<?= $tag['id'] ?>"
                            <?= in_array($tag['id'], $postTagIds) ? 'checked' : '' ?>
                        >
                        <span><?= htmlspecialchars($tag['name']) ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
            <small class="form-text">Pilih tags yang sudah ada</small>
        </div>
        <?php endif; ?>

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
            <div class="manual-tags-wrapper" style="position: relative;">
                <div class="tag-input-container" style="display: flex; flex-wrap: wrap; gap: 5px; padding: 8px; border: 1px solid #ddd; border-radius: 4px; background: #fff; min-height: 45px;">
                    <div id="active-tags-container" style="display: contents;"></div>
                    <input type="text" id="tag-input" class="tag-input-field" placeholder="Ketik tag dan tekan Enter..."
                        style="border: none; outline: none; flex: 1; min-width: 150px; padding: 4px; font-size: 1rem; background: transparent;" autocomplete="off">
                </div>
                <ul id="tag-suggestions" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: #fff; border: 1px solid #ddd; border-top: none; max-height: 200px; overflow-y: auto; z-index: 1000; list-style: none; padding: 0; margin: 0; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"></ul>
            </div>
            <input type="hidden" name="manual_tags" id="manual_tags_input">
            <small class="form-text text-muted">Ketik tag lalu tekan <b>Enter</b>, atau gunakan tombol Generate.</small>
        </div>

        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-secondary">
                <i class="fas fa-save"></i> Simpan
            </button>
        </div>

    </form>
</div>

<style>
    /* Quill Editor */
    #quill-editor {
        height: 400px;
        background: #fff;
        margin-bottom: 20px;
    }
    .ql-toolbar.ql-snow {
        border-radius: 4px 4px 0 0;
        background: #f8f9fa;
        border: 1px solid #ddd;
    }
    .ql-container.ql-snow {
        border-radius: 0 0 4px 4px;
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
    .ql-snow .ql-picker.ql-header .ql-picker-item::before { content: 'Normal'; }
    .ql-snow .ql-picker.ql-header .ql-picker-label[data-value="1"]::before,
    .ql-snow .ql-picker.ql-header .ql-picker-item[data-value="1"]::before { content: 'Heading 1'; }
    .ql-snow .ql-picker.ql-header .ql-picker-label[data-value="2"]::before,
    .ql-snow .ql-picker.ql-header .ql-picker-item[data-value="2"]::before { content: 'Heading 2'; }
    .ql-snow .ql-picker.ql-header .ql-picker-label[data-value="3"]::before,
    .ql-snow .ql-picker.ql-header .ql-picker-item[data-value="3"]::before { content: 'Heading 3'; }

    /* Image delete tooltip */
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

    /* Tags DB checkbox */
    .tags-checkbox-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        background: #fafafa;
        max-height: 220px;
        overflow-y: auto;
    }
    .tag-checkbox-item {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border: 1px solid #ddd;
        border-radius: 20px;
        background: #fff;
        cursor: pointer;
        font-size: 13px;
        transition: all 0.15s;
        user-select: none;
        margin: 0;
    }
    .tag-checkbox-item:hover {
        border-color: #d52c2c;
        background: #fff5f5;
    }
    .tag-checkbox-item input[type="checkbox"] {
        accent-color: #d52c2c;
        width: 14px;
        height: 14px;
        cursor: pointer;
        flex-shrink: 0;
    }
    .tag-checkbox-item:has(input:checked) {
        border-color: #d52c2c;
        background: #fff0f0;
        font-weight: 500;
        color: #d52c2c;
    }

    /* Manual tags chip */
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
</style>

<!-- Quill CSS -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">

<script>
// =====================
// Cover Image Functions
// =====================
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

// =====================
// Tags DB
// =====================
function clearDatabaseTags() {
    document.querySelectorAll('input[name="tags[]"]').forEach(cb => cb.checked = false);
}

// =====================
// Manual / Auto Tags
// =====================
let activeTags = [];
let debounceTimer;

document.addEventListener('DOMContentLoaded', function () {
    const manualTagsInput = document.getElementById('manual_tags_input');
    if (manualTagsInput.value) {
        activeTags = manualTagsInput.value.split(',').filter(t => t.trim() !== '');
        renderActiveTags();
    }

    const tagInput = document.getElementById('tag-input');
    const suggestionsList = document.getElementById('tag-suggestions');

    tagInput.addEventListener('input', function () {
        const query = this.value.trim();
        clearTimeout(debounceTimer);
        if (query.length < 1) {
            suggestionsList.style.display = 'none';
            return;
        }
        debounceTimer = setTimeout(() => {
            fetch(`<?= BASE_URL ?>/admin/searchTags?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
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

    tagInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const value = this.value.trim();
            if (value) addTag(value);
        }
    });

    document.addEventListener('click', function (e) {
        if (!e.target.closest('.manual-tags-wrapper')) {
            suggestionsList.style.display = 'none';
        }
    });
});

function addTag(name) {
    if (activeTags.includes(name)) {
        document.getElementById('tag-input').value = '';
        document.getElementById('tag-suggestions').style.display = 'none';
        return;
    }
    activeTags.push(name);
    renderActiveTags();
    updateHiddenInput();
    document.getElementById('tag-input').value = '';
    document.getElementById('tag-suggestions').style.display = 'none';
    document.getElementById('tag-input').focus();
}

function removeActiveTag(name) {
    activeTags = activeTags.filter(t => t !== name);
    renderActiveTags();
    updateHiddenInput();
}

function renderActiveTags() {
    const container = document.getElementById('active-tags-container');
    if (!container) return;
    container.innerHTML = '';
    activeTags.forEach(tag => {
        const chip = document.createElement('div');
        chip.className = 'active-tag-chip';
        chip.innerHTML = `${tag} <span class="remove-tag" onclick="removeActiveTag('${tag}')">&times;</span>`;
        container.appendChild(chip);
    });
}

function updateHiddenInput() {
    document.getElementById('manual_tags_input').value = activeTags.join(',');
}

function clearAllTags() {
    activeTags = [];
    renderActiveTags();
    updateHiddenInput();
}

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

    fetch('<?= BASE_URL ?>/admin/generateTags', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(tags => {
        if (tags.length > 0) {
            let count = 0;
            tags.forEach(tag => {
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
</script>

<!-- Quill JS -->
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Custom image upload handler (pakai endpoint contributor)
    function imageUploadHandler() {
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');
        input.click();

        input.onchange = function () {
            const file = input.files[0];
            if (!file) return;

            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran gambar maksimal 5MB');
                return;
            }

            const formData = new FormData();
            formData.append('image', file);
            document.body.style.cursor = 'wait';

            fetch('<?= BASE_URL ?>/contributor/uploadImage', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                document.body.style.cursor = 'default';
                if (data.url) {
                    const range = quill.getSelection(true);
                    quill.insertEmbed(range.index, 'image', data.url);
                    quill.setSelection(range.index + 1);
                } else {
                    alert('Gagal upload gambar: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(err => {
                document.body.style.cursor = 'default';
                alert('Gagal upload gambar');
                console.error(err);
            });
        };
    }

    var quill = new Quill('#quill-editor', {
        theme: 'snow',
        placeholder: 'Tulis isi artikel di sini...',
        modules: {
            toolbar: {
                container: [
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'align': [] }],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    [{ 'indent': '-1' }, { 'indent': '+1' }],
                    ['blockquote', 'code-block'],
                    ['link', 'image', 'video'],
                    ['clean']
                ],
                handlers: {
                    image: imageUploadHandler
                }
            }
        }
    });

    <?php if (!empty($post['body'])): ?>
    quill.root.innerHTML = <?= json_encode($post['body']) ?>;
    <?php endif; ?>

    // Sync on change
    quill.on('text-change', function () {
        document.getElementById('body').value = quill.root.innerHTML;
    });

    // Sync on submit
    document.querySelector('form').addEventListener('submit', function () {
        document.getElementById('body').value = quill.root.innerHTML;
    });

    // Init sync
    document.getElementById('body').value = quill.root.innerHTML;

    // --- Image delete on click ---
    var deleteTooltip = document.createElement('div');
    deleteTooltip.className = 'image-delete-tooltip';
    deleteTooltip.innerHTML = '<i class="fa-solid fa-trash"></i> Hapus Gambar';
    document.body.appendChild(deleteTooltip);
    var selectedImg = null;

    quill.root.addEventListener('click', function (e) {
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

    deleteTooltip.addEventListener('click', function () {
        if (selectedImg) {
            var blot = Quill.find(selectedImg);
            if (blot) blot.remove(); else selectedImg.remove();
            selectedImg = null;
            deleteTooltip.style.display = 'none';
            document.getElementById('body').value = quill.root.innerHTML;
        }
    });

    document.addEventListener('click', function (e) {
        if (!e.target.closest('#quill-wrapper') && !e.target.closest('.image-delete-tooltip')) {
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
document.addEventListener('DOMContentLoaded', function () {
    const input = document.querySelector('input[name="cover_image"]');
    const modal = document.getElementById('cropModal');
    const image = document.getElementById('imageToCrop');
    const btnCrop = document.getElementById('btnCrop');
    const btnCancel = document.getElementById('btnCancelCrop');
    let cropper;

    if (input) {
        input.addEventListener('change', function (e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                const file = files[0];
                if (!file.type.startsWith('image/')) return;

                const reader = new FileReader();
                reader.onload = function (e) {
                    image.src = e.target.result;
                    modal.style.display = 'block';
                    if (cropper) cropper.destroy();
                    cropper = new Cropper(image, {
                        aspectRatio: 16 / 9,
                        viewMode: 1,
                        autoCropArea: 1,
                    });
                };
                reader.readAsDataURL(file);
            }
        });
    }

    btnCrop.addEventListener('click', function () {
        if (!cropper) return;
        const canvas = cropper.getCroppedCanvas({
            width: 800,
            height: 450,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        });
        canvas.toBlob(function (blob) {
            const fileName = input.files[0] ? input.files[0].name : 'cropped.jpg';
            const newFile = new File([blob], fileName, { type: 'image/jpeg', lastModified: new Date().getTime() });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(newFile);
            input.files = dataTransfer.files;
            if (typeof previewCover === 'function') previewCover(input);
            modal.style.display = 'none';
            cropper.destroy();
            cropper = null;
        }, 'image/jpeg', 0.9);
    });

    btnCancel.addEventListener('click', function () {
        modal.style.display = 'none';
        if (cropper) { cropper.destroy(); cropper = null; }
        input.value = '';
        if (typeof clearCoverFile === 'function') clearCoverFile();
    });
});
</script>