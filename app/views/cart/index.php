<?php // Shopping Cart Page ?>

<section class="hero zine-hero">
    <div class="hero-content">
        <h1><i class="fas fa-shopping-cart"></i> Keranjang Belanja</h1>
        <p>Review pesanan Anda sebelum checkout</p>
    </div>
</section>

<section class="cart-section">
    <div class="container">
        <?php if (empty($cartItems)): ?>
            <div class="cart-empty">
                <i class="fas fa-shopping-cart"></i>
                <h2>Keranjang Anda Kosong</h2>
                <p>Yuk, mulai belanja dan temukan produk menarik!</p>
                <a href="<?= BASE_URL ?>/merch" class="btn-primary">
                    <i class="fas fa-store"></i> Lihat Produk
                </a>
            </div>
        <?php else: ?>
            <div class="cart-container">
                <div class="cart-items">
                    <div class="cart-header">
                        <span class="col-product">Produk</span>
                        <span class="col-price">Harga</span>
                        <span class="col-qty">Jumlah</span>
                        <span class="col-subtotal">Subtotal</span>
                        <span class="col-action"></span>
                    </div>
                    
                    <?php foreach ($cartItems as $item): ?>
                        <div class="cart-item" data-product-id="<?= $item['product']['id'] ?>">
                            <div class="col-product">
                                <div class="product-image">
                                    <?php if (!empty($item['product']['cover_image'])): ?>
                                        <img src="<?= BASE_URL ?>/<?= $item['product']['cover_image'] ?>" alt="<?= htmlspecialchars($item['product']['name']) ?>">
                                    <?php else: ?>
                                        <div class="no-image"><i class="fas fa-box"></i></div>
                                    <?php endif; ?>
                                </div>
                                <div class="product-info">
                                    <h3><?= htmlspecialchars($item['product']['name']) ?></h3>
                                    <span class="product-category"><?= $item['product']['category'] === 'buku' ? 'Buku' : 'Merchandise' ?></span>
                                </div>
                            </div>
                            <div class="col-price">
                                Rp <?= number_format($item['product']['price'], 0, ',', '.') ?>
                            </div>
                            <div class="col-qty">
                                <div class="qty-control">
                                    <button type="button" class="qty-btn minus" data-action="decrease">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" class="qty-input" value="<?= $item['quantity'] ?>" min="1" max="<?= $item['product']['stock'] ?>">
                                    <button type="button" class="qty-btn plus" data-action="increase">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <small class="stock-info">Stok: <?= $item['product']['stock'] ?></small>
                            </div>
                            <div class="col-subtotal">
                                Rp <span class="subtotal-value"><?= number_format($item['subtotal'], 0, ',', '.') ?></span>
                            </div>
                            <div class="col-action">
                                <button type="button" class="btn-remove" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="cart-summary">
                    <h3>Ringkasan Pesanan</h3>
                    <div class="summary-row">
                        <span>Total Item</span>
                        <span class="total-items"><?= array_sum(array_column($cartItems, 'quantity')) ?> produk</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span class="total-price">Rp <?= number_format($total, 0, ',', '.') ?></span>
                    </div>
                    
                    <a href="<?= BASE_URL ?>/cart/checkout" class="btn-checkout">
                        <i class="fab fa-whatsapp"></i> Checkout via WhatsApp
                    </a>
                    
                    <a href="<?= BASE_URL ?>/merch" class="btn-continue">
                        <i class="fas fa-arrow-left"></i> Lanjut Belanja
                    </a>
                    
                    <button type="button" class="btn-clear" onclick="clearCart()">
                        <i class="fas fa-trash-alt"></i> Kosongkan Keranjang
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.cart-section {
    padding: 40px 0 80px;
    min-height: 60vh;
}

.cart-section .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Empty Cart */
.cart-empty {
    text-align: center;
    padding: 80px 20px;
}

.cart-empty i {
    font-size: 5rem;
    color: #ddd;
    margin-bottom: 20px;
}

.cart-empty h2 {
    color: #333;
    margin-bottom: 10px;
}

.cart-empty p {
    color: #888;
    margin-bottom: 30px;
}

.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: linear-gradient(135deg, #d52c2c 0%, #a01e1e 100%);
    color: #fff;
    padding: 14px 30px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(213, 44, 44, 0.3);
}

/* Cart Container */
.cart-container {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 30px;
    align-items: start;
}

/* Cart Items */
.cart-items {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 20px rgba(0,0,0,0.05);
    overflow: hidden;
}

.cart-header {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr 50px;
    gap: 15px;
    padding: 20px;
    background: #f8f9fa;
    font-weight: 600;
    color: #555;
    font-size: 0.9rem;
}

.cart-item {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr 50px;
    gap: 15px;
    padding: 20px;
    border-bottom: 1px solid #eee;
    align-items: center;
}

.cart-item:last-child {
    border-bottom: none;
}

.col-product {
    display: flex;
    gap: 15px;
    align-items: center;
}

