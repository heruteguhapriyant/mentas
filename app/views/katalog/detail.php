<?php // Katalog Detail Page - Uses existing Mentas design ?>

<section class="hero" style="padding: 120px 60px 60px;">
    <div class="hero-content" style="display: flex; align-items: center; gap: 30px; flex-wrap: wrap; justify-content: center;">
        <?php if ($community['image']): ?>
            <img src="<?= BASE_URL ?>/<?= $community['image'] ?>" alt="<?= htmlspecialchars($community['name']) ?>" style="width: 120px; height: 120px; border-radius: 12px; object-fit: cover; border: 4px solid rgba(255,255,255,0.3);">
        <?php else: ?>
            <div style="width: 120px; height: 120px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-users" style="font-size: 3rem; color: #fff;"></i>
            </div>
        <?php endif; ?>
        
        <div style="text-align: left;">
            <h1 style="font-size: 32px; margin-bottom: 8px;"><?= htmlspecialchars($community['name']) ?></h1>
            <?php if ($community['location']): ?>
                <p style="opacity: 0.9;"><i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($community['location']) ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="program-section" style="flex-direction: column; align-items: center; gap: 30px;">
    
    <!-- Info Card -->
    <div class="program-card" style="width: 100%; max-width: 800px;">
        <h3><i class="fa-solid fa-info-circle" style="color: #d52c2c; margin-right: 8px;"></i> Tentang</h3>
        <p style="line-height: 1.8; margin-top: 15px; color: #555;">
            <?= nl2br(htmlspecialchars($community['description'])) ?>
        </p>
    </div>

    <!-- Contact Card -->
    <?php if ($community['contact'] || $community['website']): ?>
    <div class="program-card" style="width: 100%; max-width: 800px;">
        <h3><i class="fa-solid fa-address-book" style="color: #d52c2c; margin-right: 8px;"></i> Kontak</h3>
        <div style="margin-top: 15px; display: flex; flex-direction: column; gap: 12px;">
            <?php if ($community['contact']): ?>
                <p>
                    <i class="fa-solid fa-phone" style="color: #d52c2c; width: 20px;"></i> 
                    <?= htmlspecialchars($community['contact']) ?>
                </p>
            <?php endif; ?>
            <?php if ($community['website']): ?>
                <p>
                    <i class="fa-solid fa-globe" style="color: #d52c2c; width: 20px;"></i> 
                    <a href="<?= $community['website'] ?>" target="_blank" style="color: #d52c2c;"><?= $community['website'] ?></a>
                </p>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Back Button -->
    <div style="margin-top: 20px;">
        <a href="<?= BASE_URL ?>/katalog" class="btn-outline">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Katalog
        </a>
    </div>
</section>
