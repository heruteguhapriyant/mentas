<?php // Pentas Detail Page - Blog-like Layout ?>

<?php
$eventSlug = !empty($event['slug']) ? $event['slug'] : $event['id'];
$isPast = strtotime($event['event_date']) < time();
$canRegister = !$isPast && ($availableTickets == -1 || $availableTickets > 0);
?>

<!-- Hero Section -->
<section class="hero pentas-hero-detail">
    <div class="hero-content">
        
        <h1 class="pentas-detail-title">
            <?= htmlspecialchars($event['title']) ?>
        </h1>
        
        <p class="pentas-detail-meta">
            <i class="far fa-calendar-alt"></i> <?= date('d F Y', strtotime($event['event_date'])) ?>
            &nbsp;•&nbsp;
            <i class="far fa-clock"></i> <?= date('H:i', strtotime($event['event_date'])) ?> WIB
            <?php if (!empty($event['venue'])): ?>
            &nbsp;•&nbsp;
            <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($event['venue']) ?>
            <?php endif; ?>
        </p>
    </div>
</section>

<!-- Content Section - Two Column Layout Like Blog -->
<section class="pentas-detail-section">
    <div class="container">
        <div class="pentas-detail-wrapper">
            
            <!-- LEFT: Main Content -->
            <article class="pentas-main-content">
                
                <!-- Cover Image -->
                <?php if (!empty($event['cover_image'])): ?>
                    <div class="cover-wrapper" style="position:relative;">
                        <img 
                            src="<?= BASE_URL ?>/<?= $event['cover_image'] ?>" 
                            alt="<?= htmlspecialchars($event['title']) ?>" 
                            class="pentas-featured-image"
                        >
                        <div class="cover-overlay" onclick="openLightbox('<?= BASE_URL ?>/<?= $event['cover_image'] ?>')" style="position:absolute;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0);display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background 0.3s;">
                            <i class="fas fa-eye" style="color:#fff;font-size:2rem;opacity:0;transition:opacity 0.3s;"></i>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="pentas-featured-image pentas-image-placeholder">
                        <div class="placeholder-content">
                            <i class="fas fa-theater-masks"></i>
                            <span><?= htmlspecialchars($event['title']) ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Event Description -->
                <div class="pentas-content-body">
                    <h3><i class="fas fa-info-circle"></i>Tentang Event</h3>
                    <p>
                        <?= nl2br(htmlspecialchars($event['description'] ?? 'Tidak ada deskripsi.')) ?>
                    </p>
                    
                    <!-- Location Info -->
                    <!-- Jadwal & Lokasi -->
                    <?php if (!empty($schedules)): ?>
                        <div class="event-schedules-detail">
                            <h3><i class="fas fa-calendar-alt"></i> Jadwal & Lokasi</h3>
                            <div class="schedules-grid">
                                <?php foreach ($schedules as $idx => $sch):
                                    $isPastSch = strtotime($sch['event_date']) < time();
                                ?>
                                <div class="schedule-detail-card <?= $isPastSch ? 'past' : 'upcoming' ?>">
                                    <div class="sdc-header">
                                        <span class="sdc-badge <?= $isPastSch ? 'badge-past' : 'badge-upcoming' ?>">
                                            <?= $isPastSch ? 'Selesai' : 'Akan Datang' ?>
                                        </span>
                                        <span class="sdc-num">Jadwal <?= $idx + 1 ?></span>
                                    </div>
                                    <div class="sdc-date">
                                        <i class="far fa-calendar-alt"></i>
                                        <?= date('d F Y', strtotime($sch['event_date'])) ?>
                                        &nbsp;•&nbsp;
                                        <i class="far fa-clock"></i>
                                        <?= date('H:i', strtotime($sch['event_date'])) ?> WIB
                                        <?php if (!empty($sch['end_date'])): ?>
                                            &nbsp;–&nbsp; <?= date('H:i', strtotime($sch['end_date'])) ?> WIB
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!empty($sch['venue'])): ?>
                                        <div class="sdc-venue">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <?= htmlspecialchars($sch['venue']) ?>
                                            <?php if (!empty($sch['city'])): ?>
                                                <span class="sdc-city">— <?= htmlspecialchars($sch['city']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($sch['venue_address'])): ?>
                                        <div class="sdc-address">
                                            <?= nl2br(htmlspecialchars($sch['venue_address'])) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Share Section -->
                    <div class="share-section">
                        <h4><i class="fas fa-share-alt"></i>Bagikan Event</h4>
                        <div class="share-buttons">
                            <a href="https://wa.me/?text=<?= urlencode($event['title'] . ' - ' . BASE_URL . '/pentas/' . $eventSlug) ?>" target="_blank" class="share-btn whatsapp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?text=<?= urlencode($event['title']) ?>&url=<?= urlencode(BASE_URL . '/pentas/' . $eventSlug) ?>" target="_blank" class="share-btn twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(BASE_URL . '/pentas/' . $eventSlug) ?>" target="_blank" class="share-btn facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="pentas-footer">
                    <a href="<?= BASE_URL ?>/pentas" class="btn-outline">
                        <i class="fa-solid fa-arrow-left"></i> Kembali ke Pentas
                    </a>
                </div>

            </article>
            
            <!-- RIGHT: Sidebar -->
            <aside class="pentas-sidebar">
                
                <!-- Ticket Box -->
                <div class="sidebar-ticket-widget">
                    <div class="ticket-widget-header">
                        <h3><i class="fas fa-ticket-alt"></i> Tiket</h3>
                    </div>
                    
                    <div class="ticket-widget-body">

                        <!-- Price -->
                        <div class="ticket-price-display">
                            <?php if ($event['ticket_price'] == 0): ?>
                                <span class="price-value free">RESERVASI</span>
                            <?php else: ?>
                                <span class="price-label">Harga</span>
                                <span class="price-value paid">Rp <?= number_format($event['ticket_price'], 0, ',', '.') ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <!-- WhatsApp Contact Button -->
                        <?php if (!empty($event['community_wa'])): ?>
                            <a href="https://wa.me/<?= htmlspecialchars($event['community_wa']) ?>?text=<?= urlencode('Halo, saya ingin bertanya tentang event: ' . $event['title']) ?>"
                               target="_blank"
                               class="btn-wa-contact">
                                <i class="fab fa-whatsapp"></i> Hubungi via WhatsApp
                            </a>
                        <?php endif; ?>

                        <!-- Attendee count -->
                        <!--<?php if ($confirmedCount > 0): ?>-->
                        <!--    <div class="attendee-info">-->
                        <!--        <i class="fas fa-users"></i> <?= $confirmedCount ?> peserta terdaftar-->
                        <!--    </div>-->
                        <!--<?php endif; ?>-->

                        <!-- Register / Status Button -->
                        <!--<?php if ($canRegister): ?>-->
                        <!--    <a href="<?= BASE_URL ?>/pentas/register/<?= $eventSlug ?>" class="btn-register">-->
                        <!--        <i class="fas fa-ticket-alt"></i> Daftar Sekarang-->
                        <!--    </a>-->
                        <?php elseif ($isPast): ?>
                            <button class="btn-register disabled" disabled>
                                <i class="fas fa-calendar-check"></i> Event Telah Selesai
                            </button>
                        <?php else: ?>
                            <button class="btn-register disabled" disabled>
                                <i class="fas fa-ban"></i> Tiket Habis
                            </button>
                        <?php endif; ?>

                    </div>
                </div>

                <!-- Event Details Widget -->
                <div class="sidebar-details-widget">
                    <h3 class="sidebar-widget-title">Detail Event</h3>
                    <ul class="event-details-list">
                        <li>
                            <i class="far fa-calendar-alt"></i>
                            <div>
                                <span class="label">Tanggal</span>
                                <span class="value"><?= date('d F Y', strtotime($event['event_date'])) ?></span>
                            </div>
                        </li>
                        <li>
                            <i class="far fa-clock"></i>
                            <div>
                                <span class="label">Waktu</span>
                                <span class="value"><?= date('H:i', strtotime($event['event_date'])) ?> WIB</span>
                            </div>
                        </li>
                        <?php if (!empty($event['venue'])): ?>
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <span class="label">Lokasi</span>
                                <span class="value"><?= htmlspecialchars($event['venue']) ?></span>
                            </div>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Widget Yang Terlibat -->
                <?php if (!empty($eventContributors)): ?>
                <div class="sidebar-contributors-widget">
                    <h3 class="sidebar-widget-title">Kreator</h3>
                    <ul class="contributors-list">
                        <?php foreach ($eventContributors as $contributor): ?>
                            <li class="contributor-item">
                                <a href="<?= BASE_URL ?>/author/<?= $contributor['id'] ?>" class="contributor-link">
                                    <div class="contributor-avatar-initial">
                                        <?= strtoupper(mb_substr($contributor['name'], 0, 1)) ?>
                                    </div>
                                    <span class="contributor-name">
                                        <?= htmlspecialchars($contributor['name']) ?>
                                    </span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
                
                <!-- Widget Komunitas -->
                <?php if (!empty($event['community_name'])): ?>
                <div class="sidebar-community-widget">
                    <h3 class="sidebar-widget-title">Komunitas Penyelenggara</h3>
                    <ul class="contributors-list">
                        <li class="contributor-item">
                            <?php if (!empty($event['community_ig'])): ?>
                                <a href="<?= htmlspecialchars($event['community_ig']) ?>" target="_blank" rel="noopener noreferrer" class="contributor-link">
                            <?php else: ?>
                                <span class="contributor-link" style="cursor:default;">
                            <?php endif; ?>
                                <div class="contributor-avatar-initial">
                                    <?= strtoupper(mb_substr($event['community_name'], 0, 1)) ?>
                                </div>
                                <span class="contributor-name">
                                    <?= htmlspecialchars($event['community_name']) ?>
                                    <?php if (!empty($event['community_ig'])): ?>
                                        <small style="display:block; font-size:11px; color:#999; margin-top:1px;">
                                            <i class="fab fa-instagram"></i> Lihat profil Instagram
                                        </small>
                                    <?php endif; ?>
                                </span>
                            <?php if (!empty($event['community_ig'])): ?>
                                </a>
                            <?php else: ?>
                                </span>
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>
                <?php endif; ?>

            </aside>
            
        </div>
    </div>
    <!-- Lightbox -->
    <div id="lightbox" onclick="closeLightbox()" style="display:none;position:fixed;z-index:9999;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.9);cursor:zoom-out;align-items:center;justify-content:center;">
        <img id="lightbox-img" src="" style="max-width:90%;max-height:90vh;object-fit:contain;border-radius:8px;box-shadow:0 0 30px rgba(0,0,0,0.5);">
        <span onclick="closeLightbox()" style="position:fixed;top:20px;right:30px;font-size:2rem;color:#fff;cursor:pointer;line-height:1;">✕</span>
    </div>

    <style>
    .cover-wrapper:hover .cover-overlay { background: rgba(0,0,0,0.35) !important; }
    .cover-wrapper:hover .cover-overlay i { opacity: 1 !important; }

    .event-schedules-detail { margin: 24px 0; }
    .event-schedules-detail h3 { margin-bottom: 16px; }
    .schedules-grid {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .schedule-detail-card {
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        padding: 16px;
        background: #fff;
        border-left: 4px solid #d52c2c;
    }
    .schedule-detail-card.past {
        border-left-color: #aaa;
        background: #f9f9f9;
    }
    .sdc-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    .sdc-badge {
        font-size: 11px;
        padding: 3px 10px;
        border-radius: 20px;
        font-weight: 600;
    }
    .badge-upcoming { background: #fff0f0; color: #d52c2c; }
    .badge-past     { background: #f0f0f0; color: #888; }
    .sdc-num        { font-size: 12px; color: #aaa; }
    .sdc-date       { font-size: 14px; font-weight: 600; margin-bottom: 8px; }
    .sdc-venue      { font-size: 14px; color: #444; margin-bottom: 4px; }
    .sdc-city       { color: #888; }
    .sdc-address    { font-size: 13px; color: #666; margin-top: 6px; line-height: 1.6; }
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
</section>