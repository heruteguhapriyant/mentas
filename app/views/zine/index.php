<?php // Zine Page - Uses existing Mentas design ?>

<section class="hero" style="padding: 120px 60px 60px;">
    <div class="hero-content">
        <h1>Bulletin Sastra</h1>
        <p>Kumpulan zine dan publikasi sastra dari komunitas Mentas</p>
    </div>
</section>

<section class="program-section" style="flex-direction: row; flex-wrap: wrap; justify-content: center;">
    <?php if (empty($zines)): ?>
        <p style="text-align: center; width: 100%; padding: 3rem; color: #666;">Belum ada bulletin sastra.</p>
    <?php else: ?>
        <?php foreach ($zines as $zine): ?>
            <div class="program-card">
                <h3><?= htmlspecialchars($zine['title']) ?></h3>
                <p style="font-size: 14px; color: #555; margin-bottom: 15px;">
                    <?= htmlspecialchars(substr($zine['content'] ?? '', 0, 100)) ?>...
                </p>
                <a href="<?= BASE_URL ?>/zine/detail/<?= $zine['slug'] ?>" class="btn-outline">Baca Selengkapnya</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