.product-image {
    width: 80px;
    height: 80px;
    border-radius: 10px;
    overflow: hidden;
    background: #f8f9fa;
    flex-shrink: 0;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-image .no-image {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ccc;
    font-size: 1.5rem;
}

.product-info h3 {
    font-size: 1rem;
    color: #333;
    margin-bottom: 5px;
}

.product-category {
    font-size: 0.8rem;
    color: #888;
    background: #f0f0f0;
    padding: 3px 10px;
    border-radius: 10px;
}

.col-price, .col-subtotal {
    font-weight: 600;
    color: #333;
}

.col-subtotal {
    color: #d52c2c;
}

/* Quantity Control */
.qty-control {
    display: flex;
    align-items: center;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    width: fit-content;
}

.qty-btn {
    width: 36px;
    height: 36px;
    border: none;
    background: #f8f9fa;
    cursor: pointer;
    transition: all 0.2s;
}

.qty-btn:hover {
    background: #e9ecef;
}

.qty-input {
    width: 50px;
    height: 36px;
    border: none;
    text-align: center;
    font-weight: 600;
    font-size: 1rem;
}

.qty-input::-webkit-inner-spin-button,
.qty-input::-webkit-outer-spin-button {
    -webkit-appearance: none;
}

.stock-info {
    display: block;
    margin-top: 5px;
    color: #888;
    font-size: 0.75rem;
}

.btn-remove {
    width: 40px;
    height: 40px;
    border: none;
    background: #fee;
    color: #d52c2c;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-remove:hover {
    background: #d52c2c;
    color: #fff;
}

/* Cart Summary */
.cart-summary {
    background: #fff;
    border-radius: 16px;
    padding: 25px;
    box-shadow: 0 2px 20px rgba(0,0,0,0.05);
    position: sticky;
    top: 100px;
}

.cart-summary h3 {
    font-size: 1.2rem;
    color: #333;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    color: #666;
}

.summary-row.total {
    border-top: 2px solid #eee;
    margin-top: 10px;
    padding-top: 15px;
    font-size: 1.2rem;
    font-weight: 700;
    color: #333;
}

.total-price {
    color: #d52c2c;
}

.btn-checkout {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    padding: 16px;
    background: #25d366;
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    margin-top: 20px;
    text-decoration: none;
    transition: all 0.3s;
}

.btn-checkout:hover {
    background: #128c7e;
    transform: translateY(-2px);
}

.btn-continue {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    padding: 12px;
    background: #f8f9fa;
    color: #555;
    border: none;
    border-radius: 10px;
    font-weight: 500;
    cursor: pointer;
    margin-top: 10px;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-continue:hover {
    background: #e9ecef;
}

.btn-clear {
    display: block;
    width: 100%;
    padding: 10px;
    background: none;
    color: #999;
    border: none;
    font-size: 0.85rem;
    cursor: pointer;
    margin-top: 15px;
    transition: all 0.2s;
}

.btn-clear:hover {
    color: #d52c2c;
}

/* Responsive */
@media (max-width: 992px) {
    .cart-container {
        grid-template-columns: 1fr;
    }
    
    .cart-summary {
        position: static;
    }
}

@media (max-width: 768px) {
    .cart-header {
        display: none;
    }
    
    .cart-item {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .col-product {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .product-image {
        width: 100%;
        height: 150px;
    }
    
    .col-price::before { content: "Harga: "; color: #888; }
    .col-subtotal::before { content: "Subtotal: "; color: #888; }
    
    .col-action {
        position: absolute;
        top: 15px;
        right: 15px;
    }
    
    .cart-item {
        position: relative;
    }
}
</style>

<script>
// Update quantity
document.querySelectorAll('.qty-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const item = this.closest('.cart-item');
        const productId = item.dataset.productId;
        const input = item.querySelector('.qty-input');
        const maxStock = parseInt(input.getAttribute('max'));
        let qty = parseInt(input.value);
        
        if (this.dataset.action === 'increase' && qty < maxStock) {
            qty++;
        } else if (this.dataset.action === 'decrease' && qty > 1) {
            qty--;
        }
        
        input.value = qty;
        updateCart(productId, qty);
    });
});

// Direct input change
document.querySelectorAll('.qty-input').forEach(input => {
    input.addEventListener('change', function() {
        const item = this.closest('.cart-item');
        const productId = item.dataset.productId;
        const maxStock = parseInt(this.getAttribute('max'));
        let qty = parseInt(this.value);
        
        if (qty < 1) qty = 1;
        if (qty > maxStock) qty = maxStock;
        
        this.value = qty;
        updateCart(productId, qty);
    });
});

// Remove item
document.querySelectorAll('.btn-remove').forEach(btn => {
    btn.addEventListener('click', function() {
        const item = this.closest('.cart-item');
        const productId = item.dataset.productId;
        
        if (confirm('Hapus produk ini dari keranjang?')) {
            fetch('<?= BASE_URL ?>/cart/remove', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'product_id=' + productId
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    item.remove();
                    updateCartBadge(data.cartCount);
                    updateTotalDisplay(data.total);
                    
                    // Check if cart is empty
                    if (data.cartCount === 0) {
                        location.reload();
                    }
                }
            });
        }
    });
});

function updateCart(productId, quantity) {
    fetch('<?= BASE_URL ?>/cart/update', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'product_id=' + productId + '&quantity=' + quantity
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            updateCartBadge(data.cartCount);
            updateTotalDisplay(data.total);
            
            // Update subtotal for this item
            const item = document.querySelector(`.cart-item[data-product-id="${productId}"]`);
            if (item) {
                const price = parseFloat(item.querySelector('.col-price').textContent.replace(/[^\d]/g, ''));
                const subtotal = price * quantity;
                item.querySelector('.subtotal-value').textContent = formatPrice(subtotal);
            }
        }
    });
}

function updateCartBadge(count) {
    const badge = document.querySelector('.cart-badge');
    if (badge) {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'flex' : 'none';
    }
}

function updateTotalDisplay(total) {
    const totalPrice = document.querySelector('.total-price');
    if (totalPrice) {
        totalPrice.textContent = 'Rp ' + formatPrice(total);
    }
}

function formatPrice(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function clearCart() {
    if (confirm('Kosongkan semua item di keranjang?')) {
        fetch('<?= BASE_URL ?>/cart/clear', {
            method: 'POST'
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}
</script>
