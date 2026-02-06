<?php // Merch Detail Page ?>

<!-- Hero Banner for Detail Page -->
<section class="hero zine-hero detail-hero">
    <div class="hero-content">
        <h1><?= htmlspecialchars($product['name']) ?></h1>
        <p><?= ($product['category_slug'] ?? '') === 'buku' ? 'Buku' : 'Merchandise' ?> dari Mentas.id</p>
    </div>
</section>

<main class="merch-detail-page">
    <div class="container">
        <!-- Breadcrumb -->
        <nav class="breadcrumb">
            <a href="<?= BASE_URL ?>">Home</a>
            <span>/</span>
            <a href="<?= BASE_URL ?>/merch">Merch</a>
            <span>/</span>
            <a href="<?= BASE_URL ?>/merch?category=<?= $product['category_slug'] ?? '' ?>">
                <?= ($product['category_slug'] ?? '') === 'buku' ? 'Buku' : 'Merchandise' ?>
            </a>
            <span>/</span>
            <span class="current"><?= htmlspecialchars($product['name']) ?></span>
        </nav>

        <!-- Product Detail -->
        <div class="product-detail">
            <!-- Product Gallery -->
            <div class="product-gallery">
                <div class="main-image">
                    <?php if (!empty($product['cover_image'])): ?>
                        <img src="<?= BASE_URL ?>/<?= $product['cover_image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>" id="mainImage">
                    <?php else: ?>
                        <div class="no-image">
                            <i class="fas fa-image"></i>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if (!empty($images)): ?>
                <div class="thumbnail-list">
                    <div class="thumbnail active" onclick="changeImage('<?= BASE_URL ?>/<?= $product['cover_image'] ?>')">
                        <img src="<?= BASE_URL ?>/<?= $product['cover_image'] ?>" alt="Thumbnail">
                    </div>
                    <?php foreach ($images as $img): ?>
                        <div class="thumbnail" onclick="changeImage('<?= BASE_URL ?>/<?= $img ?>')">
                            <img src="<?= BASE_URL ?>/<?= $img ?>" alt="Thumbnail">
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Product Info -->
            <div class="product-info-detail">
                <span class="product-category">
                    <?= ($product['category_slug'] ?? '') === 'buku' ? 'Buku' : 'Merchandise' ?>
                </span>
                
                <h1 class="product-title"><?= htmlspecialchars($product['name']) ?></h1>
                
                <div class="product-price-box">
                    <span class="price-label">Harga</span>
                    <span class="price-value">Rp <?= number_format($product['price'], 0, ',', '.') ?></span>
                </div>

                <div class="product-stock">
                    <?php if ($product['stock'] > 0): ?>
                        <span class="stock-available">
                            <i class="fas fa-check-circle"></i> Stok tersedia (<?= $product['stock'] ?> pcs)
                        </span>
                    <?php else: ?>
                        <span class="stock-empty">
                            <i class="fas fa-times-circle"></i> Stok habis
                        </span>
                    <?php endif; ?>
                </div>

                <div class="product-description">
                    <h3>Deskripsi</h3>
                    <div class="description-content">
                        <?= nl2br(htmlspecialchars($product['description'] ?? 'Tidak ada deskripsi.')) ?>
                    </div>
                </div>

                <!-- Action Buttons -->
                <!-- <div class="product-actions">
                    <?php if ($product['stock'] > 0): ?>
                        <div class="qty-selector">
                            <label>Jumlah:</label>
                            <div class="qty-control">
                                <button type="button" class="qty-btn" onclick="changeQty(-1)">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" id="qty" value="1" min="1" max="<?= $product['stock'] ?>">
                                <button type="button" class="qty-btn" onclick="changeQty(1)">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        
                        <button type="button" class="btn-add-cart" onclick="addToCart(<?= $product['id'] ?>)">
                            <i class="fas fa-cart-plus"></i> Masukkan Keranjang
                        </button>
                        
                        <a href="<?= BASE_URL ?>/cart" class="btn-view-cart">
                            <i class="fas fa-shopping-cart"></i> Lihat Keranjang
                        </a>
                    <?php else: ?>
                        <button class="btn-add-cart disabled" disabled>
                            <i class="fas fa-ban"></i> Stok Habis
                        </button>
                    <?php endif; ?>
                </div> -->

                <div class="order-info">
                    <p><i class="fas fa-info-circle"></i> Tambahkan ke keranjang lalu checkout via WhatsApp</p>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <?php if (!empty($relatedProducts)): ?>
        <section class="related-products">
            <h2>Produk Lainnya</h2>
            <div class="products-grid">
                <?php foreach ($relatedProducts as $related): ?>
                    <a href="<?= BASE_URL ?>/merch/detail/<?= $related['id'] ?>" class="product-card">
                        <div class="product-image">
                            <?php if (!empty($related['cover_image'])): ?>
                                <img src="<?= BASE_URL ?>/<?= $related['cover_image'] ?>" alt="<?= htmlspecialchars($related['name']) ?>">
                            <?php else: ?>
                                <div class="no-image"><i class="fas fa-image"></i></div>
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <h3 class="product-name"><?= htmlspecialchars($related['name']) ?></h3>
                            <p class="product-price">Rp <?= number_format($related['price'], 0, ',', '.') ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
    </div>
