<?php // Contribute Page - Uses existing Mentas design ?>

<section class="hero contribute-hero">
    <div class="hero-content">
        <h1>Contribute</h1>
        <p>Bergabunglah bersama kami dalam menjaga keberlangsungan ruang literasi kesenian</p>
    </div>
</section>

<section class="program-section">
    <div class="program-card">
        <h3><i class="fa-solid fa-pen-nib"></i> Opini & Esai</h3>
        <p>Bagi teman-teman yang memiliki uneg-uneg terhadap isu kesenian dan kebudayaan, segera tulis dan kirimkan ke kami.</p>
    </div>

    <div class="program-card">
        <h3><i class="fa-solid fa-book-open"></i> Cerita</h3>
        <p>Kami menerima tulisan berbagi pengalaman yang mengkisahkan perjalananmu seputar kesenian.</p>
    </div>

    <div class="program-card">
        <h3><i class="fa-solid fa-calendar-days"></i> Event</h3>
        <p>Jika Anda memiliki agenda kesenian, kirimkan konten promosinya untuk kami publikasikan.</p>
    </div>
</section>

<!-- Contributors Directory Section -->
<section class="blog-section contributors-directory">
    <div class="contributors-directory-header">
        <h2>Direktori Kontributor</h2>
        <p>Kenali para penulis hebat yang telah berkontribusi di Mentas.id</p>
    </div>

    <?php if (!empty($contributors)): ?>
    <div class="contributors-list-container">
        <?php 
        // Group contributors by first letter
        $groupedContributors = [];
        foreach ($contributors as $contributor) {
            $firstLetter = strtoupper(substr($contributor['name'], 0, 1));
            if (!ctype_alpha($firstLetter)) {
                $firstLetter = '#';
            }
            $groupedContributors[$firstLetter][] = $contributor;
        }
        ksort($groupedContributors);

        // All letters A-Z for navigation
        $alphabet = array_merge(range('A', 'Z'), ['#']);
        ?>

        <!-- Alphabet Navigation -->
        <div class="alphabet-nav">
            <?php foreach ($alphabet as $letter): ?>
                <?php $hasContributors = isset($groupedContributors[$letter]); ?>
                <a href="#letter-<?= $letter ?>" class="alphabet-link <?= $hasContributors ? '' : 'disabled' ?>">
                    <?= $letter ?>
                </a>
            <?php endforeach; ?>
        </div>

        <hr class="contributors-divider">

        <!-- Contributors List -->
        <?php foreach ($groupedContributors as $letter => $list): ?>
            <div id="letter-<?= $letter ?>" class="contributor-group">
                <div class="contributor-letter-circle">
                    <h2><?= $letter ?></h2>
                </div>
                <div class="contributor-items-simple">
                    <?php foreach ($list as $contributor): ?>
                        <a href="<?= BASE_URL ?>/author/<?= $contributor['id'] ?>" class="contributor-simple-link">
                            <?= htmlspecialchars($contributor['name']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php else: ?>
        <p class="contributors-empty-state">Belum ada kontributor terdaftar.</p>
    <?php endif; ?>
</section>

<section class="consult-section">
    <div class="consult-container contribute-consult">
        <div class="consult-left contribute-consult-content">
            <h2>Ingin menjadi Contributor?</h2>
            <p>Daftar sebagai contributor dan dapatkan akses untuk menulis di platform kami.</p>
            <a href="<?= BASE_URL ?>/auth/register" class="btn-green">
                <i class="fa-solid fa-user-plus contribute-btn-icon"></i> Daftar Sekarang
            </a>
        </div>
    </div>
</section>