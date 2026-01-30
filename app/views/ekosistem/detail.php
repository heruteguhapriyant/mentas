<?php // Ekosistem Detail Page ?>

<section class="hero zine-hero">
    <div class="hero-content">
        <a href="<?= BASE_URL ?>/ekosistem" class="zine-back-link">
            ← Kembali ke Ekosistem
        </a>
        
        <span class="zine-detail-category" style="background: <?= $item['color'] ?>;"><?= htmlspecialchars($item['tagline']) ?></span>
        
        <h1 class="zine-title">
            <?= htmlspecialchars($item['name']) ?>
        </h1>
    </div>
</section>

<section class="program-section zine-section-detail">
    <article class="zine-article">
        
        <?php if (!empty($item['image']) && file_exists('../public/' . $item['image'])): ?>
            <img 
                src="<?= BASE_URL ?>/<?= $item['image'] ?>" 
                alt="<?= htmlspecialchars($item['name']) ?>" 
                class="zine-cover"
            >
        <?php else: ?>
            <div class="zine-cover" style="background: linear-gradient(135deg, <?= $item['color'] ?> 0%, #1a1a2e 100%); display: flex; align-items: center; justify-content: center; height: 300px;">
                <div style="text-align: center; color: #fff;">
                    <i class="fa-solid fa-users" style="font-size: 64px; margin-bottom: 15px; display: block;"></i>
                    <span style="font-size: 24px; font-weight: 600;"><?= htmlspecialchars($item['name']) ?></span>
                </div>
            </div>
        <?php endif; ?>

        <div class="zine-content-body">
            <p style="font-size: 16px; line-height: 1.8; color: #333; margin-bottom: 30px;">
                <?= htmlspecialchars($item['description']) ?>
            </p>
            
            <?php if (!empty($item['instagram'])): ?>
                <div style="padding-top: 20px; border-top: 1px solid #eee;">
                    <h4 style="margin-bottom: 15px; font-size: 16px;">Ikuti Kami</h4>
                    <a href="<?= $item['instagram'] ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); color: #fff; text-decoration: none; border-radius: 25px; font-weight: 500;">
                        <i class="fab fa-instagram"></i> Instagram
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <div class="zine-footer">
            <a href="<?= BASE_URL ?>/ekosistem" class="btn-outline">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>

    </article>
    
    <!-- Sidebar - Other Ekosistem -->
    <aside class="blog-sidebar">
        <div class="sidebar-widget">
            <h3 class="sidebar-title">Ekosistem Lainnya</h3>
            <ul class="sidebar-posts">
                <?php foreach ($allEkosistem as $other): ?>
                    <?php if ($other['slug'] !== $item['slug']): ?>
                        <li class="sidebar-post-item">
                            <div style="width: 12px; height: 12px; border-radius: 50%; background: <?= $other['color'] ?>; flex-shrink: 0;"></div>
                            <div class="sidebar-post-info">
                                <a href="<?= BASE_URL ?>/ekosistem/detail/<?= $other['slug'] ?>" class="sidebar-post-title">
                                    <?= htmlspecialchars($other['name']) ?>
                                </a>
                                <span class="sidebar-post-date"><?= htmlspecialchars($other['tagline']) ?></span>
                            </div>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </aside>
</section>
