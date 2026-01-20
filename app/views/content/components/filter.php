<div class="blog-filter">
    <a href="<?= BASE_URL ?>/blog"
       class="filter-btn <?= empty($activeCategory) ? 'is-active' : '' ?>">
        Semua
    </a>

    <?php if (!empty($categories)): ?>
        <?php foreach ($categories as $cat): ?>
            <a href="<?= BASE_URL ?>/blog/<?= $cat['slug']; ?>"
               class="filter-btn <?= ($activeCategory ?? '') === $cat['slug'] ? 'is-active' : '' ?>">
                <?= $cat['name']; ?>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
