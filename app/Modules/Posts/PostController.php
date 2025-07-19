<?php

namespace App\Modules\Posts;

use App\Core\Response;
use App\Core\Request;
use App\Modules\Posts\PostService;
use App\Modules\Posts\PostRequest;
use App\Middleware\AuthMiddleware;

class PostController 
{
    private $postService;

    public function __construct()
    {
        (new AuthMiddleware())->handle(Request::capture(), function($request) {});
        
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
        $validatedData = PostRequest::validateUpdate();
        $post = $this->postService->updatePost($id, $validatedData);
        Response::json(['message' => 'Post updated', 'data' => $post]);
    }

    public function delete($id)
    {
        $this->postService->deletePost($id);
        Response::json(['message' => 'Post deleted']);
    }
}