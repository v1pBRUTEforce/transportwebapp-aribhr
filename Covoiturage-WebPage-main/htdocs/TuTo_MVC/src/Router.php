<?php 

namespace App;

class Router
{
    protected $routes = [];

    private function addRoute($route, $controller, $action, $method)
    {

        $this->routes[$method][$route] = ['controller' => $controller, 'action' => $action];
    }

    public function get($route, $controller, $action)
    {
        $this->addRoute($route, $controller, $action, "GET");
    }

    public function post($route, $controller, $action)
    {
        $this->addRoute($route, $controller, $action, "POST");
    }

    public function dispatch()
    {
        $uri = strtok($_SERVER['REQUEST_URI'], '?');
        $method = $_SERVER['REQUEST_METHOD'];
    
        // Remove the trailing slash if present
        $uri = rtrim($uri, '/');
    
        // Debugging: Print requested URI and method
       /* echo "Requested URI: $uri<br>";
        echo "Request Method: $method<br>";
    
        // Debugging: Print out the routes for inspection
        echo "Defined Routes:<br>";
        echo "<pre>";
        print_r($this->routes);
        echo "</pre>";*/
    
        if (isset($this->routes[$method][$uri])) {
            $controller = $this->routes[$method][$uri]['controller'];
            $action = $this->routes[$method][$uri]['action'];
    
            // Instantiate the controller using Dependency Injector (assuming this works correctly)
            $controllerInstance = DependencyInjector::build($controller);
    
            // Call the action method on the controller
            $controllerInstance->$action();
        } else {
            throw new \Exception("No route found for URI: $uri");
        }
    }
    
    
}