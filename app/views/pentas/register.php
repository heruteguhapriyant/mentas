<?php // Pentas Register Page - Blog Layout ?>

<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pentas.css">

<!-- Hero Section (Compact) -->
<section class="hero zine-hero" style="padding-bottom: 40px; min-height: auto;">
    <div class="hero-content">
        <a href="<?= BASE_URL ?>/pentas/detail/<?= !empty($event['slug']) ? $event['slug'] : $event['id'] ?>" class="zine-back-link">
            ← Kembali ke Detail Event
        </a>
        <h1 class="zine-title" style="font-size: 1.8rem;">Form Pendaftaran</h1>
        <p class="zine-excerpt">
            Silakan lengkapi data diri Anda untuk mendaftar event ini.
        </p>
    </div>
</section>

<!-- Content Section -->
<section class="blog-section" style="padding-top: 40px;">
    <div class="container">
        <!-- Menggunakan class layout blog: Kiri Main, Kanan Sidebar -->
        <div class="blog-content-wrapper">
            
            <!-- LEFT: Main Content (Form Pendaftaran) -->
            <div class="blog-main-content">
                <div class="register-header" style="border-bottom: 2px solid #f0f0f0; padding-bottom: 20px; margin-bottom: 30px;">
                    <h2 style="font-size: 1.5rem; color: #333; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-user-edit" style="color: #d52c2c;"></i> Data Diri Peserta
                    </h2>
                </div>

                <form action="<?= BASE_URL ?>/pentas/register/<?= !empty($event['slug']) ? $event['slug'] : $event['id'] ?>" method="POST" enctype="multipart/form-data">
                    
                    <div class="form-group" style="margin-bottom: 25px;">
                        <label for="name" style="display: block; margin-bottom: 10px; font-weight: 600; color: #333;">Nama Lengkap <span style="color: red;">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" required placeholder="Masukkan nama lengkap sesuai KTP" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;">
                    </div>

                    <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
                        <div class="form-group">
                            <label for="email" style="display: block; margin-bottom: 10px; font-weight: 600; color: #333;">Email <span style="color: red;">*</span></label>
                            <input type="email" id="email" name="email" class="form-control" required placeholder="contoh@email.com" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;">
                            <small style="display: block; margin-top: 5px; color: #888; font-size: 0.85rem;">E-tiket akan dikirim ke email ini</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone" style="display: block; margin-bottom: 10px; font-weight: 600; color: #333;">Nomor WhatsApp</label>
                            <input type="text" id="phone" name="phone" class="form-control" placeholder="081234567890" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;">
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 30px;">
                        <label for="quantity" style="display: block; margin-bottom: 10px; font-weight: 600; color: #333;">Jumlah Tiket <span style="color: red;">*</span></label>
                        <select id="quantity" name="quantity" class="form-control" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; background: #fff;">
                            <?php 
                            $maxQty = ($availableTickets > 0 && $availableTickets < 5) ? $availableTickets : 5;
                            for($i=1; $i<=$maxQty; $i++): 
                            ?>
                                <option value="<?= $i ?>"><?= $i ?> Tiket</option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <!-- Payment Info if paid -->
                    <?php if ($event['ticket_price'] > 0): ?>
                        <div class="payment-section" style="background: #fff9f9; padding: 25px; border-radius: 12px; border: 1px solid #ffecec; margin-bottom: 30px;">
                            <h3 style="font-size: 1.1rem; margin-bottom: 20px; color: #d52c2c;">Info Pembayaran</h3>
                            
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
                                <span>Harga per tiket</span>
                                <span style="font-weight: 600;">Rp <?= number_format($event['ticket_price'], 0, ',', '.') ?></span>
                            </div>
                            
                            <div class="total-display" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                                <span style="font-size: 1.1rem; font-weight: 600;">Total Pembayaran</span>
                                <span class="total-amount" style="font-size: 1.5rem; font-weight: 800; color: #d52c2c;">Rp <?= number_format($event['ticket_price'], 0, ',', '.') ?></span>
                            </div>
                            
                            <!-- Bank Info -->
                            <div class="bank-info" style="text-align: center; margin-bottom: 25px;">
                                <p style="margin-bottom: 5px;">Silakan transfer ke rekening:</p>
                                <img src="<?= BASE_URL ?>/assets/images/bca.png" alt="BCA" style="height: 30px; margin-bottom: 10px;">
                                <p style="font-size: 1.2rem; font-weight: 700; letter-spacing: 1px; margin-bottom: 5px;"><?= $paymentSettings['account_number'] ?? '1234567890' ?></p>
                                <p style="color: #666;">a.n <?= $paymentSettings['account_name'] ?? 'Mentas Indonesia' ?></p>
                            </div>
                            
                            <div class="form-group">
                                <label for="payment_proof" style="display: block; margin-bottom: 10px; font-weight: 600; color: #333;">Upload Bukti Transfer <span style="color: red;">*</span></label>
                                <input type="file" id="payment_proof" name="payment_proof" class="form-control" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                                <small style="display: block; margin-top: 5px; color: #888;">Format: JPG, PNG, PDF. Maks 2MB.</small>
                            </div>
                        </div>
                    <?php endif; ?>

                    <button type="submit" class="btn-register" style="font-size: 1.1rem; padding: 18px;">
                        <i class="fas fa-check-circle" style="margin-right: 10px;"></i> Daftar Sekarang
                    </button>

                </form>
            </div>
            
            <!-- RIGHT: Sidebar (Event Summary) -->
            <aside class="blog-sidebar">
                <div class="sidebar-widget">
                    <h3 class="sidebar-title" style="background: linear-gradient(135deg, #d52c2c 0%, #a01c1c 100%); color: #fff; margin: -15px -15px 20px -15px; padding: 15px; border-radius: 10px 10px 0 0;">
                        <i class="fas fa-info-circle"></i> Ringkasan Event
                    </h3>
                    
                    <?php if (!empty($event['cover_image'])): ?>
                        <div class="sidebar-cover" style="margin-bottom: 20px; border-radius: 8px; overflow: hidden; height: 180px;">
                            <img src="<?= BASE_URL ?>/<?= $event['cover_image'] ?>" alt="Event Cover" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    <?php endif; ?>
                    
                    <h4 style="font-size: 1.1rem; margin-bottom: 15px; line-height: 1.4; color: #333;">
                        <?= htmlspecialchars($event['title']) ?>
                    </h4>
                    
                    <ul class="event-details-list">
                        <li>
                            <i class="far fa-calendar-alt"></i>
                            <div>
                                <span class="label">Tanggal</span>
                                <span class="value"><?= date('d F Y', strtotime($event['event_date'])) ?></span>
                            </div>
                        </li>
                        <li>
                            <i class="far fa-clock"></i>
                            <div>
                                <span class="label">Waktu</span>
                                <span class="value"><?= date('H:i', strtotime($event['event_date'])) ?> WIB</span>
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <span class="label">Lokasi</span>
                                <span class="value"><?= htmlspecialchars($event['venue']) ?></span>
                            </div>
                        </li>
                    </ul>
                    
                    <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #f0f0f0; text-align: center;">
                        <span style="display: block; font-size: 0.9rem; color: #888; margin-bottom: 5px;">Harga Tiket</span>
                        <?php if ($event['ticket_price'] == 0): ?>
                            <span style="font-size: 1.5rem; font-weight: 800; color: #28a745;">GRATIS</span>
                        <?php else: ?>
                            <span style="font-size: 1.5rem; font-weight: 800; color: #d52c2c;">Rp <?= number_format($event['ticket_price'], 0, ',', '.') ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </aside>
            
        </div>
    </div>
</section>

<!-- Script untuk update total harga dynamic -->
<?php if ($event['ticket_price'] > 0): ?>
<script>
document.getElementById('quantity').addEventListener('change', function() {
    const price = <?= $event['ticket_price'] ?>;
    const qty = this.value;
    const total = price * qty;
    
    // Format currency
    const formatter = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    });
    
    document.querySelector('.total-amount').textContent = formatter.format(total);
});
</script>
<?php endif; ?>
