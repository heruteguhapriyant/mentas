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

            <div class="blog-body">
                <?= nl2br(htmlspecialchars($content['body'])); ?>
            </div>

            <!-- Author Social Media -->
            <?php 
                $socials = !empty($content['author_social']) ? json_decode($content['author_social'], true) : [];
                $hasSocial = !empty(array_filter($socials));
            ?>
            <?php if ($hasSocial): ?>
            <div class="author-social-links" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
                <h4 style="font-size: 16px; margin-bottom: 15px;">Ikuti Penulis:</h4>
                <div style="display: flex; gap: 20px;">
                    <?php if (!empty($socials['website'])): ?>
                        <a href="<?= $socials['website'] ?>" target="_blank" style="text-decoration: none; color: #333; font-size: 24px;" title="Website">
                            <i class="fas fa-globe"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($socials['instagram'])): ?>
                        <a href="<?= $socials['instagram'] ?>" target="_blank" style="text-decoration: none; color: #E1306C; font-size: 24px;" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($socials['facebook'])): ?>
                        <a href="<?= $socials['facebook'] ?>" target="_blank" style="text-decoration: none; color: #1877F2; font-size: 24px;" title="Facebook">
                            <i class="fab fa-facebook"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($socials['twitter'])): ?>
                        <a href="<?= $socials['twitter'] ?>" target="_blank" style="text-decoration: none; color: #1DA1F2; font-size: 24px;" title="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Comments Section -->
            <div class="comments-section" style="margin-top: 50px;">
                <h3 style="margin-bottom: 20px;">Komentar (<?= count($comments ?? []) ?>)</h3>
                
                <!-- Comment List -->
                <div class="comments-list" style="margin-bottom: 40px;">
                    <?php if (!empty($comments)): ?>
                        <?php foreach ($comments as $comment): ?>
                            <div class="comment-item" style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
                                <div class="comment-header" style="display: flex; align-items: center; margin-bottom: 10px;">
                                    <strong style="margin-right: 10px;"><?= htmlspecialchars($comment['name']) ?></strong>
                                    <small style="color: #666;"><?= date('d M Y H:i', strtotime($comment['created_at'])) ?></small>
                                </div>
                                <div class="comment-body">
                                    <?= nl2br(htmlspecialchars($comment['body'])) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="color: #666;">Belum ada komentar. Jadilah yang pertama berkomentar!</p>
                    <?php endif; ?>
                </div>

                <!-- Comment Form -->
                <div class="comment-form-wrapper">
                    <h4 style="margin-bottom: 15px;">Tinggalkan Komentar</h4>
                    <form action="<?= BASE_URL ?>/comment/store" method="POST" class="comment-form">
                        <input type="hidden" name="post_id" value="<?= $content['id'] ?>">
                        
                        <?php if (!isset($_SESSION['user_id'])): ?>
                            <div class="form-group" style="margin-bottom: 15px;">
                                <label for="name" style="display: block; margin-bottom: 5px;">Nama</label>
                                <input type="text" name="name" id="name" class="form-control" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                            </div>
                            <div class="form-group" style="margin-bottom: 15px;">
                                <label for="email" style="display: block; margin-bottom: 5px;">Email</label>
                                <input type="email" name="email" id="email" class="form-control" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                            </div>
                        <?php endif; ?>

                        <div class="form-group" style="margin-bottom: 15px;">
                            <label for="body" style="display: block; margin-bottom: 5px;">Komentar</label>
                            <textarea name="body" id="body" rows="4" class="form-control" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;"></textarea>
                        </div>

                        <button type="submit" class="btn-primary" style="background: #e63946; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">Kirim Komentar</button>
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