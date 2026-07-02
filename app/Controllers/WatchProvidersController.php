<?php

namespace App\Controllers;

class WatchProvidersController extends BaseController
{
    public function byRegion(string $type, int $id)
    {
        $mediaType = strtolower(trim($type));
        if (! in_array($mediaType, ['media', 'show'], true) || $id <= 0) {
            return $this->response->setStatusCode(422)->setJSON([
                'ok' => false,
                'message' => 'Invalid media type or ID.',
            ]);
        }

        $country = strtoupper((string) $this->request->getGet('country'));
        if (! preg_match('/^[A-Z]{2}$/', $country)) {
            $country = 'US';
        }

        $tmdbToken = (string) (env('TMDB_API_BEARER_TOKEN') ?: '');
        if ($tmdbToken === '') {
            // Fallback for this project so watch providers work locally without extra setup.
            $tmdbToken = 'eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiJmM2ZkMmM1ODhmNjViMzVlYjA4ZjRkMTliYzJmYWJiMyIsInN1YiI6IjY2MjVlYmZlNjNlNmZiMDE3ZWZjOTE1MyIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.Qb_NLScHpCLLeyTLteXvhFN273YvCBESB-vRw4O44iI';
        }

        $tmdbPath = $mediaType === 'show' ? "tv/{$id}/watch/providers" : "movie/{$id}/watch/providers";

        try {
            $client = service('curlrequest', [
                'baseURI' => 'https://api.themoviedb.org/3/',
                'http_errors' => false,
                'timeout' => 8,
                'verify' => false,
            ]);

            $response = $client->get($tmdbPath, [
                'headers' => [
                    'accept' => 'application/json',
                    'Authorization' => "Bearer {$tmdbToken}",
                ],
            ]);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(502)->setJSON([
                'ok' => false,
                'message' => 'Unable to reach watch provider service.',
            ]);
        }

        if ($response->getStatusCode() !== 200) {
            return $this->response->setStatusCode(502)->setJSON([
                'ok' => false,
                'message' => 'Watch provider service returned an error.',
            ]);
        }

        $payload = json_decode((string) $response->getBody(), true);
        $results = $payload['results'] ?? [];
        $regionData = $results[$country] ?? null;
        $resolvedCountry = $country;

        if ($regionData === null && isset($results['US'])) {
            $regionData = $results['US'];
            $resolvedCountry = 'US';
        }

        if ($regionData === null) {
            return $this->response->setJSON([
                'ok' => true,
                'country' => $country,
                'resolvedCountry' => null,
                'link' => null,
                'providers' => [],
                'message' => 'No watch providers available for this title yet.',
            ]);
        }

        $groups = ['flatrate', 'rent', 'buy', 'free', 'ads'];
        $providers = [];

        foreach ($groups as $group) {
            $items = $regionData[$group] ?? [];
            if (! is_array($items) || $items === []) {
                continue;
            }

            $providers[$group] = array_map(static function (array $item): array {
                return [
                    'name' => $item['provider_name'] ?? 'Unknown',
                    'logo' => isset($item['logo_path']) ? 'https://image.tmdb.org/t/p/w92/' . ltrim((string) $item['logo_path'], '/') : null,
                ];
            }, $items);
        }

        return $this->response->setJSON([
            'ok' => true,
            'country' => $country,
            'resolvedCountry' => $resolvedCountry,
            'link' => $regionData['link'] ?? null,
            'providers' => $providers,
        ]);
    }
}
