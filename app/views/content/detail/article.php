<?php // Article Detail Page - Uses existing Mentas design ?>

<section class="hero" style="padding: 120px 60px 40px;">
    <div class="hero-content">
        <?php if (!empty($content['category_name'])): ?>
            <span class="btn-orange" style="font-size: 12px; padding: 6px 14px; margin-bottom: 15px; display: inline-block;"><?= $content['category_name']; ?></span>
        <?php endif; ?>
        
        <h1 style="font-size: 36px;"><?= htmlspecialchars($content['title']); ?></h1>
        
        <p style="font-size: 14px; margin-top: 15px;">
            <?php if (!empty($content['author_name'])): ?>
                ✍ <?= $content['author_name']; ?>
            <?php endif; ?>
            <?php if (!empty($content['published_at'])): ?>
                • 📅 <?= date('d M Y', strtotime($content['published_at'])); ?>
            <?php endif; ?>
            <?php if (isset($content['views'])): ?>
                • 👁 <?= $content['views']; ?> views
            <?php endif; ?>
        </p>
    </div>
</section>

<section class="program-section" style="padding: 40px 80px; flex-direction: column; align-items: center;">
    <article style="max-width: 800px; width: 100%;">
        
        <?php if (!empty($content['cover_image'])): ?>
            <img src="<?= BASE_URL ?>/<?= $content['cover_image']; ?>" alt="<?= htmlspecialchars($content['title']); ?>" style="width: 100%; border-radius: 10px; margin-bottom: 30px;">
        <?php endif; ?>

        <div class="blog-body" style="font-size: 17px; line-height: 1.9; color: #333;">
            <?= nl2br(htmlspecialchars($content['body'])); ?>
        </div>

        <div style="margin-top: 40px; padding-top: 20px; border-top: 2px solid #eee;">
            <a href="<?= BASE_URL ?>/blog" class="btn-outline">← Kembali ke Blog</a>
        </div>
    </article>
</section>
