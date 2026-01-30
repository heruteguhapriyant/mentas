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
</style>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
