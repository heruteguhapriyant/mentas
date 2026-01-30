<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="page-header">
    <h1>Produk (Merch)</h1>
    <a href="<?= BASE_URL ?>/admin/productCreate" class="btn btn-primary">+ Tambah Produk</a>
</div>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Cover</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($products)): ?>
                <tr><td colspan="7" style="text-align: center; padding: 2rem;">Belum ada produk</td></tr>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td>
                            <?php if ($product['cover_image']): ?>
                                <img src="<?= BASE_URL ?>/<?= $product['cover_image'] ?>" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                            <?php else: ?>
                                <div style="width: 50px; height: 50px; background: #eee; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-box" style="color: #999;"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td><strong><?= htmlspecialchars($product['name']) ?></strong></td>
                        <td>
                            <?php if ($product['category'] === 'buku'): ?>
                                <span class="badge badge-info">Buku</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">Merchandise</span>
                            <?php endif; ?>
                        </td>
                        <td>Rp <?= number_format($product['price'], 0, ',', '.') ?></td>
                        <td>
                            <?php if ($product['stock'] <= 0): ?>
                                <span class="badge badge-danger">Habis</span>
                            <?php elseif ($product['stock'] < 5): ?>
                                <span class="badge badge-warning"><?= $product['stock'] ?></span>
                            <?php else: ?>
                                <span class="badge badge-success"><?= $product['stock'] ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($product['is_active']): ?>
                                <span class="badge badge-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= BASE_URL ?>/admin/productEdit/<?= $product['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="<?= BASE_URL ?>/admin/productDelete/<?= $product['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus produk ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
