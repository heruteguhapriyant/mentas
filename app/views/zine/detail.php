<?php // Zine Detail Page ?>

<section class="hero zine-hero">
    <div class="hero-content">
        <a href="<?= BASE_URL ?>/zine" class="zine-back-link">
            ← Kembali ke Bulletin
        </a>

        <span class="zine-detail-category"><?= htmlspecialchars($zine['category_name'] ?? 'Bulletin') ?></span>

        <h1 class="zine-title">
            <?= htmlspecialchars($zine['title']) ?>
        </h1>

        <?php if (!empty($zine['excerpt'])): ?>
            <p class="zine-excerpt">
                <?= htmlspecialchars(generateExcerpt($zine['excerpt'], 200)) ?>
            </p>
        <?php endif; ?>
    </div>
</section>

<section class="program-section zine-section-detail">
    <article class="zine-article">

        <?php if (!empty($zine['cover_image'])): ?>
            <div class="cover-wrapper" style="position:relative;">
                <img 
                    src="<?= BASE_URL ?>/<?= $zine['cover_image'] ?>" 
                    alt="<?= htmlspecialchars($zine['title']) ?>" 
                    class="zine-cover"
                >
                <div class="cover-overlay" onclick="openLightbox('<?= BASE_URL ?>/<?= $zine['cover_image'] ?>')" style="position:absolute;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0);display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background 0.3s;">
                    <i class="fas fa-eye" style="color:#fff;font-size:2rem;opacity:0;transition:opacity 0.3s;"></i>
                </div>
            </div>
        <?php endif; ?>

        <!-- Contributor Info -->
        <div class="article-contributor" style="margin-bottom: 30px;">
            <div class="contributor-avatar">
               <?php 
                    $authorName = $zine['author_name'] ?? 'Admin';
                    $avatar = !empty($zine['author_avatar']) ? BASE_URL . '/' . $zine['author_avatar'] : 'https://ui-avatars.com/api/?name=' . urlencode($authorName) . '&background=random';
                ?>
                <img src="<?= $avatar ?>" alt="<?= htmlspecialchars($authorName) ?>">
            </div>
            <div class="contributor-details">
                <p class="contributor-by">
                    Oleh <a href="<?= BASE_URL ?>/author/<?= $zine['author_id'] ?? '#' ?>"><?= htmlspecialchars($authorName) ?></a>
                </p>
                <?php if (!empty($zine['author_bio'])): ?>
                    <p style="font-size: 0.9rem; color: #666; margin-top: 2px;">
                        <?= htmlspecialchars(substr($zine['author_bio'], 0, 100)) . (strlen($zine['author_bio']) > 100 ? '...' : '') ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($zine['pdf_link'])): ?>
            <!-- PDF Link -->
            <div class="zine-pdf-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 384 512" fill="#d52c2c">
                    <path d="M64 0C28.7 0 0 28.7 0 64V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V160H256c-17.7 0-32-14.3-32-32V0H64zM256 0V128H384L256 0zM112 256H272c8.8 0 16 7.2 16 16s-7.2 16-16 16H112c-8.8 0-16-7.2-16-16s7.2-16 16-16zm0 64H272c8.8 0 16 7.2 16 16s-7.2 16-16 16H112c-8.8 0-16-7.2-16-16s7.2-16 16-16zm0 64H272c8.8 0 16 7.2 16 16s-7.2 16-16 16H112c-8.8 0-16-7.2-16-16s7.2-16 16-16z"/>
                </svg>
                <p>PDF</p>
                <p>Klik tombol di bawah untuk membaca atau mengunduh PDF</p>
                <a href="<?= htmlspecialchars($zine['pdf_link']) ?>" target="_blank" class="btn-primary">
                    <i class="fas fa-external-link-alt"></i> Baca / Download PDF
                </a>
            </div>
        <?php elseif (!empty($zine['content'])): ?>
            <!-- Legacy text content - cleaned from WordPress blocks -->
            <div class="zine-content-body">
                <?= cleanWordPressContent($zine['content']) ?>
            </div>
        <?php endif; ?>

        <div class="zine-footer">
            <a href="<?= BASE_URL ?>/zine" class="btn-outline">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>

    </article>
</section>

<!-- Lightbox -->
<div id="lightbox" onclick="closeLightbox()" style="display:none;position:fixed;z-index:9999;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.9);cursor:zoom-out;align-items:center;justify-content:center;">
    <img id="lightbox-img" src="" style="max-width:90%;max-height:90vh;object-fit:contain;border-radius:8px;box-shadow:0 0 30px rgba(0,0,0,0.5);">
    <span onclick="closeLightbox()" style="position:fixed;top:20px;right:30px;font-size:2rem;color:#fff;cursor:pointer;line-height:1;">✕</span>
</div>

<style>
.cover-wrapper:hover .cover-overlay { background: rgba(0,0,0,0.35) !important; }
.cover-wrapper:hover .cover-overlay i { opacity: 1 !important; }
</style>

<script>
function openLightbox(src) {
    const lb = document.getElementById('lightbox');
    document.getElementById('lightbox-img').src = src;
    lb.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    document.getElementById('lightbox').style.display = 'none';
    document.body.style.overflow = '';
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeLightbox();
});
</script>