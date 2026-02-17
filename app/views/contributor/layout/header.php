<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Contributor - Mentas.id</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/mentas.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/mentas-custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Quill Editor CSS -->
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <style>
        /* Override header untuk contributor - selalu scrolled state (merah) */
        .header {
            background: #d52c2c !important;
            padding: 15px 60px;
            border-bottom: 3px solid #0000004b;
        }
        .header .logo-default { display: none; }
        .header .logo-sticky { 
            display: block;
            transform: scale(3.6);
            transform-origin: left center;
        }
        .header .navbar a { color: #fff; }
        .header .navbar a:hover { color: #000; font-weight: 600; }
        .header .profile-icon {
            border-color: #fff;
            color: #fff;
            background: transparent;
        }
        .header .profile-icon:hover {
            background: #fff;
            color: #d52c2c;
        }
        /* Container utama contributor */
        body { padding-top: 80px; }

        /* Mobile menu for contributor */
        .contributor-mobile-toggle {
            display: none;
            cursor: pointer;
            flex-direction: column;
            gap: 4px;
            padding: 5px;
        }
        .contributor-mobile-toggle span {
            width: 22px;
            height: 2px;
            background: #fff;
            transition: 0.3s;
        }
        .contributor-mobile-menu {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: #d52c2c;
            z-index: 9999;
            padding: 60px 20px 20px;
            flex-direction: column;
        }
        .contributor-mobile-menu.active {
            display: flex;
        }
        .contributor-mobile-menu .close-btn {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 28px;
            color: #fff;
            cursor: pointer;
            background: none;
            border: none;
        }
        .contributor-mobile-menu a {
            color: #fff;
            text-decoration: none;
            padding: 15px 0;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .contributor-mobile-menu a:hover {
            background: rgba(255,255,255,0.1);
            padding-left: 10px;
        }

        @media (max-width: 768px) {
            .header { padding: 10px 15px; }
            .header .navbar { display: none; }
            .header .profile-icon { display: none; }
            .contributor-mobile-toggle { display: flex; }
        }
    </style>
</head>
<body>
    <header class="header scrolled">
        <div class="header-container">
            <div class="logo">
                <a href="<?= BASE_URL ?>">
                    <img src="<?= BASE_URL ?>/assets/images/Mentas-logo.png" alt="Mentas Logo" class="logo-default">
                    <img src="<?= BASE_URL ?>/assets/images/mentas-putih.png" alt="Mentas Logo White" class="logo-sticky">
                </a>
            </div>
            <nav class="navbar">
                <a href="<?= BASE_URL ?>/contributor"><i class="fas fa-th-large"></i> Dashboard</a>
                <a href="<?= BASE_URL ?>/contributor/create"><i class="fas fa-plus-circle"></i> Tulis Artikel</a>
                <a href="<?= BASE_URL ?>/contributor/editProfile"><i class="fas fa-user-edit"></i> Edit Profil</a>
            </nav>
            <div class="header-icons" style="display:flex;align-items:center;gap:10px;">
                <a href="<?= BASE_URL ?>/auth/logout" class="profile-icon" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
                <!-- Burger Menu for Mobile -->
                <div class="contributor-mobile-toggle" onclick="document.getElementById('contributorMobileMenu').classList.add('active')">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Fullscreen Menu -->
    <div id="contributorMobileMenu" class="contributor-mobile-menu">
        <button class="close-btn" onclick="this.parentElement.classList.remove('active')">&times;</button>
        <a href="<?= BASE_URL ?>/contributor"><i class="fas fa-th-large"></i> Dashboard</a>
        <a href="<?= BASE_URL ?>/contributor/create"><i class="fas fa-plus-circle"></i> Tulis Artikel</a>
        <a href="<?= BASE_URL ?>/contributor/editProfile"><i class="fas fa-user-edit"></i> Edit Profil</a>
        <a href="<?= BASE_URL ?>/auth/logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
