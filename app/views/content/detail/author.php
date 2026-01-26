<?php // Author Detail Page - Redesigned (Koalisi Seni Style) ?>
<div class="author-page-container">
    <div class="author-layout">
        <!-- LEFT COLUMN: Contact & Info -->
        <aside class="author-sidebar">
            <h1 class="author-name-sidebar"><?= htmlspecialchars($content['name']) ?></h1>
            <p class="author-location"><?= htmlspecialchars($content['address'] ?? 'Indonesia') ?></p>
            
            <div class="author-divider"></div>

            <div class="author-contact-info">
                <?php if (!empty($content['address'])): ?>
                    <div class="contact-item">
                        <?= nl2br(htmlspecialchars($content['address'])) ?>
                    </div>
                <?php endif; ?>

                <div class="contact-item">
                    <span class="contact-label">alamat email:</span>
                    <a href="mailto:<?= htmlspecialchars($content['email']) ?>" class="contact-link"><?= htmlspecialchars($content['email']) ?></a>
                </div>

                <?php 
                    $socials = json_decode($content['social_media'] ?? '[]', true);
                    // Extract website if exists in socials or separate field (assuming socials for now)
                    $website = $socials['website'] ?? null;
                    unset($socials['website']); 
                ?>

                <?php if ($website): ?>
                <div class="contact-item">
                    <span class="contact-label">website / blog url:</span>
                    <a href="<?= htmlspecialchars($website) ?>" target="_blank" class="contact-link"><?= htmlspecialchars($website) ?></a>
                </div>
                <?php endif; ?>

                <?php if (!empty($socials)): ?>
                    <?php foreach ($socials as $platform => $handle): ?>
                        <?php if(!empty($handle)): ?>
                        <div class="contact-item">
                            <span class="contact-label"><?= htmlspecialchars($platform) ?>:</span>
                            <a href="#" class="contact-link link-red"><?= htmlspecialchars($handle) ?></a> <!-- Add real URL logic if needed -->
                        </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </aside>

        <!-- RIGHT COLUMN: Image, Bio, Works -->
        <main class="author-main-content">
            <!-- Big Landscape Image -->
            <div class="author-cover-image">
                 <?php 
                    // Use a different bigger image if available, or fall back to avatar but styled differently
                    // Ideally this should be a 'cover_photo' but we use avatar for now, styled as landscape/cover
                    $image = !empty($content['avatar']) ? BASE_URL . '/' . $content['avatar'] : 'https://ui-avatars.com/api/?name=' . urlencode($content['name']) . '&background=random&size=600';
                ?>
                <img src="<?= $image ?>" alt="<?= htmlspecialchars($content['name']) ?>">
            </div>

            <!-- Bio Text -->
            <div class="author-bio-text">
                <p><strong><?= htmlspecialchars($content['name']) ?></strong> <?= nl2br(htmlspecialchars($content['bio'])) ?></p>
            </div>

            <!-- Articles Grid -->
            <div class="author-works-section">
                <!-- <h3 class="works-title">Karya</h3> -->
                
                <?php if (empty($posts)): ?>
                    <p class="text-muted">Belum ada artikel yang ditulis.</p>
                <?php else: ?>
                    <div class="works-grid">
                    <?php foreach ($posts as $post): ?>
                        <div class="work-card">
                            <?php if (!empty($post['cover_image'])): ?>
                                <a href="<?= BASE_URL ?>/blog/<?= $post['slug'] ?>" class="work-card-img-link">
                                    <img src="<?= BASE_URL ?>/<?= $post['cover_image'] ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="work-card-img">
                                </a>
                            <?php endif; ?>
                            <div class="work-card-body">
                                <span class="work-cat"><?= htmlspecialchars($post['category_name'] ?? 'Blog') ?></span>
                                <h4 class="work-title">
                                    <a href="<?= BASE_URL ?>/blog/<?= $post['slug'] ?>"><?= htmlspecialchars($post['title']) ?></a>
                                </h4>
                                <div class="work-excerpt">
                                    <?= substr(strip_tags($post['body']), 0, 150) ?>...
                                </div>
                                <span class="work-date"><?= date('d M Y', strtotime($post['published_at'])) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>
