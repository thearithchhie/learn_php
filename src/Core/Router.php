<?php
namespace App\Core;

class Router {
    private $routes = [
        'GET' => [],
        'POST' => []
    ];
    
    public function get($uri, $controller) {
        $this->routes['GET'][$uri] = $controller;
    }
    
    public function post($uri, $controller) {
        $this->routes['POST'][$uri] = $controller;
    }
    
    public function dispatch() {
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        
        // Remove query string
        if (strpos($uri, '?') !== false) {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }
        
        // Remove trailing slash
        $uri = rtrim($uri, '/');
        
        // If the URI is empty (root), set it to '/'
        if ($uri === '') {
            $uri = '/';
        }
        
        // Debug information
        error_log("Requested URI: " . $uri);
        error_log("Request Method: " . $method);
        
        // Check static routes first
        if (isset($this->routes[$method][$uri])) {
            error_log("Matched static route: " . $uri);
            $this->callAction(
                ...explode('@', $this->routes[$method][$uri])
            );
            return;
        }
        
        // Check dynamic routes with parameters
        foreach ($this->routes[$method] as $route => $action) {
            // Skip non-parameterized routes
            if (strpos($route, ':') === false) {
                continue;
            }
            
            // Split the route and URI into segments
            $routeSegments = explode('/', trim($route, '/'));
            $uriSegments = explode('/', trim($uri, '/'));
            
            // If they don't have the same number of segments, skip
            if (count($routeSegments) !== count($uriSegments)) {
                continue;
            }
            
            $params = [];
            $match = true;
            
            // Compare segments
            for ($i = 0; $i < count($routeSegments); $i++) {
                if (strpos($routeSegments[$i], ':') === 0) {
                    // This is a parameter
                    $params[] = $uriSegments[$i];
                } else if ($routeSegments[$i] !== $uriSegments[$i]) {
                    // Static segment doesn't match
                    $match = false;
                    break;
                }
            }
            
            if ($match) {
                error_log("Matched dynamic route: " . $route);
                error_log("Parameters: " . print_r($params, true));
                
                // Get controller and method
                list($controller, $method) = explode('@', $action);
                
                error_log("Controller: " . $controller);
                error_log("Method: " . $method);
                
                // Create controller instance
                $controllerInstance = new $controller();
                
                // Call the method with parameters
                call_user_func_array([$controllerInstance, $method], $params);
                return;
            }
        }
        
        error_log("No route match found - 404");
        // Route not found
        $this->notFound();
    }
    
    protected function callAction($controller, $action) {
        //Check if the controller class exists
        if (!class_exists($controller)) {
            die("Controller class $controller does not exist");
        }

        // Check if the controller is already an object (instantiated)
        if (is_object($controller)) {
            $controllerInstance = $controller;
        } else {
            // If it's a string (class name), instantiate it
            $controllerInstance = new $controller();
        }
        
        if (!method_exists($controllerInstance, $action)) {
            throw new \Exception(
                get_class($controllerInstance) . " does not respond to the $action action."
            );
        }
        
        $controllerInstance->$action();
    }

    
    protected function notFound() {
        // Set appropriate HTTP response code
        header('HTTP/1.0 404 Not Found');
        
        // Display a 404 page
        include '../views/errors/404.php';
        exit;
    }
}

