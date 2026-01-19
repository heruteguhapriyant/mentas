<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="page-header">
    <h1>Kelola Kategori Blog</h1>
    <a href="<?= BASE_URL ?>/admin/categoryCreate" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Kategori
    </a>
</div>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Urutan</th>
                <th>Nama</th>
                <th>Slug</th>
                <th>Jumlah Artikel</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($categories)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #666;">Belum ada kategori</td>
                </tr>
            <?php else: ?>
                <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td><?= $cat['sort_order'] ?></td>
                        <td><strong><?= htmlspecialchars($cat['name']) ?></strong></td>
                        <td><code><?= $cat['slug'] ?></code></td>
                        <td><?= $cat['post_count'] ?? 0 ?></td>
                        <td>
                            <span class="badge badge-<?= $cat['is_active'] ? 'success' : 'secondary' ?>">
                                <?= $cat['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?= BASE_URL ?>/admin/categoryEdit/<?= $cat['id'] ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="<?= BASE_URL ?>/admin/categoryDelete/<?= $cat['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus kategori ini?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
