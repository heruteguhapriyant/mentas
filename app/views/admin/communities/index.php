<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="page-header">
    <h1>Katalog Komunitas</h1>
    <div class="header-actions">
        <form action="" method="GET" class="search-form">
            <input type="text" name="q" placeholder="Cari komunitas..." value="<?= htmlspecialchars($search ?? '') ?>">
            <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
        </form>
        <a href="<?= BASE_URL ?>/admin/communityCreate" class="btn btn-primary">+ Tambah Komunitas</a>
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
                <th>Logo</th>
                <th>Nama</th>
                <th>Lokasi</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($communities)): ?>
                <tr><td colspan="5" style="text-align: center; padding: 2rem;">Belum ada komunitas</td></tr>
            <?php else: ?>
                <?php foreach ($communities as $community): ?>
                    <tr>
                        <td>
                            <?php if ($community['image']): ?>
                                <img src="<?= BASE_URL ?>/<?= $community['image'] ?>" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                            <?php else: ?>
                                <div style="width: 50px; height: 50px; background: #eee; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-users" style="color: #ccc;"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($community['name']) ?></strong>
                            <?php if ($community['website']): ?>
                                <br><small><a href="<?= $community['website'] ?>" target="_blank"><?= $community['website'] ?></a></small>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($community['location'] ?? '-') ?></td>
                        <td>
                            <?php if ($community['is_active']): ?>
                                <span class="badge badge-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= BASE_URL ?>/admin/communityEdit/<?= $community['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="<?= BASE_URL ?>/admin/communityDelete/<?= $community['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
