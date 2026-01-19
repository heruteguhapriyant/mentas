<?php

class Controller
{
    public function view($view, $data = [])
    {
        extract($data);

        $__viewPath = "../app/views/$view.php";
        require "../app/views/layouts/main.php";
    }
}

