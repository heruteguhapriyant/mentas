<?php // Merch Index Page - Updated dengan Pagination ?>

<section class="hero zine-hero">
    <div class="hero-content">
        <h1>Merch</h1>
        <p>Koleksi merchandise dan buku dari Mentas.id</p>
    </div>
</section>

<!-- Category Tabs -->
<section class="zine-category-tabs">
    <div class="category-tabs-container">
        <a href="<?= BASE_URL ?>/merch" class="category-tab <?= !$activeCategory ? 'active' : '' ?>">
            Semua
        </a>
        <?php foreach ($categories as $cat): ?>
            <a href="<?= BASE_URL ?>/merch?category=<?= $cat['slug'] ?>" class="category-tab <?= ($activeCategory === $cat['slug']) ? 'active' : '' ?>">
                <?= htmlspecialchars($cat['name']) ?>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<section class="program-section zine-section">
    <?php if (empty($products)): ?>
        <p class="zine-empty">
            <?php if ($activeCategory): ?>
                Belum ada produk untuk kategori ini.
            <?php else: ?>
                Belum ada produk tersedia.
            <?php endif; ?>
        </p>
    <?php else: ?>
        <?php foreach ($products as $product): ?>
            <div class="program-card zine-card">
                <?php if (!empty($product['cover_image'])): ?>
                    <div class="zine-card-cover">
                        <img src="<?= BASE_URL ?>/<?= $product['cover_image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        <?php if (isset($product['stock']) && $product['stock'] <= 0): ?>
                            <span class="merch-badge sold-out">Habis</span>
                        <?php elseif (isset($product['stock']) && $product['stock'] < 5): ?>
                            <span class="merch-badge low-stock">Stok Terbatas</span>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="zine-card-cover" style="background: linear-gradient(135deg, #d52c2c 0%, #1a1a2e 100%); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-box" style="font-size: 48px; color: rgba(255,255,255,0.8);"></i>
                        <?php if (isset($product['stock']) && $product['stock'] <= 0): ?>
                            <span class="merch-badge sold-out">Habis</span>
                        <?php elseif (isset($product['stock']) && $product['stock'] < 5): ?>
                            <span class="merch-badge low-stock">Stok Terbatas</span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <div class="zine-card-content">
                    <span class="zine-card-category">
                        <?= htmlspecialchars($product['category_name'] ?? 'Merch') ?>
                    </span>
                    
                    <h3><?= htmlspecialchars($product['name']) ?></h3>

                    <p class="merch-price">
                        Rp <?= number_format($product['price'], 0, ',', '.') ?>
                    </p>

                    <?php if (!empty($product['description'])): ?>
                        <p class="zine-card-excerpt">
                            <?= htmlspecialchars(substr($product['description'], 0, 80)) ?>...
                        </p>
                    <?php endif; ?>

                    <div class="merch-buttons">
                        <a href="<?= BASE_URL ?>/merch/<?= $product['slug'] ?? $product['id'] ?>" class="btn-outline">
                            <i class="fas fa-eye"></i> Lihat Detail
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
    <?php endif; ?>
</section>

<?php 
// Tampilkan pagination jika ada data
if (!empty($pagination) && !empty($products)): 
    echo renderPagination($pagination);
endif; 
?>

<!-- Toast Notification -->
<div class="toast-container" id="toastContainer"></div>

<style>
/* Merch Price */
.merch-price {
    font-size: 1.2rem;
    font-weight: 700;
    color: #d52c2c;
    margin: 10px 0;
}

/* Badges */
.merch-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    z-index: 2;
}

.merch-badge.sold-out {
    background: #dc3545;
    color: #fff;
}

.merch-badge.low-stock {
    background: #ffc107;
    color: #000;
}

.zine-card-cover {
    position: relative;
}

/* Button Styles */
.merch-buttons {
    display: flex;
    gap: 10px;
    align-items: center;
    margin-top: 15px;
}

.btn-outline {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px 18px;
    border: 2px solid #d52c2c;
    color: #d52c2c;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s;
}

.btn-outline:hover {
    background: #d52c2c;
    color: #fff;
    transform: translateY(-2px);
}

.btn-whatsapp {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    border: none;
    background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);
    color: #fff;
    border-radius: 8px;
    font-size: 1.1rem;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
}

.btn-whatsapp:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4);
}

.btn-whatsapp.disabled {
    background: #ccc;
    cursor: not-allowed;
    pointer-events: none;
}

.btn-whatsapp.disabled:hover {
    transform: none;
    box-shadow: none;
}

/* Toast Notification */
.toast-container {
    position: fixed;
    top: 100px;
    right: 20px;
    z-index: 9999;
}

.toast {
    background: #333;
    color: #fff;
    padding: 15px 25px;
    border-radius: 10px;
    margin-bottom: 10px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    display: flex;
    align-items: center;
    gap: 12px;
    animation: slideIn 0.3s ease;
}

.toast.success {
    background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);
}

.toast.error {
    background: linear-gradient(135deg, #d52c2c 0%, #a01e1e 100%);
}

.toast i {
    font-size: 1.2rem;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOut {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

/* Responsive */
@media (max-width: 768px) {
    .merch-buttons {
        flex-direction: column;
    }
    
    .btn-outline {
        width: 100%;
    }
    
    .btn-whatsapp {
        width: 100%;
    }
}
</style>

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