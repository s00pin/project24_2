<?php

namespace App\Models;

use CodeIgniter\Model;

class UserListModel extends Model
{
    protected $table = 'user_lists';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'name', 'slug', 'created_at', 'updated_at'];
    protected $useTimestamps = true;

    public function ensureDefaultLists(int $userId): void
    {
        $defaults = ['Favorites', 'Watch Later'];

        foreach ($defaults as $name) {
            $exists = $this->where(['user_id' => $userId, 'name' => $name])->first();
            if (! $exists) {
                $this->insert([
                    'user_id' => $userId,
                    'name' => $name,
                    'slug' => url_title($name, '-', true),
                ]);
            }
        }
    }

    public function getUserLists(int $userId): array
    {
        return $this->where('user_id', $userId)->orderBy('created_at', 'ASC')->findAll();
    }
}
