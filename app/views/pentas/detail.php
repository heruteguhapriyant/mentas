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
                    <img 
                        src="<?= BASE_URL ?>/<?= $event['cover_image'] ?>" 
                        alt="<?= htmlspecialchars($event['title']) ?>" 
                        class="pentas-featured-image"
                    >
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
                    <?php if (!empty($event['venue']) || !empty($event['venue_address'])): ?>
                        <div class="event-location-box">
                            <h4><i class="fas fa-map-marker-alt"></i>Lokasi</h4>
                            <?php if (!empty($event['venue'])): ?>
                                <p class="venue-name"><?= htmlspecialchars($event['venue']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($event['venue_address'])): ?>
                                <p class="venue-address"><?= nl2br(htmlspecialchars($event['venue_address'])) ?></p>
                            <?php endif; ?>
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
                                <span class="price-value free">GRATIS</span>
                            <?php else: ?>
                                <span class="price-label">Harga</span>
                                <span class="price-value paid">Rp <?= number_format($event['ticket_price'], 0, ',', '.') ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Ticket Status -->
                        <div class="ticket-availability">
                            <?php if ($availableTickets == -1): ?>
                                <span class="available"><i class="fas fa-check-circle"></i> Tiket Tersedia</span>
                            <?php elseif ($availableTickets > 0): ?>
                                <span class="available"><i class="fas fa-check-circle"></i> Sisa <?= $availableTickets ?> tiket</span>
                            <?php else: ?>
                                <span class="sold-out"><i class="fas fa-times-circle"></i> Tiket Habis</span>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($confirmedCount > 0): ?>
                        <div class="attendee-info">
                            <i class="fas fa-users"></i> <?= $confirmedCount ?> peserta terdaftar
                        </div>
                        <?php endif; ?>
                        
                        <!-- Register Button -->
                        <!-- <?php if ($canRegister): ?>
                            <a href="<?= BASE_URL ?>/pentas/register/<?= $eventSlug ?>" class="btn-register">
                                <i class="fas fa-ticket-alt"></i> Daftar Sekarang
                            </a> -->
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
            </aside>
            
        </div>
    </div>
</section>