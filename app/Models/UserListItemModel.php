<?php

namespace App\Models;

use CodeIgniter\Model;

class UserListItemModel extends Model
{
    protected $table = 'user_list_items';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',
        'media_type',
        'media_id',
        'list_type',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;

    public function hasItem(int $userId, string $mediaType, int $mediaId, string $listType): bool
    {
        return $this->where([
            'user_id'    => $userId,
            'media_type' => $mediaType,
            'media_id'   => $mediaId,
            'list_type'  => $listType,
        ])->first() !== null;
    }

    public function toggleItem(int $userId, string $mediaType, int $mediaId, string $listType): bool
    {
        $existing = $this->where([
            'user_id'    => $userId,
            'media_type' => $mediaType,
            'media_id'   => $mediaId,
            'list_type'  => $listType,
        ])->first();

        if ($existing) {
            $this->delete((int) $existing['id']);
            return false;
        }

        $this->insert([
            'user_id'    => $userId,
            'media_type' => $mediaType,
            'media_id'   => $mediaId,
            'list_type'  => $listType,
        ]);

        return true;
    }

    public function getItemStates(int $userId, string $mediaType, int $mediaId): array
    {
        $rows = $this->select('list_type')
            ->where([
                'user_id'    => $userId,
                'media_type' => $mediaType,
                'media_id'   => $mediaId,
            ])
            ->findAll();

        $states = [
            'favorite' => false,
            'watchlist' => false,
        ];

        foreach ($rows as $row) {
            $type = $row['list_type'] ?? '';
            if (array_key_exists($type, $states)) {
                $states[$type] = true;
            }
        }

        return $states;
    }
}
