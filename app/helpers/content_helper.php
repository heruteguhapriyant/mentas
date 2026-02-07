<?php

/**
 * Content Helper Functions - Updated for Database
 */

/**
 * Get all published posts by type (blog)
 */
function getContentsByType($type)
{
    $postModel = new Post();
    return $postModel->all(20);
}

/**
 * Get posts by category slug
 */
function getContentsByCategory($type, $categorySlug)
{
    $postModel = new Post();
    return $postModel->getByCategory($categorySlug);
}

/**
 * Get single post by slug
 */
function getContentBySlug($slug)
{
    $postModel = new Post();
    $post = $postModel->findBySlug($slug);
    
    if ($post && $post['status'] === 'published') {
        $postModel->incrementViews($post['id']);
        return $post;
    }
    
    return null;
}

/**
 * Get active categories for blog menu
 */
function getCategoriesByType($typeSlug)
{
    $categoryModel = new Category();
    return $categoryModel->all(true, $typeSlug);
}

/**
 * Get recent posts
 */
function getRecentPosts($limit = 5)
{
    $postModel = new Post();
    return $postModel->getRecent($limit);
}

/**
 * Clean WordPress block markup from content
 * Removes <!-- wp:xxx --> comments and other WP-specific markup
 */
function cleanWordPressContent($content)
{
    if (empty($content)) {
        return '';
    }
    
    // Remove WordPress block comments (<!-- wp:xxx --> and <!-- /wp:xxx -->)
    $content = preg_replace('/<!--\s*\/?wp:[^>]*-->/s', '', $content);
    
    // Remove WordPress block JSON attributes
    $content = preg_replace('/<!--\s*\{[^}]*\}\s*-->/s', '', $content);
    
    // Clean up excessive whitespace between HTML elements
    // Replace multiple consecutive <br> or newlines with single paragraph break
    $content = preg_replace('/(<br\s*\/?>\s*){3,}/i', '<br><br>', $content);
    $content = preg_replace('/(\s*\n\s*){3,}/', "\n\n", $content);
    
    // Remove empty p tags
    $content = preg_replace('/<p>\s*<\/p>/i', '', $content);
    
    // Fix relative image paths to absolute BASE_URL
    // Matches src="uploads/..." or src="/uploads/..." and prepends BASE_URL
    $content = preg_replace('/src=["\'](?!http)(?:\/)?(uploads\/[^"\']+)["\']/', 'src="' . BASE_URL . '/$1"', $content);
    
    return trim($content);
}

/**
 * Generate a clean text excerpt from content
 * Strips HTML, WP blocks, and limits to specified length
 */
function generateExcerpt($content, $length = 150)
{
    if (empty($content)) {
        return '';
    }
    
    // First clean WordPress block markup
    $content = cleanWordPressContent($content);
    
    // Strip all HTML tags
    $content = strip_tags($content);
    
    // Decode HTML entities
    $content = html_entity_decode($content, ENT_QUOTES, 'UTF-8');
    
    // Normalize whitespace
    $content = preg_replace('/\s+/', ' ', $content);
    $content = trim($content);
    
    // Truncate to length
    if (mb_strlen($content) > $length) {
        $content = mb_substr($content, 0, $length);
        // Try to cut at word boundary
        $lastSpace = mb_strrpos($content, ' ');
        if ($lastSpace !== false && $lastSpace > $length * 0.7) {
            $content = mb_substr($content, 0, $lastSpace);
        }
        $content .= '...';
    }
    
    return $content;
}
