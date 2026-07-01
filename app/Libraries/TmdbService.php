<?php

namespace App\Libraries;

use App\Models\MediaModel;
use App\Models\ShowModel;

class TmdbService
{
    private string $token;

    public function __construct()
    {
        $this->token = (string) (env('TMDB_API_BEARER_TOKEN') ?: '');
        if ($this->token === '') {
            $this->token = 'eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiJmM2ZkMmM1ODhmNjViMzVlYjA4ZjRkMTliYzJmYWJiMyIsInN1YiI6IjY2MjVlYmZlNjNlNmZiMDE3ZWZjOTE1MyIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.Qb_NLScHpCLLeyTLteXvhFN273YvCBESB-vRw4O44iI';
        }
    }

    private function getClient()
    {
        return service('curlrequest', [
            'baseURI' => 'https://api.themoviedb.org/3/',
            'http_errors' => false,
            'timeout' => 10,
            'verify' => false,
        ]);
    }

    private function get(string $path, array $query = []): array
    {
        try {
            $response = $this->getClient()->get($path, [
                'headers' => [
                    'accept' => 'application/json',
                    'Authorization' => "Bearer {$this->token}",
                ],
                'query' => $query,
            ]);
        } catch (\Throwable $e) {
            return [];
        }

        if ($response->getStatusCode() !== 200) {
            return [];
        }

        $json = json_decode((string) $response->getBody(), true);
        return is_array($json) ? $json : [];
    }

    public function searchAndImport(string $query, int $limit = 30): array
    {
        $payload = $this->get('search/multi', [
            'query' => $query,
            'language' => 'en-US',
            'include_adult' => 'false',
            'page' => 1,
        ]);

        $results = $payload['results'] ?? [];
        if (! is_array($results) || $results === []) {
            return ['media' => [], 'shows' => []];
        }

        $mediaModel = new MediaModel();
        $showModel = new ShowModel();
        $media = [];
        $shows = [];

        foreach ($results as $item) {
            if ((count($media) + count($shows)) >= $limit) {
                break;
            }

            $type = $item['media_type'] ?? '';
            if ($type === 'movie') {
                $row = [
                    'id' => (int) ($item['id'] ?? 0),
                    'title' => trim((string) ($item['title'] ?? '')),
                    'original_language' => (string) ($item['original_language'] ?? 'en'),
                    'overview' => (string) ($item['overview'] ?? ''),
                    'release_date' => !empty($item['release_date']) ? (string) $item['release_date'] : null,
                    'genre' => isset($item['genre_ids']) ? implode(', ', array_map('strval', (array) $item['genre_ids'])) : null,
                    'background_image' => isset($item['backdrop_path']) ? ltrim((string) $item['backdrop_path'], '/') : null,
                    'poster_image' => isset($item['poster_path']) ? ltrim((string) $item['poster_path'], '/') : null,
                ];

                if ($row['id'] <= 0 || $row['title'] === '') {
                    continue;
                }

                $this->upsertMedia($mediaModel, $row);
                $media[] = $mediaModel->find($row['id']);
                continue;
            }

            if ($type === 'tv') {
                $row = [
                    'id' => (int) ($item['id'] ?? 0),
                    'title' => trim((string) ($item['name'] ?? '')),
                    'seasons' => 1,
                    'episodes' => 1,
                    'genre' => isset($item['genre_ids']) ? implode(', ', array_map('strval', (array) $item['genre_ids'])) : null,
                    'begin_date' => !empty($item['first_air_date']) ? (string) $item['first_air_date'] : null,
                    'end_date' => null,
                    'runtime' => 45,
                    'language' => (string) ($item['original_language'] ?? 'en'),
                    'overview' => (string) ($item['overview'] ?? ''),
                    'poster' => isset($item['poster_path']) ? ltrim((string) $item['poster_path'], '/') : null,
                    'background' => isset($item['backdrop_path']) ? ltrim((string) $item['backdrop_path'], '/') : null,
                ];

                if ($row['id'] <= 0 || $row['title'] === '') {
                    continue;
                }

                $this->upsertShow($showModel, $row);
                $shows[] = $showModel->find($row['id']);
            }
        }

        return ['media' => array_values(array_filter($media)), 'shows' => array_values(array_filter($shows))];
    }

    public function importPopular(int $movieCount = 100, int $showCount = 100): array
    {
        $mediaModel = new MediaModel();
        $showModel = new ShowModel();
        $moviesImported = 0;
        $showsImported = 0;

        for ($page = 1; $moviesImported < $movieCount && $page <= 12; $page++) {
            $payload = $this->get('movie/popular', ['language' => 'en-US', 'page' => $page]);
            foreach (($payload['results'] ?? []) as $item) {
                if ($moviesImported >= $movieCount) {
                    break;
                }

                $id = (int) ($item['id'] ?? 0);
                $title = trim((string) ($item['title'] ?? ''));
                if ($id <= 0 || $title === '') {
                    continue;
                }

                $this->upsertMedia($mediaModel, [
                    'id' => $id,
                    'title' => $title,
                    'original_language' => (string) ($item['original_language'] ?? 'en'),
                    'overview' => (string) ($item['overview'] ?? ''),
                    'release_date' => !empty($item['release_date']) ? (string) $item['release_date'] : null,
                    'genre' => isset($item['genre_ids']) ? implode(', ', array_map('strval', (array) $item['genre_ids'])) : null,
                    'background_image' => isset($item['backdrop_path']) ? ltrim((string) $item['backdrop_path'], '/') : null,
                    'poster_image' => isset($item['poster_path']) ? ltrim((string) $item['poster_path'], '/') : null,
                ]);
                $moviesImported++;
            }
        }

        for ($page = 1; $showsImported < $showCount && $page <= 12; $page++) {
            $payload = $this->get('tv/popular', ['language' => 'en-US', 'page' => $page]);
            foreach (($payload['results'] ?? []) as $item) {
                if ($showsImported >= $showCount) {
                    break;
                }

                $id = (int) ($item['id'] ?? 0);
                $title = trim((string) ($item['name'] ?? ''));
                if ($id <= 0 || $title === '') {
                    continue;
                }

                $this->upsertShow($showModel, [
                    'id' => $id,
                    'title' => $title,
                    'seasons' => 1,
                    'episodes' => 1,
                    'genre' => isset($item['genre_ids']) ? implode(', ', array_map('strval', (array) $item['genre_ids'])) : null,
                    'begin_date' => !empty($item['first_air_date']) ? (string) $item['first_air_date'] : null,
                    'end_date' => null,
                    'runtime' => 45,
                    'language' => (string) ($item['original_language'] ?? 'en'),
                    'overview' => (string) ($item['overview'] ?? ''),
                    'poster' => isset($item['poster_path']) ? ltrim((string) $item['poster_path'], '/') : null,
                    'background' => isset($item['backdrop_path']) ? ltrim((string) $item['backdrop_path'], '/') : null,
                ]);
                $showsImported++;
            }
        }

        return ['movies' => $moviesImported, 'shows' => $showsImported];
    }

    private function upsertMedia(MediaModel $model, array $row): void
    {
        $existing = $model->find($row['id']);
        if ($existing) {
            $model->update($row['id'], $row);
        } else {
            $model->insert($row);
        }
    }

    private function upsertShow(ShowModel $model, array $row): void
    {
        $existing = $model->find($row['id']);
        if ($existing) {
            $model->update($row['id'], $row);
        } else {
            $model->insert($row);
        }
    }
}
