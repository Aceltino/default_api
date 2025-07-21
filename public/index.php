<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Modules\Auth\AuthController;
use App\Modules\Posts\PostController;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

header("Content-Type: application/json");

$router = new Router();

// Auth routes
$router->add("POST", "/auth/register", [new AuthController, "register"]);
$router->add("POST", "/auth/login", [new AuthController, "login"]);

// Post routes (RESTful)
$router->add("GET", "/posts", [new PostController, "index"]); // Listar todos os posts
$router->add("POST", "/posts", [new PostController, "create"]); // Criar novo post
$router->add("GET", "/posts/{id}", [new PostController, "show"]); // Mostrar post específico
$router->add("PUT", "/posts/{id}", [new PostController, "update"]); // Atualizar post
$router->add("DELETE", "/posts/{id}", [new PostController, "delete"]); // Deletar post

// Post actions routes
$router->add("PATCH", "/posts/{id}/views", [new PostController, "incrementViews"]); // Incrementar visualizações
$router->add("PATCH", "/posts/{id}/rates", [new PostController, "incrementRates"]); // Incrementar avaliações

$router->run();