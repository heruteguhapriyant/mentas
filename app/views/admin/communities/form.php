<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="page-header">
    <h1><?= $community ? 'Edit Komunitas' : 'Tambah Komunitas' ?></h1>
    <a href="<?= BASE_URL ?>/admin/communities" class="btn btn-secondary">← Kembali</a>
</div>

<div class="card">
    <form action="<?= BASE_URL ?>/admin/<?= $community ? 'communityUpdate/'.$community['id'] : 'communityStore' ?>" method="POST" enctype="multipart/form-data">
        
        <div class="form-group">
            <label>Nama Komunitas *</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($community['name'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label>Logo/Gambar</label>
            <?php if ($community && $community['image']): ?>
                <div style="margin-bottom: 0.5rem;">
                    <img src="<?= BASE_URL ?>/<?= $community['image'] ?>" alt="" style="max-width: 150px; border-radius: 4px;">
                </div>
            <?php endif; ?>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="description" class="form-control" rows="5"><?= htmlspecialchars($community['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label>Lokasi</label>
            <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($community['location'] ?? '') ?>" placeholder="Contoh: Jakarta, Indonesia">
        </div>

        <div class="form-group">
            <label>Kontak</label>
            <input type="text" name="contact" class="form-control" value="<?= htmlspecialchars($community['contact'] ?? '') ?>" placeholder="Email atau nomor telepon">
        </div>

        <div class="form-group">
            <label>Website</label>
            <input type="url" name="website" class="form-control" value="<?= htmlspecialchars($community['website'] ?? '') ?>" placeholder="https://...">
        </div>

        <div class="form-group">
            <label class="form-check">
                <input type="checkbox" name="is_active" value="1" <?= ($community['is_active'] ?? 1) ? 'checked' : '' ?>>
                <span>Aktif (tampil di website)</span>
            </label>
        </div>

        <button type="submit" class="btn btn-primary"><?= $community ? 'Update' : 'Simpan' ?></button>
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
    const input = document.querySelector('input[name="image"]');
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
                        aspectRatio: 1, // 1:1 for logos
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
            width: 800, // Reasonable max width for logos
            height: 800,
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
