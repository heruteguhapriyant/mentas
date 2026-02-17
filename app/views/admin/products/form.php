<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="page-header">
    <h1><?= $product ? 'Edit Produk' : 'Tambah Produk' ?></h1>
    <a href="<?= BASE_URL ?>/admin/products" class="btn btn-secondary">Kembali</a>
</div>

<div class="card">
    <form action="<?= BASE_URL ?>/admin/<?= $product ? 'productUpdate/' . $product['id'] : 'productStore' ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Nama Produk *</label>
            <input type="text" name="name" class="form-control" value="<?= $product['name'] ?? '' ?>" required>
        </div>

        <div class="form-group">
            <label>Kategori *</label>
            <select name="category_id" class="form-control" required>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($product['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-row">
            <div class="form-group" style="flex: 1;">
                <label>Harga (Rp) *</label>
                <input type="number" name="price" class="form-control" value="<?= $product['price'] ?? 0 ?>" min="0" required>
            </div>
            <div class="form-group" style="flex: 1;">
                <label>Stok *</label>
                <input type="number" name="stock" class="form-control" value="<?= $product['stock'] ?? 0 ?>" min="0" required>
            </div>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="description" class="form-control" rows="5"><?= $product['description'] ?? '' ?></textarea>
        </div>

        <div class="form-group">
            <label>Cover Image</label>
            <?php if (!empty($product['cover_image'])): ?>
                <div style="margin-bottom: 10px;">
                    <img src="<?= BASE_URL ?>/<?= $product['cover_image'] ?>" alt="" style="max-width: 150px; border-radius: 8px;">
                </div>
            <?php endif; ?>
            <input type="file" name="cover_image" class="form-control" accept="image/*">
            <small class="form-text">Format: JPG, PNG. Max 2MB</small>
        </div>

        <div class="form-group">
            <label>Nomor WhatsApp</label>
            <input type="text" name="whatsapp_number" class="form-control" value="<?= $product['whatsapp_number'] ?? '6283895189649' ?>" placeholder="6281234567890">
            <small class="form-text">Format internasional tanpa +, contoh: 6281234567890</small>
        </div>

        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="is_active" <?= ($product['is_active'] ?? 1) ? 'checked' : '' ?>>
                Aktif (tampil di website)
            </label>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?= $product ? 'Update' : 'Simpan' ?></button>
            <a href="<?= BASE_URL ?>/admin/products" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<style>
.form-row { display: flex; gap: 20px; }
@media (max-width: 768px) { .form-row { flex-direction: column; gap: 0; } }
</style>

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
                        aspectRatio: 1, // 1:1 for products
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

            // Simple preview update if img exists nearby (optional but good UX)
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
