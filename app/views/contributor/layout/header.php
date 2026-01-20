<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Contributor - Mentas.id</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/mentas.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { background: #f5f5f5; }
        .contributor-header {
            background: linear-gradient(135deg, #d52c2c 0%, #8b1a1a 100%);
            color: #fff;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .contributor-header h1 {
            font-size: 1.2rem;
            margin: 0;
        }
        .contributor-header h1 a {
            color: #fff;
            text-decoration: none;
        }
        .contributor-header .welcome {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        .contributor-header nav {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        .contributor-header nav a {
            color: #fff;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-size: 0.9rem;
            transition: background 0.2s;
        }
        .contributor-header nav a:hover {
            background: rgba(255,255,255,0.2);
        }
        .contributor-header nav a.btn-write {
            background: #fff;
            color: #d52c2c;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <header class="contributor-header">
        <div>
            <h1><a href="<?= BASE_URL ?>">Mentas.id</a></h1>
            <span class="welcome">Selamat datang, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Contributor') ?></span>
        </div>
        <nav>
            <a href="<?= BASE_URL ?>"><i class="fas fa-home"></i> Website</a>
            <a href="<?= BASE_URL ?>/contributor"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="<?= BASE_URL ?>/contributor/create" class="btn-write"><i class="fas fa-plus"></i> Tulis Artikel</a>
            <a href="<?= BASE_URL ?>/auth/logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </header>
