<?php

namespace App\Controllers;

use App\Models\MediaModel;
use App\Models\UserLikeModel;
use App\Models\UserListEntryModel;
use App\Models\UserListModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Media extends BaseController
{
    public function index()
    {
        $model = model(MediaModel::class);

        $data = [
            'media'  => $model->getMedia(),
            'title' => 'Movie Library',
        ];

        return view('templates/header', $data)
            . view('media/top_movies',$data)
            . view('templates/footer');
        }

     public function media_detail($id = null)
    {
        $model = model(MediaModel::class);

        $data['media'] = $model->getMedia($id);

        if (empty($data['media'])) {
            throw new PageNotFoundException('Cannot find the media item: ' . $id);
        }

        $data['title'] = $data['media']['title'];
        $data['userLists'] = [];
        $data['activeListIds'] = [];
        $data['isLiked'] = false;
        $data['likesCount'] = 0;

        $likeModel = new UserLikeModel();
        $data['likesCount'] = $likeModel->countLikes('media', (int) $data['media']['id']);

        if (session()->get('logged_in')) {
            $userId = (int) session()->get('user_id');

            $listModel = new UserListModel();
            $entryModel = new UserListEntryModel();

            $listModel->ensureDefaultLists($userId);
            $lists = $listModel->getUserLists($userId);
            $listIds = array_map(static fn(array $row): int => (int) $row['id'], $lists);

            $data['userLists'] = $lists;
            $data['activeListIds'] = $entryModel->getListIdsForItem($listIds, 'media', (int) $data['media']['id']);
            $data['isLiked'] = $likeModel->isLiked($userId, 'media', (int) $data['media']['id']);
        }

        return view('templates/header', $data)
            . view('media/movies_view',$data)
            . view('templates/footer');
    }
    
}   
