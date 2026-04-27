<?php

declare(strict_types=1);

namespace App\Core;

class View
{
    public static function render(string $view, array $params = []): string
    {
        $viewPath = __DIR__ . '/../Views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            return 'View not found';
        }

        extract($params);
        ob_start();
        include $viewPath;
        return ob_get_clean() ?: '';
    }
}
