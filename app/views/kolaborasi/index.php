<?php // Kolaborasi Index Page ?>

<section class="hero ekosistem-hero">
    <div class="hero-content">
        <h1>Koleb</h1>
        <p>Pertemuan yang melahirkan gerak mencipta ruang dialog, untuk merayakan perbedaan cara pandang budaya, dan membuka kemungkinan masa depan. Di titik itulah kebudayaan tidak lagi dipahami sebagai benda mati yang disimpan, melainkan sebagai gerak yang terus hidup serta bertumbuh dari percakapan, dari kolaborasi, dari keberanian untuk saling mendengar dan mencipta bersama..</p>
    </div>
</section>

<section class="ekosistem-section">
    <div class="ekosistem-grid">
        <?php if (empty($kolaborasi)): ?>
            <p class="zine-empty">Belum ada data kolaborasi.</p>
        <?php else: ?>
            <?php foreach ($kolaborasi as $item): 
                $socials = [];
                if (!empty($item['social_media'])) {
                    $socials = is_array($item['social_media']) ? $item['social_media'] : (json_decode($item['social_media'], true) ?? []);
                }
                $igUrl = $socials['instagram'] ?? '';
            ?>
                <div class="ekosistem-card">
                    <?php if (!empty($item['cover_image']) && file_exists('../public/' . $item['cover_image'])): ?>
                        <div class="ekosistem-card-image">
                            <img src="<?= BASE_URL ?>/<?= $item['cover_image'] ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                        </div>
                    <?php else: ?>
                        <div class="ekosistem-card-image">
                            <div class="ekosistem-card-placeholder" style="background: linear-gradient(135deg, #d52c2c 0%, #1a1a2e 100%);">
                                <i class="fa-solid fa-handshake"></i>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="ekosistem-card-content">
                        <h3><?= htmlspecialchars($item['title']) ?></h3>

                        <?php if (!empty($item['contributor_names'])): ?>
                        <div style="margin-bottom: 8px;">
                            <small style="color: #888;"><i class="fas fa-users"></i> <?= htmlspecialchars($item['contributor_names']) ?></small>
                        </div>
                        <?php endif; ?>

                        <p>
                            <?= htmlspecialchars(substr($item['description'] ?? '', 0, 100)) ?>...
                        </p>

                        <div class="ekosistem-card-actions">
                            <a href="<?= BASE_URL ?>/kolaborasi/detail/<?= $item['slug'] ?>" class="btn-outline" target="_blank">
                                <i class="fa-regular fa-eye"></i> Detail
                            </a>
                            <?php if (!empty($igUrl)): ?>
                                <a href="<?= htmlspecialchars($igUrl) ?>" target="_blank" class="ekosistem-social">
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
