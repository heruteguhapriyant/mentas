<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mentas.id</title>
    
    <meta name="google-site-verification" content="GCIIoCSf8_uJTraiHeIqJemJy4zXa1aRXAFQRIZqt8w" />

    <!-- Open Graph Meta Tags -->
    <?php
        $ogData = $content ?? $event ?? $post ?? [];
        $ogTitle = !empty($ogData['title']) ? $ogData['title'] : 'Mentas.id';

        $ogDescription = '';
        if (!empty($ogData['excerpt'])) {
            $ogDescription = $ogData['excerpt'];
        } elseif (!empty($ogData['description'])) {
            $ogDescription = $ogData['description'];
        } elseif (!empty($ogData['body'])) {
            $ogDescription = substr(strip_tags($ogData['body']), 0, 150);
        }

        $rawImage = $ogData['cover_image'] ?? $ogData['thumbnail'] ?? $ogData['avatar'] ?? '';
        $ogImage  = !empty($rawImage)
            ? BASE_URL . '/' . ltrim($rawImage, '/')
            : BASE_URL . '/assets/images/og-default.png';

        // Fix ogUrl - deteksi tipe halaman untuk prefix yang benar
        $rawSlug = $ogData['slug'] ?? '';
        if (!empty($rawSlug)) {
            if (isset($event)) {
                $ogUrl = BASE_URL . '/pentas/' . ltrim($rawSlug, '/');
            } elseif (isset($content)) {
                $ogUrl = BASE_URL . '/blog/' . ltrim($rawSlug, '/');
            } else {
                $ogUrl = BASE_URL . '/' . ltrim($rawSlug, '/');
            }
        } else {
            $ogUrl = BASE_URL;
        }
    ?>
    <meta property="og:site_name" content="Mentas.id">
    <meta property="og:title" content="<?= htmlspecialchars($ogTitle) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($ogDescription) ?>">
    <meta property="og:image" content="<?= htmlspecialchars($ogImage) ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:url" content="<?= htmlspecialchars($ogUrl) ?>">
    <meta property="og:type" content="article">

    <!-- WhatsApp & Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($ogTitle) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($ogDescription) ?>">
    <meta name="twitter:image" content="<?= htmlspecialchars($ogImage) ?>">

    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/fav-icon.png">

    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/mentas.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/blog.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/bulletin.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/katalog.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/ekosistem.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/merch.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pentas.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/contribute.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/mentas-custom.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<?php require_once __DIR__ . '/header.php'; ?>

<main>
    <?php require_once $__viewPath; ?>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>

<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body>
</html>