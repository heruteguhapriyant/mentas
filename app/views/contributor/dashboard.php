<?php require_once __DIR__ . '/layout/header.php'; ?>

<main class="contributor-dashboard">
    <div class="container">
        <?php $flash = getFlash(); ?>
        <?php if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?>">
                <?= $flash['message'] ?>
            </div>
        <?php endif; ?>

        <div class="dashboard-welcome">
            <div class="welcome-text">
                <h1>Selamat datang, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Contributor') ?>!</h1>
                <p>Kelola artikel dan kontribusi Anda di Mentas.id</p>
            </div>
            <a href="<?= BASE_URL ?>/contributor/create" class="btn-write">
                <i class="fa-solid fa-plus"></i> Tulis Artikel Baru
            </a>
        </div>

        <div class="stats-grid">
            <div class="stat-card published">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-info">
                    <span class="stat-number"><?= $stats['published'] ?></span>
                    <span class="stat-label">Artikel Published</span>
                </div>
            </div>
            <div class="stat-card draft">
                <div class="stat-icon"><i class="fas fa-edit"></i></div>
                <div class="stat-info">
                    <span class="stat-number"><?= $stats['drafts'] ?></span>
                    <span class="stat-label">Draft Tersimpan</span>
                </div>
            </div>
        </div>

        <div class="articles-section">
            <div class="section-header">
                <h2><i class="fas fa-newspaper"></i> Artikel Saya</h2>
            </div>
            
            <?php if (empty($posts)): ?>
                <div class="empty-state">
                    <i class="fas fa-file-alt"></i>
                    <h3>Belum ada artikel</h3>
                    <p>Mulai berbagi karya dan tulisan Anda dengan komunitas seni budaya Indonesia</p>
                    <a href="<?= BASE_URL ?>/contributor/create" class="btn-primary">
                        <i class="fas fa-plus"></i> Tulis Artikel Pertama
                    </a>
                </div>
            <?php else: ?>
                <div class="articles-table-wrap">
                    <table class="articles-table">
                        <thead>
                            <tr>
                                <th>Judul Artikel</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($posts as $post): ?>
                                <tr>
                                    <td class="article-title">
                                        <strong><?= htmlspecialchars($post['title']) ?></strong>
                                    </td>
                                    <td><?= $post['category_name'] ?? '-' ?></td>
                                    <td>
                                        <span class="status-badge <?= $post['status'] ?>">
                                            <?= $post['status'] === 'published' ? 'Published' : 'Draft' ?>
                                        </span>
                                    </td>
                                    <td><?= date('d M Y', strtotime($post['created_at'])) ?></td>
                                    <td class="actions">
                                        <?php if ($post['status'] === 'published'): ?>
                                            <a href="<?= BASE_URL ?>/blog/<?= $post['slug'] ?>" class="action-btn view" target="_blank" title="Lihat">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        <?php endif; ?>
                                        <a href="<?= BASE_URL ?>/contributor/edit/<?= $post['id'] ?>" class="action-btn edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>/contributor/delete/<?= $post['id'] ?>" class="action-btn delete" onclick="return confirm('Hapus artikel ini?')" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<style>
.contributor-dashboard {
    background: #f8f9fa;
    min-height: calc(100vh - 200px);
    padding: 2rem 0;
}
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Welcome Section */
.dashboard-welcome {
    background: linear-gradient(135deg, #d52c2c 0%, #8b1a1a 100%);
    color: #fff;
    padding: 2rem;
    border-radius: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    box-shadow: 0 4px 15px rgba(213, 44, 44, 0.3);
}
.welcome-text h1 { font-size: 1.5rem; margin-bottom: 0.5rem; }
.welcome-text p { opacity: 0.9; margin: 0; }
.btn-write {
    background: #fff;
    color: #d52c2c;
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: transform 0.2s, box-shadow 0.2s;
}
.btn-write:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}
.stat-card {
    background: #fff;
    padding: 1.5rem;
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border-left: 4px solid;
}
.stat-card.published { border-color: #28a745; }
.stat-card.draft { border-color: #ffc107; }
.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}
.stat-card.published .stat-icon { background: #e8f5e9; color: #28a745; }
.stat-card.draft .stat-icon { background: #fff8e1; color: #ffc107; }
.stat-number { display: block; font-size: 1.8rem; font-weight: 700; color: #333; }
.stat-label { color: #666; font-size: 0.9rem; }

/* Articles Section */
.articles-section {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    overflow: hidden;
}
.section-header {
    padding: 1.5rem;
    border-bottom: 1px solid #eee;
    background: #fafafa;
}
.section-header h2 {
    margin: 0;
    font-size: 1.2rem;
    color: #333;
    display: flex;
    align-items: center;
    gap: 10px;
}
.section-header h2 i { color: #d52c2c; }

/* Empty State */
.empty-state {
    padding: 4rem 2rem;
    text-align: center;
    color: #666;
}
.empty-state i { font-size: 4rem; color: #ddd; margin-bottom: 1rem; }
.empty-state h3 { margin-bottom: 0.5rem; color: #333; }
.empty-state p { margin-bottom: 1.5rem; }
.btn-primary {
    background: #d52c2c;
    color: #fff;
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

/* Table */
.articles-table-wrap { overflow-x: auto; }
.articles-table {
    width: 100%;
    border-collapse: collapse;
}
.articles-table th,
.articles-table td {
    padding: 1rem 1.5rem;
    text-align: left;
    border-bottom: 1px solid #eee;
}
.articles-table th {
    background: #f9f9f9;
    font-weight: 600;
    color: #555;
    font-size: 0.85rem;
    text-transform: uppercase;
}
.articles-table tbody tr:hover { background: #f8f9fa; }
.article-title strong { color: #333; }
.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}
.status-badge.published { background: #e8f5e9; color: #2e7d32; }
.status-badge.draft { background: #fff8e1; color: #f57c00; }

/* Action Buttons */
.actions { display: flex; gap: 6px; }
.action-btn {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: opacity 0.2s;
}
.action-btn:hover { opacity: 0.8; }
.action-btn.view { background: #e3f2fd; color: #1976d2; }
.action-btn.edit { background: #fff8e1; color: #f57c00; }
.action-btn.delete { background: #ffebee; color: #d32f2f; }

/* Alert */
.alert {
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
}
.alert-success { background: #e8f5e9; color: #2e7d32; }
.alert-error { background: #ffebee; color: #d32f2f; }

@media (max-width: 768px) {
    .dashboard-welcome { flex-direction: column; text-align: center; gap: 1rem; }
    .stats-grid { grid-template-columns: 1fr; }
}
</style>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
