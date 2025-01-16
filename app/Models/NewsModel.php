<?php

namespace App\Models;

use CodeIgniter\Model;

class NewsModel extends Model
{
    protected $table = 'news';
    protected $allowedFields = ['title', 'slug', 'body'];

    public function getNews($slug = false)
    {
        if ($slug === false) {
            return $this->findAll();
        }

        return $this->where(['slug' => $slug])->first();
    }

    public function updateNews($id, $data)
    {
        return $this->update($id, $data);
    }

    public function deleteNews($id)
    {
        return $this->delete($id);
    }
}
