<?php

namespace App\Models;

use CodeIgniter\Model;

class ShowModel extends Model
{
    protected $table = 'shows';
    
    
    public function getShow($id = false)
    {
        if ($id === false) {
            return $this->findAll();
        }

        return $this->where(['id' => $id])->first();
    }
    protected $allowedFields = ['id', 'title', 'seasons', 'episodes', 'genre', 'begin_date', 'end_date','runtime', 'language', 'overview', 'poster', 'background'];
}
