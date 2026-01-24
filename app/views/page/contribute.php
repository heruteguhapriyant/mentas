<?php // Contribute Page - Uses existing Mentas design ?>

<section class="hero" style="padding: 120px 60px 60px;">
    <div class="hero-content">
        <h1>Contribute</h1>
        <p>Bergabunglah bersama kami dalam menjaga keberlangsungan ruang literasi kesenian</p>
    </div>
</section>

<section class="program-section">
    <div class="program-card">
        <h3><i class="fa-solid fa-pen-nib" style="color: #d52c2c; margin-right: 8px;"></i> Opini & Esai</h3>
        <p style="margin-top: 15px;">Bagi teman-teman yang memiliki uneg-uneg terhadap isu kesenian dan kebudayaan, segera tulis dan kirimkan ke kami.</p>
    </div>

    <div class="program-card">
        <h3><i class="fa-solid fa-book-open" style="color: #d52c2c; margin-right: 8px;"></i> Cerita</h3>
        <p style="margin-top: 15px;">Kami menerima tulisan berbagi pengalaman yang mengkisahkan perjalananmu seputar kesenian.</p>
    </div>

    <div class="program-card">
        <h3><i class="fa-solid fa-calendar-days" style="color: #d52c2c; margin-right: 8px;"></i> Event</h3>
        <p style="margin-top: 15px;">Jika Anda memiliki agenda kesenian, kirimkan konten promosinya untuk kami publikasikan.</p>
    </div>
</section>

<!-- Contributors Directory Section -->
<section class="blog-section" style="padding: 60px 20px; background: #fafafa;">
    <div style="text-align: center; margin-bottom: 40px;">
        <h2 style="font-size: 28px; margin-bottom: 10px;">Direktori Kontributor</h2>
        <p style="color: #666;">Kenali para penulis hebat yang telah berkontribusi di Mentas.id</p>
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

        <hr class="divider">

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

<style>
.contributors-list-container {
    max-width: 1000px;
    margin: 0 auto;
}

/* Alphabet Nav */
.alphabet-nav {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
    margin-bottom: 30px;
}

.alphabet-link {
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #e0d4fc; /* Light purple like reference */
    color: #333;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.2s;
    border: 1px solid #ccc;
}

.alphabet-link:hover {
    background: #d52c2c;
    color: #fff;
    border-color: #d52c2c;
}

.alphabet-link.disabled {
    background: #f5f5f5;
    color: #ccc;
    pointer-events: none;
    border-color: #eee;
}

.divider {
    border: 0;
    border-top: 1px solid #ddd;
    margin-bottom: 40px;
}

/* Contributor Group */
.contributor-group {
    margin-bottom: 50px;
    display: flex; /* Flex to separate letter and list if needed, or block */
    flex-direction: column;
    align-items: flex-start;
}

/* Letter Circle Header */
.contributor-letter-circle {
    width: 50px;
    height: 50px;
    background: #fceea7; /* Yellow like reference */
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 25px;
    border: 2px solid #333;
}

.contributor-letter-circle h2 {
    font-size: 1.5rem;
    color: #000;
    margin: 0;
    font-weight: 800;
}

/* 3-Column Simple List */
.contributor-items-simple {
    width: 100%;
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* 3 Columns */
    gap: 10px 40px; /* Row gap, Column gap */
}

.contributor-simple-link {
    display: block;
    color: #333;
    text-decoration: none;
    font-size: 1rem;
    padding: 2px 0;
    transition: color 0.2s;
}

.contributor-simple-link:hover {
    color: #d52c2c;
    text-decoration: underline;
}

@media (max-width: 768px) {
    .contributor-items-simple {
        grid-template-columns: 1fr; /* Single column on mobile */
    }
    
    .alphabet-link {
        width: 30px;
        height: 30px;
        font-size: 12px;
    }
}
</style>
    <?php else: ?>
        <p style="text-align: center; color: #999;">Belum ada kontributor terdaftar.</p>
    <?php endif; ?>
</section>

<section class="consult-section">
    <div class="consult-container" style="flex-direction: column; text-align: center;">
        <div class="consult-left" style="text-align: center;">
            <h2>Ingin menjadi Contributor?</h2>
            <p>Daftar sebagai contributor dan dapatkan akses untuk menulis di platform kami.</p>
            <a href="<?= BASE_URL ?>/auth/register" class="btn-green">
                <i class="fa-solid fa-user-plus" style="margin-right: 8px;"></i> Daftar Sekarang
            </a>
        </div>
    </div>
</section>
