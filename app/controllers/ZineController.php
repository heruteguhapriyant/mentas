<?php

/**
 * ZineController - Bulletin Sastra (PDF Support)
 */
class ZineController extends Controller
{
    private $zineModel;

    public function __construct()
    {
        $this->zineModel = new Zine();
    }

    /**
     * List all zines with optional category filter
     */
    public function index()
    {
        $categorySlug = $_GET['category'] ?? null;
        $categories = $this->zineModel->getCategories();
        
        if ($categorySlug) {
            $zines = $this->zineModel->getByCategory($categorySlug);
        } else {
            $zines = $this->zineModel->all();
        }
        
        return $this->view('zine/index', [
            'zines' => $zines,
            'categories' => $categories,
            'activeCategory' => $categorySlug
        ]);
    }

    /**
     * View single zine
     */
    public function detail($slug)
    {
        $zine = $this->zineModel->findBySlug($slug);
        
        if (!$zine) {
            return $this->view('errors/404');
        }

        return $this->view('zine/detail', ['zine' => $zine]);
    }
}
