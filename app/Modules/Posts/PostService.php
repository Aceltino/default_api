<?php

namespace App\Modules\Posts;

use App\Modules\Posts\PostModel;
use App\Modules\Posts\PostFileUploadService;

class PostService
{
    protected $fileUploadService;

    public function __construct()
    {
        $this->fileUploadService = new PostFileUploadService();
    }

    public function getAllPosts()
    {
        return PostModel::getAll();
    }

    public function getPostById($id)
    {
        $post = PostModel::find($id);
        
        if (!$post) {
            throw new \RuntimeException('Post not found', 404);
        }

        return $post;
    }

    public function createPost($data)
    {
        // Processar upload de imagem se existir
        if (isset($_FILES['image'])) {
            $data['img'] = $this->fileUploadService->upload($_FILES['image']);
        }

        return PostModel::create($data);
    }

    public function updatePost($id, $data)
    {
        if (isset($_FILES['image'])) {
            $data['img'] = $this->fileUploadService->upload($_FILES['image']);
        }

        return PostModel::update($id, $data);
    }

    public function deletePost($id)
    {
        $post = $this->getPostById($id);
        
        if ($post['img']) {
            $this->deleteImage($post['img']);
        }

        return PostModel::delete($id);
    }

    protected function deleteImage($imagePath)
    {
        $fullPath = __DIR__ . '/../../../public' . $imagePath;
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
}