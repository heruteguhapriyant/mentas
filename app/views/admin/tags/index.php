<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="page-header">
    <h1>Kelola Tags</h1>
    <div class="header-actions">
        <form action="" method="GET" class="search-form">
            <input type="text" name="q" placeholder="Cari tag..." value="<?= htmlspecialchars($search ?? '') ?>">
            <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
        </form>
        <a href="<?= BASE_URL ?>/admin/tagCreate" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Tag
        </a>
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

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Nama Tag</th>
                <th>Slug</th>
                <th>Jumlah Post</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($tags)): ?>
                <tr>
                    <td colspan="4" style="text-align: center; color: #888;">Belum ada tag</td>
                </tr>
            <?php else: ?>
                <?php foreach ($tags as $tag): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($tag['name']) ?></strong></td>
                        <td><code><?= $tag['slug'] ?></code></td>
                        <td>
                            <span class="badge badge-info"><?= $tag['post_count'] ?? 0 ?></span>
                        </td>
                        <td>
                            <a href="<?= BASE_URL ?>/admin/tagEdit/<?= $tag['id'] ?>" class="btn btn-sm btn-secondary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="<?= BASE_URL ?>/admin/tagDelete/<?= $tag['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus tag ini?')">
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
