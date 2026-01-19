<?php

/**
 * Authentication Helper Functions
 */

/**
 * Check if user is logged in
 */
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

/**
 * Get current logged in user
 */
function getCurrentUser()
{
    if (!isLoggedIn()) {
        return null;
    }

    $userModel = new User();
    return $userModel->find($_SESSION['user_id']);
}

/**
 * Check if current user is admin
 */
function isAdmin()
{
    $user = getCurrentUser();
    return $user && $user['role'] === 'admin';
}

/**
 * Check if current user is contributor
 */
function isContributor()
{
    $user = getCurrentUser();
    return $user && $user['role'] === 'contributor' && $user['status'] === 'active';
}

/**
 * Require login - redirect if not logged in
 */
function requireLogin()
{
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . '/auth/login');
        exit;
    }
}

/**
 * Require admin - redirect if not admin
 */
function requireAdmin()
{
    requireLogin();
    if (!isAdmin()) {
        header('Location: ' . BASE_URL);
        exit;
    }
}

/**
 * Require contributor - redirect if not active contributor
 */
function requireContributor()
{
    requireLogin();
    if (!isContributor() && !isAdmin()) {
        header('Location: ' . BASE_URL);
        exit;
    }
}

/**
 * Login user
 */
function loginUser($user)
{
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_role'] = $user['role'];
}

/**
 * Logout user
 */
function logoutUser()
{
    unset($_SESSION['user_id']);
    unset($_SESSION['user_name']);
    unset($_SESSION['user_role']);
    session_destroy();
}

/**
 * Set flash message
 */
function setFlash($type, $message)
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Get and clear flash message
 */
function getFlash()
{
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * CSRF Token generation
 */
function generateCsrfToken()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF Token
 */
function verifyCsrfToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
