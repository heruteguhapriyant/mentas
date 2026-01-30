<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="page-header">
    <h1>
        Tiket
        <?php if ($currentEvent): ?>
            - <?= htmlspecialchars($currentEvent['title']) ?>
        <?php endif; ?>
    </h1>
    <div style="display: flex; gap: 10px;">
        <select onchange="window.location.href='<?= BASE_URL ?>/admin/tickets' + (this.value ? '?event_id=' + this.value : '')" class="form-control" style="width: auto;">
            <option value="">Semua Event</option>
            <?php foreach ($events as $event): ?>
                <option value="<?= $event['id'] ?>" <?= ($currentEvent && $currentEvent['id'] == $event['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($event['title']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<?php if ($currentEvent): ?>
<div class="stats-row" style="margin-bottom: 20px;">
    <div class="stat-card">
        <div class="stat-number"><?= $currentEvent['ticket_quota'] ?></div>
        <div class="stat-label">Total Kuota</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $currentEvent['tickets_sold'] ?? 0 ?></div>
        <div class="stat-label">Terjual</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">
            <?php 
            $confirmedCount = 0;
            $pendingCount = 0;
            foreach ($tickets as $t) {
                if ($t['status'] === 'confirmed') $confirmedCount++;
                if ($t['status'] === 'pending') $pendingCount++;
            }
            echo $confirmedCount;
            ?>
        </div>
        <div class="stat-label">Terkonfirmasi</div>
    </div>
    <div class="stat-card">
        <div class="stat-number" style="color: #ffc107;"><?= $pendingCount ?></div>
        <div class="stat-label">Pending</div>
    </div>
</div>
<?php endif; ?>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Kode Tiket</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Status</th>
                <th>Bukti Bayar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($tickets)): ?>
                <tr><td colspan="8" style="text-align: center; padding: 2rem;">Belum ada tiket</td></tr>
            <?php else: ?>
                <?php foreach ($tickets as $ticket): ?>
                    <tr>
                        <td>
                            <strong style="font-family: monospace;"><?= $ticket['ticket_code'] ?></strong>
                            <?php if (!$currentEvent && !empty($ticket['event_title'])): ?>
                                <br><small class="text-muted"><?= htmlspecialchars($ticket['event_title']) ?></small>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($ticket['name']) ?></td>
                        <td>
                            <?= htmlspecialchars($ticket['email']) ?>
                            <?php if (!empty($ticket['phone'])): ?>
                                <br><small><?= htmlspecialchars($ticket['phone']) ?></small>
                            <?php endif; ?>
                        </td>
                        <td><?= $ticket['quantity'] ?></td>
                        <td>
                            <?php if ($ticket['total_price'] > 0): ?>
                                Rp <?= number_format($ticket['total_price'], 0, ',', '.') ?>
                            <?php else: ?>
                                <span class="badge badge-success">GRATIS</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                            $statusClass = [
                                'pending' => 'badge-warning',
                                'confirmed' => 'badge-success',
                                'cancelled' => 'badge-danger',
                                'checked_in' => 'badge-info'
                            ];
                            $statusLabel = [
                                'pending' => 'Pending',
                                'confirmed' => 'Dikonfirmasi',
                                'cancelled' => 'Dibatalkan',
                                'checked_in' => 'Sudah Check-in'
                            ];
                            ?>
                            <span class="badge <?= $statusClass[$ticket['status']] ?? 'badge-secondary' ?>">
                                <?= $statusLabel[$ticket['status']] ?? $ticket['status'] ?>
                            </span>
                            <?php if ($ticket['status'] === 'checked_in' && $ticket['checked_in_at']): ?>
                                <br><small><?= date('d/m H:i', strtotime($ticket['checked_in_at'])) ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($ticket['payment_proof'])): ?>
                                <a href="<?= BASE_URL ?>/<?= $ticket['payment_proof'] ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-image"></i> Lihat
                                </a>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php $eventParam = $currentEvent ? '?event_id=' . $currentEvent['id'] : ''; ?>
                            
                            <?php if ($ticket['status'] === 'pending'): ?>
                                <a href="<?= BASE_URL ?>/admin/ticketConfirm/<?= $ticket['id'] ?><?= $eventParam ?>" class="btn btn-sm btn-success" onclick="return confirm('Konfirmasi tiket ini?')">
                                    <i class="fas fa-check"></i>
                                </a>
                                <a href="<?= BASE_URL ?>/admin/ticketCancel/<?= $ticket['id'] ?><?= $eventParam ?>" class="btn btn-sm btn-warning" onclick="return confirm('Batalkan tiket ini?')">
                                    <i class="fas fa-times"></i>
                                </a>
                            <?php endif; ?>
                            
                            <a href="<?= BASE_URL ?>/admin/ticketDelete/<?= $ticket['id'] ?><?= $eventParam ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus tiket ini?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
.stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
}
.stat-card {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}
.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #d52c2c;
}
.stat-label {
    color: #666;
    font-size: 0.9rem;
}
@media (max-width: 768px) {
    .stats-row { grid-template-columns: repeat(2, 1fr); }
}
</style>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
