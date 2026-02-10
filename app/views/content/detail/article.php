<?php // Article Detail Page - Split Layout with Sidebar ?>

<section class="hero blog-hero-detail">
    <div class="hero-content">
        <?php if (!empty($content['category_name'])): ?>
            <span class="btn-orange blog-detail-category"><?= $content['category_name']; ?></span>
        <?php endif; ?>
        
        <h1 class="blog-detail-title"><?= htmlspecialchars($content['title']); ?></h1>
        
        <p class="blog-detail-meta">
            <?php if (!empty($content['author_name'])): ?>
                ✍ <?= $content['author_name']; ?>
            <?php endif; ?>
            <?php if (!empty($content['published_at'])): ?>
                - 📅 <?= date('d M Y', strtotime($content['published_at'])); ?>
            <?php endif; ?>
        </p>
    </div>
</section>

<section class="program-section blog-section-detail">
    <div class="blog-layout">
        <!-- Main Content (Left) -->
        <article class="blog-main">
            <?php if (!empty($content['cover_image'])): ?>
                <img src="<?= BASE_URL ?>/<?= $content['cover_image']; ?>" alt="<?= htmlspecialchars($content['title']); ?>" class="blog-cover">
            <?php endif; ?>

            <!-- Contributor Section -->
            <div class="article-contributor">
                <div class="contributor-avatar">
                   <?php 
                        $avatar = !empty($content['author_avatar']) ? BASE_URL . '/' . $content['author_avatar'] : 'https://ui-avatars.com/api/?name=' . urlencode($content['author_name']) . '&background=random';
                    ?>
                    <img src="<?= $avatar ?>" alt="<?= htmlspecialchars($content['author_name']) ?>">
                </div>
                <div class="contributor-details">
                    <p class="contributor-by">
                        Oleh <a href="<?= BASE_URL ?>/author/<?= $content['author_id'] ?>"><?= htmlspecialchars($content['author_name']) ?></a>
                        <?php 
                            if (!empty($content['author_bio'])) {
                                $bioWords = explode(' ', $content['author_bio']);
                                $truncatedBio = implode(' ', array_slice($bioWords, 0, 20));
                                echo ', ' . htmlspecialchars($truncatedBio) . (count($bioWords) > 20 ? '...' : '');
                            }
                        ?>
                    </p>
                </div>
            </div>

            <div class="blog-body article-content">
                <?php
                    // Allow safe HTML tags for rich text content
                    $allowedTags = '<h1><h2><h3><h4><h5><h6><p><br><strong><b><em><i><u><s><strike><a><ul><ol><li><blockquote><pre><code><img><table><thead><tbody><tr><th><td><hr><span><div><figure><figcaption><iframe>';
                    $body = strip_tags($content['body'], $allowedTags);
                    
                    // Fix relative image paths to absolute BASE_URL
                    // Matches src="uploads/..." or src="/uploads/..." and prepends BASE_URL
                    $body = preg_replace('/src=["\'](?!http)(?:\/)?(uploads\/[^"\']+)["\']/', 'src="' . BASE_URL . '/$1"', $body);
                    
                    echo $body;
                ?>
            </div>

