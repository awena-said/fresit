<?php

namespace App\Core;

class Router
{
    private $routes = [];

    /**
     * Add GET route
     */
    public function get($path, $handler)
    {
        $this->routes[] = [
            'method' => 'GET',
            'path' => $path,
            'handler' => $handler
        ];
    }

    /**
     * Add POST route
     */
    public function post($path, $handler)
    {
        $this->routes[] = [
            'method' => 'POST',
            'path' => $path,
            'handler' => $handler
        ];
    }

    /**
     * Dispatch request
     */
    public function dispatch($method, $uri)
    {
        // Remove query string
        $uri = parse_url($uri, PHP_URL_PATH);

        // Remove trailing slash
        $uri = rtrim($uri, '/');

        // If empty, set to root
        if (empty($uri)) {
            $uri = '/';
        }

        foreach ($this->routes as $route) {
            if ($route['method'] === $method) {
                $params = $this->matchPath($route['path'], $uri);
                if ($params !== false) {
                    return $this->executeHandler($route['handler'], $params);
                }
            }
        }

        // No route found - show 404
        http_response_code(404);
        echo '404 Not Found';
    }

    /**
     * Match path with parameters
     */
    private function matchPath($routePath, $requestPath)
    {
        // Convert route path to regex pattern
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $routePath);
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $requestPath, $matches)) {
            // Extract parameter names from route path
            preg_match_all('/\{([^}]+)\}/', $routePath, $paramNames);

            $params = [];
            for ($i = 0; $i < count($paramNames[1]); $i++) {
                $params[$paramNames[1][$i]] = $matches[$i + 1];
            }

            return $params;
        }

        return false;
    }

    /**
     * Execute handler
     */
    private function executeHandler($handler, $params = [])
    {
        if (is_callable($handler)) {
            return call_user_func($handler);
        }

        if (is_string($handler)) {
            // Parse "Controller@method" format
            if (strpos($handler, '@') !== false) {
                list($controller, $method) = explode('@', $handler);
                $controllerClass = "App\\Controllers\\{$controller}";

                // Debug: Check if class exists
                if (!class_exists($controllerClass)) {
                    throw new \Exception("Controller class '{$controllerClass}' not found. Handler: {$handler}");
                }

                $controllerInstance = new $controllerClass();

                if (!method_exists($controllerInstance, $method)) {
                    throw new \Exception("Method '{$method}' not found in controller '{$controllerClass}'. Handler: {$handler}");
                }

                // Pass parameters to the method
                if (!empty($params)) {
                    return call_user_func_array([$controllerInstance, $method], $params);
                } else {
                    return call_user_func([$controllerInstance, $method]);
                }
            }
        }

        throw new \Exception('Invalid handler: ' . (is_string($handler) ? $handler : gettype($handler)));
    }
} 