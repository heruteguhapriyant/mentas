<?php

/**
 * ZineController - Bulletin Sastra (Static View)
 */
class ZineController extends Controller
{
    private $zineModel;

    public function __construct()
    {
        $this->zineModel = new Zine();
    }

    /**
     * List all zines
     */
    public function index()
    {
        $zines = $this->zineModel->all();
        return $this->view('zine/index', ['zines' => $zines]);
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
