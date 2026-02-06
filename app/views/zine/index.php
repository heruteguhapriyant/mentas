<?php // Zine Index Page - Updated dengan Pagination ?>

<section class="hero zine-hero">
    <div class="hero-content">
        <h1>Bulletin</h1>
        <p>Kumpulan publikasi sastra dari komunitas Mentas dalam format PDF</p>
    </div>
</section>

<!-- Category Tabs -->
<section class="zine-category-tabs">
    <div class="category-tabs-container">
        <a href="<?= BASE_URL ?>/bulletin" class="category-tab <?= $activeCategory === null ? 'active' : '' ?>">
            Semua
        </a>
        <?php foreach ($categories as $cat): ?>
            <a href="<?= BASE_URL ?>/bulletin?category=<?= $cat['slug'] ?>" class="category-tab <?= $activeCategory === $cat['slug'] ? 'active' : '' ?>">
                <?= htmlspecialchars($cat['name']) ?>
            </a>
        <?php endforeach; ?>
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
                        <i class="fa-solid fa-file-pdf" style="font-size: 48px; color: rgba(255,255,255,0.8);"></i>
                        
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
                                <i class="fa-solid fa-file-pdf"></i> Download PDF
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

<style>
/* Zine Cover Placeholder */
.zine-cover-placeholder {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

/* Issue Badge */
.zine-issue-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(0, 0, 0, 0.8);
    color: #fff;
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
    z-index: 2;
}

/* Zine Meta Info */
.zine-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin: 10px 0;
    padding-top: 10px;
    border-top: 1px solid #e0e0e0;
    font-size: 0.85rem;
    color: #666;
}

.zine-meta span {
    display: flex;
    align-items: center;
    gap: 5px;
}

.zine-meta i {
    font-size: 0.9rem;
}

/* Zine Buttons */
.zine-buttons {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}

.btn-outline {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px 16px;
    border: 2px solid;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s;
}

.btn-primary {
    border-color: #667eea;
    color: #667eea;
}

.btn-primary:hover {
    background: #667eea;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-secondary {
    border-color: #6c757d;
    color: #6c757d;
}

.btn-secondary:hover {
    background: #6c757d;
    color: #fff;
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 768px) {
    .zine-buttons {
        flex-direction: column;
    }
    
    .btn-outline {
        width: 100%;
    }
    
    .zine-meta {
        font-size: 0.8rem;
        gap: 0.75rem;
    }
}

/* Empty State */
.zine-empty {
    text-align: center;
    padding: 60px 20px;
    color: #666;
    font-size: 1.1rem;
}

/* Loading State (optional) */
.zine-section.loading {
    opacity: 0.6;
    pointer-events: none;
}

/* Smooth transitions */
.program-card.zine-card {
    transition: all 0.3s ease;
}

.program-card.zine-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

.zine-card-cover img {
    transition: transform 0.3s ease;
}

.program-card.zine-card:hover .zine-card-cover img {
    transform: scale(1.05);
}
</style>

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