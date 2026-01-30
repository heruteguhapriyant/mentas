<?php
require_once "../app/core/Controller.php";

class Router
{
    public function dispatch()
    {
        $controllerName = 'HomeController';
        $method = 'index';
        $params = [];

        if (isset($_GET['url']) && !empty($_GET['url'])) {
            $url = explode('/', rtrim($_GET['url'], '/'));

            // First segment = controller or content type
            $segment1 = $url[0] ?? '';
            $segment2 = $url[1] ?? null;
            $segment3 = $url[2] ?? null;

            // Check if controller exists
            $possibleController = ucfirst($segment1) . 'Controller';
            $controllerPath = "../app/controllers/$possibleController.php";

            if (file_exists($controllerPath)) {
                $controllerName = $possibleController;
                
                // If second segment exists
                if ($segment2) {
                    // Load controller to check if method exists
                    require_once $controllerPath;
                    $tempController = new $possibleController;
                    
                    // Check if segment2 is a real method in the controller (excluding __call)
                    $reflectionClass = new ReflectionClass($tempController);
                    $hasRealMethod = false;
                    
                    try {
                        $reflectionMethod = $reflectionClass->getMethod($segment2);
                        // Check if it's a real method (not __call) and is public
                        $hasRealMethod = $reflectionMethod->isPublic() && $reflectionMethod->getName() === $segment2;
                    } catch (ReflectionException $e) {
                        // Method doesn't exist
                        $hasRealMethod = false;
                    }
                    
                    if ($hasRealMethod) {
                        // Use as method name
                        $method = $segment2;
                        $params = array_slice($url, 2);
                    } else {
                        // Treat segment2 as a slug/parameter for index method
                        $method = 'index';
                        $params = array_slice($url, 1);
                    }
                    
                    // Use existing controller instance
                    $controller = $tempController;
                    
                    // Handle method call
                    if (!method_exists($controller, $method)) {
                        if (method_exists($controller, '__call')) {
                            return call_user_func_array([$controller, $method], $params);
                        }
                        die("Method tidak ditemukan: $method di $controllerName");
                    }
                    
                    return call_user_func_array([$controller, $method], $params);
                }
            } else {
                // Fallback to ContentController for blog, etc
                $controllerName = 'ContentController';
                $method = 'handle';
                
                // Set query params for ContentController
                $_GET['type'] = $segment1;
                $_GET['slug'] = $segment2;
            }
        }

        $controllerPath = "../app/controllers/$controllerName.php";

        if (!file_exists($controllerPath)) {
            die("Controller tidak ditemukan: $controllerName");
        }

        require_once $controllerPath;
        $controller = new $controllerName;

        // Handle method existence
        if (!method_exists($controller, $method)) {
            // Try __call magic method
            if (method_exists($controller, '__call')) {
                return call_user_func_array([$controller, $method], $params);
            }
            die("Method tidak ditemukan: $method di $controllerName");
        }

        call_user_func_array([$controller, $method], $params);
    }
}

