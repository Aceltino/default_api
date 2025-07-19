<?php

namespace App\Modules\Posts;

use App\Core\Request;
use App\Core\Validator;

class PostRequest
{
    public static function validateCreate()
    {
        $data = $_POST;
        $data['img'] = $_FILES['image']['name'] ?? null;

        $validated = Validator::validate($data, [
           'title' => 'required|string|max:255',
            'content' => 'required|string',
            'rate' => 'sometimes|integer',
            'views' => 'sometimes|integer',
            'user_id' => 'required|integer|exists:users,id',
            'img' => 'sometimes|file|image|max:2048'
        ]);

        return $validated;
    }

    public static function validateUpdate()
    {
        $data = $_POST;
        $data['img'] = $_FILES['image']['name'] ?? null;

        $validated = Validator::validate($data, [
           'title' => 'required|string|max:255',
            'content' => 'required|string',
            'rate' => 'sometimes|integer',
            'views' => 'sometimes|integer',
            'img' => 'sometimes|file|image|max:2048'
        ]);

        return $validated;
    }
}