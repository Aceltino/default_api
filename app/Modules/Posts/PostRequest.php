<?php

namespace App\Modules\Posts;

use App\Core\Validator;

class PostRequest
{
    public static function validateCreate()
    {
        $data = $_POST;
        $data['img'] = $_FILES['image']['name'] ?? null;

        $data['rate'] = isset($data['rate']) ? (int)$data['rate'] : 0;
        $data['views'] = isset($data['views']) ? (int)$data['views'] : 0;

        $validated = Validator::validate($data, [
           'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
            'rate' => 'sometimes|integer',
            'views' => 'sometimes|integer',
            'user_id' => 'required|integer|exists:users,id',
            'img' => 'sometimes|file|image|max:2048'
        ]);

        return $validated;
    }

public static function validateUpdate($data): array
{ 
    $rules = [
        'title' => 'sometimes|string|max:255',
        'content' => 'sometimes|string',
        'category' => 'sometimes|string',
        'rate' => 'sometimes|integer',
        'views' => 'sometimes|integer',
        'img' => 'sometimes|file|image|max:2048'
    ];

    // Filtrar regras apenas para campos presentes
    $applicableRules = array_intersect_key($rules, $data);
    
    return Validator::validate($data, $applicableRules);
}
}