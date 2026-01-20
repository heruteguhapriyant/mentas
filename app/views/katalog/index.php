<?php // Katalog Page ?>

<section class="hero katalog-hero">
    <div class="hero-content">
        <h1>Index Komunitas Seni & Budaya</h1>
        <p>Direktori komunitas seni dan budaya di Indonesia</p>
    </div>
</section>

<section class="program-section katalog-section">
    <?php if (empty($communities)): ?>
        <p class="katalog-empty">Belum ada data komunitas.</p>
    <?php else: ?>
        <?php foreach ($communities as $community): ?>
            <div class="program-card katalog-card">

                <?php if (!$community['image']): ?>
                    <div class="katalog-thumb katalog-thumb-placeholder">
                        <i class="fa-solid fa-users"></i>
                    </div>
                <?php else: ?>
                    <img
                        src="<?= BASE_URL ?>/<?= $community['image'] ?>"
                        alt="<?= htmlspecialchars($community['name']) ?>"
                        class="katalog-thumb"
                    >
                <?php endif; ?>

                <h3><?= htmlspecialchars($community['name']) ?></h3>

                <?php if ($community['location']): ?>
                    <p class="katalog-location">
                        <i class="fa-solid fa-location-dot"></i>
                        <?= htmlspecialchars($community['location']) ?>
                    </p>
                <?php endif; ?>

                <?php if ($community['description']): ?>
                    <p class="katalog-excerpt">
                        <?= substr($community['description'], 0, 80) ?>...
                    </p>
                <?php endif; ?>

                <a href="<?= BASE_URL ?>/katalog/detail/<?= $community['slug'] ?>" class="btn-outline">
                    Lihat Detail
                </a>

            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</section>
