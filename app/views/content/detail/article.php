<?php // Article Detail Page - Uses existing Mentas design ?>

<section class="hero blog-hero-detail">
    <div class="hero-content">
        <?php if (!empty($content['category_name'])): ?>
            <span class="btn-orange blog-detail-category"><?= $content['category_name']; ?></span>
        <?php endif; ?>
        
        <h1 class="blog-detail-title"><?= htmlspecialchars($content['title']); ?></h1>
        
        <p class="blog-detail-meta">
            <?php if (!empty($content['author_name'])): ?>
                ✍ <?= $content['author_name']; ?>
            <?php endif; ?>
            <?php if (!empty($content['published_at'])): ?>
                - 📅 <?= date('d M Y', strtotime($content['published_at'])); ?>
            <?php endif; ?>
            <?php if (isset($content['views'])): ?>
                - 👁 <?= $content['views']; ?> views
            <?php endif; ?>
        </p>
    </div>
</section>

<section class="program-section blog-section-detail">
    <article class="blog-article">
        
        <?php if (!empty($content['cover_image'])): ?>
            <img src="<?= BASE_URL ?>/<?= $content['cover_image']; ?>" alt="<?= htmlspecialchars($content['title']); ?>" class="blog-cover">
        <?php endif; ?>

        <div class="blog-body">
            <?= nl2br(htmlspecialchars($content['body'])); ?>
        </div>

        <div class="blog-footer">
            <a href="<?= BASE_URL ?>/blog" class="btn-outline">← Kembali ke Blog</a>
        </div>
    </article>
</section>