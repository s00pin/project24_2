<?php

namespace App\Controllers;

use App\Models\UserLikeModel;
use App\Models\UserListEntryModel;
use App\Models\UserListModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $userId = (int) session()->get('user_id');
        $db = db_connect();

        $listModel = new UserListModel();
        $entryModel = new UserListEntryModel();
        $likeModel = new UserLikeModel();

        $listModel->ensureDefaultLists($userId);
        $lists = $listModel->getUserLists($userId);

        foreach ($lists as &$list) {
            $list['items'] = $this->resolveListItems($entryModel, (int) $list['id'], $db);
        }
        unset($list);

        $likedItems = $this->resolveLikedItems($likeModel, $userId, $db);

        $data = [
            'title' => 'Dashboard',
            'lists' => $lists,
            'likedItems' => $likedItems,
        ];

        return view('templates/header', $data)
            . view('dashboard')
            . view('templates/footer');
    }

    private function resolveListItems(UserListEntryModel $entryModel, int $listId, $db): array
    {
        $rows = $entryModel->where('list_id', $listId)->orderBy('created_at', 'DESC')->findAll();
        $items = [];

        foreach ($rows as $row) {
            $mediaType = $row['media_type'] ?? '';
            $mediaId = (int) ($row['media_id'] ?? 0);

            if ($mediaType === 'media') {
                $record = $db->table('media')->select('id,title,poster_image')->where('id', $mediaId)->get()->getRowArray();
                if ($record) {
                    $items[] = [
                        'title' => $record['title'],
                        'url' => base_url('media/' . $record['id']),
                        'poster' => 'https://image.tmdb.org/t/p/w300/' . ltrim((string) $record['poster_image'], '/'),
                    ];
                }
                continue;
            }

            if ($mediaType === 'show') {
                $record = $db->table('shows')->select('id,title,poster')->where('id', $mediaId)->get()->getRowArray();
                if ($record) {
                    $items[] = [
                        'title' => $record['title'],
                        'url' => base_url('show/' . $record['id']),
                        'poster' => 'https://image.tmdb.org/t/p/w300/' . ltrim((string) $record['poster'], '/'),
                    ];
                }
            }
        }

        return $items;
    }

    private function resolveLikedItems(UserLikeModel $likeModel, int $userId, $db): array
    {
        $rows = $likeModel->where('user_id', $userId)->orderBy('created_at', 'DESC')->findAll();
        $items = [];

        foreach ($rows as $row) {
            $mediaType = $row['media_type'] ?? '';
            $mediaId = (int) ($row['media_id'] ?? 0);

            if ($mediaType === 'media') {
                $record = $db->table('media')->select('id,title,poster_image')->where('id', $mediaId)->get()->getRowArray();
                if ($record) {
                    $items[] = [
                        'title' => $record['title'],
                        'url' => base_url('media/' . $record['id']),
                        'poster' => 'https://image.tmdb.org/t/p/w300/' . ltrim((string) $record['poster_image'], '/'),
                    ];
                }
                continue;
            }

            if ($mediaType === 'show') {
                $record = $db->table('shows')->select('id,title,poster')->where('id', $mediaId)->get()->getRowArray();
                if ($record) {
                    $items[] = [
                        'title' => $record['title'],
                        'url' => base_url('show/' . $record['id']),
                        'poster' => 'https://image.tmdb.org/t/p/w300/' . ltrim((string) $record['poster'], '/'),
                    ];
                }
            }
        }

        return $items;
    }
}
