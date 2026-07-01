<?php

namespace App\Controllers;

use App\Models\ShowModel;
use App\Models\UserLikeModel;
use App\Models\UserListEntryModel;
use App\Models\UserListModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Show extends BaseController
{
    public function index()
    {
        $model = model(ShowModel::class);

        $data = [
            'show'  => $model->getShow(),
            'title' => 'Show Library',
        ];

        return view('templates/header', $data)
            . view('media/top_shows',$data)
            . view('templates/footer');
        }

     public function show_detail($id = null)
    {
        $model = model(ShowModel::class);

        $data['show'] = $model->getShow($id);

        if (empty($data['show'])) {
            throw new PageNotFoundException('Cannot find the media item: ' . $id);
        }

        $data['title'] = $data['show']['title'];
        $data['userLists'] = [];
        $data['activeListIds'] = [];
        $data['isLiked'] = false;
        $data['likesCount'] = 0;

        $likeModel = new UserLikeModel();
        $data['likesCount'] = $likeModel->countLikes('show', (int) $data['show']['id']);

        if (session()->get('logged_in')) {
            $userId = (int) session()->get('user_id');
            $listModel = new UserListModel();
            $entryModel = new UserListEntryModel();

            $listModel->ensureDefaultLists($userId);
            $lists = $listModel->getUserLists($userId);
            $listIds = array_map(static fn(array $row): int => (int) $row['id'], $lists);

            $data['userLists'] = $lists;
            $data['activeListIds'] = $entryModel->getListIdsForItem($listIds, 'show', (int) $data['show']['id']);
            $data['isLiked'] = $likeModel->isLiked($userId, 'show', (int) $data['show']['id']);
        }

        return view('templates/header', $data)
            . view('media/shows_view',$data)
            . view('templates/footer');
    }
    
}   
