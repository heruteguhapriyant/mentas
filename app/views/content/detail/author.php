
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

                <?php /* Email hidden per request
                <div class="contact-item">
                    <span class="contact-label">alamat email:</span>
                    <a href="mailto:<?= htmlspecialchars($content['email']) ?>" class="contact-link"><?= htmlspecialchars($content['email']) ?></a>
                </div>
                */ ?>

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
                            <a href="<?= htmlspecialchars($handle) ?>" target="_blank" class="contact-link link-red"><?= htmlspecialchars($handle) ?></a> 
                        </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- Content Submenu -->
                <div class="author-submenu" style="margin-top: 25px; border-top: 1px solid #eee; padding-top: 15px;">
                    <h4 style="font-size: 0.9rem; margin-bottom: 10px; font-weight: 700; color: #333;">Karya & Kontribusi</h4>
                    
                    <style>
                        .content-dropdown { margin-bottom: 8px; }
                        .content-dropdown summary {
                            cursor: pointer;
                            font-size: 0.9rem;
                            color: #555;
                            display: flex;
                            align-items: center;
                            list-style: none; /* Hide default triangle */
                            padding: 5px 0;
                        }
                        .content-dropdown summary::-webkit-details-marker { display: none; }
                        .content-dropdown summary:hover { color: #000; }
                        .content-dropdown summary .count-badge {
                            margin-left: auto;
                            background: #eee;
                            padding: 2px 8px;
                            border-radius: 10px;
                            font-size: 0.75rem;
                            font-weight: 600;
                        }
                        .content-dropdown summary i.fa-chevron-down {
                            margin-left: 8px;
                            font-size: 0.7rem;
                            transition: transform 0.2s;
                        }
                        .content-dropdown[open] summary i.fa-chevron-down {
                            transform: rotate(180deg);
                        }
                        .dropdown-list {
                            list-style: none;
                            padding: 5px 0 5px 25px;
                            margin: 0;
                            border-left: 2px solid #eee;
                            margin-left: 8px;
                        }
                        .dropdown-list li { margin-bottom: 5px; }
                        .dropdown-list li a {
                            color: #666;
                            text-decoration: none;
                            font-size: 0.85rem;
                            display: block;
                            padding: 2px 0;
                        }
                        .dropdown-list li a:hover { color: #d63384; }
                    </style>

                    <!-- BLOG -->
                    <?php if (!empty($contentItems['blog'])): ?>
                        <details class="content-dropdown">
                            <summary>
                                <i class="fas fa-pen-nib" style="width: 20px; color: #888;"></i> Blog 
                                <span class="count-badge"><?= count($contentItems['blog']) ?></span>
                                <i class="fas fa-chevron-down"></i>
                            </summary>
                            <ul class="dropdown-list">
                                <?php foreach ($contentItems['blog'] as $item): ?>
                                    <li><a href="<?= BASE_URL ?>/blog/<?= $item['slug'] ?>" target="_blank"><?= htmlspecialchars($item['title']) ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </details>
                    <?php endif; ?>

                    <!-- BULLETIN -->
                    <?php if (!empty($contentItems['bulletin'])): ?>
                        <details class="content-dropdown">
                            <summary>
                                <i class="fas fa-book-open" style="width: 20px; color: #888;"></i> Bulletin 
                                <span class="count-badge"><?= count($contentItems['bulletin']) ?></span>
                                <i class="fas fa-chevron-down"></i>
                            </summary>
                            <ul class="dropdown-list">
                                <?php foreach ($contentItems['bulletin'] as $item): ?>
                                    <!-- Check if bulletin has slug or file link -->
                                    <li><a href="<?= BASE_URL ?>/bulletin/<?= $item['slug'] ?? '#' ?>" target="_blank"><?= htmlspecialchars($item['title']) ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </details>
                    <?php endif; ?>

                    <!-- KOLABORASI -->
                    <?php if (!empty($contentItems['kolaborasi'])): ?>
                        <details class="content-dropdown">
                            <summary>
                                <i class="fas fa-users" style="width: 20px; color: #888;"></i> Kolaborasi 
                                <span class="count-badge"><?= count($contentItems['kolaborasi']) ?></span>
                                <i class="fas fa-chevron-down"></i>
                            </summary>
                            <ul class="dropdown-list">
                                <?php foreach ($contentItems['kolaborasi'] as $item): ?>
                                    <li><a href="<?= BASE_URL ?>/kolaborasi/<?= $item['slug'] ?>" target="_blank"><?= htmlspecialchars($item['title']) ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </details>
                    <?php endif; ?>
                </div>
            </div>

            <!-- QRIS Support Section -->
            <?php if (!empty($content['qris_image'])): ?>
            <div class="author-divider"></div>
            <div class="author-qris-section">
                <h4 style="font-size: 14px; color: #333; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 1px;">
                    <i class="fa-solid fa-heart" style="color: #e63946;"></i> Dukung Kontributor
                </h4>
                <div class="qris-container" style="background: #fff; padding: 15px; border-radius: 10px; border: 2px solid #eee; text-align: center;">
                    <img 
                        src="<?= BASE_URL ?>/<?= $content['qris_image'] ?>" 
                        alt="QRIS <?= htmlspecialchars($content['name']) ?>"
                        style="max-width: 200px; width: 100%; height: auto; border-radius: 8px;"
                    >
                    <p style="margin-top: 10px; font-size: 12px; color: #666;">
                        Scan QRIS untuk mendukung karya penulis
                    </p>
                </div>
            </div>
            <?php endif; ?>
        </aside>

        <!-- RIGHT COLUMN: Image, Bio, Works -->
        <main class="author-main-content">
            <!-- Big Landscape Image -->
            <!-- Profile Image (Square as cropped) -->
            <div class="author-profile-image-container" style="width: 100%; max-width: 350px; aspect-ratio: 1/1; border-radius: 12px; overflow: hidden; margin-bottom: 30px; border: 1px solid #eee; margin-left: auto; margin-right: auto;">
                 <?php 
                    // Use a different bigger image if available, or fall back to avatar but styled differently
                    // Ideally this should be a 'cover_photo' but we use avatar for now, styled as landscape/cover
                    $image = !empty($content['avatar']) ? BASE_URL . '/' . $content['avatar'] : 'https://ui-avatars.com/api/?name=' . urlencode($content['name']) . '&background=random&size=600';
                ?>
                <img src="<?= $image ?>" alt="<?= htmlspecialchars($content['name']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
            </div>

            <!-- Bio Text -->
            <div class="author-bio-text">
                <p><strong><?= htmlspecialchars($content['name']) ?></strong> <?= nl2br(htmlspecialchars($content['bio'])) ?></p>
            </div>

            <!-- Articles Grid -->
            <div class="author-works-section">
                <!-- <h3 class="works-title">Karya</h3> -->
                
                <?php 
                // Fallback if latestContent not set (backward compatibility)
                $feedItems = $latestContent ?? $posts; 
                ?>

                <?php if (empty($feedItems)): ?>
                    <p class="text-muted">Belum ada karya yang dibuat.</p>
                <?php else: ?>
                    <div class="works-grid">
                    <?php foreach ($feedItems as $item): ?>
                        <div class="work-card">
                            <?php if (!empty($item['cover_image'])): ?>
                                <a href="<?= $item['url'] ?? (BASE_URL . '/blog/' . $item['slug']) ?>" class="work-card-img-link" target="_blank">
                                    <img src="<?= BASE_URL ?>/<?= $item['cover_image'] ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="work-card-img">
                                </a>
                            <?php endif; ?>
                            <div class="work-card-body">
                                <span class="work-cat">
                                    <?php 
                                        // Display category or type
                                        echo htmlspecialchars($item['category_name'] ?? 'Karya');
                                    ?>
                                </span>
                                <h4 class="work-title">
                                    <a href="<?= $item['url'] ?? (BASE_URL . '/blog/' . $item['slug']) ?>" target="_blank"><?= htmlspecialchars($item['title']) ?></a>
                                </h4>
                                <div class="work-excerpt">
                                    <?= substr(strip_tags($item['excerpt'] ?? ''), 0, 100) ?>...
                                </div>
                                <span class="work-date">
                                    <?= isset($item['created_at']) ? date('d M Y', strtotime($item['created_at'])) : '' ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>
