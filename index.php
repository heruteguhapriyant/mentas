<?php
// Set session cookie to expire on browser close
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
    'httponly' => true,
    'samesite' => 'Lax'
]);

// Handle static assets for PHP Built-in Server (when running from root)
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Check if it's a static file request
if (preg_match('/^\/(assets|uploads|favicon\.ico)/', $requestUri)) {
    $file = __DIR__ . '/public' . $requestUri;
    if (file_exists($file)) {
        $mime = mime_content_type($file);
        // Fix incorrect mime types for CSS/JS
        if (strpos($file, '.css') !== false) $mime = 'text/css';
        if (strpos($file, '.js') !== false) $mime = 'application/javascript';
        
        header('Content-Type: ' . $mime);
        readfile($file);
        exit;
    }
}

// Simulate .htaccess for PHP Built-in Server
// If $_GET['url'] is not set (which happens with php -S), parse it from REQUEST_URI
if (!isset($_GET['url'])) {
    $url = ltrim($requestUri, '/'); // Remove leading slash
    if (!empty($url)) {
        $_GET['url'] = $url;
    }
}

session_start();

require_once 'app/config/config.php';
require_once 'app/config/database.php';
require_once 'app/core/Database.php';
require_once 'app/core/Router.php';

/**
 * LOAD MODELS
 */
require_once 'app/models/User.php';
require_once 'app/models/Category.php';
require_once 'app/models/Post.php';
require_once 'app/models/Tag.php';
require_once 'app/models/Zine.php';
require_once 'app/models/Community.php';
require_once 'app/models/Comment.php';
require_once 'app/models/Product.php';
require_once 'app/models/Event.php';
require_once 'app/models/Ticket.php';

/**
 * LOAD GLOBAL HELPERS
 */
require_once 'app/helpers/pagination_helper.php';
require_once 'app/helpers/content_helper.php';
require_once 'app/helpers/url_helper.php';
require_once 'app/helpers/auth_helper.php';

$router = new Router();
$router->dispatch();
