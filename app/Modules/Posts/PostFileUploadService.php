<?php

namespace App\Modules\Posts;

class PostFileUploadService
{
    protected $uploadPath = __DIR__ . '/../../../public/uploads/posts/';
    
    public function upload($file, $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'])
    {
        // Verificar se é um arquivo válido
        if (!isset($file['error']) || is_array($file['error'])) {
            throw new \RuntimeException('Invalid file parameters.');
        }

        // Verificar erros de upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('File upload failed.');
        }

        // Verificar tipo MIME
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        
        if (!in_array($mime, $allowedTypes)) {
            throw new \RuntimeException('Invalid file type.');
        }

        // Gerar nome único para o arquivo
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = sprintf('%s.%s', sha1_file($file['tmp_name']), $extension);

        // Mover arquivo para diretório de uploads
        if (!move_uploaded_file($file['tmp_name'], $this->uploadPath . $filename)) {
            throw new \RuntimeException('Failed to move uploaded file.');
        }

        return '/uploads/posts/' . $filename;
    }
}