<?php
class HomeController extends Controller
{
    public function index()
    {
        $postModel = new Post();
        $featuredPosts = $postModel->all(3); // Get 3 latest posts
        
        $this->view('home/index', [
            'featuredPosts' => $featuredPosts
        ]);
    }
}
