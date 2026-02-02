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
