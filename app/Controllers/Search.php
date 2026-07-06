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

        $type = strtolower(trim((string) $this->request->getGet('type')));
        if (! in_array($type, ['all', 'media', 'show'], true)) {
            $type = 'all';
        }

        $sort = strtolower(trim((string) $this->request->getGet('sort')));
        if (! in_array($sort, ['random', 'title', 'newest', 'oldest'], true)) {
            $sort = 'random';
        }

        $yearFromRaw = trim((string) $this->request->getGet('year_from'));
        $yearToRaw = trim((string) $this->request->getGet('year_to'));

        $yearFrom = ctype_digit($yearFromRaw) ? (int) $yearFromRaw : 0;
        $yearTo = ctype_digit($yearToRaw) ? (int) $yearToRaw : 0;

        if ($yearFrom < 1900 || $yearFrom > 2100) {
            $yearFrom = 0;
        }

        if ($yearTo < 1900 || $yearTo > 2100) {
            $yearTo = 0;
        }

        if ($yearFrom > 0 && $yearTo > 0 && $yearFrom > $yearTo) {
            [$yearFrom, $yearTo] = [$yearTo, $yearFrom];
        }

        $mediaModel = new MediaModel();
        $showModel = new ShowModel();

        if ($query !== '') {
            $mediaRows = $mediaModel->like('title', $query, 'both')->findAll(80);
            $showRows = $showModel->like('title', $query, 'both')->findAll(80);

            if (count($mediaRows) + count($showRows) === 0) {
                $tmdb = new TmdbService();
                $imported = $tmdb->searchAndImport($query, 30);
                $mediaRows = $imported['media'];
                $showRows = $imported['shows'];
            }
        } else {
            $mediaRows = $mediaModel->findAll(120);
            $showRows = $showModel->findAll(120);
        }

        $results = $this->buildResults($mediaRows, $showRows);
        $results = $this->filterResults($results, $type, $yearFrom, $yearTo);
        $results = $this->sortResults($results, $sort);

        $movieCount = 0;
        $showCount = 0;
        foreach ($results as $item) {
            if (($item['type'] ?? '') === 'media') {
                $movieCount++;
                continue;
            }

            if (($item['type'] ?? '') === 'show') {
                $showCount++;
            }
        }

        $data = [
            'title' => 'Search',
            'query' => $query,
            'results' => $results,
            'counts' => [
                'results' => count($results),
                'movies' => $movieCount,
                'shows' => $showCount,
            ],
            'filters' => [
                'type' => $type,
                'year_from' => $yearFrom,
                'year_to' => $yearTo,
                'sort' => $sort,
            ],
        ];

        return view('templates/header', $data)
            . view('media/search_results', $data)
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

    private function buildResults(array $mediaRows, array $showRows): array
    {
        $results = [];

        foreach ($mediaRows as $item) {
            $year = $this->extractYear($item['release_date'] ?? null);
            $results[] = [
                'id' => (int) ($item['id'] ?? 0),
                'title' => (string) ($item['title'] ?? 'Untitled movie'),
                'type' => 'media',
                'label' => 'Movie',
                'poster' => (string) ($item['poster_image'] ?? ''),
                'year' => $year > 0 ? (string) $year : '',
                'year_int' => $year,
            ];
        }

        foreach ($showRows as $item) {
            $year = $this->extractYear($item['begin_date'] ?? null);
            $results[] = [
                'id' => (int) ($item['id'] ?? 0),
                'title' => (string) ($item['title'] ?? 'Untitled show'),
                'type' => 'show',
                'label' => 'Show',
                'poster' => (string) ($item['poster'] ?? ''),
                'year' => $year > 0 ? (string) $year : '',
                'year_int' => $year,
            ];
        }

        return array_values(array_filter($results, static fn(array $item): bool => (int) ($item['id'] ?? 0) > 0));
    }

    private function filterResults(array $results, string $type, int $yearFrom, int $yearTo): array
    {
        $filtered = array_filter($results, static function (array $item) use ($type, $yearFrom, $yearTo): bool {
            $itemType = (string) ($item['type'] ?? '');
            $year = (int) ($item['year_int'] ?? 0);

            if ($type === 'media' && $itemType !== 'media') {
                return false;
            }

            if ($type === 'show' && $itemType !== 'show') {
                return false;
            }

            if ($yearFrom > 0 && ($year === 0 || $year < $yearFrom)) {
                return false;
            }

            if ($yearTo > 0 && ($year === 0 || $year > $yearTo)) {
                return false;
            }

            return true;
        });

        return array_values($filtered);
    }

    private function sortResults(array $results, string $sort): array
    {
        switch ($sort) {
            case 'title':
                usort($results, static fn(array $a, array $b): int => strcasecmp((string) $a['title'], (string) $b['title']));
                return $results;

            case 'newest':
                usort($results, static function (array $a, array $b): int {
                    $aYear = (int) ($a['year_int'] ?? 0);
                    $bYear = (int) ($b['year_int'] ?? 0);

                    if ($aYear === $bYear) {
                        return strcasecmp((string) $a['title'], (string) $b['title']);
                    }

                    if ($aYear === 0) {
                        return 1;
                    }

                    if ($bYear === 0) {
                        return -1;
                    }

                    return $bYear <=> $aYear;
                });
                return $results;

            case 'oldest':
                usort($results, static function (array $a, array $b): int {
                    $aYear = (int) ($a['year_int'] ?? 0);
                    $bYear = (int) ($b['year_int'] ?? 0);

                    if ($aYear === $bYear) {
                        return strcasecmp((string) $a['title'], (string) $b['title']);
                    }

                    if ($aYear === 0) {
                        return 1;
                    }

                    if ($bYear === 0) {
                        return -1;
                    }

                    return $aYear <=> $bYear;
                });
                return $results;

            case 'random':
            default:
                shuffle($results);
                return $results;
        }
    }

    private function extractYear(?string $dateValue): int
    {
        if (! is_string($dateValue) || trim($dateValue) === '') {
            return 0;
        }

        if (preg_match('/^(\d{4})/', $dateValue, $matches) !== 1) {
            return 0;
        }

        return (int) $matches[1];
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
