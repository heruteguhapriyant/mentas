<?php

/**
 * PageController - Static Pages
 */
class PageController extends Controller
{
    /**
     * Handle index route /page/{slug}
     * Router calls this when method {slug} doesn't exist
     */
    public function index($page = null)
    {
        if ($page) {
            return $this->__call($page, []);
        }
        return $this->view('errors/404');
    }

    /**
     * Handle page by slug (called as method by Router)
     * Route: /page/jual-beli calls $this->jual-beli() which doesn't work
     * So we use __call magic method
     */
    public function __call($name, $arguments)
    {
        $page = str_replace('_', '-', $name);
        
        switch ($page) {
            case 'about':
                return $this->showAbout();
            case 'contribute':
                return $this->showContribute();
            case 'jual-beli':
            case 'event':
                return $this->showComingSoon($page);
            default:
                return $this->view('errors/404');
        }
    }

    private function showAbout()
    {
        return $this->view('page/about');
    }

    private function showContribute()
    {
        $userModel = new User();
        $contributors = $userModel->getActiveContributors(true);

        return $this->view('page/contribute', [
            'contributors' => $contributors
        ]);
    }

    private function showComingSoon($page)
    {
        $titles = [
            'jual-beli' => 'Jual Beli',
            'event' => 'Event & Ticketing',
        ];

        return $this->view('page/coming-soon', [
            'title' => $titles[$page] ?? 'Halaman',
            'page' => $page
        ]);
    }
}

