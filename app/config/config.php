<?php
/**
 * Dynamic BASE_URL Configuration
 * Otomatis mendeteksi URL berdasarkan cara akses (XAMPP, PHP built-in server, dll)
 */

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

// Detect if running from subdirectory (e.g., /mentas-main/public)
$scriptDir = dirname($_SERVER['SCRIPT_NAME']);
$basePath = ($scriptDir === '/' || $scriptDir === '\\') ? '' : $scriptDir;

define('BASE_URL', $protocol . '://' . $host . $basePath);
