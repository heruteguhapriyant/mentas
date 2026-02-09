<?php // Merch Index Page - Updated dengan Pagination ?>

<section class="hero merch-hero">
    <div class="hero-content">
        <h1 class="page-title">Merch</h1>
        <p class="page-subtitle">Koleksi karya dan merchendise</p>
    </div>
</section>

<!-- Category Tabs -->
<section class="category-tabs-section">
    <div class="category-tabs">
        <a href="<?= BASE_URL ?>/merch" class="tab-btn <?= !$activeCategory ? 'active' : '' ?>">
            Semua
        </a>
        <?php foreach ($categories as $cat): ?>
            <a href="<?= BASE_URL ?>/merch?category=<?= $cat['slug'] ?>" class="tab-btn <?= ($activeCategory === $cat['slug']) ? 'active' : '' ?>">
                <?= htmlspecialchars($cat['name']) ?>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<section class="merch-section">
    <div class="container">
        <?php if (empty($products)): ?>
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <?php if ($activeCategory): ?>
                    <h3>Belum ada produk untuk kategori ini.</h3>
                <?php else: ?>
                    <h3>Belum ada produk tersedia.</h3>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach ($products as $product): ?>
                    <div class="merch-card">
                        <?php if (!empty($product['cover_image'])): ?>
                            <div class="merch-card-image">
                                <img src="<?= BASE_URL ?>/<?= $product['cover_image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                
                                <span class="merch-card-category">
                                    <?= htmlspecialchars($product['category_name'] ?? 'Merch') ?>
                                </span>
                                
                                <?php if (isset($product['stock']) && $product['stock'] <= 0): ?>
                                    <span class="merch-badge sold-out">Habis</span>
                                <?php elseif (isset($product['stock']) && $product['stock'] < 5): ?>
                                    <span class="merch-badge low-stock">Stok Terbatas</span>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="merch-card-image merch-cover-placeholder">
                                <i class="fas fa-box"></i>
                                
                                <span class="merch-card-category">
                                    <?= htmlspecialchars($product['category_name'] ?? 'Merch') ?>
                                </span>
                                
                                <?php if (isset($product['stock']) && $product['stock'] <= 0): ?>
                                    <span class="merch-badge sold-out">Habis</span>
                                <?php elseif (isset($product['stock']) && $product['stock'] < 5): ?>
                                    <span class="merch-badge low-stock">Stok Terbatas</span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="merch-card-content">
                            <h3><?= htmlspecialchars($product['name']) ?></h3>

                            <p class="merch-price">
                                Rp <?= number_format($product['price'], 0, ',', '.') ?>
                            </p>

                            <?php if (!empty($product['description'])): ?>
                                <p class="merch-card-excerpt">
                                    <?= htmlspecialchars(substr($product['description'], 0, 80)) ?>...
                                </p>
                            <?php endif; ?>

                            <div class="merch-buttons">
                                <a href="<?= BASE_URL ?>/merch/<?= $product['slug'] ?? $product['id'] ?>" class="btn-outline">
                                    <i class="fa-regular fa-eye"></i> Detail
                                </a>
                                
                                <?php if (!isset($product['stock']) || $product['stock'] > 0): ?>
                                    <a href="<?= BASE_URL ?>/merch/order/<?= $product['slug'] ?? $product['id'] ?>" 
                                       class="btn-whatsapp" 
                                       title="Order via WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                <?php else: ?>
                                    <button type="button" class="btn-whatsapp disabled" disabled title="Stok Habis">
                                        <i class="fab fa-whatsapp"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php 
// Tampilkan pagination jika ada data
if (!empty($pagination) && !empty($products)): 
    echo renderPagination($pagination);
endif; 
?>

<!-- Toast Notification -->
<div class="toast-container" id="toastContainer"></div>

<script>
function showToast(type, message) {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = 'toast ' + type;
    toast.innerHTML = message;
    container.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>