</main>

<!-- Toast Notification -->
<div class="toast-container" id="toastContainer"></div>

<script>
function changeImage(src) {
    document.getElementById('mainImage').src = src;
    document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
    event.currentTarget.classList.add('active');
}

function changeQty(delta) {
    const input = document.getElementById('qty');
    const max = parseInt(input.getAttribute('max'));
    let val = parseInt(input.value) + delta;
    if (val < 1) val = 1;
    if (val > max) val = max;
    input.value = val;
}

function addToCart(productId) {
    const qty = document.getElementById('qty').value;
    
    fetch('<?= BASE_URL ?>/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'product_id=' + productId + '&quantity=' + qty
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

<style>
/* Merch Detail Page Styles */
.merch-detail-page {
    padding: 30px 0 60px;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.breadcrumb {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 30px;
    font-size: 0.9rem;
}

.breadcrumb a {
    color: #888;
    text-decoration: none;
}

.breadcrumb a:hover {
    color: #d52c2c;
}

.breadcrumb span {
    color: #ccc;
}

.breadcrumb .current {
    color: #333;
    font-weight: 500;
}

.product-detail {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 50px;
    margin-bottom: 60px;
}

.product-gallery .main-image {
    width: 100%;
    border-radius: 16px;
    overflow: hidden;
    background: #f8f9fa;
}

.product-gallery .main-image img {
    width: 100%;
    height: auto;
    display: block;
}

.product-gallery .no-image {
    width: 100%;
    height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    color: #ccc;
    font-size: 4rem;
}

.thumbnail-list {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}

.thumbnail {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    overflow: hidden;
    cursor: pointer;
    border: 2px solid transparent;
    transition: all 0.2s;
}

.thumbnail.active,
.thumbnail:hover {
    border-color: #d52c2c;
}

.thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-info-detail {
    padding-top: 20px;
}

.product-category {
    display: inline-block;
    background: #f8f9fa;
    color: #666;
    padding: 6px 15px;
    border-radius: 20px;
    font-size: 0.85rem;
    margin-bottom: 15px;
}

.product-title {
    font-size: 2rem;
    color: #333;
    margin-bottom: 20px;
    line-height: 1.3;
}

.product-price-box {
    margin-bottom: 20px;
}

.product-price-box .price-label {
    display: block;
    color: #888;
    font-size: 0.9rem;
    margin-bottom: 5px;
}

.product-price-box .price-value {
    font-size: 2rem;
    font-weight: 800;
    color: #d52c2c;
}

.product-stock {
    margin-bottom: 25px;
}

.stock-available {
    color: #28a745;
    font-weight: 500;
}

.stock-empty {
    color: #dc3545;
    font-weight: 500;
}

.product-description {
    margin-bottom: 30px;
}

.product-description h3 {
    font-size: 1.1rem;
    color: #333;
    margin-bottom: 10px;
}

.description-content {
    color: #555;
    line-height: 1.8;
}

/* Product Actions */
.product-actions {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.qty-selector {
    display: flex;
    align-items: center;
    gap: 15px;
}

.qty-selector label {
    color: #666;
    font-weight: 500;
}

.qty-control {
    display: flex;
    align-items: center;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
}

.qty-btn {
    width: 40px;
    height: 40px;
    border: none;
    background: #f8f9fa;
    cursor: pointer;
    transition: all 0.2s;
}

.qty-btn:hover {
    background: #e9ecef;
}

#qty {
    width: 60px;
    height: 40px;
    border: none;
    text-align: center;
    font-weight: 600;
    font-size: 1rem;
}

#qty::-webkit-inner-spin-button,
#qty::-webkit-outer-spin-button {
    -webkit-appearance: none;
}

.btn-add-cart {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    padding: 16px 30px;
    background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);
    color: #fff;
    text-decoration: none;
    border-radius: 10px;
    font-size: 1.1rem;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-add-cart:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(37, 211, 102, 0.3);
}

.btn-add-cart.disabled {
    background: #ccc;
    cursor: not-allowed;
}

.btn-view-cart {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    padding: 14px 30px;
    background: #f8f9fa;
    color: #333;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-view-cart:hover {
    background: #e9ecef;
}

.order-info {
    margin-top: 15px;
    text-align: center;
    color: #888;
    font-size: 0.9rem;
}

/* Related Products */
.related-products {
    margin-top: 60px;
}

.related-products h2 {
    font-size: 1.5rem;
    margin-bottom: 25px;
    color: #333;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 25px;
}

.product-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    text-decoration: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    transition: all 0.3s;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.product-card .product-image {
    height: 180px;
    background: #f8f9fa;
}

.product-card .product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-card .product-info {
    padding: 15px;
}

.product-card .product-name {
    font-size: 1rem;
    color: #333;
    margin-bottom: 8px;
}

.product-card .product-price {
    color: #d52c2c;
    font-weight: 700;
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

@keyframes slideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

@keyframes slideOut {
    from { transform: translateX(0); opacity: 1; }
    to { transform: translateX(100%); opacity: 0; }
}

@media (max-width: 768px) {
    .product-detail {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    
    .product-title {
        font-size: 1.5rem;
    }
    
    .product-price-box .price-value {
        font-size: 1.5rem;
    }
}
</style>
