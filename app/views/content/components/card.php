<article class="blog-card">
    <a href="<?= BASE_URL ?>/blog/<?= $item['slug']; ?>">
        <?php if (!empty($item['cover_image'])): ?>
            <img src="<?= BASE_URL ?>/<?= $item['cover_image']; ?>" alt="<?= htmlspecialchars($item['title']); ?>">
        <?php else: ?>
            <img src="<?= BASE_URL ?>/assets/images/default-cover.jpg" alt="<?= htmlspecialchars($item['title']); ?>">
        <?php endif; ?>
    </a>

    <div class="blog-card-body">
        <?php if (!empty($item['category_name'])): ?>
            <span class="blog-category">
                <?= $item['category_name']; ?>
            </span>
        <?php endif; ?>

        <h2>
            <a href="<?= BASE_URL ?>/blog/<?= $item['slug']; ?>">
                <?= htmlspecialchars($item['title']); ?>
            </a>
        </h2>

        <p class="blog-excerpt">
            <?= htmlspecialchars($item['excerpt'] ?? ''); ?>
        </p>

        <div class="blog-meta">
            <?php if (!empty($item['author_name'])): ?>
                <span>✍ <?= $item['author_name']; ?></span>
            <?php endif; ?>
            <?php if (!empty($item['published_at'])): ?>
                <span>📅 <?= date('d M Y', strtotime($item['published_at'])); ?></span>
            <?php endif; ?>
        </div>
    </div>
</article>

