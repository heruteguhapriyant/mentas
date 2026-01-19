<?php // Coming Soon Page - Uses existing Mentas design ?>

<section class="hero" style="padding: 120px 60px 60px;">
    <div class="hero-content">
        <h1><?= htmlspecialchars($title) ?></h1>
        <p>Coming Soon</p>
    </div>
</section>

<section class="program-section" style="flex-direction: column; align-items: center;">
    <div class="program-card" style="width: 100%; max-width: 600px; text-align: center;">
        <div style="font-size: 4rem; color: #d52c2c; margin-bottom: 20px;">
            <i class="fa-solid fa-rocket"></i>
        </div>
        <h3>Fitur ini sedang dalam pengembangan</h3>
        <p style="margin: 15px 0;">
            <?php if ($page === 'jual-beli'): ?>
                Marketplace untuk buku dan merchandise seni & budaya akan segera hadir.
            <?php elseif ($page === 'event'): ?>
                Platform event & ticketing untuk acara seni & budaya akan segera hadir.
            <?php endif; ?>
        </p>
        <a href="<?= BASE_URL ?>" class="btn-outline">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Home
        </a>
    </div>
</section>
