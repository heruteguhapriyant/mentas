<div style="display: flex; gap: 12px; flex-wrap: wrap; justify-content: center; margin-bottom: 20px;">
    <a href="<?= BASE_URL ?>/blog" class="<?= empty($activeCategory) ? 'btn-orange' : 'btn-outline' ?>" style="padding: 10px 20px; font-size: 14px;">Semua</a>
    <?php if (!empty($categories)): ?>
        <?php foreach ($categories as $cat): ?>
            <a href="<?= BASE_URL ?>/blog/<?= $cat['slug']; ?>" class="<?= ($activeCategory ?? '') === $cat['slug'] ? 'btn-orange' : 'btn-outline' ?>" style="padding: 10px 20px; font-size: 14px;">
                <?= $cat['name']; ?>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>
</div>