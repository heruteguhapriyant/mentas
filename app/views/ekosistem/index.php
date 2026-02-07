<?php // Ekosistem Index Page ?>

<section class="hero ekosistem-hero">
    <div class="hero-content">
        <h1>Ekosistem</h1>
        <p>Jaringan kolaborasi komunitas seni dan budaya yang terhubung dengan Mentas</p>
    </div>
</section>

<section class="ekosistem-section">
    <div class="ekosistem-grid">
        <?php if (empty($ekosistem)): ?>
            <p class="zine-empty">Belum ada data ekosistem.</p>
        <?php else: ?>
            <?php foreach ($ekosistem as $item): ?>
                <div class="ekosistem-card">
                    <?php if (!empty($item['image']) && file_exists('../public/' . $item['image'])): ?>
                        <div class="ekosistem-card-image">
                            <img src="<?= BASE_URL ?>/<?= $item['image'] ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                            <div class="ekosistem-card-overlay">
                                <span class="ekosistem-tagline"><?= htmlspecialchars($item['tagline']) ?></span>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="ekosistem-card-image">
                            <div class="ekosistem-card-placeholder" style="background: linear-gradient(135deg, <?= $item['color'] ?> 0%, #1a1a2e 100%);">
                                <i class="fa-solid fa-users"></i>
                            </div>
                            <div class="ekosistem-card-overlay">
                                <span class="ekosistem-tagline"><?= htmlspecialchars($item['tagline']) ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="ekosistem-card-content">
                        <h3><?= htmlspecialchars($item['name']) ?></h3>

                        <p>
                            <?= htmlspecialchars(substr($item['description'], 0, 100)) ?>...
                        </p>

                        <?php if (!empty($item['created_at'])): ?>
                        <div class="ekosistem-card-meta">
                            <i class="fa-regular fa-calendar"></i>
                            <?= date('d M Y', strtotime($item['created_at'])) ?>
                        </div>
                        <?php endif; ?>

                        <div class="ekosistem-card-actions">
                            <a href="<?= BASE_URL ?>/ekosistem/detail/<?= $item['slug'] ?>" class="btn-outline">
                                <i class="fa-regular fa-eye"></i> Detail
                            </a>
                            <?php if (!empty($item['instagram'])): ?>
                                <a href="<?= $item['instagram'] ?>" target="_blank" class="ekosistem-social">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>