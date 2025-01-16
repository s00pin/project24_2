<?php

namespace App\Controllers;

use App\Models\MediaModel;

class Media extends BaseController
{
    public function index()
    {
        $model = model(MediaModel::class);

        $data = [
            'media'  => $model->getMedia(),
            'title' => 'Top movies',
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

        return view('templates/header', $data)
            . view('media/movies_view',$data)
            . view('templates/footer');
    }
    
}   