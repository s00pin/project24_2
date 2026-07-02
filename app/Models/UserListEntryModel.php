<?php

namespace App\Models;

use CodeIgniter\Model;

class UserListEntryModel extends Model
{
    protected $table = 'user_list_entries';
    protected $primaryKey = 'id';
    protected $allowedFields = ['list_id', 'media_type', 'media_id', 'created_at', 'updated_at'];
    protected $useTimestamps = true;

    public function toggleItem(int $listId, string $mediaType, int $mediaId): bool
    {
        $existing = $this->where([
            'list_id' => $listId,
            'media_type' => $mediaType,
            'media_id' => $mediaId,
        ])->first();

        if ($existing) {
            $this->delete((int) $existing['id']);
            return false;
        }

        $this->insert([
            'list_id' => $listId,
            'media_type' => $mediaType,
            'media_id' => $mediaId,
        ]);

        return true;
    }

    public function getListIdsForItem(array $listIds, string $mediaType, int $mediaId): array
    {
        if ($listIds === []) {
            return [];
        }

        $rows = $this->select('list_id')
            ->whereIn('list_id', $listIds)
            ->where('media_type', $mediaType)
            ->where('media_id', $mediaId)
            ->findAll();

        return array_map(static fn(array $row): int => (int) $row['list_id'], $rows);
    }
}
