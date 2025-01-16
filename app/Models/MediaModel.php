<?php

namespace App\Models;

use CodeIgniter\Model;

class MediaModel extends Model
{
    protected $table = 'media';
    
    
    public function getMedia($id = false)
    {
        if ($id === false) {
            return $this->findAll();
        }

        return $this->where(['id' => $id])->first();
    }
    protected $allowedFields = ['title', 'original_language', 'overview', 'release_date', 'genre', 'background_image', 'poster_image'];
}
