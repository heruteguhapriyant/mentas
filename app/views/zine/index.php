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
        <?php foreach ($categories as $key => $label): ?>
            <a href="<?= BASE_URL ?>/zine?category=<?= $key ?>" class="category-tab <?= $activeCategory === $key ? 'active' : '' ?>">
                <?= $label ?>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<section class="program-section zine-section">
    <?php if (empty($zines)): ?>
        <p class="zine-empty">
            <?php if ($activeCategory): ?>
                Belum ada bulletin untuk kategori <?= Zine::getCategoryLabel($activeCategory) ?>.
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
                    <span class="zine-card-category"><?= Zine::getCategoryLabel($zine['category'] ?? 'esai') ?></span>
                    
                    <h3><?= htmlspecialchars($zine['title']) ?></h3>

                    <p class="zine-card-excerpt">
                        <?= htmlspecialchars($zine['excerpt'] ?? substr($zine['content'] ?? '', 0, 100)) ?>
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
