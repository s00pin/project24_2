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
        $allMovies = array_values($mediaModel->getMedia());
        $allShows = array_values($showModel->getShow());

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

        $featuredMovies = $this->randomSubset($allMovies, 8);
        $featuredShows = $this->randomSubset($allShows, 8);

        $data = [
            'title' => 'Home',
            'featuredMovies' => $featuredMovies,
            'featuredShows' => $featuredShows,
            'popularMovies' => $popularMovies,
            'popularShows' => $popularShows,
        ];

        return view('templates/header', $data)
            . view('welcome_message', $data)
            . view('templates/footer');
    }

    private function randomSubset(array $items, int $limit): array
    {
        if ($items === [] || $limit <= 0) {
            return [];
        }

        $pool = array_values($items);
        shuffle($pool);

        return array_slice($pool, 0, min($limit, count($pool)));
    }
}
