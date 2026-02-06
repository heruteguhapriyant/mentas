<?php
/**
 * Admin Layout
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Mentas.id</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f5f5f5; }
        
        .admin-container { display: flex; min-height: 100vh; }
        
        /* Sidebar */
        .sidebar {
            width: 250px;
            background: #1a1a2e;
            color: #fff;
            padding: 1rem 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        
        .sidebar-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 1rem;
        }
        
        .sidebar-header h2 { font-size: 1.2rem; }
        .sidebar-header small { color: rgba(255,255,255,0.6); }
        
        .sidebar-menu { list-style: none; }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }
        
        .sidebar-menu i { width: 20px; text-align: center; }
        
        .menu-divider {
            border-top: 1px solid rgba(255,255,255,0.1);
            margin: 1rem 0;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 2rem;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .page-header h1 { font-size: 1.5rem; color: #333; }
        
        /* Cards */
        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }
        
        .card-header h3 { font-size: 1rem; color: #333; }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: #fff;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .stat-card h4 { color: #666; font-size: 0.85rem; margin-bottom: 0.5rem; }
        .stat-card .number { font-size: 2rem; font-weight: bold; color: #333; }
        .stat-card.primary .number { color: #007bff; }
        .stat-card.success .number { color: #28a745; }
        .stat-card.warning .number { color: #ffc107; }
        .stat-card.danger .number { color: #dc3545; }
        
        /* Tables */
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th, .table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .table th { color: #666; font-weight: 600; background: #f9f9f9; }
        .table tr:hover { background: #f9f9f9; }
        
        /* Buttons */
        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.875rem;
        }
        
        .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.75rem; }
        .btn-primary { background: #007bff; color: #fff; }
        .btn-success { background: #28a745; color: #fff; }
        .btn-danger { background: #dc3545; color: #fff; }
        .btn-warning { background: #ffc107; color: #000; }
        .btn-secondary { background: #6c757d; color: #fff; }
        
        /* Badges */
        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
        }
        
        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        .badge-secondary { background: #e2e3e5; color: #383d41; }
        
        /* Alerts */
        .alert {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
        
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }
        
        /* Forms */
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; }
        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        .form-control:focus { outline: none; border-color: #007bff; }
        textarea.form-control { min-height: 150px; resize: vertical; }
        
        .form-check { display: flex; align-items: center; gap: 0.5rem; }
        .form-check input { width: auto; }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>Mentas.id</h2>
                <small>Admin Panel</small>
            </div>
            
            <ul class="sidebar-menu">
                <li><a href="<?= BASE_URL ?>/admin" class="<?= ($_GET['url'] ?? '') === 'admin' ? 'active' : '' ?>">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a></li>
                <li><a href="<?= BASE_URL ?>/admin/categories">
                    <i class="fas fa-folder"></i> Kategori
                </a></li>
                
                <div class="menu-divider"></div>
                <li style="padding: 0.5rem 1.5rem; color: rgba(255,255,255,0.4); font-size: 0.75rem; text-transform: uppercase;">Blog</li>
                
                <li><a href="<?= BASE_URL ?>/admin/tags">
                    <i class="fas fa-tags"></i> Tags
                </a></li>
                <li><a href="<?= BASE_URL ?>/admin/posts">
                    <i class="fas fa-newspaper"></i> Artikel
                </a></li>
                
                <div class="menu-divider"></div>
                <li style="padding: 0.5rem 1.5rem; color: rgba(255,255,255,0.4); font-size: 0.75rem; text-transform: uppercase;">Konten</li>
                
                <li><a href="<?= BASE_URL ?>/admin/zines">
                    <i class="fas fa-book"></i> Buletin Sastra
                </a></li>
                <li><a href="<?= BASE_URL ?>/admin/communities">
                    <i class="fas fa-users-cog"></i> Katalog Komunitas
                </a></li>
                
                <div class="menu-divider"></div>
                <li style="padding: 0.5rem 1.5rem; color: rgba(255,255,255,0.4); font-size: 0.75rem; text-transform: uppercase;">Merch & Pentas</li>
                
                <li><a href="<?= BASE_URL ?>/admin/products">
                    <i class="fas fa-box"></i> Produk
                </a></li>
                <li><a href="<?= BASE_URL ?>/admin/events">
                    <i class="fas fa-calendar-alt"></i> Events
                </a></li>
                <li><a href="<?= BASE_URL ?>/admin/tickets">
                    <i class="fas fa-ticket-alt"></i> Tiket
                </a></li>
                
                <div class="menu-divider"></div>
                <li style="padding: 0.5rem 1.5rem; color: rgba(255,255,255,0.4); font-size: 0.75rem; text-transform: uppercase;">Pengguna</li>
                
                <li><a href="<?= BASE_URL ?>/admin/users">
                    <i class="fas fa-users"></i> Kelola User
                </a></li>
                
                <div class="menu-divider"></div>
                
                <li><a href="<?= BASE_URL ?>/admin/settings">
                    <i class="fas fa-cog"></i> Pengaturan
                </a></li>

                <li><a href="<?= BASE_URL ?>/auth/logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a></li>
            </ul>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <?php $flash = getFlash(); ?>
            <?php if ($flash): ?>
                <div class="alert alert-<?= $flash['type'] ?>">
                    <?= $flash['message'] ?>
                </div>
            <?php endif; ?>
