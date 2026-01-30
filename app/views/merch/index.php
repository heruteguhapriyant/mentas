<?php // Merch Index Page ?>

<section class="hero zine-hero">
    <div class="hero-content">
        <h1>Merch</h1>
        <p>Koleksi merchandise dan buku dari Mentas.id</p>
    </div>
</section>

<!-- Category Tabs -->
<section class="zine-category-tabs">
    <div class="category-tabs-container">
        <a href="<?= BASE_URL ?>/merch" class="category-tab <?= !isset($_GET['category']) ? 'active' : '' ?>">
            Semua
        </a>
        <a href="<?= BASE_URL ?>/merch?category=merchandise" class="category-tab <?= (isset($_GET['category']) && $_GET['category'] === 'merchandise') ? 'active' : '' ?>">
            <i class="fas fa-tshirt"></i> Merchandise
        </a>
        <a href="<?= BASE_URL ?>/merch?category=buku" class="category-tab <?= (isset($_GET['category']) && $_GET['category'] === 'buku') ? 'active' : '' ?>">
            <i class="fas fa-book"></i> Buku
        </a>
    </div>
</section>

<section class="program-section zine-section">
    <?php if (empty($products)): ?>
        <p class="zine-empty">
            <?php if (isset($_GET['category'])): ?>
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
                        <?php if ($product['stock'] <= 0): ?>
                            <span class="merch-badge sold-out">Habis</span>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="zine-card-cover" style="background: linear-gradient(135deg, #d52c2c 0%, #1a1a2e 100%); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-box" style="font-size: 48px; color: rgba(255,255,255,0.8);"></i>
                        <?php if ($product['stock'] <= 0): ?>
                            <span class="merch-badge sold-out">Habis</span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <div class="zine-card-content">
                    <span class="zine-card-category">
                        <?= $product['category'] === 'buku' ? 'Buku' : 'Merchandise' ?>
                    </span>
                    
                    <h3><?= htmlspecialchars($product['name']) ?></h3>

                    <p class="merch-price">
                        Rp <?= number_format($product['price'], 0, ',', '.') ?>
                    </p>

                    <p class="zine-card-excerpt">
                        <?= htmlspecialchars(substr($product['description'] ?? '', 0, 80)) ?>...
                    </p>

                    <div class="merch-buttons">
                        <a href="<?= BASE_URL ?>/merch/detail/<?= $product['id'] ?>" class="btn-outline">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                        <?php if ($product['stock'] > 0): ?>
                            <button type="button" class="btn-cart" onclick="addToCart(<?= $product['id'] ?>)" title="Masukkan Keranjang">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        <?php else: ?>
                            <button type="button" class="btn-cart disabled" disabled title="Stok Habis">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>

<!-- Toast Notification -->
<div class="toast-container" id="toastContainer"></div>

<style>
.merch-price {
    font-size: 1.2rem;
    font-weight: 700;
    color: #d52c2c;
    margin: 10px 0;
}
.merch-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}
.merch-badge.sold-out {
    background: #dc3545;
    color: #fff;
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
    display: inline-flex;
    align-items: center;
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
}

.btn-cart {
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
}

.btn-cart:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4);
}

.btn-cart.disabled {
    background: #ccc;
    cursor: not-allowed;
}

.btn-cart.disabled:hover {
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
</style>

<script>
function addToCart(productId) {
    fetch('<?= BASE_URL ?>/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'product_id=' + productId + '&quantity=1'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', '<i class="fas fa-check"></i> ' + data.message);
            updateCartBadge(data.cartCount);
        } else {
            showToast('error', '<i class="fas fa-exclamation-circle"></i> ' + data.message);
        }
    })
    .catch(error => {
        showToast('error', '<i class="fas fa-exclamation-circle"></i> Terjadi kesalahan');
    });
}

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

function updateCartBadge(count) {
    const badge = document.querySelector('.cart-badge');
    if (badge) {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'flex' : 'none';
    }
}
</script>
