<div class="blog-filter-wrapper">
    <!-- Search Bar - Centered above filters -->
    <div class="blog-search-container">
        <form class="blog-search-form" id="blog-search-form" action="<?= BASE_URL ?>/blog" method="GET">
            <div class="search-input-wrapper">
                <input type="text" 
                       name="q" 
                       id="blog-search-input"
                       class="search-input" 
                       placeholder="Cari artikel..." 
                       value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Category Filter - Centered below search -->
    <div class="blog-filter">
        <a href="<?= BASE_URL ?>/blog"
           class="filter-btn <?= empty($activeCategory) && empty($_GET['q']) ? 'is-active' : '' ?>">
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
</div>
