<?php

namespace App\Modules\Posts;

use App\Core\Response;
use App\Core\Request;
use App\Core\RequestParser;
use App\Modules\Posts\PostService;
use App\Modules\Posts\PostRequest;
use App\Middleware\AuthMiddleware;

class PostController
{
    private $postService;

    public function __construct()
    {
        (new AuthMiddleware())->handle(Request::capture(), function ($request) {});

        $this->postService = new PostService();
    }

    public function index()
    {
        $posts = $this->postService->getAllPosts();
        Response::json($posts);
    }

    public function show($id)
    {
        $post = $this->postService->getPostById($id);
        Response::json($post);
    }

    public function create()
    {
        $validatedData = PostRequest::validateCreate();
        $post = $this->postService->createPost($validatedData);
        Response::json(['message' => 'Post created', 'data' => $post], 201);
    }

    public function update($id)
    {
        // Parse manual dos dados
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        if (strpos($contentType, 'multipart/form-data') !== false && $_SERVER['REQUEST_METHOD'] === 'PUT') {
            $data = RequestParser::parsePutFormData();
                    print_r("Dados: " . print_r($data, true));

        } else {
            $data = array_merge($_POST, $_FILES);
                    print_r("Dados recebidos: " . print_r($data, true));

        }

        // Validação
        $validatedData = PostRequest::validateUpdate($data);

        if (isset($data['img']) && is_array($data['img'])) {
            // Criar arquivo temporário no formato esperado
            $tempFile = $this->createTempFile($data['img']);
            $validatedData['img'] = $this->postService->handleImageUpload($tempFile);
        }
        // Atualização
        $updatedPost = $this->postService->updatePost($id, $validatedData);

        Response::json([
            'message' => 'Post updated successfully',
            'data' => $updatedPost
        ]);
    }

    public function delete($id)
    {
        $this->postService->deletePost($id);
        Response::json(['message' => 'Post deleted']);
    }

    public function incrementViews(int $id): bool
    {
        $post = $this->postService->getPostById($id);
        return PostModel::update($id, ['views' => $post['views'] + 1]);
    }

    public function incrementRates(int $id): bool
    {
        $post = $this->postService->getPostById($id);
        return PostModel::update($id, ['rate' => $post['rate'] + 1]);
    }

    private function createTempFile(array $fileData): array
    {
        $tempPath = tempnam(sys_get_temp_dir(), 'putupload');
        file_put_contents($tempPath, $fileData['content']);

        return [
            'name' => $fileData['filename'],
            'type' => $fileData['content-type'],
            'tmp_name' => $tempPath,
            'error' => 0,
            'size' => $fileData['size']
        ];
    }
}
