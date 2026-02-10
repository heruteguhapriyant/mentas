<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="page-header">
    <h1>Kelola Artikel</h1>
    <a href="<?= BASE_URL ?>/admin/postCreate" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tulis Artikel
    </a>
</div>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Penulis</th>
                <th>Status</th>
                <th>Views</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($posts)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: #666;">Belum ada artikel</td>
                </tr>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($post['title']) ?></strong>
                            <?php if ($post['excerpt']): ?>
                                <br><small style="color: #666;"><?= substr($post['excerpt'], 0, 60) ?>...</small>
                            <?php endif; ?>
                        </td>
                        <td><?= $post['category_name'] ?? '-' ?></td>
                        <td><?= $post['author_name'] ?? '-' ?></td>
                        <td>
                            <span class="badge badge-<?= $post['status'] === 'published' ? 'success' : 'warning' ?>">
                                <?= ucfirst($post['status']) ?>
                            </span>
                        </td>
                        <td><?= $post['views'] ?></td>
                        <td>
                            <?php if ($post['published_at']): ?>
                                <?= date('d M Y H:i', strtotime($post['published_at'])) ?>
                                <?php if (strtotime($post['published_at']) > time()): ?>
                                    <br><small class="text-info"><i class="fas fa-clock"></i> Terjadwal</small>
                                <?php endif; ?>
                            <?php else: ?>
                                <?= date('d M Y', strtotime($post['created_at'])) ?>
                                <br><small class="text-muted">Dibuat</small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= BASE_URL ?>/blog/<?= $post['slug'] ?>" class="btn btn-sm btn-secondary" target="_blank" title="Lihat">
                                <i class="fas fa-eye"></i>
                            </a>
                            
                            <?php if ($post['status'] === 'pending'): ?>
                                <a href="<?= BASE_URL ?>/admin/postApprove/<?= $post['id'] ?>" class="btn btn-sm btn-success" title="Approve" onclick="return confirm('Publish artikel ini?')">
                                    <i class="fas fa-check"></i>
                                </a>
                                <a href="<?= BASE_URL ?>/admin/postReject/<?= $post['id'] ?>" class="btn btn-sm btn-warning" title="Reject (Draft)" onclick="return confirm('Kembalikan ke draft?')">
                                    <i class="fas fa-times"></i>
                                </a>
                            <?php endif; ?>

                            <a href="<?= BASE_URL ?>/admin/postEdit/<?= $post['id'] ?>" class="btn btn-sm btn-warning" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="<?= BASE_URL ?>/admin/postDelete/<?= $post['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus artikel ini?')" title="Hapus">
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
