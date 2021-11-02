<?php

namespace Template\Core;

use Exception;

class Router
{
    /**
     * All registered routes.
     *
     * @var array
     */
    public $routes = [
        'GET' => [],
        'POST' => [],
        'DELETE' => [],
    ];

    /**
     * Load a user's routes file.
     *
     * @param string $file
     * @return Router
     */
    public static function load(string $file): Router
    {
        $router = new static;

        require $file;

        return $router;
    }

    /**
     * Set a route for a GET request
     *
     * @param $uri
     * @param $controller
     * @throws Exception
     */
    public function get($uri, $controller): void
    {
        $this->map($uri, $controller, 'GET');
    }

    /**
     * Set a route for a POST request
     *
     * @param $uri
     * @param $controller
     * @throws Exception
     */
    public function post($uri, $controller): void
    {
        $this->map($uri, $controller, 'POST');
    }

    /**
     * Set a route for a DELETE request
     *
     * @param $uri
     * @param $controller
     * @throws Exception
     */
    public function delete($uri, $controller): void
    {
        $this->map($uri, $controller, 'DELETE');
    }

    /**
     * Direct traffic for route
     *
     * @param $uri
     * @param $requestType
     * @return mixed
     * @throws \Exception
     */
    public function direct($uri, $requestType)
    {
        if ($endpoint = $this->match($uri, $requestType)) {
            return $this->callAction($endpoint['controller'], $endpoint['action'], $endpoint['arguments']);
        }

        throw new \Exception("No route defined for this URI.");
    }

    /**
     * Call a action on a controller
     *
     * @param $controller
     * @param $action
     * @param null $args
     * @return mixed
     * @throws \Exception
     */
    protected function callAction($controller, $action, $args = null)
    {
        $controller = "Template\\App\\Controller\\{$controller}";

        if (! method_exists($controller, $action)) {
            throw new \Exception("Unable to call controller action");
        }

        $controller = new $controller;

        return call_user_func_array([$controller, $action], $args);
    }

    /**
     * Map a route
     *
     * @param $uri
     * @param $controller
     * @param $requestType
     * @throws Exception
     */
    protected function map($uri, $controller, $requestType): void
    {
        if (strpos($controller, "@") === false) {
            throw new Exception('Invalid route');
        }

        [$controller, $action] = explode("@", $controller);

        $this->routes[$requestType][$uri] = [
            'uri' => $this->toWild($uri),
            'controller' => $controller,
            'action' => $action
        ];

    }

    /**
     * Find wildcards in route and enclose them in regular expression to match later
     *
     * @param $uri
     * @return string
     */
    protected function toWild($uri): string
    {
        $uri = str_replace(['/', '{', '}'], ['\/', '(?\'', '\'[a-zA-Z0-9\-\.\,]*)'], $uri);
        return "/^{$uri}$/";
    }

    /**
     * Remove integer keys from array
     *
     * @param array $array
     * @return array
     */
    protected function onlyAssoc(array $array): array
    {
        foreach ($array as $key => $value) {
            if (is_numeric($key)) {
                unset($array[$key]);
            }
        }
        return $array;
    }

    /**
     * Match a route
     *
     * @param $uri
     * @param $requestType
     * @return array
     */
    protected function match($uri, $requestType): ?array
    {
        $routes = $this->routes[$requestType];
        foreach ($routes as $key => $route) {
            preg_match($route['uri'], $uri, $arguments);
            if (!empty($arguments)) {
                $this->routes[$requestType][$key]['arguments'] = $this->onlyAssoc($arguments);
                return $this->routes[$requestType][$key];
            }
        }
        return null;
    }
}
