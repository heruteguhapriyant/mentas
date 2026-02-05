<?php // Zine Index Page ?>

<section class="hero zine-hero">
    <div class="hero-content">
        <h1>Bulletin</h1>
        <p>Kumpulan publikasi sastra dari komunitas Mentas dalam format PDF</p>
    </div>
</section>

<!-- Category Tabs -->
<section class="zine-category-tabs">
    <div class="category-tabs-container">
        <a href="<?= BASE_URL ?>/zine" class="category-tab <?= $activeCategory === null ? 'active' : '' ?>">
            Semua
        </a>
        <?php foreach ($categories as $cat): ?>
            <a href="<?= BASE_URL ?>/zine?category=<?= $cat['slug'] ?>" class="category-tab <?= $activeCategory === $cat['slug'] ? 'active' : '' ?>">
                <?= htmlspecialchars($cat['name']) ?>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<section class="program-section zine-section">
    <?php if (empty($zines)): ?>
        <p class="zine-empty">
            <?php if ($activeCategory): 
                $activeCatName = 'Kategori';
                foreach($categories as $cat) { if($cat['slug'] === $activeCategory) $activeCatName = $cat['name']; }
            ?>
                Belum ada bulletin untuk kategori <?= htmlspecialchars($activeCatName) ?>.
            <?php else: ?>
                Belum ada bulletin.
            <?php endif; ?>
        </p>
    <?php else: ?>
        <?php foreach ($zines as $zine): ?>
            <div class="program-card zine-card">
                <?php if (!empty($zine['cover_image'])): ?>
                    <div class="zine-card-cover">
                        <img src="<?= BASE_URL ?>/<?= $zine['cover_image'] ?>" alt="<?= htmlspecialchars($zine['title']) ?>">
                    </div>
                <?php endif; ?>
                
                <div class="zine-card-content">
                    <span class="zine-card-category"><?= htmlspecialchars($zine['category_name'] ?? 'General') ?></span>
                    
                    <h3><?= htmlspecialchars($zine['title']) ?></h3>

                    <p class="zine-card-excerpt">
                        <?php 
                        $excerpt = $zine['excerpt'] ?? '';
                        if (empty($excerpt) && !empty($zine['content'])) {
                            $excerpt = generateExcerpt($zine['content'], 120);
                        } else {
                            $excerpt = generateExcerpt($excerpt, 120);
                        }
                        echo htmlspecialchars($excerpt);
                        ?>
                    </p>

                    <?php if (!empty($zine['pdf_file'])): ?>
                        <a 
                            href="<?= BASE_URL ?>/<?= $zine['pdf_file'] ?>" 
                            target="_blank"
                            class="btn-outline"
                        >
                            <i class="fa-solid fa-file-pdf"></i> Baca Selengkapnya
                        </a>
                    <?php else: ?>
                        <a 
                            href="<?= BASE_URL ?>/zine/detail/<?= $zine['slug'] ?>" 
                            class="btn-outline"
                        >
                            Baca Selengkapnya
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
