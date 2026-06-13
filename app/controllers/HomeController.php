<?php
class HomeController extends Controller
{
    public function index()
    {
        $postModel = new Post();
        $zineModel = new Zine();
        $productModel = new Product();
        $eventModel = new Event();
        $collabModel = new Collaboration();

        $featuredPosts   = $postModel->getExcludeCategory('editorial', 3);
        $editorialPosts  = $postModel->getByCategory('editorial', 3);
        $latestBulletins = $zineModel->all(3);
        $latestProducts  = $productModel->getAll(4);
        $latestEvents    = $eventModel->getAll();
        $latestCollaborations = $collabModel->getActive();

        // Sort berdasarkan tanggal terbaru
        usort($latestEvents, function($a, $b) {
            return strtotime($b['event_date']) - strtotime($a['event_date']);
        });
        usort($latestCollaborations, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        // Limit
        $latestEvents         = array_slice($latestEvents, 0, 3);
        $latestCollaborations = array_slice($latestCollaborations, 0, 3);

        $this->view('home/index', [
            'featuredPosts'        => $featuredPosts,
            'editorialPosts'       => $editorialPosts,
            'latestBulletins'      => $latestBulletins,
            'latestProducts'       => $latestProducts,
            'latestEvents'         => $latestEvents,
            'latestCollaborations' => $latestCollaborations,
        ]);
    }
}