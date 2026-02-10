<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="page-header">
    <h1>Buletin Sastra</h1>
    <div class="header-actions">
        <form action="" method="GET" class="search-form">
            <input type="text" name="q" placeholder="Cari buletin..." value="<?= htmlspecialchars($search ?? '') ?>">
            <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
        </form>
        <a href="<?= BASE_URL ?>/admin/categories?type=zine" class="btn btn-secondary">Kelola Kategori</a>
        <a href="<?= BASE_URL ?>/admin/zineCreate" class="btn btn-primary">+ Tambah Buletin</a>
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
                <th>Judul</th>
                <th>Kategori</th>
                <th>PDF</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($zines)): ?>
                <tr><td colspan="7" style="text-align: center; padding: 2rem;">Belum ada buletin</td></tr>
            <?php else: ?>
                <?php foreach ($zines as $zine): ?>
                    <tr>
                        <td>
                            <?php if ($zine['cover_image']): ?>
                                <img src="<?= BASE_URL ?>/<?= $zine['cover_image'] ?>" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                            <?php else: ?>
                                <div style="width: 50px; height: 50px; background: #eee; border-radius: 4px;"></div>
                            <?php endif; ?>
                        </td>
                        <td><strong><?= htmlspecialchars($zine['title']) ?></strong></td>
                        <td>
                            <span class="badge badge-info"><?= htmlspecialchars($zine['category_name'] ?? '-') ?></span>
                        </td>
                        <td>
                            <?php if (!empty($zine['pdf_file'])): ?>
                                <a href="<?= BASE_URL ?>/<?= $zine['pdf_file'] ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-file-pdf"></i> Lihat PDF
                                </a>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($zine['is_active']): ?>
                                <span class="badge badge-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date('d M Y', strtotime($zine['created_at'])) ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>/admin/zineEdit/<?= $zine['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="<?= BASE_URL ?>/admin/zineDelete/<?= $zine['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
