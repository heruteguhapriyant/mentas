<?php // Pentas Detail Page - Blog-like Layout ?>

<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pentas.css">

<?php
$eventSlug = !empty($event['slug']) ? $event['slug'] : $event['id'];
$isPast = strtotime($event['event_date']) < time();
$canRegister = !$isPast && ($availableTickets == -1 || $availableTickets > 0);
?>

<!-- Hero Section -->
<section class="hero zine-hero">
    <div class="hero-content">
        <a href="<?= BASE_URL ?>/pentas" class="zine-back-link">
            ← Kembali ke Pentas
        </a>
        
        <h1 class="zine-title">
            <?= htmlspecialchars($event['title']) ?>
        </h1>
        
        <p class="zine-excerpt">
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
<section class="blog-section">
    <div class="container">
        <div class="blog-content-wrapper">
            
            <!-- LEFT: Main Content -->
            <article class="blog-main-content">
                
                <!-- Cover Image -->
                <?php if (!empty($event['cover_image'])): ?>
                    <img 
                        src="<?= BASE_URL ?>/<?= $event['cover_image'] ?>" 
                        alt="<?= htmlspecialchars($event['title']) ?>" 
                        class="blog-featured-image"
                    >
                <?php else: ?>
                    <div class="blog-featured-image" style="background: linear-gradient(135deg, #d52c2c 0%, #1a1a2e 100%); display: flex; align-items: center; justify-content: center; height: 300px; border-radius: 10px;">
                        <div style="text-align: center; color: #fff;">
                            <i class="fas fa-theater-masks" style="font-size: 64px; margin-bottom: 15px; display: block;"></i>
                            <span style="font-size: 20px; font-weight: 600;"><?= htmlspecialchars($event['title']) ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Event Description -->
                <div class="blog-content-body">
                    <h3><i class="fas fa-info-circle" style="color: #d52c2c; margin-right: 8px;"></i>Tentang Event</h3>
                    <p>
                        <?= nl2br(htmlspecialchars($event['description'] ?? 'Tidak ada deskripsi.')) ?>
                    </p>
                    
                    <!-- Location Info -->
                    <?php if (!empty($event['venue']) || !empty($event['venue_address'])): ?>
                        <div class="event-location-box">
                            <h4><i class="fas fa-map-marker-alt" style="color: #d52c2c; margin-right: 8px;"></i>Lokasi</h4>
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
                        <h4><i class="fas fa-share-alt" style="color: #d52c2c; margin-right: 8px;"></i>Bagikan Event</h4>
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

                <div class="blog-footer">
                    <a href="<?= BASE_URL ?>/pentas" class="btn-outline">
                        <i class="fa-solid fa-arrow-left"></i> Kembali ke Pentas
                    </a>
                </div>

            </article>
            
            <!-- RIGHT: Sidebar -->
            <aside class="blog-sidebar">
                
                <!-- Ticket Box -->
                <div class="sidebar-widget">
                    <h3 class="sidebar-title" style="background: linear-gradient(135deg, #d52c2c 0%, #a01c1c 100%); color: #fff; margin: -15px -15px 20px -15px; padding: 15px; border-radius: 10px 10px 0 0;">
                        <i class="fas fa-ticket-alt"></i> Tiket
                    </h3>
                    
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
                    <?php if ($canRegister): ?>
                        <a href="<?= BASE_URL ?>/pentas/register/<?= $eventSlug ?>" class="btn-register">
                            <i class="fas fa-ticket-alt"></i> Daftar Sekarang
                        </a>
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
                
                <!-- Event Details Widget -->
                <div class="sidebar-widget">
                    <h3 class="sidebar-title">Detail Event</h3>
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
