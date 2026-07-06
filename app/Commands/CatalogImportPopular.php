<?php

namespace App\Commands;

use App\Libraries\TmdbService;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CatalogImportPopular extends BaseCommand
{
    protected $group       = 'Catalog';
    protected $name        = 'catalog:import-popular';
    protected $description = 'Import popular movies and shows from TMDB into the local catalog.';
    protected $usage       = 'catalog:import-popular [--movies <count>] [--shows <count>] [--only-if-empty]';
    protected $options     = [
        '--movies'        => 'How many popular movies to import (default: 100).',
        '--shows'         => 'How many popular shows to import (default: 100).',
        '--only-if-empty' => 'Skip import when both media and shows tables already have records.',
    ];

    public function run(array $params)
    {
        $movieCount = $this->sanitizeCount(CLI::getOption('movies'), 100);
        $showCount  = $this->sanitizeCount(CLI::getOption('shows'), 100);
        $onlyIfEmpty = CLI::getOption('only-if-empty') !== null;

        if ($onlyIfEmpty) {
            $db = db_connect();
            $existingMovies = (int) $db->table('media')->countAllResults();
            $existingShows  = (int) $db->table('shows')->countAllResults();

            if ($existingMovies > 0 && $existingShows > 0) {
                CLI::write(
                    'Skipped catalog import: media and shows tables are already populated.',
                    'yellow'
                );
                return;
            }
        }

        CLI::write(
            "Starting TMDB import (movies: {$movieCount}, shows: {$showCount})...",
            'yellow'
        );

        $service = new TmdbService();
        $result  = $service->importPopular($movieCount, $showCount);

        $importedMovies = (int) ($result['movies'] ?? 0);
        $importedShows  = (int) ($result['shows'] ?? 0);

        CLI::write("Imported movies: {$importedMovies}", 'green');
        CLI::write("Imported shows: {$importedShows}", 'green');
    }

    private function sanitizeCount($value, int $default): int
    {
        if ($value === null || $value === '') {
            return $default;
        }

        $count = is_numeric($value) ? (int) $value : $default;

        if ($count < 1) {
            return 1;
        }

        if ($count > 500) {
            return 500;
        }

        return $count;
    }
}

