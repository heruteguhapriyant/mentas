<?php require_once __DIR__ . '/layout/header.php'; ?>

<main class="container contributor-dashboard">
    <div class="dashboard-header">
        <h1>Dashboard Contributor</h1>
        <a href="<?= BASE_URL ?>/contributor/create" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Tulis Artikel
        </a>
    </div>

    <?php $flash = getFlash(); ?>
    <?php if ($flash): ?>
        <div class="alert alert-<?= $flash['type'] ?>">
            <?= $flash['message'] ?>
        </div>
    <?php endif; ?>

    <div class="stats-row">
        <div class="stat-box">
            <span class="stat-number"><?= $stats['published'] ?></span>
            <span class="stat-label">Published</span>
        </div>
        <div class="stat-box">
            <span class="stat-number"><?= $stats['drafts'] ?></span>
            <span class="stat-label">Draft</span>
        </div>
    </div>

    <div class="posts-list">
        <h2>Artikel Saya</h2>
        
        <?php if (empty($posts)): ?>
            <p class="empty-state">Anda belum menulis artikel apapun. <a href="<?= BASE_URL ?>/contributor/create">Mulai menulis</a></p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($post['title']) ?></strong></td>
                            <td><?= $post['category_name'] ?? '-' ?></td>
                            <td>
                                <span class="badge badge-<?= $post['status'] === 'published' ? 'success' : 'warning' ?>">
                                    <?= ucfirst($post['status']) ?>
                                </span>
                            </td>
                            <td><?= date('d M Y', strtotime($post['created_at'])) ?></td>
                            <td>
                                <?php if ($post['status'] === 'published'): ?>
                                    <a href="<?= BASE_URL ?>/blog/<?= $post['slug'] ?>" class="btn btn-sm btn-secondary" target="_blank">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                <?php endif; ?>
                                <a href="<?= BASE_URL ?>/contributor/edit/<?= $post['id'] ?>" class="btn btn-sm btn-warning">
                                    <i class="fa-solid fa-edit"></i>
                                </a>
                                <a href="<?= BASE_URL ?>/contributor/delete/<?= $post['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus artikel ini?')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</main>

<style>
.contributor-dashboard { padding: 2rem 0; }
.dashboard-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
.stats-row { display: flex; gap: 1rem; margin-bottom: 2rem; }
.stat-box { background: #fff; padding: 1.5rem 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center; }
.stat-number { display: block; font-size: 2rem; font-weight: bold; color: #007bff; }
.stat-label { color: #666; }
.posts-list h2 { margin-bottom: 1rem; }
.table { width: 100%; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.table th, .table td { padding: 1rem; text-align: left; border-bottom: 1px solid #eee; }
.table th { background: #f9f9f9; }
.btn { display: inline-block; padding: 0.5rem 1rem; border: none; border-radius: 4px; text-decoration: none; cursor: pointer; font-size: 0.875rem; }
.btn-sm { padding: 0.25rem 0.5rem; }
.btn-primary { background: #007bff; color: #fff; }
.btn-secondary { background: #6c757d; color: #fff; }
.btn-warning { background: #ffc107; color: #000; }
.btn-danger { background: #dc3545; color: #fff; }
.badge { padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem; }
.badge-success { background: #d4edda; color: #155724; }
.badge-warning { background: #fff3cd; color: #856404; }
.alert { padding: 1rem; border-radius: 4px; margin-bottom: 1rem; }
.alert-success { background: #d4edda; color: #155724; }
.alert-error { background: #f8d7da; color: #721c24; }
.empty-state { padding: 2rem; text-align: center; background: #f9f9f9; border-radius: 8px; color: #666; }
</style>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
