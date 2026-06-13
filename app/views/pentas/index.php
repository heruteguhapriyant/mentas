<?php // Pentas Index Page ?>

<section class="hero pentas-hero">
    <div class="hero-content">
        <h1 class="page-title">Pentas</h1>
        <?php if (!empty($searchQuery)): ?>
            <p class="page-subtitle">Hasil pencarian: "<?= htmlspecialchars($searchQuery) ?>"</p>
        <?php else: ?>
            <p class="page-subtitle">Acara Seni Budaya</p>
        <?php endif; ?>
    </div>
</section>

<section class="pentas-section">
    <div class="container">

        <!-- Search Bar -->
        <form method="GET" action="<?= BASE_URL ?>/pentas" class="pentas-search-form">
            <div class="pentas-search-wrap">
                <i class="fas fa-search pentas-search-icon"></i>
                <input
                    type="text"
                    name="search"
                    class="pentas-search-input"
                    placeholder="Cari event pentas..."
                    value="<?= htmlspecialchars($searchQuery ?? '') ?>"
                />
                <?php if (!empty($searchQuery)): ?>
                    <a href="<?= BASE_URL ?>/pentas" class="pentas-search-clear" title="Hapus pencarian">
                        <i class="fas fa-times"></i>
                    </a>
                <?php endif; ?>
                <button type="submit" class="pentas-search-btn">Cari</button>
            </div>
        </form>

        <!-- Upcoming Events -->
        <h2 class="section-heading">
            <i class="fas fa-calendar-alt"></i>
            <?= !empty($searchQuery) ? 'Hasil Pencarian' : 'Event Mendatang' ?>
        </h2>

        <?php if (empty($upcomingEvents)): ?>
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <?php if (!empty($searchQuery)): ?>
                    <h3>Event tidak ditemukan</h3>
                    <p>Coba kata kunci lain atau <a href="<?= BASE_URL ?>/pentas">lihat semua event</a>.</p>
                <?php else: ?>
                    <h3>Belum ada event mendatang</h3>
                    <p>Stay tuned untuk event-event menarik selanjutnya!</p>
                <?php endif; ?>
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
                                    <span class="month"><?= date('M Y', strtotime($event['event_date'])) ?></span>
                                </div>
                                <?php if (strtotime($event['event_date']) > time()): ?>
                                    <span class="event-badge upcoming">Akan Datang</span>
                                <?php elseif ($event['ticket_price'] == 0): ?>
                                    <span class="event-badge free">GRATIS</span>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="pentas-card-image pentas-cover-placeholder">
                                <i class="fas fa-theater-masks"></i>
                                <div class="event-date-badge">
                                    <span class="day"><?= date('d', strtotime($event['event_date'])) ?></span>
                                    <span class="month"><?= date('M Y', strtotime($event['event_date'])) ?></span>
                                </div>
                                <?php if (strtotime($event['event_date']) > time()): ?>
                                    <span class="event-badge upcoming">Akan Datang</span>
                                <?php elseif ($event['ticket_price'] == 0): ?>
                                    <span class="event-badge free">GRATIS</span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <div class="pentas-card-content">
                            <h3><?= htmlspecialchars($event['title']) ?></h3>
                            <?php if ($event['ticket_price'] > 0): ?>
                                <p class="event-price">Rp <?= number_format($event['ticket_price'], 0, ',', '.') ?></p>
                            <?php endif; ?>
                            <p class="pentas-card-excerpt">
                                <?= htmlspecialchars(substr($event['description'] ?? '', 0, 100)) ?>...
                            </p>
                        
                            <!-- Daftar semua jadwal -->
                            <?php if (!empty($event['schedules'])): ?>
                                <div class="event-schedules-list">
                                    <?php foreach ($event['schedules'] as $sch): ?>
                                        <div class="schedule-row <?= strtotime($sch['event_date']) < time() ? 'past' : '' ?>">
                                            <span class="schedule-date">
                                                <i class="far fa-calendar-alt"></i>
                                                <?= date('d M Y', strtotime($sch['event_date'])) ?>
                                                <span class="schedule-time"><?= date('H:i', strtotime($sch['event_date'])) ?> WIB</span>
                                            </span>
                                            <?php if (!empty($sch['venue'])): ?>
                                                <span class="schedule-venue">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    <?= htmlspecialchars($sch['venue']) ?>
                                                    <?php if (!empty($sch['city'])): ?>
                                                        <em>(<?= htmlspecialchars($sch['city']) ?>)</em>
                                                    <?php endif; ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        
                            <a href="<?= BASE_URL ?>/pentas/<?= !empty($event['slug']) ? $event['slug'] : $event['id'] ?>"
                               class="btn-outline" target="_blank">
                                <i class="fa-regular fa-eye"></i> Lihat Detail
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php
            if (!empty($paginationUpcoming) && count($upcomingEvents) > 0):
                echo renderPagination($paginationUpcoming, ['page_key' => 'page_upcoming']);
            endif;
            ?>
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
                        <h3><?= htmlspecialchars($event['title']) ?></h3>
                        <?php if ($event['ticket_price'] > 0): ?>
                            <p class="event-price">Rp <?= number_format($event['ticket_price'], 0, ',', '.') ?></p>
                        <?php endif; ?>
                        <p class="pentas-card-excerpt">
                            <?= htmlspecialchars(substr($event['description'] ?? '', 0, 100)) ?>...
                        </p>
                    
                        <!-- Daftar semua jadwal -->
                        <?php if (!empty($event['schedules'])): ?>
                            <div class="event-schedules-list">
                                <?php foreach ($event['schedules'] as $sch): ?>
                                    <div class="schedule-row <?= strtotime($sch['event_date']) < time() ? 'past' : '' ?>">
                                        <span class="schedule-date">
                                            <i class="far fa-calendar-alt"></i>
                                            <?= date('d M Y', strtotime($sch['event_date'])) ?>
                                            <span class="schedule-time"><?= date('H:i', strtotime($sch['event_date'])) ?> WIB</span>
                                        </span>
                                        <?php if (!empty($sch['venue'])): ?>
                                            <span class="schedule-venue">
                                                <i class="fas fa-map-marker-alt"></i>
                                                <?= htmlspecialchars($sch['venue']) ?>
                                                <?php if (!empty($sch['city'])): ?>
                                                    <em>(<?= htmlspecialchars($sch['city']) ?>)</em>
                                                <?php endif; ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    
                        <a href="<?= BASE_URL ?>/pentas/<?= !empty($event['slug']) ? $event['slug'] : $event['id'] ?>"
                           class="btn-outline" target="_blank">
                            <i class="fa-regular fa-eye"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php
        if (!empty($paginationPast) && count($pastEvents) > 0):
            echo renderPagination($paginationPast, [
                'wrapper_class' => 'pagination-wrapper past-pagination',
                'page_key'      => 'page_past'
            ]);
        endif;
        ?>

    </div>
</section>
<?php endif; ?>