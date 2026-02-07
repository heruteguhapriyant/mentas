    <section class="hero">
        <div class="hero-content">
            <h1>Ruang Arsip, Wacana, dan Ekosistem Seni-Budaya</h1>
            <p>Mentas.id adalah platform seni dan kebudayaan yang memuat tulisan, bulletin sastra, katalog riset, agenda peristiwa, serta ruang jual beli untuk mendukung ekosistem kreatif.</p>
            <a href="<?= BASE_URL ?>/blog" class="btn-orange">Jelajahi</a>
        </div>
    </section>

    <!-- Featured Section -->
    <?php if (!empty($featuredPosts)): ?>
    <section class="featured-section">
        <div class="featured-grid">
            
            <!-- Main Feature (First Post) -->
            <?php if (isset($featuredPosts[0])): $main = $featuredPosts[0]; ?>
            <a href="<?= BASE_URL ?>/blog/<?= $main['slug'] ?>" class="featured-card featured-main">
                <img src="<?= BASE_URL ?>/<?= $main['cover_image'] ?>" alt="<?= htmlspecialchars($main['title']) ?>">
                <div class="featured-overlay">
                    <div class="featured-meta">
                        <span><i class="fa-solid fa-user"></i> Mentas.id</span>
                        <span>at <i class="fa-regular fa-clock"></i> <?= date('d F Y', strtotime($main['published_at'])) ?></span>
                    </div>
                    <h2><?= htmlspecialchars($main['title']) ?></h2>
                </div>
            </a>
            <?php endif; ?>

            <!-- Sub Features (Next 2 Posts) -->
            <div class="featured-sub">
                <?php for($i=1; $i<3; $i++): if(isset($featuredPosts[$i])): $post = $featuredPosts[$i]; ?>
                <a href="<?= BASE_URL ?>/blog/<?= $post['slug'] ?>" class="featured-card featured-item">
                    <img src="<?= BASE_URL ?>/<?= $post['cover_image'] ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                    <div class="featured-overlay">
                        <div class="featured-meta-small">
                            <span><i class="fa-solid fa-user"></i> Mentas.id</span>
                            <span>at <i class="fa-regular fa-clock"></i> <?= date('d F Y', strtotime($post['published_at'])) ?></span>
                        </div>
                        <h3><?= htmlspecialchars($post['title']) ?></h3>
                    </div>
                </a>
                <?php endif; endfor; ?>
            </div>
        </div>
        
        <div class="featured-action">
            <a href="<?= BASE_URL ?>/blog" class="featured-btn-all">
                <i class="fas fa-link"></i> Tampilkan Semua
            </a>
        </div>
    </section>
    <?php endif; ?>

    <!-- PROGRAM CARD -->
    <section class="program-section">
        <div class="program-card">
            <h3>Blog & Wacana</h3>
            <a href="<?= BASE_URL ?>/blog" class="btn-outline">Baca Artikel</a>
        </div>

        <div class="program-card">
            <h3>Bulletin Sastra</h3>
            <a href="<?= BASE_URL ?>/zine" class="btn-outline">Lihat Bulletin</a>
        </div>

        <div class="program-card">
            <h3>Agenda & Event</h3>
            <a href="<?= BASE_URL ?>/page/event" class="btn-outline">Lihat Event</a>
        </div>
    </section>

    <!-- Katalog Section -->
    <section class="program-section" style="padding-top: 0;">
        <div class="program-card">
            <h3>Katalog Komunitas</h3>
            <a href="<?= BASE_URL ?>/katalog" class="btn-outline">Lihat Katalog</a>
        </div>

        <div class="program-card">
            <h3>Jual Beli</h3>
            <a href="<?= BASE_URL ?>/page/jual-beli" class="btn-outline">Lihat Produk</a>
        </div>
    </section>

    <!-- KONSULTASI -->
    <section class="consult-section">
        <div class="consult-container">
            <div class="consult-left">
                <h2>Ingin Terlibat dalam Ekosistem Seni & Budaya?</h2>
                <p>Mentas membuka ruang bagi penulis, peneliti, komunitas, kurator, dan pelaku seni untuk berkontribusi dan berkolaborasi.</p>
                <a href="<?= BASE_URL ?>/page/contribute" class="btn-green">Mulai berkontribusi</a>
            </div>

            <div class="consult-right">
                <img src="<?= BASE_URL ?>/assets/images/mentas-putih.png" alt="Maskot" class="maskot-img">
            </div>
        </div>
    </section>
