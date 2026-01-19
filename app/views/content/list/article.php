<?php // Blog List Page - Uses existing Mentas design ?>

<section class="hero" style="padding: 120px 60px 60px;">
    <div class="hero-content">
        <h1><?= $type['name'] ?? 'Blog'; ?></h1>
        <?php if (!empty($categoryName)): ?>
            <p>Kategori: <?= $categoryName; ?></p>
        <?php else: ?>
            <p>Artikel, esai, dan berita seputar seni & budaya</p>
        <?php endif; ?>
    </div>
</section>

<section class="program-section" style="flex-direction: column; align-items: center;">
    <?php require __DIR__ . '/../components/filter.php'; ?>

    <div class="blog-grid" style="max-width: 1200px; width: 100%; margin-top: 2rem;">
        <?php if (empty($contents)): ?>
            <p style="text-align: center; grid-column: 1/-1; padding: 3rem; color: #666;">Belum ada tulisan di kategori ini.</p>
        <?php else: ?>
            <?php foreach ($contents as $item): ?>
                <article class="program-card" style="width: 100%; box-shadow: 3px 3px 0 #000;">
                    <?php if (!empty($item['cover_image'])): ?>
                        <img src="<?= BASE_URL ?>/<?= $item['cover_image']; ?>" alt="<?= htmlspecialchars($item['title']); ?>" style="width: 100%; height: 180px; object-fit: cover; border-radius: 8px 8px 0 0;">
                    <?php endif; ?>
                    
                    <?php if (!empty($item['category_name'])): ?>
                        <span style="display: inline-block; font-size: 12px; color: #d52c2c; font-weight: 600; margin-bottom: 8px;">
                            <?= $item['category_name']; ?>
                        </span>
                    <?php endif; ?>

                    <h3 style="margin-bottom: 10px;">
                        <a href="<?= BASE_URL ?>/blog/<?= $item['slug']; ?>" style="color: inherit; text-decoration: none;">
                            <?= htmlspecialchars($item['title']); ?>
                        </a>
                    </h3>

                    <p style="font-size: 14px; color: #555; line-height: 1.5; margin-bottom: 12px;">
                        <?= htmlspecialchars(substr($item['excerpt'] ?? '', 0, 120)); ?>...
                    </p>

                    <div style="font-size: 12px; color: #999;">
                        <?php if (!empty($item['author_name'])): ?>
                            ✍ <?= $item['author_name']; ?>
                        <?php endif; ?>
                        <?php if (!empty($item['published_at'])): ?>
                            • 📅 <?= date('d M Y', strtotime($item['published_at'])); ?>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