<!-- Author Social Media -->
            <?php 
                $socials = !empty($content['author_social']) ? json_decode($content['author_social'], true) : [];
                $hasSocial = !empty(array_filter($socials));
            ?>
            <?php if ($hasSocial): ?>
            <div class="author-social-links">
                <h4>Ikuti Penulis:</h4>
                <div class="social-icons">
                    <?php if (!empty($socials['website'])): ?>
                        <a href="<?= $socials['website'] ?>" target="_blank" class="social-website" title="Website">
                            <i class="fas fa-globe"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($socials['instagram'])): ?>
                        <a href="<?= $socials['instagram'] ?>" target="_blank" class="social-instagram" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($socials['facebook'])): ?>
                        <a href="<?= $socials['facebook'] ?>" target="_blank" class="social-facebook" title="Facebook">
                            <i class="fab fa-facebook"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($socials['twitter'])): ?>
                        <a href="<?= $socials['twitter'] ?>" target="_blank" class="social-twitter" title="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Share Article Section -->
            <div class="share-article-section">
                <h4>
                    <i class="fa-solid fa-share-nodes"></i> Bagikan Artikel Ini
                </h4>
                <div class="share-buttons">
                    <?php 
                        $articleUrl = BASE_URL . '/blog/' . $content['slug'];
                        $articleTitle = urlencode($content['title']);
                        $encodedUrl = urlencode($articleUrl);
                    ?>
                    
                    <!-- WhatsApp -->
                    <a href="https://wa.me/?text=<?= $articleTitle ?>%20<?= $encodedUrl ?>" 
                       target="_blank" 
                       class="share-btn share-whatsapp">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                    
                    <!-- Facebook -->
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $encodedUrl ?>" 
                       target="_blank"
                       class="share-btn share-facebook">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                    
                    <!-- Twitter/X -->
                    <a href="https://twitter.com/intent/tweet?text=<?= $articleTitle ?>&url=<?= $encodedUrl ?>" 
                       target="_blank"
                       class="share-btn share-twitter">
                        <i class="fab fa-x-twitter"></i> Twitter
                    </a>
                    
                    <!-- Copy Link -->
                    <button onclick="copyArticleLink('<?= $articleUrl ?>')" 
                            class="share-btn share-copy">
                        <i class="fa-solid fa-link"></i> <span id="copy-text">Salin Link</span>
                    </button>
                </div>
            </div>

            <script>
            function copyArticleLink(url) {
                navigator.clipboard.writeText(url).then(function() {
                    var copyText = document.getElementById('copy-text');
                    var originalText = copyText.innerText;
                    copyText.innerText = 'Tersalin!';
                    setTimeout(function() {
                        copyText.innerText = originalText;
                    }, 2000);
                }).catch(function(err) {
                    alert('Gagal menyalin link');
                });
            }
            </script>

            <!-- Comments Section -->
            <div class="comments-section">
                <h3>Komentar (<?= count($comments ?? []) ?>)</h3>
                
                <!-- Comment List -->
                <div class="comments-list">
                    <?php if (!empty($comments)): ?>
                        <?php foreach ($comments as $comment): ?>
                            <div class="comment-item">
                                <div class="comment-header">
                                    <strong><?= htmlspecialchars($comment['name']) ?></strong>
                                    <small><?= date('d M Y H:i', strtotime($comment['created_at'])) ?></small>
                                </div>
                                <div class="comment-body">
                                    <?= nl2br(htmlspecialchars($comment['body'])) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Belum ada komentar. Jadilah yang pertama berkomentar!</p>
                    <?php endif; ?>
                </div>

                <!-- Comment Form -->
                <div class="comment-form-wrapper">
                    <h4>Tinggalkan Komentar</h4>
                    <form action="<?= BASE_URL ?>/comment/store" method="POST" class="comment-form">
                        <input type="hidden" name="post_id" value="<?= $content['id'] ?>">
                        
                        <?php if (!isset($_SESSION['user_id'])): ?>
                            <div class="form-group">
                                <label for="name">Nama</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="body">Komentar</label>
                            <textarea name="body" id="body" rows="4" class="form-control" required></textarea>
                        </div>

                        <button type="submit" class="btn-primary">Kirim Komentar</button>
                    </form>
                </div>
            </div>

            <div class="blog-footer">
                <a href="<?= BASE_URL ?>/blog" class="btn-outline">← Kembali ke Blog</a>
            </div>
        </article>

        <!-- Sidebar (Right) -->
        <aside class="blog-sidebar">
            <!-- Categories Widget -->
            <?php if (!empty($categoriesWithCount)): ?>
            <div class="sidebar-widget">
                <h3 class="sidebar-title">Kategori</h3>
                <ul class="sidebar-categories">
                    <?php foreach ($categoriesWithCount as $cat): ?>
                        <li>
                            <a href="<?= BASE_URL ?>/blog/<?= $cat['slug']; ?>">
                                <?= $cat['name']; ?>
                                <span class="category-count"><?= $cat['post_count']; ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <!-- Recent Posts Widget -->
            <?php if (!empty($recentPosts)): ?>
            <div class="sidebar-widget">
                <h3 class="sidebar-title">Artikel Terbaru</h3>
                <ul class="sidebar-posts">
                    <?php foreach ($recentPosts as $post): ?>
                        <li class="sidebar-post-item">
                            <?php if (!empty($post['cover_image'])): ?>
                                <a href="<?= BASE_URL ?>/blog/<?= $post['slug']; ?>" class="sidebar-post-thumb">
                                    <img src="<?= BASE_URL ?>/<?= $post['cover_image']; ?>" alt="<?= htmlspecialchars($post['title']); ?>">
                                </a>
                            <?php endif; ?>
                            <div class="sidebar-post-info">
                                <a href="<?= BASE_URL ?>/blog/<?= $post['slug']; ?>" class="sidebar-post-title">
                                    <?= htmlspecialchars($post['title']); ?>
                                </a>
                                <span class="sidebar-post-date">
                                    <?= date('d M Y', strtotime($post['published_at'] ?? $post['created_at'])); ?>
                                </span>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <!-- Tags Widget -->
            <?php if (!empty($postTags)): ?>
            <div class="sidebar-widget">
                <h3 class="sidebar-title">Tags</h3>
                <div class="sidebar-tags">
                    <?php foreach ($postTags as $tag): ?>
                        <a href="<?= BASE_URL ?>/blog?tag=<?= $tag['slug']; ?>" class="tag-item">
                            <?= $tag['name']; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </aside>
    </div>
</section>