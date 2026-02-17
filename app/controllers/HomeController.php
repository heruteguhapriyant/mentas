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

        // Get latest content ordered by published_at/created_at
        $featuredPosts = $postModel->all(3); // 3 latest published posts
        $latestBulletins = $zineModel->all(3); // 3 latest bulletins
        $latestProducts = $productModel->getAll(4); // 4 latest products
        $latestEvents = $eventModel->getAll(); // upcoming events
        $latestCollaborations = $collabModel->getActive(); // active collaborations
        
        // Limit events and collaborations
        $latestEvents = array_slice($latestEvents, 0, 3);
        $latestCollaborations = array_slice($latestCollaborations, 0, 3);

        $this->view('home/index', [
            'featuredPosts' => $featuredPosts,
            'latestBulletins' => $latestBulletins,
            'latestProducts' => $latestProducts,
            'latestEvents' => $latestEvents,
            'latestCollaborations' => $latestCollaborations
        ]);
    }
}
