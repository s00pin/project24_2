<?php

namespace App\Models;

use CodeIgniter\Model;

class UserLikeModel extends Model
{
    protected $table = 'user_likes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'media_type', 'media_id', 'created_at', 'updated_at'];
    protected $useTimestamps = true;

    public function toggleLike(int $userId, string $mediaType, int $mediaId): bool
    {
        $existing = $this->where([
            'user_id' => $userId,
            'media_type' => $mediaType,
            'media_id' => $mediaId,
        ])->first();

        if ($existing) {
            $this->delete((int) $existing['id']);
            return false;
        }

        $this->insert([
            'user_id' => $userId,
            'media_type' => $mediaType,
            'media_id' => $mediaId,
        ]);

        return true;
    }

    public function isLiked(int $userId, string $mediaType, int $mediaId): bool
    {
        return $this->where([
            'user_id' => $userId,
            'media_type' => $mediaType,
            'media_id' => $mediaId,
        ])->first() !== null;
    }

    public function countLikes(string $mediaType, int $mediaId): int
    {
        return (int) $this->where(['media_type' => $mediaType, 'media_id' => $mediaId])->countAllResults();
    }
}
