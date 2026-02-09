<?php // Ekosistem Detail Page ?>

<section class="hero ekosistem-hero-detail">
    <div class="hero-content">
        <a href="<?= BASE_URL ?>/ekosistem" class="ekosistem-back-link">
            ← Kembali ke Ekosistem
        </a>
        
        <span class="ekosistem-detail-tagline" style="background: <?= $item['color'] ?>;"><?= htmlspecialchars($item['tagline']) ?></span>
        
        <h1 class="ekosistem-detail-title">
            <?= htmlspecialchars($item['name']) ?>
        </h1>
    </div>
</section>

<section class="ekosistem-detail-section">
    <article class="ekosistem-article">
        
        <?php if (!empty($item['image']) && file_exists('../public/' . $item['image'])): ?>
            <img 
                src="<?= BASE_URL ?>/<?= $item['image'] ?>" 
                alt="<?= htmlspecialchars($item['name']) ?>" 
                class="ekosistem-detail-image"
            >
        <?php else: ?>
            <div class="ekosistem-detail-placeholder" style="background: linear-gradient(135deg, <?= $item['color'] ?> 0%, #1a1a2e 100%);">
                <i class="fa-solid fa-users"></i>
                <span><?= htmlspecialchars($item['name']) ?></span>
            </div>
        <?php endif; ?>

        <div class="ekosistem-detail-body">
            <p class="ekosistem-description">
                <?= htmlspecialchars($item['description']) ?>
            </p>
            
            <?php if (!empty($item['instagram'])): ?>
                <div class="ekosistem-social-links">
                    <h4>Ikuti Kami</h4>
                    <a href="<?= $item['instagram'] ?>" target="_blank" class="ekosistem-instagram-btn">
                        <i class="fab fa-instagram"></i> Instagram
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <div class="ekosistem-footer">
            <a href="<?= BASE_URL ?>/ekosistem" class="btn-outline">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>

    </article>
    
    <!-- Sidebar - Other Ekosistem -->
    <aside class="ekosistem-others">
        <h3>Ekosistem Lainnya</h3>
        <div class="ekosistem-others-list">
            <?php foreach ($allEkosistem as $other): ?>
                <?php if ($other['slug'] !== $item['slug']): ?>
                    <a href="<?= BASE_URL ?>/ekosistem/detail/<?= $other['slug'] ?>" class="ekosistem-other-item">
                        <div class="ekosistem-other-dot" style="background: <?= $other['color'] ?>;"></div>
                        <div>
                            <div style="font-weight: 500;"><?= htmlspecialchars($other['name']) ?></div>
                            <small style="color: #666; font-size: 12px;"><?= htmlspecialchars($other['tagline']) ?></small>
                        </div>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </aside>
</section>