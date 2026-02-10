<?php // Zine Detail Page ?>

<section class="hero zine-hero">
    <div class="hero-content">
        <a href="<?= BASE_URL ?>/zine" class="zine-back-link">
            ← Kembali ke Bulletin Sastra
        </a>

        <span class="zine-detail-category"><?= htmlspecialchars($zine['category_name'] ?? 'Bulletin') ?></span>

        <h1 class="zine-title">
            <?= htmlspecialchars($zine['title']) ?>
        </h1>

        <?php if (!empty($zine['excerpt'])): ?>
            <p class="zine-excerpt">
                <?= htmlspecialchars(generateExcerpt($zine['excerpt'], 200)) ?>
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

        <?php if (!empty($zine['pdf_link'])): ?>
            <!-- PDF Link -->
            <div class="zine-pdf-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 384 512" fill="#d52c2c">
                    <path d="M64 0C28.7 0 0 28.7 0 64V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V160H256c-17.7 0-32-14.3-32-32V0H64zM256 0V128H384L256 0zM112 256H272c8.8 0 16 7.2 16 16s-7.2 16-16 16H112c-8.8 0-16-7.2-16-16s7.2-16 16-16zm0 64H272c8.8 0 16 7.2 16 16s-7.2 16-16 16H112c-8.8 0-16-7.2-16-16s7.2-16 16-16zm0 64H272c8.8 0 16 7.2 16 16s-7.2 16-16 16H112c-8.8 0-16-7.2-16-16s7.2-16 16-16z"/>
                </svg>
                <p>PDF</p>
                <p>Klik tombol di bawah untuk membaca atau mengunduh PDF</p>
                <a href="<?= htmlspecialchars($zine['pdf_link']) ?>" target="_blank" class="btn-primary">
                    <i class="fas fa-external-link-alt"></i> Baca / Download PDF
                </a>
            </div>
        <?php elseif (!empty($zine['content'])): ?>
            <!-- Legacy text content - cleaned from WordPress blocks -->
            <div class="zine-content-body">
                <?= cleanWordPressContent($zine['content']) ?>
            </div>
        <?php endif; ?>

        <div class="zine-footer">
            <a href="<?= BASE_URL ?>/zine" class="btn-outline">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>

    </article>
</section>