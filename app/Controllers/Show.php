<?php

namespace App\Controllers;

use App\Models\ShowModel;
use App\Models\UserLikeModel;
use App\Models\UserListEntryModel;
use App\Models\UserListModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Show extends BaseController
{
    public function index()
    {
        $model = model(ShowModel::class);

        $data = [
            'show' => $model->getShow(),
            'title' => 'Show Library',
        ];

        return view('templates/header', $data)
            . view('media/top_shows', $data)
            . view('templates/footer');
    }

    public function show_detail($id = null)
    {
        $model = model(ShowModel::class);

        $data['show'] = $model->getShow($id);

        if (empty($data['show'])) {
            throw new PageNotFoundException('Cannot find the media item: ' . $id);
        }

        $allShows = $model->getShow();
        $data['title'] = $data['show']['title'];
        $data['userLists'] = [];
        $data['activeListIds'] = [];
        $data['isLiked'] = false;
        $data['likesCount'] = 0;
        $data['moreLikeThis'] = $this->buildMoreLikeThis($data['show'], $allShows);

        $likeModel = new UserLikeModel();
        $data['likesCount'] = $likeModel->countLikes('show', (int) $data['show']['id']);

        if (session()->get('logged_in')) {
            $userId = (int) session()->get('user_id');
            $listModel = new UserListModel();
            $entryModel = new UserListEntryModel();

            $listModel->ensureDefaultLists($userId);
            $lists = $listModel->getUserLists($userId);
            $listIds = array_map(static fn(array $row): int => (int) $row['id'], $lists);

            $data['userLists'] = $lists;
            $data['activeListIds'] = $entryModel->getListIdsForItem($listIds, 'show', (int) $data['show']['id']);
            $data['isLiked'] = $likeModel->isLiked($userId, 'show', (int) $data['show']['id']);
        }

        return view('templates/header', $data)
            . view('media/shows_view', $data)
            . view('templates/footer');
    }

    private function buildMoreLikeThis(array $currentShow, array $allShows): array
    {
        $currentId = (int) ($currentShow['id'] ?? 0);
        $currentGenres = $this->genreTokens((string) ($currentShow['genre'] ?? ''));
        $currentLanguage = strtolower(trim((string) ($currentShow['language'] ?? '')));
        $currentYear = (int) substr((string) ($currentShow['begin_date'] ?? ''), 0, 4);

        $scored = [];

        foreach ($allShows as $candidate) {
            $candidateId = (int) ($candidate['id'] ?? 0);
            if ($candidateId <= 0 || $candidateId === $currentId) {
                continue;
            }

            $score = 0;
            $candidateGenres = $this->genreTokens((string) ($candidate['genre'] ?? ''));
            $candidateLanguage = strtolower(trim((string) ($candidate['language'] ?? '')));
            $candidateYear = (int) substr((string) ($candidate['begin_date'] ?? ''), 0, 4);

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
            $show = $row['item'];

            return [
                'id' => (int) ($show['id'] ?? 0),
                'title' => (string) ($show['title'] ?? 'Untitled show'),
                'poster' => (string) ($show['poster'] ?? ''),
                'year' => ! empty($show['begin_date']) ? substr((string) $show['begin_date'], 0, 4) : '',
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
