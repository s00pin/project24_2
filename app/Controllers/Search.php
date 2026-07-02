<?php

namespace App\Controllers;

use App\Libraries\TmdbService;
use App\Models\MediaModel;
use App\Models\ShowModel;

class Search extends BaseController
{
    public function index()
    {
        $query = trim((string) $this->request->getGet('query'));
        $mediaModel = new MediaModel();
        $showModel = new ShowModel();
        $data = ['title' => 'Search Results'];

        if (!empty($query)) {
            $media = $mediaModel->like('title', $query, 'both')->findAll(30);
            $shows = $showModel->like('title', $query, 'both')->findAll(30);

            if (count($media) + count($shows) === 0) {
                $tmdb = new TmdbService();
                $imported = $tmdb->searchAndImport($query, 30);
                $media = $imported['media'];
                $shows = $imported['shows'];
            }
        } else {
            $media = $mediaModel->findAll();
            $shows = $showModel->findAll();
        }

        return view('templates/header', $data)
            . view('media/search_results', ['media' => $media, 'shows' => $shows, 'query' => $query])
            . view('templates/footer');
    }

    public function searchSuggestions()
    {
        $searchTerm = trim((string) $this->request->getVar('query'));

        if (strlen($searchTerm) < 2) {
            return $this->response->setJSON($this->popularSuggestions());
        }

        $mediaModel = new MediaModel();
        $showModel = new ShowModel();

        $mediaResults = $mediaModel
            ->like('title', $searchTerm, 'both')
            ->limit(5)
            ->findAll();

        $showResults = $showModel
            ->like('title', $searchTerm, 'both')
            ->limit(5)
            ->findAll();

        if (count($mediaResults) + count($showResults) === 0 && strlen($searchTerm) >= 3) {
            $tmdb = new TmdbService();
            $imported = $tmdb->searchAndImport($searchTerm, 10);
            $mediaResults = $imported['media'];
            $showResults = $imported['shows'];
        }

        $suggestions = [];

        foreach ($mediaResults as $media) {
            $suggestions[] = [
                'Title' => $media['title'],
                'Poster' => $media['poster_image'] ?? null,
                'ID' => $media['id'],
                'Type' => 'media',
            ];
        }

        foreach ($showResults as $show) {
            $suggestions[] = [
                'Title' => $show['title'],
                'Poster' => $show['poster'] ?? null,
                'ID' => $show['id'],
                'Type' => 'show',
            ];
        }

        return $this->response->setJSON($suggestions);
    }

    private function popularSuggestions(): array
    {
        $db = db_connect();
        $movieLikeRows = $db->table('user_likes')
            ->select('media_id, COUNT(*) as c')
            ->where('media_type', 'media')
            ->groupBy('media_id')
            ->orderBy('c', 'DESC')
            ->limit(3)
            ->get()
            ->getResultArray();

        $showLikeRows = $db->table('user_likes')
            ->select('media_id, COUNT(*) as c')
            ->where('media_type', 'show')
            ->groupBy('media_id')
            ->orderBy('c', 'DESC')
            ->limit(3)
            ->get()
            ->getResultArray();

        $suggestions = [];

        foreach ($movieLikeRows as $row) {
            $movie = $db->table('media')->where('id', (int) $row['media_id'])->get()->getRowArray();
            if ($movie) {
                $suggestions[] = [
                    'Title' => $movie['title'],
                    'Poster' => $movie['poster_image'] ?? null,
                    'ID' => $movie['id'],
                    'Type' => 'media',
                ];
            }
        }

        foreach ($showLikeRows as $row) {
            $show = $db->table('shows')->where('id', (int) $row['media_id'])->get()->getRowArray();
            if ($show) {
                $suggestions[] = [
                    'Title' => $show['title'],
                    'Poster' => $show['poster'] ?? null,
                    'ID' => $show['id'],
                    'Type' => 'show',
                ];
            }
        }

        if ($suggestions === []) {
            $mediaFallback = $db->table('media')->select('id,title,poster_image')->orderBy('id', 'DESC')->limit(3)->get()->getResultArray();
            $showFallback = $db->table('shows')->select('id,title,poster')->orderBy('id', 'DESC')->limit(3)->get()->getResultArray();

            foreach ($mediaFallback as $movie) {
                $suggestions[] = [
                    'Title' => $movie['title'],
                    'Poster' => $movie['poster_image'] ?? null,
                    'ID' => $movie['id'],
                    'Type' => 'media',
                ];
            }

            foreach ($showFallback as $show) {
                $suggestions[] = [
                    'Title' => $show['title'],
                    'Poster' => $show['poster'] ?? null,
                    'ID' => $show['id'],
                    'Type' => 'show',
                ];
            }
        }

        return array_slice($suggestions, 0, 6);
    }
}
