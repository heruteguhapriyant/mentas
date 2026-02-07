<?php

class Controller
{
    public function view($view, $data = [])
    {
        extract($data);

        $__viewPath = dirname(__DIR__) . "/views/$view.php";
        
        // Admin, auth, and contributor views use their own layout (no public header/footer wrapping)
        if (strpos($view, 'admin/') === 0 || strpos($view, 'auth/') === 0 || strpos($view, 'contributor/') === 0) {
            if (file_exists($__viewPath)) {
                require $__viewPath;
            } else {
                die("View not found: $view");
            }
        } else {
            // Public views use main layout with header/footer
            $layoutPath = dirname(__DIR__) . "/views/layouts/main.php";
            if (file_exists($layoutPath)) {
                require $layoutPath;
            } else {
                die("Layout not found");
            }
        }
    }
}
