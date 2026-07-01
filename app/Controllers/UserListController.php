<?php

namespace App\Controllers;

use App\Models\UserListEntryModel;
use App\Models\UserListModel;

class UserListController extends BaseController
{
    public function all()
    {
        $userId = (int) session()->get('user_id');
        if ($userId <= 0) {
            return $this->response->setStatusCode(401)->setJSON(['ok' => false, 'message' => 'Please log in first.']);
        }

        $listModel = new UserListModel();
        $listModel->ensureDefaultLists($userId);

        return $this->response->setJSON([
            'ok' => true,
            'lists' => $listModel->getUserLists($userId),
        ]);
    }

    public function create()
    {
        $userId = (int) session()->get('user_id');
        if ($userId <= 0) {
            return $this->response->setStatusCode(401)->setJSON(['ok' => false, 'message' => 'Please log in first.']);
        }

        $name = trim((string) $this->request->getPost('name'));
        if ($name === '' || mb_strlen($name) > 120) {
            return $this->response->setStatusCode(422)->setJSON(['ok' => false, 'message' => 'List name is required (max 120 chars).']);
        }

        $listModel = new UserListModel();
        $existing = $listModel->where(['user_id' => $userId, 'name' => $name])->first();
        if ($existing) {
            return $this->response->setStatusCode(409)->setJSON(['ok' => false, 'message' => 'List name already exists.']);
        }

        $id = $listModel->insert([
            'user_id' => $userId,
            'name' => $name,
            'slug' => url_title($name, '-', true),
        ]);

        return $this->response->setJSON([
            'ok' => true,
            'list' => $listModel->find($id),
        ]);
    }

    public function rename(int $id)
    {
        $userId = (int) session()->get('user_id');
        if ($userId <= 0) {
            return $this->response->setStatusCode(401)->setJSON(['ok' => false, 'message' => 'Please log in first.']);
        }

        $name = trim((string) $this->request->getPost('name'));
        if ($name === '' || mb_strlen($name) > 120) {
            return $this->response->setStatusCode(422)->setJSON(['ok' => false, 'message' => 'List name is required (max 120 chars).']);
        }

        $listModel = new UserListModel();
        $list = $listModel->where(['id' => $id, 'user_id' => $userId])->first();
        if (! $list) {
            return $this->response->setStatusCode(404)->setJSON(['ok' => false, 'message' => 'List not found.']);
        }

        $listModel->update($id, [
            'name' => $name,
            'slug' => url_title($name, '-', true),
        ]);

        return $this->response->setJSON([
            'ok' => true,
            'list' => $listModel->find($id),
        ]);
    }

    public function delete(int $id)
    {
        $userId = (int) session()->get('user_id');
        if ($userId <= 0) {
            return $this->response->setStatusCode(401)->setJSON(['ok' => false, 'message' => 'Please log in first.']);
        }

        $listModel = new UserListModel();
        $list = $listModel->where(['id' => $id, 'user_id' => $userId])->first();
        if (! $list) {
            return $this->response->setStatusCode(404)->setJSON(['ok' => false, 'message' => 'List not found.']);
        }

        $protected = ['favorites', 'watch-later'];
        if (in_array((string) ($list['slug'] ?? ''), $protected, true)) {
            return $this->response->setStatusCode(403)->setJSON(['ok' => false, 'message' => 'Default lists cannot be deleted.']);
        }

        $listModel->delete($id);

        return $this->response->setJSON([
            'ok' => true,
            'message' => 'List deleted.',
        ]);
    }

    public function toggleItem()
    {
        $userId = (int) session()->get('user_id');
        if ($userId <= 0) {
            return $this->response->setStatusCode(401)->setJSON(['ok' => false, 'message' => 'Please log in first.']);
        }

        $listId = (int) $this->request->getPost('list_id');
        $mediaType = strtolower(trim((string) $this->request->getPost('media_type')));
        $mediaId = (int) $this->request->getPost('media_id');

        if ($listId <= 0 || ! in_array($mediaType, ['media', 'show'], true) || $mediaId <= 0) {
            return $this->response->setStatusCode(422)->setJSON(['ok' => false, 'message' => 'Invalid payload.']);
        }

        $listModel = new UserListModel();
        $list = $listModel->where(['id' => $listId, 'user_id' => $userId])->first();
        if (! $list) {
            return $this->response->setStatusCode(404)->setJSON(['ok' => false, 'message' => 'List not found.']);
        }

        $entryModel = new UserListEntryModel();
        $inList = $entryModel->toggleItem($listId, $mediaType, $mediaId);

        return $this->response->setJSON([
            'ok' => true,
            'inList' => $inList,
            'listId' => $listId,
            'mediaType' => $mediaType,
            'mediaId' => $mediaId,
        ]);
    }

    public function itemMemberships()
    {
        $userId = (int) session()->get('user_id');
        if ($userId <= 0) {
            return $this->response->setStatusCode(401)->setJSON(['ok' => false, 'message' => 'Please log in first.']);
        }

        $mediaType = strtolower(trim((string) $this->request->getGet('media_type')));
        $mediaId = (int) $this->request->getGet('media_id');

        if (! in_array($mediaType, ['media', 'show'], true) || $mediaId <= 0) {
            return $this->response->setStatusCode(422)->setJSON(['ok' => false, 'message' => 'Invalid payload.']);
        }

        $listModel = new UserListModel();
        $lists = $listModel->getUserLists($userId);
        $listIds = array_map(static fn(array $row): int => (int) $row['id'], $lists);

        $entryModel = new UserListEntryModel();
        $activeIds = $entryModel->getListIdsForItem($listIds, $mediaType, $mediaId);

        return $this->response->setJSON([
            'ok' => true,
            'activeListIds' => $activeIds,
            'lists' => $lists,
        ]);
    }
}
