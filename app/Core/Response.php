<?php

declare(strict_types=1);

namespace App\Core;

class Response
{
    public function setStatusCode(int $code): void
    {
        http_response_code($code);
    }

    public function json(array $data, int $statusCode = 200): string
    {
        $this->setStatusCode($statusCode);
        header('Content-Type: application/json');
        return json_encode($data, JSON_THROW_ON_ERROR);
    }

    public function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit;
    }
}
