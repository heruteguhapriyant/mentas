<?php // Pentas Index Page ?>

<section class="hero pentas-hero">
    <div class="hero-content">
        <h1 class="page-title">Pentas</h1>
        <p class="page-subtitle">Event, pertunjukan, dan kegiatan dari Mentas.id</p>
    </div>
</section>

<section class="pentas-section">
    <div class="container">
        <h2 class="section-heading">
            <i class="fas fa-calendar-alt"></i> Event Mendatang
        </h2>
        
        <?php if (empty($upcomingEvents)): ?>
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <h3>Belum ada event mendatang</h3>
                <p>Stay tuned untuk event-event menarik selanjutnya!</p>
            </div>
        <?php else: ?>
            <div class="events-grid">
                <?php foreach ($upcomingEvents as $event): ?>
                    <div class="pentas-card">
                        <?php if (!empty($event['cover_image'])): ?>
                            <div class="pentas-card-image">
                                <img src="<?= BASE_URL ?>/<?= $event['cover_image'] ?>" alt="<?= htmlspecialchars($event['title']) ?>">
                                
                                <div class="event-date-badge">
                                    <span class="day"><?= date('d', strtotime($event['event_date'])) ?></span>
                                    <span class="month"><?= date('M', strtotime($event['event_date'])) ?></span>
                                </div>
                                
                                <?php if ($event['ticket_price'] == 0): ?>
                                    <span class="event-badge free">GRATIS</span>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="pentas-card-image pentas-cover-placeholder">
                                <i class="fas fa-theater-masks"></i>
                                
                                <div class="event-date-badge">
                                    <span class="day"><?= date('d', strtotime($event['event_date'])) ?></span>
                                    <span class="month"><?= date('M', strtotime($event['event_date'])) ?></span>
                                </div>
                                
                                <?php if ($event['ticket_price'] == 0): ?>
                                    <span class="event-badge free">GRATIS</span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="pentas-card-content">
                            <div class="pentas-card-meta">
                                <span>
                                    <i class="far fa-clock"></i> <?= date('H:i', strtotime($event['event_date'])) ?> WIB
                                </span>
                                <?php if (!empty($event['venue'])): ?>
                                    <span>
                                        <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($event['venue']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <h3><?= htmlspecialchars($event['title']) ?></h3>

                            <?php if ($event['ticket_price'] > 0): ?>
                                <p class="event-price">Rp <?= number_format($event['ticket_price'], 0, ',', '.') ?></p>
                            <?php endif; ?>

                            <p class="pentas-card-excerpt">
                                <?= htmlspecialchars(substr($event['description'] ?? '', 0, 100)) ?>...
                            </p>

                            <a href="<?= BASE_URL ?>/pentas/<?= !empty($event['slug']) ? $event['slug'] : $event['id'] ?>" class="btn-outline">
                                <i class="fa-regular fa-eye"></i> Lihat Detail
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php if (!empty($pastEvents)): ?>
<section class="pentas-section past-section">
    <div class="container">
        <h2 class="section-heading">
            <i class="fas fa-history"></i> Event Sebelumnya
        </h2>
        
        <div class="events-grid">
            <?php foreach ($pastEvents as $event): ?>
                <div class="pentas-card past-card">
                    <?php if (!empty($event['cover_image'])): ?>
                        <div class="pentas-card-image">
                            <img src="<?= BASE_URL ?>/<?= $event['cover_image'] ?>" alt="<?= htmlspecialchars($event['title']) ?>">
                            <span class="event-badge past">Selesai</span>
                        </div>
                    <?php else: ?>
                        <div class="pentas-card-image pentas-cover-placeholder past">
                            <i class="fas fa-theater-masks"></i>
                            <span class="event-badge past">Selesai</span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="pentas-card-content">
                        <div class="pentas-card-meta">
                            <span>
                                <i class="far fa-calendar"></i> <?= date('d M Y', strtotime($event['event_date'])) ?>
                            </span>
                        </div>
                        
                        <h3><?= htmlspecialchars($event['title']) ?></h3>

                        <a href="<?= BASE_URL ?>/pentas/<?= !empty($event['slug']) ? $event['slug'] : $event['id'] ?>" class="btn-outline">
                            <i class="fa-regular fa-eye"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>