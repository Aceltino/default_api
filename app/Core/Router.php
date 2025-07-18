<?php

namespace App\Core;

class Router {
    private $routes = [];

    public function add($method, $path, $handler) {
        $this->routes[] = compact('method', 'path', 'handler');
    }

public function run()
{
    // Pega a URI da requisição
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // Remove base path se estiver em subdiretório (ajuste conforme seu projeto)
    $basePath = '/default_api/public'; // Modifique aqui conforme seu ambiente
    if (str_starts_with($uri, $basePath)) {
        $uri = substr($uri, strlen($basePath));
    }

    // Remove barra final e trata rota raiz
    $uri = rtrim($uri, '/');
    if ($uri === '') {
        $uri = '/';
    }

    // Método da requisição
    $method = $_SERVER['REQUEST_METHOD'];

    // Debug opcional
    if ($_ENV['APP_ENV'] === 'development') {
        error_log("Router | Method: $method | URI: $uri");
    }

    // Roteamento
    foreach ($this->routes as $route) {
        if ($route['method'] === $method && $route['path'] === $uri) {
            return call_user_func($route['handler']);
        }
    }

    // Se rota não encontrada
    http_response_code(404);
    echo json_encode([
        "message" => "Not Found",
        "method" => $method,
        "uri" => $uri,
        "available_routes" => array_map(function ($r) {
            return [
                'method' => $r['method'],
                'path' => $r['path']
            ];
        }, $this->routes)
    ]);
}

}
