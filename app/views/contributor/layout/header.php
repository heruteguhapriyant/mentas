<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Contributor - Mentas.id</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/mentas.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/mentas-custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
            <a href="<?= BASE_URL ?>/auth/logout" class="profile-icon" title="Logout">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </header>
