<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="page-header">
    <h1><?= $zine ? 'Edit Bulletin' : 'Tambah Bulletin' ?></h1>
    <a href="<?= BASE_URL ?>/admin/zines" class="btn btn-secondary">← Kembali</a>
</div>

<div class="card">
    <form action="<?= BASE_URL ?>/admin/<?= $zine ? 'zineUpdate/'.$zine['id'] : 'zineStore' ?>" method="POST" enctype="multipart/form-data">
        
        <div class="form-group">
            <label>Judul Bulletin *</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($zine['title'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label>Kategori *</label>
            <select name="category_id" class="form-control" required>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($zine['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Penulis / Kolaborator</label>
            <div class="contributor-selection-container" style="max-height: 300px; overflow-y: auto; border: 1px solid #ced4da; padding: 10px; border-radius: 4px; background: #f8f9fa;">
                <div class="contributor-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 10px;">
                    
                    <!-- Option: Tanpa Penulis -->
                    <label class="contributor-option" style="display: flex; align-items: center; padding: 8px 12px; background: #fff; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; transition: all 0.2s;">
                        <input type="radio" name="author_id" value="" <?= empty($zine['author_id']) ? 'checked' : '' ?> style="margin-right: 8px;">
                        <span>-- Tanpa Penulis --</span>
                    </label>

                    <?php if (!empty($contributors)): ?>
                        <?php foreach ($contributors as $contributor): ?>
                            <label class="contributor-option" style="display: flex; align-items: center; padding: 8px 12px; background: #fff; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; transition: all 0.2s;">
                                <input type="radio" name="author_id" value="<?= $contributor['id'] ?>" <?= ($zine['author_id'] ?? '') == $contributor['id'] ? 'checked' : '' ?> style="margin-right: 8px;">
                                <span><?= htmlspecialchars($contributor['name']) ?></span>
                            </label>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <small class="form-text text-muted">Pilih satu penulis/kontributor utama untuk bulletin ini.</small>
        </div>

        <div class="form-group">
            <label>Deskripsi Singkat (Excerpt)</label>
            <textarea name="excerpt" class="form-control" rows="3" placeholder="Deskripsi singkat yang akan ditampilkan di card..."><?= htmlspecialchars($zine['excerpt'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label>Cover Image</label>
            <?php if ($zine && $zine['cover_image']): ?>
                <div style="margin-bottom: 0.5rem;">
                    <img src="<?= BASE_URL ?>/<?= $zine['cover_image'] ?>" alt="" style="max-width: 200px; border-radius: 4px;">
                </div>
            <?php endif; ?>
            <input type="file" name="cover_image" class="form-control" accept="image/*">
            <small class="form-text text-muted">Format: JPG, PNG, GIF. Ukuran maksimal: 2MB</small>
        </div>



        <div class="form-group">
            <label>Link Google Drive (PDF) *</label>
            <input type="text" name="pdf_link" class="form-control" value="<?= htmlspecialchars($zine['pdf_link'] ?? '') ?>" placeholder="https://drive.google.com/..." required>
            <small class="form-text text-muted">Masukkan link sharing Google Drive untuk file PDF bulletin.</small>
        </div>

        <div class="form-group">
            <label class="form-check">
                <input type="checkbox" name="is_active" value="1" <?= ($zine['is_active'] ?? 1) ? 'checked' : '' ?>>
                <span>Aktif (tampil di website)</span>
            </label>
        </div>

        <div class="form-group">
            <label>Tanggal Publish</label>
            <input type="datetime-local" name="published_at" class="form-control" 
                value="<?= !empty($zine['published_at']) ? date('Y-m-d\TH:i', strtotime($zine['published_at'])) : '' ?>">
            <small class="form-text text-muted">Isi tanggal kapan zine ini diterbitkan. Jika dikosongkan, otomatis menggunakan tanggal hari ini.</small>
        </div>

        <button type="submit" class="btn btn-primary"><?= $zine ? 'Update' : 'Simpan' ?></button>
    </form>
</div>

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

    input.addEventListener('change', function(e) {
        const files = e.target.files;
        if (files && files.length > 0) {
            const file = files[0];
            
            // Only proceed if it's an image
             if (!file.type.startsWith('image/')) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                // Determine previous image to restore if cancelled (optional, for now just clear)
                image.src = e.target.result;
                modal.style.display = 'block';

                if (cropper) {
                    cropper.destroy();
                }

                cropper = new Cropper(image, {
                    aspectRatio: 3 / 4, // 3:4 aspect ratio
                    viewMode: 1, // Restrict crop box to canvas
                    autoCropArea: 1,
                });
            };
            reader.readAsDataURL(file);
        }
    });

    btnCrop.addEventListener('click', function() {
        if (!cropper) return;

        // Get cropped canvas
        const canvas = cropper.getCroppedCanvas({
            width: 600, // Reasonable max width for cover
            height: 800,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        });

        canvas.toBlob(function(blob) {
            // Create a new file from the blob
            const fileName = input.files[0].name; // Keep original name
            const newFile = new File([blob], fileName, { type: 'image/jpeg', lastModified: new Date().getTime() });

            // Update the file input with the new cropped file
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(newFile);
            input.files = dataTransfer.files;

            // Optional: Update a preview image if you have one
            // const preview = document.querySelector('.img-preview');
            // if(preview) preview.src = URL.createObjectURL(newFile);

            // Close modal
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
        input.value = ''; // Clear input if cancelled
    });
});
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
