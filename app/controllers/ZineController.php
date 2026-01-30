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
        $category = $_GET['category'] ?? null;
        $categories = Zine::getCategories();
        
        if ($category && isset($categories[$category])) {
            $zines = $this->zineModel->getByCategory($category);
        } else {
            $zines = $this->zineModel->all();
            $category = null; // Reset to show "all" as active
        }
        
        return $this->view('zine/index', [
            'zines' => $zines,
            'categories' => $categories,
            'activeCategory' => $category
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
