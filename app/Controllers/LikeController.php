<?php

namespace App\Controllers;

use App\Models\UserLikeModel;

class LikeController extends BaseController
{
    public function toggle()
    {
        $userId = (int) session()->get('user_id');
        if ($userId <= 0) {
            return $this->response->setStatusCode(401)->setJSON([
                'ok' => false,
                'message' => 'Please log in first.',
            ]);
        }

        $mediaType = strtolower(trim((string) $this->request->getPost('media_type')));
        $mediaId = (int) $this->request->getPost('media_id');

        if (! in_array($mediaType, ['media', 'show'], true) || $mediaId <= 0) {
            return $this->response->setStatusCode(422)->setJSON([
                'ok' => false,
                'message' => 'Invalid payload.',
            ]);
        }

        $model = new UserLikeModel();
        $liked = $model->toggleLike($userId, $mediaType, $mediaId);
        $count = $model->countLikes($mediaType, $mediaId);

        return $this->response->setJSON([
            'ok' => true,
            'liked' => $liked,
            'likesCount' => $count,
        ]);
    }
}
