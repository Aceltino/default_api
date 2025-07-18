<?php

namespace App\Modules\Posts;

use App\Core\Database;

class PostController {
    public function index() {
        $pdo = Database::connect();
        $stmt = $pdo->query("SELECT * FROM posts");
        echo json_encode($stmt->fetchAll());
    }

    public function create() {
        $data = json_decode(file_get_contents('php://input'), true);
        $pdo = Database::connect();

        $stmt = $pdo->prepare("INSERT INTO posts (title, content, user_id, img) VALUES (?, ?, ?, ?)");
        $stmt->execute([$data['title'], $data['content'], $data['user_id']]);

        echo json_encode(["message" => "Post created"]);
    }

    public function show() {
        $id = $_GET['id'] ?? null;
        $pdo = Database::connect();

        $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
        $stmt->execute([$id]);

        echo json_encode($stmt->fetch());
    }

    public function update() {
        $data = json_decode(file_get_contents('php://input'), true);
        $pdo = Database::connect();

        $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ?, img  = ? WHERE id = ?");
        $stmt->execute([$data['title'], $data['content'], $data['id']]);

        echo json_encode(["message" => "Post updated"]);
    }

    public function delete() {
        $data = json_decode(file_get_contents('php://input'), true);
        $pdo = Database::connect();

        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->execute([$data['id']]);

        echo json_encode(["message" => "Post deleted"]);
    }
}
