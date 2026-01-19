<?php // Katalog Page - Uses existing Mentas design ?>

<section class="hero" style="padding: 120px 60px 60px;">
    <div class="hero-content">
        <h1>Index Komunitas Seni & Budaya</h1>
        <p>Direktori komunitas seni dan budaya di Indonesia</p>
    </div>
</section>

<section class="program-section" style="flex-direction: row; flex-wrap: wrap; justify-content: center;">
    <?php if (empty($communities)): ?>
        <p style="text-align: center; width: 100%; padding: 3rem; color: #666;">Belum ada data komunitas.</p>
    <?php else: ?>
        <?php foreach ($communities as $community): ?>
            <div class="program-card">
                <?php if (!$community['image']): ?>
                    <div style="width: 100%; height: 120px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                        <i class="fa-solid fa-users" style="font-size: 2.5rem; color: #fff;"></i>
                    </div>
                <?php else: ?>
                    <img src="<?= BASE_URL ?>/<?= $community['image'] ?>" alt="<?= htmlspecialchars($community['name']) ?>" style="width: 100%; height: 120px; object-fit: cover; border-radius: 8px; margin-bottom: 15px;">
                <?php endif; ?>
                
                <h3><?= htmlspecialchars($community['name']) ?></h3>
                
                <?php if ($community['location']): ?>
                    <p style="color: #d52c2c; font-size: 13px; margin-bottom: 8px;">
                        <i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($community['location']) ?>
                    </p>
                <?php endif; ?>
                
                <?php if ($community['description']): ?>
                    <p style="font-size: 14px; color: #555; margin-bottom: 15px;">
                        <?= substr($community['description'], 0, 80) ?>...
                    </p>
                <?php endif; ?>
                
                <a href="<?= BASE_URL ?>/katalog/detail/<?= $community['slug'] ?>" class="btn-outline">Lihat Detail</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
