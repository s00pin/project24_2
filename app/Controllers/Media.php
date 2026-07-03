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
            'media' => $model->getMedia(),
            'title' => 'Movie Library',
        ];

        return view('templates/header', $data)
            . view('media/top_movies', $data)
            . view('templates/footer');
    }

    public function media_detail($id = null)
    {
        $model = model(MediaModel::class);

        $data['media'] = $model->getMedia($id);

        if (empty($data['media'])) {
            throw new PageNotFoundException('Cannot find the media item: ' . $id);
        }

        $allMovies = $model->getMedia();
        $data['title'] = $data['media']['title'];
        $data['userLists'] = [];
        $data['activeListIds'] = [];
        $data['isLiked'] = false;
        $data['likesCount'] = 0;
        $data['moreLikeThis'] = $this->buildMoreLikeThis($data['media'], $allMovies);

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
            . view('media/movies_view', $data)
            . view('templates/footer');
    }

    private function buildMoreLikeThis(array $currentMovie, array $allMovies): array
    {
        $currentId = (int) ($currentMovie['id'] ?? 0);
        $currentGenres = $this->genreTokens((string) ($currentMovie['genre'] ?? ''));
        $currentLanguage = strtolower(trim((string) ($currentMovie['original_language'] ?? '')));
        $currentYear = (int) substr((string) ($currentMovie['release_date'] ?? ''), 0, 4);

        $scored = [];

        foreach ($allMovies as $candidate) {
            $candidateId = (int) ($candidate['id'] ?? 0);
            if ($candidateId <= 0 || $candidateId === $currentId) {
                continue;
            }

            $score = 0;
            $candidateGenres = $this->genreTokens((string) ($candidate['genre'] ?? ''));
            $candidateLanguage = strtolower(trim((string) ($candidate['original_language'] ?? '')));
            $candidateYear = (int) substr((string) ($candidate['release_date'] ?? ''), 0, 4);

            if ($currentGenres !== [] && $candidateGenres !== []) {
                $score += count(array_intersect($currentGenres, $candidateGenres)) * 4;
            }

            if ($currentLanguage !== '' && $candidateLanguage !== '' && $currentLanguage === $candidateLanguage) {
                $score += 2;
            }

            if ($currentYear > 0 && $candidateYear > 0) {
                $yearDiff = abs($currentYear - $candidateYear);
                if ($yearDiff <= 1) {
                    $score += 2;
                } elseif ($yearDiff <= 4) {
                    $score += 1;
                }
            }

            $scored[] = [
                'score' => $score,
                'item' => $candidate,
            ];
        }

        usort($scored, static function (array $a, array $b): int {
            if ($a['score'] === $b['score']) {
                return ((int) ($b['item']['id'] ?? 0)) <=> ((int) ($a['item']['id'] ?? 0));
            }

            return $b['score'] <=> $a['score'];
        });

        $top = array_slice($scored, 0, 12);

        return array_map(static function (array $row): array {
            $movie = $row['item'];

            return [
                'id' => (int) ($movie['id'] ?? 0),
                'title' => (string) ($movie['title'] ?? 'Untitled movie'),
                'poster' => (string) ($movie['poster_image'] ?? ''),
                'year' => ! empty($movie['release_date']) ? substr((string) $movie['release_date'], 0, 4) : '',
            ];
        }, $top);
    }

    private function genreTokens(string $genreValue): array
    {
        if (trim($genreValue) === '') {
            return [];
        }

        $parts = preg_split('/[,|\\/]+/', strtolower($genreValue));
        $parts = array_map(static fn(string $part): string => trim($part), $parts ?: []);
        $parts = array_filter($parts, static fn(string $part): bool => $part !== '');

        return array_values(array_unique($parts));
    }
}
