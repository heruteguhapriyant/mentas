<?php // Ekosistem Index Page ?>

<section class="hero zine-hero">
    <div class="hero-content">
        <h1>Ekosistem</h1>
        <p>Jaringan kolaborasi komunitas seni dan budaya yang terhubung dengan Mentas</p>
    </div>
</section>

<section class="program-section zine-section">
    <?php if (empty($ekosistem)): ?>
        <p class="zine-empty">Belum ada data ekosistem.</p>
    <?php else: ?>
        <?php foreach ($ekosistem as $item): ?>
            <div class="program-card zine-card">
                <?php if (!empty($item['image']) && file_exists('../public/' . $item['image'])): ?>
                    <div class="zine-card-cover">
                        <img src="<?= BASE_URL ?>/<?= $item['image'] ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                    </div>
                <?php else: ?>
                    <div class="zine-card-cover" style="background: linear-gradient(135deg, <?= $item['color'] ?> 0%, #1a1a2e 100%); display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-users" style="font-size: 48px; color: rgba(255,255,255,0.8);"></i>
                    </div>
                <?php endif; ?>
                
                <div class="zine-card-content">
                    <span class="zine-card-category"><?= htmlspecialchars($item['tagline']) ?></span>
                    
                    <h3><?= htmlspecialchars($item['name']) ?></h3>

                    <p class="zine-card-excerpt">
                        <?= htmlspecialchars(substr($item['description'], 0, 100)) ?>...
                    </p>

                    <div style="display: flex; gap: 10px; align-items: center;">
                        <a href="<?= BASE_URL ?>/ekosistem/detail/<?= $item['slug'] ?>" class="btn-outline">
                            Lihat Detail
                        </a>
                        <?php if (!empty($item['instagram'])): ?>
                            <a href="<?= $item['instagram'] ?>" target="_blank" style="color: #E1306C; font-size: 20px;">
                                <i class="fab fa-instagram"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
