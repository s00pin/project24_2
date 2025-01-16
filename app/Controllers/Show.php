<?php

namespace App\Controllers;

use App\Models\ShowModel;

class Show extends BaseController
{
    public function index()
    {
        $model = model(ShowModel::class);

        $data = [
            'show'  => $model->getShow(),
            'title' => 'Top shows',
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

        return view('templates/header', $data)
            . view('media/shows_view',$data)
            . view('templates/footer');
    }
    
}   