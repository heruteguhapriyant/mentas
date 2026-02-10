<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="page-header">
    <h1>Kelola User</h1>
    <div class="header-actions">
        <form action="" method="GET" class="search-form">
            <input type="text" name="q" placeholder="Cari user..." value="<?= htmlspecialchars($search ?? '') ?>">
            <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
        </form>
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
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Terdaftar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($users)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #666;">Belum ada user</td>
                </tr>
            <?php else: ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($user['name']) ?></strong>
                            <?php if ($user['bio']): ?>
                                <br><small style="color: #666;"><?= substr($user['bio'], 0, 50) ?>...</small>
                            <?php endif; ?>
                        </td>
                        <td><?= $user['email'] ?></td>
                        <td>
                            <span class="badge badge-<?= $user['role'] === 'admin' ? 'primary' : 'secondary' ?>" style="<?= $user['role'] === 'admin' ? 'background:#007bff;color:#fff;' : '' ?>">
                                <?= ucfirst($user['role']) ?>
                            </span>
                        </td>
                        <td>
                            <?php
                            $statusColors = [
                                'active' => 'success',
                                'pending' => 'warning',
                                'rejected' => 'danger'
                            ];
                            ?>
                            <span class="badge badge-<?= $statusColors[$user['status']] ?>">
                                <?= ucfirst($user['status']) ?>
                            </span>
                        </td>
                        <td><?= date('d M Y', strtotime($user['created_at'])) ?></td>
                        <td>
                            <?php if ($user['role'] !== 'admin'): ?>
                                <?php if ($user['status'] === 'pending'): ?>
                                    <a href="<?= BASE_URL ?>/admin/userApprove/<?= $user['id'] ?>" class="btn btn-sm btn-success" title="Approve">
                                        <i class="fas fa-check"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>/admin/userReject/<?= $user['id'] ?>" class="btn btn-sm btn-danger" title="Reject">
                                        <i class="fas fa-times"></i>
                                    </a>
                                <?php elseif ($user['status'] === 'rejected'): ?>
                                    <a href="<?= BASE_URL ?>/admin/userApprove/<?= $user['id'] ?>" class="btn btn-sm btn-success" title="Approve">
                                        <i class="fas fa-check"></i> Approve
                                    </a>
                                <?php endif; ?>
                                <a href="<?= BASE_URL ?>/admin/userDelete/<?= $user['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus user ini?')" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </a>
                            <?php else: ?>
                                <span style="color: #999;">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
