<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="page-header">
    <h1>Events (Pentas)</h1>
    <div class="header-actions">
        <form action="" method="GET" class="search-form">
            <input type="text" name="q" placeholder="Cari event..." value="<?= htmlspecialchars($search ?? '') ?>">
            <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
        </form>
        <a href="<?= BASE_URL ?>/admin/eventCreate" class="btn btn-primary">+ Tambah Event</a>
    </div>
</div>

<style>
.header-actions {
    display: flex;
    gap: 10px;
    align-items: center;
}
.search-form {
    display: flex;
    gap: 5px;
}
.search-form input {
    padding: 5px 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
}
</style>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Cover</th>
                <th>Judul Event</th>
                <th>Tanggal</th>
                <th>Venue</th>
                <th>Tiket</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($events)): ?>
                <tr><td colspan="7" style="text-align: center; padding: 2rem;">Belum ada event</td></tr>
            <?php else: ?>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td>
                            <?php if ($event['cover_image']): ?>
                                <img src="<?= BASE_URL ?>/<?= $event['cover_image'] ?>" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                            <?php else: ?>
                                <div style="width: 50px; height: 50px; background: #eee; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-calendar" style="color: #999;"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($event['title']) ?></strong>
                            <?php if ($event['ticket_price'] == 0): ?>
                                <span class="badge badge-success" style="margin-left: 5px;">GRATIS</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= date('d M Y', strtotime($event['event_date'])) ?><br>
                            <small><?= date('H:i', strtotime($event['event_date'])) ?> WIB</small>
                        </td>
                        <td><?= htmlspecialchars($event['venue'] ?? '-') ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>/admin/tickets?event_id=<?= $event['id'] ?>" class="btn btn-sm btn-outline-primary">
                                <?= $event['tickets_sold'] ?? 0 ?>/<?= $event['ticket_quota'] ?>
                            </a>
                            <?php if ($event['ticket_price'] > 0): ?>
                                <br><small>Rp <?= number_format($event['ticket_price'], 0, ',', '.') ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php 
                            $eventDate = strtotime($event['event_date']);
                            $now = time();
                            if ($eventDate < $now): ?>
                                <span class="badge badge-secondary">Selesai</span>
                            <?php elseif ($event['is_active']): ?>
                                <span class="badge badge-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge badge-warning">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= BASE_URL ?>/admin/eventEdit/<?= $event['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="<?= BASE_URL ?>/admin/eventDelete/<?= $event['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus event ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
