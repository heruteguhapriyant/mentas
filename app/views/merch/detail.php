<?php // Merch Detail Page ?>

<!-- Hero Banner for Detail Page -->
<section class="hero merch-hero detail-hero">
    <div class="hero-content">
        <h1 class="page-title"><?= htmlspecialchars($product['name']) ?></h1>
        <p class="page-subtitle"><?= ($product['category_slug'] ?? '') === 'buku' ? 'Buku' : 'Merchandise' ?> dari Mentas.id</p>
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
                <div class="product-actions">
                    <?php if ($product['stock'] > 0): ?>
                        <a href="<?= BASE_URL ?>/merch/order/<?= $product['slug'] ?? $product['id'] ?>" class="btn-order">
                            <i class="fab fa-whatsapp"></i> Order via WhatsApp
                        </a>
                    <?php else: ?>
                        <button class="btn-order disabled" disabled>
                            <i class="fas fa-ban"></i> Stok Habis
                        </button>
                    <?php endif; ?>
                </div>

                <div class="order-info">
                    <p><i class="fas fa-info-circle"></i> Klik tombol untuk order langsung via WhatsApp</p>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <?php if (!empty($relatedProducts)): ?>
        <section class="related-products">
            <h2>Produk Lainnya</h2>
            <div class="products-grid-related">
                <?php foreach ($relatedProducts as $related): ?>
                    <a href="<?= BASE_URL ?>/merch/detail/<?= $related['id'] ?>" class="product-card-related">
                        <div class="product-image-related">
                            <?php if (!empty($related['cover_image'])): ?>
                                <img src="<?= BASE_URL ?>/<?= $related['cover_image'] ?>" alt="<?= htmlspecialchars($related['name']) ?>">
                            <?php else: ?>
                                <div class="no-image"><i class="fas fa-image"></i></div>
                            <?php endif; ?>
                        </div>
                        <div class="product-info-related">
                            <h3 class="product-name-related"><?= htmlspecialchars($related['name']) ?></h3>
                            <p class="product-price-related">Rp <?= number_format($related['price'], 0, ',', '.') ?></p>
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