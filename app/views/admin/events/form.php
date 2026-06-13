<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="page-header">
    <h1><?= $event ? 'Edit Event' : 'Tambah Event' ?></h1>
    <a href="<?= BASE_URL ?>/admin/events" class="btn btn-secondary">Kembali</a>
</div>

<div class="card">
    <form action="<?= BASE_URL ?>/admin/<?= $event ? 'eventUpdate/' . $event['id'] : 'eventStore' ?>" method="POST" enctype="multipart/form-data">

        <div class="form-group">
            <label>Judul Event *</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($event['title'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="description" class="form-control" rows="5"><?= htmlspecialchars($event['description'] ?? '') ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group" style="flex:1;">
                <label>Harga Tiket (Rp)</label>
                <input type="number" name="ticket_price" class="form-control" value="<?= $event['ticket_price'] ?? 0 ?>" min="0">
                <small class="form-text">Isi 0 untuk event gratis</small>
            </div>
            <div class="form-group" style="flex:1;">
                <label>Total Kuota Tiket *</label>
                <input type="number" name="ticket_quota" class="form-control" value="<?= $event['ticket_quota'] ?? 100 ?>" min="1" required>
                <small class="form-text">Kuota total semua jadwal</small>
            </div>
        </div>

        <div class="form-group">
            <label>Cover Image</label>
            <?php if (!empty($event['cover_image'])): ?>
                <div style="margin-bottom:10px;">
                    <img src="<?= BASE_URL ?>/<?= $event['cover_image'] ?>" style="max-width:200px;border-radius:8px;">
                </div>
            <?php endif; ?>
            <input type="file" name="cover_image" class="form-control" accept="image/*">
            <small class="form-text">Format: JPG, PNG. Ukuran ideal 1200x630px</small>
        </div>

        <!-- JADWAL -->
        <div class="form-group">
            <label>Jadwal & Lokasi</label>
            <small style="display:block;margin-bottom:10px;color:#666;">
                Tambahkan satu atau lebih jadwal untuk event ini.
            </small>

            <div id="schedules-container">
                <?php
                // Load existing schedules saat edit, atau 1 baris kosong saat create
                $existingSchedules = [];
                if ($event) {
                    $eventModel = new Event();
                    $existingSchedules = $eventModel->getSchedules($event['id']);
                }
                if (empty($existingSchedules)) {
                    $existingSchedules = [['venue'=>'','venue_address'=>'','event_date'=>'','end_date'=>'','ticket_quota'=>0,'city'=>'']];
                }
                foreach ($existingSchedules as $i => $sch):
                ?>
                <div class="schedule-item" data-index="<?= $i ?>">
                    <div class="schedule-item-header">
                        <strong>Jadwal #<span class="schedule-num"><?= $i + 1 ?></span></strong>
                        <button type="button" class="btn-remove-schedule" onclick="removeSchedule(this)" <?= count($existingSchedules) <= 1 ? 'style="display:none"' : '' ?>>
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                    <div class="form-row">
                        <div class="form-group" style="flex:1;">
                            <label>Tanggal & Waktu Mulai *</label>
                            <input type="datetime-local" name="schedules[<?= $i ?>][event_date]" class="form-control"
                                   value="<?= !empty($sch['event_date']) ? date('Y-m-d\TH:i', strtotime($sch['event_date'])) : '' ?>" required>
                        </div>
                        <div class="form-group" style="flex:1;">
                            <label>Tanggal & Waktu Selesai</label>
                            <input type="datetime-local" name="schedules[<?= $i ?>][end_date]" class="form-control"
                                   value="<?= !empty($sch['end_date']) ? date('Y-m-d\TH:i', strtotime($sch['end_date'])) : '' ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group" style="flex:1;">
                            <label>Venue / Tempat</label>
                            <input type="text" name="schedules[<?= $i ?>][venue]" class="form-control"
                                   value="<?= htmlspecialchars($sch['venue'] ?? '') ?>" placeholder="Nama tempat">
                        </div>
                        <div class="form-group" style="flex:1;">
                            <label>Kota</label>
                            <input type="text" name="schedules[<?= $i ?>][city]" class="form-control"
                                   value="<?= htmlspecialchars($sch['city'] ?? '') ?>" placeholder="Contoh: Semarang">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Alamat Venue</label>
                        <textarea name="schedules[<?= $i ?>][venue_address]" class="form-control" rows="2"
                                  placeholder="Alamat lengkap"><?= htmlspecialchars($sch['venue_address'] ?? '') ?></textarea>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <button type="button" class="btn btn-secondary" id="btn-add-schedule" onclick="addSchedule()" style="margin-top:8px;">
                <i class="fas fa-plus"></i> Tambah Jadwal
            </button>
        </div>

        <!-- KONTRIBUTOR -->
        <div class="form-group">
            <label>Kontributor yang Terlibat</label>
            <?php if (!empty($contributors)): ?>
                <div class="contributors-grid">
                    <?php foreach ($contributors as $contributor): ?>
                        <label class="contributor-checkbox">
                            <input type="checkbox" name="contributors[]" value="<?= $contributor['id'] ?>"
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
            <small style="display:block;margin-bottom:8px;color:#666;">Opsional</small>
            <div class="form-row">
                <div class="form-group" style="flex:1;">
                    <label>Nama Komunitas</label>
                    <input type="text" name="community_name" class="form-control"
                           value="<?= htmlspecialchars($event['community_name'] ?? '') ?>">
                </div>
                <div class="form-group" style="flex:1;">
                    <label>Link Instagram Komunitas</label>
                    <input type="url" name="community_ig" class="form-control"
                           value="<?= htmlspecialchars($event['community_ig'] ?? '') ?>">
                </div>
            </div>
            <div class="form-group">
                <label>No. WhatsApp Komunitas</label>
                <input type="text" name="community_wa" class="form-control"
                       value="<?= htmlspecialchars($event['community_wa'] ?? '') ?>"
                       placeholder="6281234567890">
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
.schedule-item {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 12px;
    background: #fafafa;
}
.schedule-item-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}
.btn-remove-schedule {
    background: #dc3545;
    color: #fff;
    border: none;
    border-radius: 4px;
    padding: 4px 10px;
    cursor: pointer;
    font-size: 12px;
}
.contributors-grid { display: flex; flex-wrap: wrap; gap: 8px; }
.contributor-checkbox {
    display: flex; align-items: center; gap: 6px;
    padding: 7px 14px; border: 1px solid #ddd;
    border-radius: 20px; cursor: pointer;
    font-size: 14px; background: #f9f9f9;
}
.contributor-checkbox:has(input:checked) { border-color: #333; background: #e8e8e8; font-weight: 600; }
</style>

<script>
var scheduleCount = <?= count($existingSchedules) ?>;

function addSchedule() {
    var i = scheduleCount;
    scheduleCount++;
    var html = `
    <div class="schedule-item" data-index="${i}">
        <div class="schedule-item-header">
            <strong>Jadwal #<span class="schedule-num">${scheduleCount}</span></strong>
            <button type="button" class="btn-remove-schedule" onclick="removeSchedule(this)">
                <i class="fas fa-trash"></i> Hapus
            </button>
        </div>
        <div class="form-row">
            <div class="form-group" style="flex:1;">
                <label>Tanggal & Waktu Mulai *</label>
                <input type="datetime-local" name="schedules[${i}][event_date]" class="form-control" required>
            </div>
            <div class="form-group" style="flex:1;">
                <label>Tanggal & Waktu Selesai</label>
                <input type="datetime-local" name="schedules[${i}][end_date]" class="form-control">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group" style="flex:1;">
                <label>Venue / Tempat</label>
                <input type="text" name="schedules[${i}][venue]" class="form-control" placeholder="Nama tempat">
            </div>
            <div class="form-group" style="flex:1;">
                <label>Kota</label>
                <input type="text" name="schedules[${i}][city]" class="form-control" placeholder="Contoh: Semarang">
            </div>
        </div>
        <div class="form-group">
            <label>Alamat Venue</label>
            <textarea name="schedules[${i}][venue_address]" class="form-control" rows="2" placeholder="Alamat lengkap"></textarea>
        </div>
    </div>`;
    document.getElementById('schedules-container').insertAdjacentHTML('beforeend', html);
    updateRemoveButtons();
}

function removeSchedule(btn) {
    btn.closest('.schedule-item').remove();
    renumberSchedules();
    updateRemoveButtons();
}

function renumberSchedules() {
    document.querySelectorAll('.schedule-item .schedule-num').forEach(function(el, idx) {
        el.textContent = idx + 1;
    });
}

function updateRemoveButtons() {
    var items = document.querySelectorAll('.schedule-item');
    items.forEach(function(item) {
        var btn = item.querySelector('.btn-remove-schedule');
        btn.style.display = items.length <= 1 ? 'none' : 'inline-block';
    });
}
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>