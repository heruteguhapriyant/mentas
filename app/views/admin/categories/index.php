<?php require_once __DIR__ . '/../layout/header.php'; ?>

<?php
$typeLabels = [
    'blog' => 'Blog',
    'zine' => 'Bulletin Sastra',
    'merch' => 'Merchandise'
];
$pageTitle = isset($activeType) && $activeType ? 'Kelola Kategori ' . ($typeLabels[$activeType] ?? ucfirst($activeType)) : 'Kelola Semua Kategori';
?>

<style>
.filter-tabs {
    display: flex;
    gap: 8px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}
.filter-tab {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}
.filter-tab i {
    font-size: 0.85rem;
}
.filter-tab.all {
    background: #f0f0f0;
    color: #333;
}
.filter-tab.all.active,
.filter-tab.all:hover {
    background: #333;
    color: #fff;
}
.filter-tab.blog {
    background: #e3f2fd;
    color: #1976d2;
}
.filter-tab.blog.active,
.filter-tab.blog:hover {
    background: #1976d2;
    color: #fff;
}
.filter-tab.zine {
    background: #e0f7fa;
    color: #00838f;
}
.filter-tab.zine.active,
.filter-tab.zine:hover {
    background: #00838f;
    color: #fff;
}
.filter-tab.merch {
    background: #fff3e0;
    color: #e65100;
}
.filter-tab.merch.active,
.filter-tab.merch:hover {
    background: #e65100;
    color: #fff;
}
.badge-primary { background: #e3f2fd; color: #1976d2; }
.badge-info { background: #e0f7fa; color: #00838f; }
.badge-warning { background: #fff3e0; color: #e65100; }
</style>

<div class="page-header">
    <h1><?= $pageTitle ?></h1>
    <a href="<?= BASE_URL ?>/admin/categoryCreate<?= isset($activeType) && $activeType ? '?type=' . $activeType : '' ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Kategori
    </a>
</div>

<!-- Type Filter Tabs -->
<div class="filter-tabs">
    <a href="<?= BASE_URL ?>/admin/categories" class="filter-tab all <?= !isset($activeType) || !$activeType ? 'active' : '' ?>">
        <i class="fas fa-th-large"></i> Semua
    </a>
    <a href="<?= BASE_URL ?>/admin/categories?type=blog" class="filter-tab blog <?= ($activeType ?? '') === 'blog' ? 'active' : '' ?>">
        <i class="fas fa-newspaper"></i> Blog
    </a>
    <a href="<?= BASE_URL ?>/admin/categories?type=zine" class="filter-tab zine <?= ($activeType ?? '') === 'zine' ? 'active' : '' ?>">
        <i class="fas fa-book-open"></i> Bulletin Sastra
    </a>
    <a href="<?= BASE_URL ?>/admin/categories?type=merch" class="filter-tab merch <?= ($activeType ?? '') === 'merch' ? 'active' : '' ?>">
        <i class="fas fa-shopping-bag"></i> Merchandise
    </a>
</div>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Urutan</th>
                <th>Nama</th>
                <th>Slug</th>
                <th>Tipe</th>
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
                        <td>
                            <?php 
                            $typeBadgeClass = [
                                'blog' => 'badge-primary',
                                'zine' => 'badge-info',
                                'merch' => 'badge-warning'
                            ];
                            $typeLabel = $typeLabels[$cat['type'] ?? 'blog'] ?? 'Blog';
                            $badgeClass = $typeBadgeClass[$cat['type'] ?? 'blog'] ?? 'badge-secondary';
                            ?>
                            <span class="badge <?= $badgeClass ?>"><?= $typeLabel ?></span>
                        </td>
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
