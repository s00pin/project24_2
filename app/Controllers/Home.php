<?php

namespace App\Controllers;

use App\Models\MediaModel;
use App\Models\ShowModel;

class Home extends BaseController
{
    public function index(): string
    {
        $mediaModel = model(MediaModel::class);
        $showModel = model(ShowModel::class);
        $db = db_connect();

        $popularMovies = $db->table('media m')
            ->select('m.*, COUNT(ul.id) AS likes_count')
            ->join('user_likes ul', "ul.media_type = 'media' AND ul.media_id = m.id", 'left')
            ->groupBy('m.id')
            ->orderBy('likes_count', 'DESC')
            ->orderBy('m.id', 'DESC')
            ->limit(6)
            ->get()
            ->getResultArray();

        $popularShows = $db->table('shows s')
            ->select('s.*, COUNT(ul.id) AS likes_count')
            ->join('user_likes ul', "ul.media_type = 'show' AND ul.media_id = s.id", 'left')
            ->groupBy('s.id')
            ->orderBy('likes_count', 'DESC')
            ->orderBy('s.id', 'DESC')
            ->limit(6)
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Home',
            'featuredMovies' => array_slice($mediaModel->getMedia(), 0, 4),
            'featuredShows' => array_slice($showModel->getShow(), 0, 4),
            'popularMovies' => $popularMovies,
            'popularShows' => $popularShows,
        ];

        return view('templates/header', $data)
            . view('welcome_message', $data)
            . view('templates/footer');
    }
}
