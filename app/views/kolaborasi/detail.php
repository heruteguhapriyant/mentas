<?php 
// Kolaborasi Detail Page 
// Color palette for UI elements since collaboration table doesn't have color field
$colors = ['#1abc9c', '#e67e22', '#9b59b6', '#3498db', '#e74c3c', '#2ecc71', '#f1c40f'];
$colorConfig = $colors[($item['id'] ?? 0) % count($colors)];

// Parse social media
$socials = [];
if (!empty($item['social_media'])) {
    $socials = is_array($item['social_media']) ? $item['social_media'] : (json_decode($item['social_media'], true) ?? []);
}
?>

<section class="hero ekosistem-hero-detail">
    <div class="hero-content">
        <a href="<?= BASE_URL ?>/kolaborasi" class="ekosistem-back-link">
            ← Kembali ke Kolaborasi
        </a>
        
        <span class="ekosistem-detail-tagline" style="background: <?= $colorConfig ?>;">KOLABORASI</span>
        
        <h1 class="ekosistem-detail-title">
            <?= htmlspecialchars($item['title']) ?>
        </h1>
    </div>
</section>

<section class="ekosistem-detail-section">
    <article class="ekosistem-article">
        
        <?php if (!empty($item['cover_image']) && file_exists('public/' . $item['cover_image'])): ?>
            <img 
                src="<?= BASE_URL ?>/<?= $item['cover_image'] ?>" 
                alt="<?= htmlspecialchars($item['title']) ?>" 
                class="ekosistem-detail-image"
            >
        <?php else: ?>
            <div class="ekosistem-detail-placeholder" style="background: linear-gradient(135deg, <?= $colorConfig ?> 0%, #1a1a2e 100%);">
                <i class="fa-solid fa-users"></i>
                <span><?= htmlspecialchars($item['title']) ?></span>
            </div>
        <?php endif; ?>

        <div class="ekosistem-detail-body">
            <p class="ekosistem-description">
                <?= nl2br(htmlspecialchars($item['description'] ?? '')) ?>
            </p>
            
            <!-- Contributors Section (Adapted) -->
            <?php if (!empty($item['contributors'])): ?>
            <div class="contributors-section" style="margin-bottom: 30px; padding-top: 20px; border-top: 1px solid #eee;">
                <h4 style="margin-bottom: 15px; font-size: 16px; color: #1a1a2e;">Kontributor</h4>
                <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                    <?php foreach ($item['contributors'] as $contributor): ?>
                        <a href="<?= BASE_URL ?>/author/<?= $contributor['id'] ?>" 
                           style="background:#f0f2f5; color:#1a1a2e; padding:6px 14px; border-radius:20px; font-size:14px; text-decoration:none; font-weight: 500; transition: background 0.2s; border: 1px solid #e1e4e8;">
                            <i class="fas fa-user-circle" style="color: #666; margin-right: 4px;"></i> <?= htmlspecialchars($contributor['name']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php elseif (!empty($item['contributor_names'])): ?>
            <div class="contributors-section" style="margin-bottom: 30px; padding-top: 20px; border-top: 1px solid #eee;">
                 <h4 style="margin-bottom: 15px; font-size: 16px; color: #1a1a2e;">Kontributor</h4>
                 <span style="color: #555;"><?= htmlspecialchars($item['contributor_names']) ?></span>
            </div>
            <?php endif; ?>

            <?php if (!empty($socials['instagram'])): 
                $igLink = $socials['instagram'];
                // Check if it's a URL
                if (!preg_match("~^(?:f|ht)tps?://~i", $igLink)) {
                    // Remove @ if present
                    $igUsername = ltrim($igLink, '@');
                    $igLink = "https://instagram.com/" . $igUsername;
                }
            ?>
                <div class="ekosistem-social-links">
                    <h4>Ikuti Kami</h4>
                    <a href="<?= $igLink ?>" target="_blank" class="ekosistem-instagram-btn">
                        <i class="fab fa-instagram"></i> Instagram
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <div class="ekosistem-footer">
            <a href="<?= BASE_URL ?>/kolaborasi" class="btn-outline">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>

    </article>
    
    <!-- Sidebar - Other Kolaborasi -->
    <aside class="ekosistem-others">
        <h3>Kolaborasi Lainnya</h3>
        <div class="ekosistem-others-list">
            <?php 
            $i = 0;
            foreach ($allKolaborasi as $other): 
                if ($other['slug'] === $item['slug']) continue;
                $otherColor = $colors[($other['id'] ?? $i) % count($colors)];
                $i++;
            ?>
                <a href="<?= BASE_URL ?>/kolaborasi/detail/<?= $other['slug'] ?>" class="ekosistem-other-item">
                    <div class="ekosistem-other-dot" style="background: <?= $otherColor ?>;"></div>
                    <div>
                        <div style="font-weight: 500;"><?= htmlspecialchars($other['title']) ?></div>
                        <small style="color: #666; font-size: 12px;">Kolaborasi</small>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </aside>
</section>
