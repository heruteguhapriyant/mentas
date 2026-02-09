<?php // Zine Index Page - Updated dengan Pagination & Search ?>

<section class="hero zine-hero">
    <div class="hero-content">
        <?php if (!empty($searchQuery)): ?>
            <h1>Hasil Pencarian</h1>
            <p>Hasil pencarian: "<?= htmlspecialchars($searchQuery) ?>"</p>
        <?php else: ?>
            <h1>Bulletin</h1>
            <p>Kumpulan publikasi sastra dari komunitas Mentas dalam format PDF</p>
        <?php endif; ?>
    </div>
</section>

<!-- Category Tabs & Search -->
<section class="zine-category-tabs">
    <div class="zine-filter-wrapper">
        <!-- Search Bar - Centered above filters -->
        <div class="zine-search-container">
            <form class="zine-search-form" id="zine-search-form" action="<?= BASE_URL ?>/bulletin" method="GET">
                <div class="search-input-wrapper">
                    <input type="text" 
                           name="q" 
                           id="zine-search-input"
                           class="search-input" 
                           placeholder="Cari bulletin..." 
                           value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Category Tabs - Centered below search -->
        <div class="category-tabs-container">
            <a href="<?= BASE_URL ?>/bulletin" class="category-tab <?= $activeCategory === null && empty($_GET['q']) ? 'active' : '' ?>">
                Semua
            </a>
            <?php foreach ($categories as $cat): ?>
                <a href="<?= BASE_URL ?>/bulletin?category=<?= $cat['slug'] ?>" class="category-tab <?= $activeCategory === $cat['slug'] ? 'active' : '' ?>">
                    <?= htmlspecialchars($cat['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="program-section zine-section">
    <?php if (empty($zines)): ?>
        <p class="zine-empty">
            <?php if ($activeCategory): 
                $activeCatName = 'Kategori';
                foreach($categories as $cat) { 
                    if($cat['slug'] === $activeCategory) {
                        $activeCatName = $cat['name']; 
                    }
                }
            ?>
                Belum ada bulletin untuk kategori <?= htmlspecialchars($activeCatName) ?>.
            <?php else: ?>
                Belum ada bulletin.
            <?php endif; ?>
        </p>
    <?php else: ?>
        <?php foreach ($zines as $zine): ?>
            <div class="program-card zine-card">
                <?php if (!empty($zine['cover_image'])): ?>
                    <div class="zine-card-cover">
                        <img src="<?= BASE_URL ?>/<?= $zine['cover_image'] ?>" alt="<?= htmlspecialchars($zine['title']) ?>">
                        
                        <?php if (!empty($zine['issue_number'])): ?>
                            <span class="zine-issue-badge">Edisi #<?= $zine['issue_number'] ?></span>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="zine-card-cover zine-cover-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 384 512" fill="rgba(255,255,255,0.9)">
                            <path d="M64 0C28.7 0 0 28.7 0 64V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V160H256c-17.7 0-32-14.3-32-32V0H64zM256 0V128H384L256 0zM112 256H272c8.8 0 16 7.2 16 16s-7.2 16-16 16H112c-8.8 0-16-7.2-16-16s7.2-16 16-16zm0 64H272c8.8 0 16 7.2 16 16s-7.2 16-16 16H112c-8.8 0-16-7.2-16-16s7.2-16 16-16zm0 64H272c8.8 0 16 7.2 16 16s-7.2 16-16 16H112c-8.8 0-16-7.2-16-16s7.2-16 16-16z"/>
                        </svg>
                        <span style="color: rgba(255,255,255,0.9); font-weight: 600; font-size: 14px; margin-top: 8px;">PDF</span>
                        
                        <?php if (!empty($zine['issue_number'])): ?>
                            <span class="zine-issue-badge">Edisi #<?= $zine['issue_number'] ?></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <div class="zine-card-content">
                    <span class="zine-card-category">
                        <?= htmlspecialchars($zine['category_name'] ?? 'General') ?>
                    </span>
                    
                    <h3><?= htmlspecialchars($zine['title']) ?></h3>

                    <?php if (!empty($zine['excerpt']) || !empty($zine['content'])): ?>
                        <p class="zine-card-excerpt">
                            <?php 
                            $excerpt = $zine['excerpt'] ?? '';
                            if (empty($excerpt) && !empty($zine['content'])) {
                                // Generate excerpt from content if excerpt is empty
                                $content = strip_tags($zine['content']);
                                $excerpt = substr($content, 0, 120);
                            } else {
                                $excerpt = substr($excerpt, 0, 120);
                            }
                            echo htmlspecialchars($excerpt);
                            ?>...
                        </p>
                    <?php endif; ?>

                    <!-- Meta Info -->
                    <div class="zine-meta">
                        <?php if (!empty($zine['published_at']) || !empty($zine['created_at'])): ?>
                            <span class="zine-date">
                                <i class="fa-regular fa-calendar"></i>
                                <?= date('d M Y', strtotime($zine['published_at'] ?? $zine['created_at'])) ?>
                            </span>
                        <?php endif; ?>
                        
                        <?php if (!empty($zine['downloads']) && $zine['downloads'] > 0): ?>
                            <span class="zine-downloads">
                                <i class="fa-solid fa-download"></i>
                                <?= number_format($zine['downloads']) ?> downloads
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Action Buttons -->
                    <div class="zine-buttons">
                        <?php if (!empty($zine['pdf_file'])): ?>
                            <a href="<?= BASE_URL ?>/bulletin/<?= $zine['slug'] ?>" 
                               class="btn-outline btn-secondary">
                                <i class="fa-solid fa-eye"></i> Detail
                            </a>
                            <a href="<?= BASE_URL ?>/<?= $zine['pdf_file'] ?>" 
                               target="_blank"
                               class="btn-outline btn-primary"
                               download>
                                <i class="fas fa-file-pdf"></i> Download
                            </a>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/bulletin/<?= $zine['slug'] ?>" 
                               class="btn-outline btn-primary">
                                <i class="fa-solid fa-book-open"></i> Baca Selengkapnya
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>

<?php 
// Tampilkan pagination jika ada data
if (!empty($pagination) && !empty($zines)): 
    echo renderPagination($pagination);
endif; 
?>

<script>
// Optional: Smooth scroll to top when clicking pagination
document.addEventListener('DOMContentLoaded', function() {
    const paginationLinks = document.querySelectorAll('.pagination a');
    
    paginationLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Scroll to section top
            const section = document.querySelector('.zine-section');
            if (section) {
                setTimeout(() => {
                    section.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 100);
            }
        });
    });
});
</script>