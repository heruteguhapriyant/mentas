<?php
/**
 * Router untuk PHP Built-in Server
 * Menangani static files dan routing ke index.php
 */

$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);

// Jika request adalah file static yang ada, serve langsung
$staticFile = __DIR__ . $path;
if ($path !== '/' && file_exists($staticFile) && is_file($staticFile)) {
    // Return false untuk biarkan PHP serve file static
    return false;
}

// Semua request lain masuk ke index.php
$_GET['url'] = trim($path, '/');
require __DIR__ . '/index.php';
