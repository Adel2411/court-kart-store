<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    private $notFoundCallback = null;

    private $unauthorizedCallback = null;

    /**
     * Register a route with a callback
     */
    public function addRoute(string $method, string $route, $callback, ?string $middleware = null): void
    {
        $this->routes[$method][$route] = [
            'callback' => $callback,
            'middleware' => $middleware,
        ];
    }

    /**
     * Register a GET route
     */
    public function get(string $route, $callback, ?string $middleware = null): void
    {
        $this->addRoute('GET', $route, $callback, $middleware);
    }

    /**
     * Register a POST route
     */
    public function post(string $route, $callback, ?string $middleware = null): void
    {
        $this->addRoute('POST', $route, $callback, $middleware);
    }

    /**
     * Set the 404 not found handler
     */
    public function setNotFoundHandler($callback): void
    {
        $this->notFoundCallback = $callback;
    }

    /**
     * Set the 403 unauthorized handler
     */
    public function setUnauthorizedHandler($callback): void
    {
        $this->unauthorizedCallback = $callback;
    }

    /**
     * Check if the URI matches a route pattern
     */
    private function matchRoute(string $pattern, string $uri)
    {
        $paramNames = [];
        if (preg_match_all('/\{([a-zA-Z0-9_]+)\}/', $pattern, $matches)) {
            $paramNames = $matches[1];
        }

        $patternRegex = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $pattern);
        $patternRegex = '#^'.$patternRegex.'$#';

        if (preg_match($patternRegex, $uri, $matches)) {
            array_shift($matches);

            $params = [];
            if (! empty($paramNames) && count($paramNames) === count($matches)) {
                foreach ($paramNames as $index => $name) {
                    if (is_numeric($matches[$index]) && (int) $matches[$index] == $matches[$index]) {
                        $params[$name] = (int) $matches[$index];
                    } else {
                        $params[$name] = $matches[$index];
                    }
                }

                return $params;
            }

            return $matches;
        }

        return false;
    }

    /**
     * Apply middleware to a route
     */
    private function applyMiddleware(?string $middleware): bool
    {
        if ($middleware === null) {
            return true;
        }

        if (method_exists(Middleware::class, $middleware)) {
            return Middleware::$middleware();
        }

        return true;
    }

    /**
     * Handle the current HTTP request
     */
    public function dispatch(): void
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $route => $routeData) {
                $params = $this->matchRoute($route, $uri);

                if ($params !== false) {
                    if (! $this->applyMiddleware($routeData['middleware'])) {
                        return;
                    }

                    $callback = $routeData['callback'];

                    if (is_callable($callback)) {
                        call_user_func_array($callback, $params);

                        return;
                    } elseif (is_string($callback)) {
                        if (strpos($callback, '@') !== false) {
                            [$controller, $method] = explode('@', $callback);
                            $controllerClass = "App\\Controllers\\$controller";

                            if (class_exists($controllerClass)) {
                                $controllerInstance = new $controllerClass;
                                if (method_exists($controllerInstance, $method)) {
                                    try {
                                        if (count($params) > 0 && array_keys($params) !== range(0, count($params) - 1)) {
                                            $reflection = new \ReflectionMethod($controllerClass, $method);
                                            $methodParams = [];

                                            foreach ($reflection->getParameters() as $param) {
                                                $paramName = $param->getName();
                                                if (isset($params[$paramName])) {
                                                    $methodParams[] = $params[$paramName];
                                                } elseif ($param->isDefaultValueAvailable()) {
                                                    $methodParams[] = $param->getDefaultValue();
                                                } else {
                                                    $methodParams[] = null;
                                                }
                                            }

                                            call_user_func_array([$controllerInstance, $method], $methodParams);
                                        } else {
                                            call_user_func_array([$controllerInstance, $method], $params);
                                        }

                                        return;
                                    } catch (\Exception $e) {
                                        error_log($e->getMessage());

                                        header('HTTP/1.1 500 Internal Server Error');
                                        echo '<h1>500 - Internal Server Error</h1>';
                                        echo '<p>Error details: '.htmlspecialchars($e->getMessage()).'</p>';

                                        return;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($this->notFoundCallback) {
            call_user_func($this->notFoundCallback);
        } else {
            header('HTTP/1.0 404 Not Found');
            echo '<h1>404 - Page Not Found</h1>';
        }
    }
}
