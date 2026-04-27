<?php

declare(strict_types=1);

namespace App\Core;

use Dotenv\Dotenv;

class Application
{
    public static Application $app;
    public Router $router;
    public Request $request;
    public Response $response;
    public Database $db;
    public Session $session;
    public string $rootPath;

    public function __construct(string $rootPath)
    {
        self::$app = $this;
        $this->rootPath = $rootPath;

        if (file_exists($rootPath . '/.env')) {
            Dotenv::createImmutable($rootPath)->safeLoad();
        }

        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->router = new Router($this->request, $this->response);
        $this->db = new Database();

        $routes = require $rootPath . '/routes/web.php';
        $routes($this->router);
    }

    public function run(): void
    {
        echo $this->router->resolve();
    }
}
