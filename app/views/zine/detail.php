<?php // Zine Detail Page ?>

<section class="hero zine-hero">
    <div class="hero-content">
        <a href="<?= BASE_URL ?>/zine" class="zine-back-link">
            ← Kembali ke Bulletin Sastra
        </a>

        <span class="zine-detail-category"><?= Zine::getCategoryLabel($zine['category'] ?? 'esai') ?></span>

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

        <?php if (!empty($zine['pdf_file'])): ?>
            <!-- PDF Link -->
            <div class="zine-pdf-link" style="text-align: center; padding: 40px; background: #f8f9fa; border-radius: 10px; border: 2px solid #eee;">
                <i class="fa-solid fa-file-pdf" style="font-size: 64px; color: #d52c2c; margin-bottom: 20px; display: block;"></i>
                <p style="margin-bottom: 20px; color: #666;">Klik tombol di bawah untuk membaca PDF</p>
                <a href="<?= BASE_URL ?>/<?= $zine['pdf_file'] ?>" target="_blank" class="btn-primary" style="background: #d52c2c; color: white; padding: 15px 30px; border-radius: 25px; text-decoration: none; display: inline-block; font-weight: 600;">
                    <i class="fa-solid fa-external-link"></i> Buka PDF
                </a>
            </div>
        <?php elseif (!empty($zine['content'])): ?>
            <!-- Legacy text content -->
            <div class="zine-content-body">
                <?= $zine['content'] ?>
            </div>
        <?php endif; ?>

        <div class="zine-footer">
            <a href="<?= BASE_URL ?>/zine" class="btn-outline">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>

    </article>
</section>
