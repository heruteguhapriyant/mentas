<?php
require_once __DIR__ . '/Controller.php';

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

            // Controller aliases (map URL to actual controller)
            $controllerAliases = [
                'bulletin' => 'Zine',  // /bulletin -> ZineController
            ];

            // Check if there's an alias
            $controllerBase = $segment1;
            if (isset($controllerAliases[strtolower($segment1)])) {
                $controllerBase = $controllerAliases[strtolower($segment1)];
            }

            // Check if controller exists
            $possibleController = ucfirst($controllerBase) . 'Controller';
            $controllerPath = dirname(__DIR__) . "/controllers/$possibleController.php";

            if (file_exists($controllerPath)) {
                $controllerName = $possibleController;
                require_once $controllerPath;
                $controller = new $possibleController;
                
                // If second segment exists
                if ($segment2) {
                    // Check if segment2 is a real method in the controller
                    $reflectionClass = new ReflectionClass($controller);
                    $hasRealMethod = false;
                    
                    try {
                        $reflectionMethod = $reflectionClass->getMethod($segment2);
                        $hasRealMethod = $reflectionMethod->isPublic() && $reflectionMethod->getName() === $segment2;
                    } catch (ReflectionException $e) {
                        $hasRealMethod = false;
                    }
                    
                    if ($hasRealMethod) {
                        // Use as method name
                        $method = $segment2;
                        $params = array_slice($url, 2);
                    } else {
                        // Treat segment2 as a slug/parameter
                        if (method_exists($controller, 'detail')) {
                            $method = 'detail';
                            $params = [$segment2];
                        } else {
                            $method = 'index';
                            $params = array_slice($url, 1);
                        }
                    }
                }
                // If no segment2, method stays as 'index'
                
                // Handle method call
                if (!method_exists($controller, $method)) {
                    if (method_exists($controller, '__call')) {
                        return call_user_func_array([$controller, $method], $params);
                    }
                    die("Method tidak ditemukan: $method di $controllerName");
                }
                
                return call_user_func_array([$controller, $method], $params);
                
            } else {
                // Fallback to ContentController for blog, etc
                $controllerName = 'ContentController';
                $method = 'handle';
                
                // Set query params for ContentController
                $_GET['type'] = $segment1;
                $_GET['slug'] = $segment2;
            }
        }

        $controllerPath = dirname(__DIR__) . "/controllers/$controllerName.php";

        if (!file_exists($controllerPath)) {
            die("Controller tidak ditemukan: $controllerName");
        }

        require_once $controllerPath;
        $controller = new $controllerName;

        // Handle method existence
        if (!method_exists($controller, $method)) {
            if (method_exists($controller, '__call')) {
                return call_user_func_array([$controller, $method], $params);
            }
            die("Method tidak ditemukan: $method di $controllerName");
        }

        call_user_func_array([$controller, $method], $params);
    }
}
