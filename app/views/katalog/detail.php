<?php // Katalog Detail Page ?>

<section class="hero katalog-hero">
    <div class="hero-content katalog-hero-content">

        <?php if ($community['image']): ?>
            <img
                src="<?= BASE_URL ?>/<?= $community['image'] ?>"
                alt="<?= htmlspecialchars($community['name']) ?>"
                class="katalog-avatar"
            >
        <?php else: ?>
            <div class="katalog-avatar katalog-avatar-placeholder">
                <i class="fa-solid fa-users"></i>
            </div>
        <?php endif; ?>

        <div class="katalog-hero-text">
            <h1 class="katalog-title">
                <?= htmlspecialchars($community['name']) ?>
            </h1>

            <?php if ($community['location']): ?>
                <p class="katalog-location">
                    <i class="fa-solid fa-location-dot"></i>
                    <?= htmlspecialchars($community['location']) ?>
                </p>
            <?php endif; ?>
        </div>

    </div>
</section>

<section class="program-section katalog-detail-section">

    <!-- Info Card -->
    <div class="program-card katalog-card">
        <h3>
            <i class="fa-solid fa-info-circle"></i> Tentang
        </h3>
        <p class="katalog-description">
            <?= nl2br(htmlspecialchars($community['description'])) ?>
        </p>
    </div>

    <!-- Contact Card -->
    <?php if ($community['contact'] || $community['website']): ?>
        <div class="program-card katalog-card">
            <h3>
                <i class="fa-solid fa-address-book"></i> Kontak
            </h3>

            <div class="katalog-contact">
                <?php if ($community['contact']): ?>
                    <p>
                        <i class="fa-solid fa-phone"></i>
                        <?= htmlspecialchars($community['contact']) ?>
                    </p>
                <?php endif; ?>

                <?php if ($community['website']): ?>
                    <p>
                        <i class="fa-solid fa-globe"></i>
                        <a href="<?= $community['website'] ?>" target="_blank">
                            <?= $community['website'] ?>
                        </a>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Back Button -->
    <div class="katalog-footer">
        <a href="<?= BASE_URL ?>/katalog" class="btn-outline">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Katalog
        </a>
    </div>

</section>
