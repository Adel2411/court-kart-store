<?php

namespace App\Core;

class Router
{
    private array $routes = [];
    private $notFoundCallback = null;
    
    /**
     * Register a route with a callback
     * 
     * @param string $method HTTP method (GET, POST, etc.)
     * @param string $route Route path
     * @param callable|string $callback Function or Controller method to execute
     * @return void
     */
    public function addRoute(string $method, string $route, $callback): void
    {
        $this->routes[$method][$route] = $callback;
    }
    
    /**
     * Register a GET route
     * 
     * @param string $route Route path
     * @param callable|string $callback Function or Controller method to execute
     * @return void
     */
    public function get(string $route, $callback): void
    {
        $this->addRoute('GET', $route, $callback);
    }
    
    /**
     * Register a POST route
     * 
     * @param string $route Route path
     * @param callable|string $callback Function or Controller method to execute
     * @return void
     */
    public function post(string $route, $callback): void
    {
        $this->addRoute('POST', $route, $callback);
    }
    
    /**
     * Set the 404 not found handler
     * 
     * @param callable|string $callback Function or Controller method to execute
     * @return void
     */
    public function setNotFoundHandler($callback): void
    {
        $this->notFoundCallback = $callback;
    }
    
    /**
     * Check if the URI matches a route pattern
     * 
     * @param string $pattern Route pattern
     * @param string $uri URI to match
     * @return array|false Parameters if matched, false otherwise
     */
    private function matchRoute(string $pattern, string $uri)
    {
        // Convert route pattern with parameters to regex
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $pattern);
        $pattern = '#^' . $pattern . '$#';
        
        if (preg_match($pattern, $uri, $matches)) {
            array_shift($matches); // Remove the full match
            return $matches;
        }
        
        return false;
    }
    
    /**
     * Handle the current HTTP request
     * 
     * @return void
     */
    public function dispatch(): void
    {
        // Get request URI and method
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        
        // Check each route for the current method
        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $route => $callback) {
                $params = $this->matchRoute($route, $uri);
                
                if ($params !== false) {
                    // Route matched, execute callback with parameters
                    if (is_callable($callback)) {
                        call_user_func_array($callback, $params);
                        return;
                    } elseif (is_string($callback)) {
                        // Handle controller@method format
                        if (strpos($callback, '@') !== false) {
                            list($controller, $method) = explode('@', $callback);
                            $controllerClass = "App\\Controllers\\$controller";
                            
                            if (class_exists($controllerClass)) {
                                $controllerInstance = new $controllerClass();
                                if (method_exists($controllerInstance, $method)) {
                                    call_user_func_array([$controllerInstance, $method], $params);
                                    return;
                                }
                            }
                        }
                    }
                }
            }
        }
        
        // Route not found
        if ($this->notFoundCallback) {
            call_user_func($this->notFoundCallback);
        } else {
            header('HTTP/1.0 404 Not Found');
            echo '<h1>404 - Page Not Found</h1>';
        }
    }
}
