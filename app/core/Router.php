<?php

namespace FastRoute\core;

use Closure;
use FastRoute\controllers\MethodNotAllowedController;
use FastRoute\controllers\NotFoundController;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

class Router
{
    private array $routes;
    private array $group;

    public function add(string $method, string $uri, array $controller)
    {
        $this->routes[] = [$method, $uri, $controller];
    }

    public function group(string $prefix, Closure $callback)
    {
        $this->group[$prefix] = $callback;
    }

    private function routes(RouteCollector $r)
    {
        foreach ($this->routes as $route) {
            $r->addRoute(...$route);
        }
    }

    private function groupRoutes(RouteCollector $r)
    {
        foreach ($this->group as $prefix => $routes) {
            $r->addGroup($prefix, function (RouteCollector $r) use ($routes) {
                foreach ($routes() as $route) {
                    $r->addRoute(...$route);
                }
            });
        }
    }

    public function run()
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $r) {

            if (!empty($this->group)) {
                $this->groupRoutes($r);
            }

            if (!empty($this->routes)) {
                $this->routes($r);
            }
        });

        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'])['path'];
        $uri = ($uri !== '/') ? rtrim($uri, '/') : $uri;


        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

        $this->handle($routeInfo);
    }

    private function handle(array $routeInfo)
    {
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                call_user_func_array([new NotFoundController, 'index'], []);

                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                call_user_func_array([new MethodNotAllowedController, 'index'], []);

                break;
            case Dispatcher::FOUND:
                [, [$controller, $method], $vars] = $routeInfo;

                call_user_func_array([new $controller, $method], $vars);

                break;
        }
    }
}
