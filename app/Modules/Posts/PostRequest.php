<?php

namespace App\Modules\Posts;

use App\Core\Validator;

class PostRequest
{
    public static function validateCreate()
    {
        $data = $_POST;
        $data['img'] = $_FILES['image']['name'] ?? null;
        print_r($data, true);

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

public static function validateUpdate($data): array
{ 
    $rules = [
        'title' => 'sometimes|string|max:255',
        'content' => 'sometimes|string',
        'rate' => 'sometimes|integer',
        'views' => 'sometimes|integer',
        'img' => 'sometimes|file|image|max:2048'
    ];

    // Filtrar regras apenas para campos presentes
    $applicableRules = array_intersect_key($rules, $data);
    
    return Validator::validate($data, $applicableRules);
}
}