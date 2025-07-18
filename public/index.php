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

// Post routes
$router->add("GET", "/posts", [new PostController, "index"]);
$router->add("POST", "/posts", [new PostController, "create"]);
$router->add("GET", "/posts/show", [new PostController, "show"]);
$router->add("PUT", "/posts/update", [new PostController, "update"]);
$router->add("DELETE", "/posts/delete", [new PostController, "delete"]);

$router->run();