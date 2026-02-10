<?php require_once __DIR__ . '/layout/header.php'; ?>

<div class="page-header">
    <h1>Dashboard</h1>
    <span>Selamat datang, <?= $_SESSION['user_name'] ?></span>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <a href="<?= BASE_URL ?>/admin/posts?status=published" class="stat-card primary">
        <h4>Artikel Published</h4>
        <div class="number"><?= $stats['posts'] ?></div>
    </a>
    <a href="<?= BASE_URL ?>/admin/posts?status=draft" class="stat-card warning">
        <h4>Artikel Draft</h4>
        <div class="number"><?= $stats['drafts'] ?></div>
    </a>
    <a href="<?= BASE_URL ?>/admin/categories" class="stat-card success">
        <h4>Kategori</h4>
        <div class="number"><?= $stats['categories'] ?></div>
    </a>
    <a href="<?= BASE_URL ?>/admin/users?status=pending" class="stat-card danger">
        <h4>Contributor Pending</h4>
        <div class="number"><?= $stats['pending_contributors'] ?></div>
    </a>
    <a href="<?= BASE_URL ?>/admin/users?status=active" class="stat-card">
        <h4>Contributor Aktif</h4>
        <div class="number"><?= $stats['active_contributors'] ?></div>
    </a>
</div>

<style>
.stat-card {
    text-decoration: none;
    color: inherit;
    display: block; /* Ensure it behaves like a block */
    transition: transform 0.2s, box-shadow 0.2s;
}
.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    text-decoration: none;
    color: inherit;
}
/* Ensure headings inside don't get decorated either */
.stat-card h4, .stat-card .number {
    color: inherit;
}
</style>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
    <!-- Recent Posts -->
    <div class="card">
        <div class="card-header">
            <h3>Artikel Terbaru</h3>
            <a href="<?= BASE_URL ?>/admin/posts" class="btn btn-sm btn-primary">Lihat Semua</a>
        </div>
        
        <?php if (empty($recentPosts)): ?>
            <p style="color: #666;">Belum ada artikel.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentPosts as $post): ?>
                        <tr>
                            <td><?= htmlspecialchars($post['title']) ?></td>
                            <td><?= $post['category_name'] ?? '-' ?></td>
                            <td>
                                <span class="badge badge-<?= $post['status'] === 'published' ? 'success' : 'warning' ?>">
                                    <?= ucfirst($post['status']) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- Pending Contributors -->
    <div class="card">
        <div class="card-header">
            <h3>Menunggu Persetujuan</h3>
        </div>
        
        <?php if (empty($pendingContributors)): ?>
            <p style="color: #666;">Tidak ada contributor pending.</p>
        <?php else: ?>
            <?php foreach ($pendingContributors as $contributor): ?>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0; border-bottom: 1px solid #eee;">
                    <div>
                        <strong><?= htmlspecialchars($contributor['name']) ?></strong>
                        <br><small style="color: #666;"><?= $contributor['email'] ?></small>
                    </div>
                    <div>
                        <a href="<?= BASE_URL ?>/admin/userApprove/<?= $contributor['id'] ?>" class="btn btn-sm btn-success">
                            <i class="fas fa-check"></i>
                        </a>
                        <a href="<?= BASE_URL ?>/admin/userReject/<?= $contributor['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tolak contributor ini?')">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
