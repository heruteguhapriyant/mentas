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

<style>
.blog-filter-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.blog-search-container {
    width: 100%;
    display: flex;
    justify-content: center;
}

.blog-search-form {
    width: 100%;
    max-width: 500px;
}

.search-input-wrapper {
    display: flex;
    border: 2px solid #ddd;
    border-radius: 30px;
    overflow: hidden;
    background: #fff;
    transition: all 0.3s;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.search-input-wrapper:focus-within {
    border-color: var(--primary-color, #d35400);
    box-shadow: 0 4px 12px rgba(211, 84, 0, 0.15);
}

.search-input {
    flex: 1;
    padding: 12px 24px;
    border: none;
    outline: none;
    font-size: 1rem;
}

.search-input::placeholder {
    color: #999;
}

.search-btn {
    padding: 12px 20px;
    background: var(--primary-color, #d35400);
    color: white;
    border: none;
    cursor: pointer;
    transition: background 0.3s;
}

.search-btn:hover {
    background: var(--primary-dark, #b34700);
}

.blog-filter {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 8px;
}
</style>
