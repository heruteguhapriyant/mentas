<?php // Zine Detail Page ?>

<section class="hero zine-hero">
    <div class="hero-content">
        <a href="<?= BASE_URL ?>/zine" class="zine-back-link">
            ← Kembali ke Bulletin Sastra
        </a>

        <h1 class="zine-title">
            <?= htmlspecialchars($zine['title']) ?>
        </h1>

        <?php if (!empty($zine['excerpt'])): ?>
            <p class="zine-excerpt">
                <?= htmlspecialchars($zine['excerpt']) ?>
            </p>
        <?php endif; ?>
    </div>
</section>

<section class="program-section zine-section-detail">
    <article class="zine-article">

        <?php if (!empty($zine['cover_image'])): ?>
            <img 
                src="<?= BASE_URL ?>/<?= $zine['cover_image'] ?>" 
                alt="<?= htmlspecialchars($zine['title']) ?>" 
                class="zine-cover"
            >
        <?php endif; ?>

        <div class="zine-content-body">
            <?= $zine['content'] ?>
        </div>

        <div class="zine-footer">
            <a href="<?= BASE_URL ?>/zine" class="btn-outline">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>

    </article>
</section>
