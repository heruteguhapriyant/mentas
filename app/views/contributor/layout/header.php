<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Contributor - Mentas.id</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/mentas.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8f9fa; 
        }
        .contributor-nav {
            background: #1a1a2e;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 60px;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .contributor-nav .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #fff;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.2rem;
        }
        .contributor-nav .logo img {
            height: 35px;
        }
        .contributor-nav .nav-links {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        .contributor-nav .nav-links a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: color 0.2s;
        }
        .contributor-nav .nav-links a:hover {
            color: #fff;
        }
        .contributor-nav .nav-links .logout {
            color: #ff6b6b;
        }
    </style>
</head>
<body>
    <nav class="contributor-nav">
        <a href="<?= BASE_URL ?>" class="logo">
            <img src="<?= BASE_URL ?>/assets/images/mentas-putih.png" alt="Mentas.id">
        </a>
        <div class="nav-links">
            <a href="<?= BASE_URL ?>"><i class="fas fa-globe"></i> Website</a>
            <a href="<?= BASE_URL ?>/contributor"><i class="fas fa-th-large"></i> Dashboard</a>
            <a href="<?= BASE_URL ?>/auth/logout" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </nav>
