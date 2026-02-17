<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="page-header">
    <h1><?= $collaboration ? 'Edit Kolaborasi' : 'Tambah Kolaborasi' ?></h1>
    <a href="<?= BASE_URL ?>/admin/collaborations" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<?php $flash = getFlash(); ?>
<?php if ($flash): ?>
    <div class="alert alert-<?= $flash['type'] ?>"><?= $flash['message'] ?></div>
<?php endif; ?>

<style>
.form-card {
    background: #fff;
    border-radius: 8px;
    padding: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
.form-group {
    margin-bottom: 20px;
}
.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: #333;
}
.form-control {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
}
textarea.form-control {
    min-height: 120px;
    resize: vertical;
}
.contributors-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 10px;
    max-height: 300px;
    overflow-y: auto;
    padding: 10px;
    border: 1px solid #eee;
    border-radius: 8px;
    background: #fafafa;
}
.contributor-checkbox {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border-radius: 6px;
    background: #fff;
    border: 1px solid #e0e0e0;
    cursor: pointer;
    transition: all 0.2s;
}
.contributor-checkbox:hover {
    border-color: #d52c2c;
    background: #fff5f5;
}
.contributor-checkbox input:checked + span {
    color: #d52c2c;
    font-weight: 600;
}
.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}
.social-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}
.checkbox-label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}
</style>

<div class="form-card">
    <form action="<?= BASE_URL ?>/admin/<?= $collaboration ? 'collaborationUpdate/' . $collaboration['id'] : 'collaborationStore' ?>" method="POST" enctype="multipart/form-data">
        
        <div class="form-group">
            <label for="title">Nama Kolaborasi *</label>
            <input type="text" id="title" name="title" class="form-control" required 
                   value="<?= htmlspecialchars($collaboration['title'] ?? '') ?>">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="cover_image">Cover Image</label>
                <?php if (!empty($collaboration['cover_image'])): ?>
                    <div style="margin-bottom: 0.5rem;">
                        <img src="<?= BASE_URL ?>/<?= $collaboration['cover_image'] ?>" style="max-width: 150px; border-radius: 4px;">
                    </div>
                <?php endif; ?>
                <input type="file" id="cover_image" name="cover_image" class="form-control" accept="image/*">
            </div>

            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="is_active" value="1" 
                           <?= ($collaboration['is_active'] ?? 1) ? 'checked' : '' ?>>
                    <span>Aktif (tampilkan di website)</span>
                </label>
            </div>
        </div>

        <div class="form-group">
            <label for="description">Deskripsi</label>
            <textarea id="description" name="description" class="form-control"
                      placeholder="Deskripsi tentang kolaborasi ini..."><?= htmlspecialchars($collaboration['description'] ?? '') ?></textarea>
        </div>

        <?php 
        $socials = [];
        if (!empty($collaboration['social_media'])) {
            $socials = json_decode($collaboration['social_media'], true) ?? [];
        }
        ?>

        <div class="form-group">
            <label>Media Sosial</label>
            <div class="social-grid">
                <div>
                    <label for="instagram" style="font-weight:normal;font-size:13px;">
                        <i class="fab fa-instagram"></i> Instagram
                    </label>
                    <input type="text" id="instagram" name="instagram" class="form-control" 
                           placeholder="@username" value="<?= htmlspecialchars($socials['instagram'] ?? '') ?>">
                </div>
                <div>
                    <label for="twitter" style="font-weight:normal;font-size:13px;">
                        <i class="fab fa-twitter"></i> Twitter
                    </label>
                    <input type="text" id="twitter" name="twitter" class="form-control" 
                           placeholder="@username" value="<?= htmlspecialchars($socials['twitter'] ?? '') ?>">
                </div>
                <div>
                    <label for="facebook" style="font-weight:normal;font-size:13px;">
                        <i class="fab fa-facebook"></i> Facebook
                    </label>
                    <input type="text" id="facebook" name="facebook" class="form-control" 
                           placeholder="facebook.com/..." value="<?= htmlspecialchars($socials['facebook'] ?? '') ?>">
                </div>
                <div>
                    <label for="website" style="font-weight:normal;font-size:13px;">
                        <i class="fas fa-globe"></i> Website
                    </label>
                    <input type="text" id="website" name="website" class="form-control" 
                           placeholder="https://..." value="<?= htmlspecialchars($socials['website'] ?? '') ?>">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Kontributor</label>
            <small style="display:block; margin-bottom:8px; color:#666;">Pilih kontributor yang terlibat dalam kolaborasi ini</small>
            <?php if (!empty($contributors)): ?>
            <div class="contributors-grid">
                <?php foreach ($contributors as $contributor): ?>
                    <label class="contributor-checkbox">
                        <input type="checkbox" name="contributors[]" value="<?= $contributor['id'] ?>"
                               <?= in_array($contributor['id'], $selectedContributors) ? 'checked' : '' ?>>
                        <span><?= htmlspecialchars($contributor['name']) ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
                <p style="color: #999;">Belum ada kontributor aktif.</p>
            <?php endif; ?>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> <?= $collaboration ? 'Update' : 'Simpan' ?>
            </button>
            <a href="<?= BASE_URL ?>/admin/collaborations" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

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
                        aspectRatio: 16 / 9, // 16:9 for collaborations
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
            width: 1200, // HD width
            height: 675,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        });

        canvas.toBlob(function(blob) {
            const fileName = input.files[0] ? input.files[0].name : "cropped.jpg";
            const newFile = new File([blob], fileName, { type: 'image/jpeg', lastModified: new Date().getTime() });

            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(newFile);
            input.files = dataTransfer.files;

            // Optional: Update a preview image if you have one
            // const preview = input.previousElementSibling && input.previousElementSibling.querySelector('img');
            // if (preview) preview.src = URL.createObjectURL(newFile);

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
