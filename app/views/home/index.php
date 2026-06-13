    <section class="hero">
        <div class="hero-content">
            <h1>Art & Culture Enthusiast, where stories unfold</h1>
            <p>Media, Wacana, dan Kolaborasi Seni-Budaya</p>
            <a href="<?= BASE_URL ?>/blog" class="btn-orange">Jelajahi</a>
        </div>
    </section>
 <!-- Pentas Terbaru Section -->
    <?php if (!empty($latestEvents)): ?>
    <section class="featured-section" style="padding-top: 2rem;">
        <div class="section-header" style="display:flex;justify-content:space-between;align-items:center;max-width:1200px;margin:0 auto 1rem;padding:0 1rem;">
            <h2 style="margin:0;font-size:1.5rem;">Pentas</h2>
            <a href="<?= BASE_URL ?>/pentas" class="featured-btn-all" style="font-size:14px;">
                <i class="fas fa-arrow-right"></i> Lihat Semua
            </a>
        </div>
        <div class="home-grid-3" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.5rem;max-width:1200px;margin:0 auto;padding:0 1rem;">
            <?php foreach ($latestEvents as $event): ?>
            <a href="<?= BASE_URL ?>/pentas/<?= $event['slug'] ?? $event['id'] ?>" target="_blank" class="home-card" style="text-decoration:none;color:inherit;border-radius:12px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,0.08);transition:transform 0.3s,box-shadow 0.3s;display:block;background:#fff;">
                <?php if (!empty($event['cover_image'])): ?>
                <div style="height:200px;overflow:hidden;">
                    <img src="<?= BASE_URL ?>/<?= $event['cover_image'] ?>" alt="<?= htmlspecialchars($event['title']) ?>" style="width:100%;height:100%;object-fit:cover;">
                </div>
                <?php endif; ?>
                <div style="padding:1rem;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.5rem;">
                        <span style="font-size:12px;color:#6c5ce7;font-weight:600;">PENTAS</span>
                        <?php if (!empty($event['event_date'])): ?>
                        <span style="font-size:12px;color:#999;"><i class="fa-regular fa-calendar"></i> <?= date('d M Y', strtotime($event['event_date'])) ?></span>
                        <?php endif; ?>
                    </div>
                    <h3 style="margin:0;font-size:1.1rem;line-height:1.4;"><?= htmlspecialchars($event['title']) ?></h3>
                    <?php if (!empty($event['venue'])): ?>
                    <p style="margin:0.5rem 0 0;font-size:13px;color:#666;"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($event['venue']) ?></p>
                    <?php endif; ?>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- Featured Blog Section -->
    <?php if (!empty($featuredPosts)): ?>
    <section class="featured-section">
        <div class="section-header" style="display:flex;justify-content:space-between;align-items:center;max-width:1200px;margin:0 auto 1rem;padding:0 1rem;">
            <h2 style="margin:0;font-size:1.5rem;">Blog</h2>
            <a href="<?= BASE_URL ?>/blog" class="featured-btn-all" style="font-size:14px;">
                <i class="fas fa-arrow-right"></i> Lihat Semua
            </a>
        </div>
        <div class="featured-grid">
            
            <!-- Main Feature (First Post) -->
            <?php if (isset($featuredPosts[0])): $main = $featuredPosts[0]; ?>
            <a href="<?= BASE_URL ?>/blog/<?= $main['slug'] ?>" target="_blank" class="featured-card featured-main">
                <img src="<?= BASE_URL ?>/<?= $main['cover_image'] ?>" alt="<?= htmlspecialchars($main['title']) ?>">
                <div class="featured-overlay">
                    <div class="featured-meta">
                    </div>
                    <h2><?= htmlspecialchars($main['title']) ?></h2>
                </div>
            </a>
            <?php endif; ?>

            <!-- Sub Features (Next 2 Posts) -->
            <div class="featured-sub">
                <?php for($i=1; $i<3; $i++): if(isset($featuredPosts[$i])): $post = $featuredPosts[$i]; ?>
                <a href="<?= BASE_URL ?>/blog/<?= $post['slug'] ?>" target="_blank" class="featured-card featured-item">
                    <img src="<?= BASE_URL ?>/<?= $post['cover_image'] ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                    <div class="featured-overlay">
                        <div class="featured-meta-small">
                        </div>
                        <h3><?= htmlspecialchars($post['title']) ?></h3>
                    </div>
                </a>
                <?php endif; endfor; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- Featured Blog Editorial Section -->
    <?php if (!empty($editorialPosts)): ?>
    <section class="featured-section">
        <div class="section-header" style="display:flex;justify-content:space-between;align-items:center;max-width:1200px;margin:0 auto 1rem;padding:0 1rem;">
            <h2 style="margin:0;font-size:1.5rem;">Editorial</h2>
            <a href="<?= BASE_URL ?>/blog/editorial" class="featured-btn-all" style="font-size:14px;">
                <i class="fas fa-arrow-right"></i> Lihat Semua
            </a>
        </div>
        <div class="featured-grid">
    
            <!-- Main Editorial (Post Pertama) -->
            <?php if (isset($editorialPosts[0])): $main = $editorialPosts[0]; ?>
            <a href="<?= BASE_URL ?>/blog/<?= $main['slug'] ?>" target="_blank" class="featured-card featured-main">
                <?php if (!empty($main['cover_image'])): ?>
                    <img src="<?= BASE_URL ?>/<?= $main['cover_image'] ?>" alt="<?= htmlspecialchars($main['title']) ?>">
                <?php else: ?>
                    <div style="width:100%;height:100%;background:#1a1a2e;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-pen-nib" style="font-size:3rem;color:#666;"></i>
                    </div>
                <?php endif; ?>
                <div class="featured-overlay">
                    <div class="featured-meta">
                        <span style="font-size:11px;background:rgba(255,255,255,0.2);padding:2px 8px;border-radius:20px;color:#fff;text-transform:uppercase;letter-spacing:1px;">Editorial</span>
                    </div>
                    <h2><?= htmlspecialchars($main['title']) ?></h2>
                </div>
            </a>
            <?php endif; ?>
    
            <!-- Sub Editorial (2 Post Berikutnya) -->
            <div class="featured-sub">
                <?php for ($i = 1; $i < 3; $i++): if (isset($editorialPosts[$i])): $post = $editorialPosts[$i]; ?>
                <a href="<?= BASE_URL ?>/blog/<?= $post['slug'] ?>" target="_blank" class="featured-card featured-item">
                    <?php if (!empty($post['cover_image'])): ?>
                        <img src="<?= BASE_URL ?>/<?= $post['cover_image'] ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                    <?php else: ?>
                        <div style="width:100%;height:100%;background:#1a1a2e;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-pen-nib" style="font-size:2rem;color:#666;"></i>
                        </div>
                    <?php endif; ?>
                    <div class="featured-overlay">
                        <div class="featured-meta-small">
                            <span style="font-size:10px;background:rgba(255,255,255,0.2);padding:2px 8px;border-radius:20px;color:#fff;text-transform:uppercase;letter-spacing:1px;">Editorial</span>
                        </div>
                        <h3><?= htmlspecialchars($post['title']) ?></h3>
                    </div>
                </a>
                <?php endif; endfor; ?>
            </div>
    
        </div>
    </section>
    <?php endif; ?>
    
    

    <!-- Bulletin Terbaru Section -->
    <?php if (!empty($latestBulletins)): ?>
    <section class="featured-section" style="padding-top: 2rem;">
        <div class="section-header" style="display:flex;justify-content:space-between;align-items:center;max-width:1200px;margin:0 auto 1rem;padding:0 1rem;">
            <h2 style="margin:0;font-size:1.5rem;">Katalog</h2>
            <a href="<?= BASE_URL ?>/bulletin" class="featured-btn-all" style="font-size:14px;">
                <i class="fas fa-arrow-right"></i> Lihat Semua
            </a>
        </div>
        <div class="home-grid-3" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.5rem;max-width:1200px;margin:0 auto;padding:0 1rem;">
            <?php foreach ($latestBulletins as $bulletin): ?>
            <a href="<?= BASE_URL ?>/bulletin/<?= $bulletin['slug'] ?? $bulletin['id'] ?>" target="_blank" class="home-card" style="text-decoration:none;color:inherit;border-radius:12px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,0.08);transition:transform 0.3s,box-shadow 0.3s;display:block;background:#fff;">
                <?php if (!empty($bulletin['cover_image'])): ?>
                <div style="height:200px;overflow:hidden;">
                    <img src="<?= BASE_URL ?>/<?= $bulletin['cover_image'] ?>" alt="<?= htmlspecialchars($bulletin['title']) ?>" style="width:100%;height:100%;object-fit:cover;">
                </div>
                <?php endif; ?>
                <div style="padding:1rem;">
                    <span style="font-size:12px;color:#e63946;font-weight:600;text-transform:uppercase;"><?= htmlspecialchars($bulletin['category_name'] ?? 'Bulletin') ?></span>
                    <h3 style="margin:0.5rem 0;font-size:1.1rem;line-height:1.4;"><?= htmlspecialchars($bulletin['title']) ?></h3>
                    <span style="font-size:12px;color:#999;"><?= date('d M Y', strtotime($bulletin['created_at'])) ?></span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Merch Terbaru Section -->
    <?php if (!empty($latestProducts)): ?>
    <section class="featured-section" style="padding-top: 2rem;">
        <div class="section-header" style="display:flex;justify-content:space-between;align-items:center;max-width:1200px;margin:0 auto 1rem;padding:0 1rem;">
            <h2 style="margin:0;font-size:1.5rem;">Produk</h2>
            <a href="<?= BASE_URL ?>/merch" class="featured-btn-all" style="font-size:14px;">
                <i class="fas fa-arrow-right"></i> Lihat Semua
            </a>
        </div>
        <div class="home-grid-4" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:1.5rem;max-width:1200px;margin:0 auto;padding:0 1rem;">
            <?php foreach ($latestProducts as $product): ?>
            <a href="<?= BASE_URL ?>/merch/<?= $product['slug'] ?? $product['id'] ?>" target="_blank" class="home-card" style="text-decoration:none;color:inherit;border-radius:12px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,0.08);transition:transform 0.3s,box-shadow 0.3s;display:block;background:#fff;">
                <?php if (!empty($product['cover_image'])): ?>
                <div style="height:200px;overflow:hidden;">
                    <img src="<?= BASE_URL ?>/<?= $product['cover_image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>" style="width:100%;height:100%;object-fit:cover;">
                </div>
                <?php endif; ?>
                <div style="padding:1rem;">
                    <span style="font-size:12px;color:#2a9d8f;font-weight:600;"><?= htmlspecialchars($product['category_name'] ?? 'Merchandise') ?></span>
                    <h3 style="margin:0.5rem 0;font-size:1rem;"><?= htmlspecialchars($product['name']) ?></h3>
                    <span style="font-size:14px;font-weight:700;color:#e63946;">Rp <?= number_format($product['price'], 0, ',', '.') ?></span>
                </div>
            </a>
            <?php endforeach; ?>
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
            <h3>Bulletin</h3>
            <a href="<?= BASE_URL ?>/bulletin" class="btn-outline">Lihat Bulletin</a>
        </div>

        <div class="program-card">
            <h3>Pentas</h3>
            <a href="<?= BASE_URL ?>/pentas" class="btn-outline">Lihat Pentas</a>
        </div>
    </section>

    <!-- Katalog Section -->
    <section class="program-section" style="padding-top: 0;">
        <div class="program-card">
            <h3>Kolaborasi</h3>
            <a href="<?= BASE_URL ?>/kolaborasi" class="btn-outline">Lihat Kolaborasi</a>
        </div>

        <div class="program-card">
            <h3>Merch</h3>
            <a href="<?= BASE_URL ?>/merch" class="btn-outline">Lihat Produk</a>
        </div>
    </section>

    <!-- KONSULTASI -->
    <section class="consult-section">
        <div class="consult-container">
            <div class="consult-left">
                <h2>Ingin Terlibat dalam Kolaborasi Seni & Budaya?</h2>
                <a href="<?= BASE_URL ?>/page/contribute" class="btn-green">Mulai berkontribusi</a>
            </div>

            <div class="consult-right">
                <img src="<?= BASE_URL ?>/assets/images/mentas-putih.png" alt="Maskot" class="maskot-img">
            </div>
        </div>
    </section>

<style>
.home-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}
</style>
