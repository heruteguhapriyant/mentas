<?php 
/**
 * Blog List Page - Updated dengan Pagination
 * File: app/views/content/list/article.php
 */
?>

<section class="hero blog-hero">
    <div class="hero-content">
        <h1><?= $type['name'] ?? 'Blog'; ?></h1>
        <?php if (!empty($categoryName)): ?>
            <p>Kategori: <?= $categoryName; ?></p>
        <?php else: ?>
            <p>Artikel, esai, dan berita seputar seni & budaya</p>
        <?php endif; ?>
    </div>
</section>

<section class="program-section blog-section">
    <?php require __DIR__ . '/../components/filter.php'; ?>

    <div class="blog-grid">
        <?php if (empty($contents)): ?>
            <p class="blog-empty">Belum ada tulisan di kategori ini.</p>
        <?php else: ?>
            <?php foreach ($contents as $item): ?>
                <article class="program-card blog-card">
                    <?php if (!empty($item['cover_image'])): ?>
                        <img src="<?= BASE_URL ?>/<?= $item['cover_image']; ?>" 
                             alt="<?= htmlspecialchars($item['title']); ?>" 
                             class="blog-card-img">
                    <?php endif; ?>
                    
                    <?php if (!empty($item['category_name'])): ?>
                        <span class="blog-category">
                            <?= $item['category_name']; ?>
                        </span>
                    <?php endif; ?>

                    <h3 class="blog-title">
                        <a href="<?= BASE_URL ?>/blog/<?= $item['slug']; ?>" class="blog-title-link">
                            <?= htmlspecialchars($item['title']); ?>
                        </a>
                    </h3>

                    <p class="blog-excerpt">
                        <?= htmlspecialchars(substr($item['excerpt'] ?? '', 0, 120)); ?>...
                    </p>

                    <div class="blog-meta">
                        <?php if (!empty($item['author_name'])): ?>
                            ✍ <?= $item['author_name']; ?>
                        <?php endif; ?>
                        <?php if (!empty($item['published_at'])): ?>
                            - 📅 <?= date('d M Y', strtotime($item['published_at'])); ?>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php 
    // Tampilkan pagination jika ada data
    if (!empty($pagination) && !empty($contents)): 
        echo renderPagination($pagination);
    endif; 
    ?>
</section>