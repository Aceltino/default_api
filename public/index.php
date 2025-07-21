<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Modules\Auth\AuthController;
use App\Modules\Posts\PostController;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Configurações CORS
header("Access-Control-Allow-Origin: http://127.0.0.1:5500"); // Ou '*' para desenvolvimento
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

// Responder a requisições OPTIONS (pré-flight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$router = new Router();

// Auth routes
$router->add("POST", "/auth/register", [new AuthController, "register"]);
$router->add("POST", "/auth/login", [new AuthController, "login"]);


// Post routes (RESTful)
$router->add("GET", "/posts/categories", [new PostController, "categories"]);
$router->add("GET", "/posts/category/{category}", [new PostController, "getByCategory"]);

$router->add("GET", "/posts", [new PostController, "index"]); // Listar todos os posts
$router->add("POST", "/posts", [new PostController, "create"]); // Criar novo post
$router->add("GET", "/posts/{id}", [new PostController, "show"]); // Mostrar post específico
$router->add("PUT", "/posts/{id}", [new PostController, "update"]); // Atualizar post
$router->add("DELETE", "/posts/{id}", [new PostController, "delete"]); // Deletar post


// Post actions routes
$router->add("PATCH", "/posts/{id}/views", [new PostController, "incrementViews"]); // Incrementar visualizações
$router->add("PATCH", "/posts/{id}/rates", [new PostController, "incrementRates"]); // Incrementar avaliações

$router->run();