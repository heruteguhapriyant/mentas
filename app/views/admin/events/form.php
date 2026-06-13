<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="page-header">
    <h1><?= $event ? 'Edit Event' : 'Tambah Event' ?></h1>
    <a href="<?= BASE_URL ?>/admin/events" class="btn btn-secondary">Kembali</a>
</div>

<div class="card">
    <form action="<?= BASE_URL ?>/admin/<?= $event ? 'eventUpdate/' . $event['id'] : 'eventStore' ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Judul Event *</label>
            <input type="text" name="title" class="form-control" value="<?= $event['title'] ?? '' ?>" required>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="description" class="form-control" rows="5"><?= $event['description'] ?? '' ?></textarea>
        </div>

        <div class="form-group">
            <label>Venue / Tempat *</label>
            <input type="text" name="venue" class="form-control" value="<?= $event['venue'] ?? '' ?>" placeholder="Nama tempat" required>
        </div>

        <div class="form-group">
            <label>Alamat Venue</label>
            <textarea name="venue_address" class="form-control" rows="2" placeholder="Alamat lengkap"><?= $event['venue_address'] ?? '' ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group" style="flex: 1;">
                <label>Tanggal & Waktu Mulai *</label>
                <input type="datetime-local" name="event_date" class="form-control" value="<?= $event ? date('Y-m-d\TH:i', strtotime($event['event_date'])) : '' ?>" required>
            </div>
            <div class="form-group" style="flex: 1;">
                <label>Tanggal & Waktu Selesai</label>
                <input type="datetime-local" name="event_end_date" class="form-control" value="<?= !empty($event['end_date']) ? date('Y-m-d\TH:i', strtotime($event['end_date'])) : '' ?>">
                <small class="form-text">Opsional</small>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group" style="flex: 1;">
                <label>Harga Tiket (Rp)</label>
                <input type="number" name="ticket_price" class="form-control" value="<?= $event['ticket_price'] ?? 0 ?>" min="0">
                <small class="form-text">Isi 0 untuk event gratis</small>
            </div>
            <div class="form-group" style="flex: 1;">
                <label>Kuota Tiket *</label>
                <input type="number" name="ticket_quota" class="form-control" value="<?= $event['ticket_quota'] ?? 100 ?>" min="1" required>
            </div>
        </div>

        <div class="form-group">
            <label>Cover Image</label>
            <?php if (!empty($event['cover_image'])): ?>
                <div style="margin-bottom: 10px;">
                    <img src="<?= BASE_URL ?>/<?= $event['cover_image'] ?>" alt="" style="max-width: 200px; border-radius: 8px;">
                </div>
            <?php endif; ?>
            <input type="file" name="cover_image" class="form-control" accept="image/*">
            <small class="form-text">Format: JPG, PNG. Ukuran ideal 1200x630px</small>
        </div>

        <!-- KONTRIBUTOR -->
        <div class="form-group">
            <label>Kontributor yang Terlibat</label>
            <small style="display:block; margin-bottom:8px; color:#666;">
                Pilih kontributor yang terlibat dalam event ini
            </small>
            <?php if (!empty($contributors)): ?>
                <div class="contributors-grid">
                    <?php foreach ($contributors as $contributor): ?>
                        <label class="contributor-checkbox">
                            <input type="checkbox"
                                   name="contributors[]"
                                   value="<?= $contributor['id'] ?>"
                                   <?= in_array($contributor['id'], $selectedContributors ?? []) ? 'checked' : '' ?>>
                            <span><?= htmlspecialchars($contributor['name']) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p style="color:#999;">Belum ada kontributor aktif.</p>
            <?php endif; ?>
        </div>
        
        
        <!-- KOMUNITAS -->
        <div class="form-group">
            <label>Informasi Komunitas</label>
            <small style="display:block; margin-bottom:8px; color:#666;">
                Opsional — isi jika event ini diselenggarakan bersama komunitas tertentu
            </small>
            <div class="form-row">
                <div class="form-group" style="flex: 1;">
                    <label>Nama Komunitas</label>
                    <input type="text" name="community_name" class="form-control"
                           value="<?= htmlspecialchars($event['community_name'] ?? '') ?>"
                           placeholder="Contoh: Komunitas Teater Bandung">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Link Instagram Komunitas</label>
                    <input type="url" name="community_ig" class="form-control"
                           value="<?= htmlspecialchars($event['community_ig'] ?? '') ?>"
                           placeholder="https://instagram.com/namakomunitas">
                </div>
            </div>
            <div class="form-group">
                <label>No. WhatsApp Komunitas</label>
                <input type="text" name="community_wa" class="form-control"
                       value="<?= htmlspecialchars($event['community_wa'] ?? '') ?>"
                       placeholder="Contoh: 6281234567890 (format internasional, tanpa + atau spasi)">
                <small class="form-text">Format: 628xxx — dipakai untuk tombol chat WhatsApp di halaman event</small>
            </div>
        </div>

        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="is_active" <?= ($event['is_active'] ?? 1) ? 'checked' : '' ?>>
                Aktif (tampil di website)
            </label>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?= $event ? 'Update' : 'Simpan' ?></button>
            <a href="<?= BASE_URL ?>/admin/events" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<style>
.form-row { display: flex; gap: 20px; }
@media (max-width: 768px) { .form-row { flex-direction: column; gap: 0; } }

/* Kontributor grid */
.contributors-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}
.contributor-checkbox {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 7px 14px;
    border: 1px solid #ddd;
    border-radius: 20px;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 14px;
    background: #f9f9f9;
    user-select: none;
}
.contributor-checkbox:hover {
    border-color: #888;
    background: #f0f0f0;
}
.contributor-checkbox input[type="checkbox"] {
    accent-color: #333;
    cursor: pointer;
}
.contributor-checkbox input:checked + span {
    font-weight: 600;
}
.contributor-checkbox:has(input:checked) {
    border-color: #333;
    background: #e8e8e8;
}
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

    btnCrop.addEventListener('click', function() {
        if (!cropper) return;

        const canvas = cropper.getCroppedCanvas({
            width: 1200,
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
    });
});
</script>