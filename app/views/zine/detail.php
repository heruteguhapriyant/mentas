<?php // Zine Detail Page - Uses existing Mentas design ?>

<section class="hero" style="padding: 120px 60px 60px;">
    <div class="hero-content">
        <a href="<?= BASE_URL ?>/zine" style="color: #fff; text-decoration: none; font-size: 14px; display: block; margin-bottom: 15px;">← Kembali ke Bulletin Sastra</a>
        <h1 style="font-size: 32px;"><?= htmlspecialchars($zine['title']) ?></h1>
        <?php if (!empty($zine['excerpt'])): ?>
            <p><?= htmlspecialchars($zine['excerpt']) ?></p>
        <?php endif; ?>
    </div>
</section>

<section class="program-section" style="padding: 40px 80px; flex-direction: column; align-items: center;">
    <article style="max-width: 800px; width: 100%;">
        
        <?php if (!empty($zine['cover_image'])): ?>
            <img src="<?= BASE_URL ?>/<?= $zine['cover_image'] ?>" alt="<?= htmlspecialchars($zine['title']) ?>" style="width: 100%; border-radius: 10px; margin-bottom: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
        <?php endif; ?>

        <div class="zine-content-body" style="background: #fff; padding: 40px; border-radius: 10px; border: 3px solid #000; box-shadow: 5px 5px 0 #000;">
            <div style="font-size: 17px; line-height: 2; color: #333; white-space: pre-wrap;">
<?= $zine['content'] ?>
            </div>
        </div>

        <div style="margin-top: 40px; display: flex; gap: 15px; justify-content: center;">
            <a href="<?= BASE_URL ?>/zine" class="btn-outline">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>
    </article>
</section>

<style>
.zine-content-body h1, .zine-content-body h2, .zine-content-body h3 {
    color: #d52c2c;
    margin: 25px 0 15px;
    font-weight: 700;
}
.zine-content-body p {
    margin-bottom: 18px;
    text-align: justify;
}
/* Style for poetry/verse */
.zine-content-body em {
    font-style: italic;
    color: #555;
}
</style>
