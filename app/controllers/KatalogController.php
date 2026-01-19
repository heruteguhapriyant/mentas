<?php

/**
 * KatalogController - Index/Katalog Komunitas (Static View)
 */
class KatalogController extends Controller
{
    private $communityModel;

    public function __construct()
    {
        $this->communityModel = new Community();
    }

    /**
     * List all communities
     */
    public function index()
    {
        $communities = $this->communityModel->all();
        return $this->view('katalog/index', ['communities' => $communities]);
    }

    /**
     * View single community
     */
    public function detail($slug)
    {
        $community = $this->communityModel->findBySlug($slug);
        
        if (!$community) {
            return $this->view('errors/404');
        }

        return $this->view('katalog/detail', ['community' => $community]);
    }
}
