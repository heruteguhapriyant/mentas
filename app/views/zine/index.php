<?php // Zine Index Page ?>

<section class="hero zine-hero">
    <div class="hero-content">
        <h1>Bulletin Sastra</h1>
        <p>Kumpulan zine dan publikasi sastra dari komunitas Mentas</p>
    </div>
</section>

<section class="program-section zine-section">
    <?php if (empty($zines)): ?>
        <p class="zine-empty">Belum ada bulletin sastra.</p>
    <?php else: ?>
        <?php foreach ($zines as $zine): ?>
            <div class="program-card zine-card">
                <h3><?= htmlspecialchars($zine['title']) ?></h3>

                <p class="zine-card-excerpt">
                    <?= htmlspecialchars(substr($zine['content'] ?? '', 0, 100)) ?>...
                </p>

                <a 
                    href="<?= BASE_URL ?>/zine/detail/<?= $zine['slug'] ?>" 
                    class="btn-outline"
                >
                    Baca Selengkapnya
                </a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
