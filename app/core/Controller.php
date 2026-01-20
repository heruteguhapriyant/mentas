<?php

class Controller
{
    public function view($view, $data = [])
    {
        extract($data);

        $__viewPath = "../app/views/$view.php";
        
        // Admin, auth, and contributor views use their own layout (no public header/footer wrapping)
        if (strpos($view, 'admin/') === 0 || strpos($view, 'auth/') === 0 || strpos($view, 'contributor/') === 0) {
            require $__viewPath;
        } else {
            // Public views use main layout with header/footer
            require "../app/views/layouts/main.php";
        }
    }
}
