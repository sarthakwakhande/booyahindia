<?php

declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];

    public function __construct(private Request $request, private Response $response)
    {
    }

    public function get(string $path, callable|array $handler): void
    {
        $this->routes['get'][$path] = $handler;
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->routes['post'][$path] = $handler;
    }

    public function resolve(): string
    {
        $path = $this->request->getPath();
        $method = $this->request->method();
        $handler = $this->routes[$method][$path] ?? null;

        if ($handler === null) {
            $this->response->setStatusCode(404);
            return View::render('layouts/error', ['message' => 'Page not found']);
        }

        if (is_array($handler)) {
            [$controller, $action] = $handler;
            $instance = new $controller();
            return $instance->$action($this->request, $this->response);
        }

        return call_user_func($handler, $this->request, $this->response);
    }
}
