<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="page-header">
    <h1>Kolaborasi</h1>
    <div class="header-actions">
        <form action="" method="GET" class="search-form">
            <input type="text" name="q" placeholder="Cari kolaborasi..." value="<?= htmlspecialchars($search ?? '') ?>">
            <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
        </form>
        <a href="<?= BASE_URL ?>/admin/collaborationCreate" class="btn btn-primary">+ Tambah Kolaborasi</a>
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
.contributor-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
}
.contributor-tag {
    background: #e8f4fd;
    color: #0c5a8a;
    font-size: 11px;
    padding: 2px 8px;
    border-radius: 12px;
}
</style>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Cover</th>
                <th>Nama Kolaborasi</th>
                <th>Kontributor</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($collaborations)): ?>
                <tr><td colspan="6" style="text-align: center; padding: 2rem;">Belum ada kolaborasi</td></tr>
            <?php else: ?>
                <?php foreach ($collaborations as $collab): ?>
                    <tr>
                        <td>
                            <?php if (!empty($collab['cover_image'])): ?>
                                <img src="<?= BASE_URL ?>/<?= $collab['cover_image'] ?>" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                            <?php else: ?>
                                <div style="width: 50px; height: 50px; background: #eee; border-radius: 4px; display:flex; align-items:center; justify-content:center;">
                                    <i class="fas fa-handshake" style="color:#ccc;"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($collab['title']) ?></strong>
                            <?php if (!empty($collab['description'])): ?>
                                <br><small style="color: #666;"><?= substr(htmlspecialchars($collab['description']), 0, 60) ?>...</small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($collab['contributor_names'])): ?>
                                <div class="contributor-tags">
                                    <?php foreach (explode(', ', $collab['contributor_names']) as $name): ?>
                                        <span class="contributor-tag"><?= htmlspecialchars($name) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <span style="color:#999;">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($collab['is_active']): ?>
                                <span class="badge badge-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date('d M Y', strtotime($collab['created_at'])) ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>/admin/collaborationEdit/<?= $collab['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="<?= BASE_URL ?>/admin/collaborationDelete/<?= $collab['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus kolaborasi ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
