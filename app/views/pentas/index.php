<?php // Pentas Index Page ?>

<section class="hero zine-hero">
    <div class="hero-content">
        <h1>Pentas</h1>
        <p>Event, pertunjukan, dan kegiatan dari Mentas.id</p>
    </div>
</section>

<section class="program-section zine-section">
    <h2 class="section-heading"><i class="fas fa-calendar-alt"></i> Event Mendatang</h2>
    
    <?php if (empty($upcomingEvents)): ?>
        <p class="zine-empty">Belum ada event mendatang. Stay tuned!</p>
    <?php else: ?>
        <?php foreach ($upcomingEvents as $event): ?>
            <div class="program-card zine-card">
                <?php if (!empty($event['cover_image'])): ?>
                    <div class="zine-card-cover">
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
                    <div class="zine-card-cover" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-theater-masks" style="font-size: 48px; color: rgba(255,255,255,0.8);"></i>
                        <div class="event-date-badge">
                            <span class="day"><?= date('d', strtotime($event['event_date'])) ?></span>
                            <span class="month"><?= date('M', strtotime($event['event_date'])) ?></span>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="zine-card-content">
                    <span class="zine-card-category">
                        <i class="far fa-clock"></i> <?= date('H:i', strtotime($event['event_date'])) ?> WIB
                        <?php if (!empty($event['venue'])): ?>
                            &nbsp;|&nbsp; <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($event['venue']) ?>
                        <?php endif; ?>
                    </span>
                    
                    <h3><?= htmlspecialchars($event['title']) ?></h3>

                    <?php if ($event['ticket_price'] > 0): ?>
                        <p class="event-price">Rp <?= number_format($event['ticket_price'], 0, ',', '.') ?></p>
                    <?php endif; ?>

                    <p class="zine-card-excerpt">
                        <?= htmlspecialchars(substr($event['description'] ?? '', 0, 100)) ?>...
                    </p>

                    <a href="<?= BASE_URL ?>/pentas/<?= !empty($event['slug']) ? $event['slug'] : $event['id'] ?>" class="btn-outline">
                        Lihat Detail
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>

<?php if (!empty($pastEvents)): ?>
<section class="program-section zine-section" style="opacity: 0.8;">
    <h2 class="section-heading"><i class="fas fa-history"></i> Event Sebelumnya</h2>
    
    <?php foreach ($pastEvents as $event): ?>
        <div class="program-card zine-card">
            <?php if (!empty($event['cover_image'])): ?>
                <div class="zine-card-cover" style="filter: grayscale(50%);">
                    <img src="<?= BASE_URL ?>/<?= $event['cover_image'] ?>" alt="<?= htmlspecialchars($event['title']) ?>">
                    <span class="event-badge past">Selesai</span>
                </div>
            <?php else: ?>
                <div class="zine-card-cover" style="background: linear-gradient(135deg, #555 0%, #333 100%); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-theater-masks" style="font-size: 48px; color: rgba(255,255,255,0.5);"></i>
                </div>
            <?php endif; ?>
            
            <div class="zine-card-content">
                <span class="zine-card-category">
                    <i class="far fa-calendar"></i> <?= date('d M Y', strtotime($event['event_date'])) ?>
                </span>
                
                <h3><?= htmlspecialchars($event['title']) ?></h3>

                <a href="<?= BASE_URL ?>/pentas/<?= !empty($event['slug']) ? $event['slug'] : $event['id'] ?>" class="btn-outline">
                    Lihat Detail
                </a>
            </div>
        </div>
    <?php endforeach; ?>
</section>
<?php endif; ?>

<style>
.section-heading {
    font-size: 1.4rem;
    margin-bottom: 25px;
    color: #333;
    display: flex;
    align-items: center;
    gap: 10px;
}
.section-heading i {
    color: #d52c2c;
}
.event-date-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #d52c2c;
    color: #fff;
    padding: 8px 12px;
    border-radius: 8px;
    text-align: center;
    line-height: 1.2;
}
.event-date-badge .day {
    display: block;
    font-size: 1.3rem;
    font-weight: 800;
}
.event-date-badge .month {
    display: block;
    font-size: 0.7rem;
    text-transform: uppercase;
}
.event-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
}
.event-badge.free {
    background: #28a745;
    color: #fff;
}
.event-badge.past {
    background: #666;
    color: #fff;
}
.event-price {
    font-size: 1.2rem;
    font-weight: 700;
    color: #d52c2c;
    margin: 10px 0;
}
.zine-card-cover {
    position: relative;
}
</style>
