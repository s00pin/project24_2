<?php

namespace App\Database\Seeds;

use App\Libraries\TmdbService;
use CodeIgniter\Database\Seeder;

class CatalogExpansionSeeder extends Seeder
{
    public function run()
    {
        $service = new TmdbService();
        $result = $service->importPopular(100, 100);

        echo 'Imported movies: ' . (int) ($result['movies'] ?? 0) . PHP_EOL;
        echo 'Imported shows: ' . (int) ($result['shows'] ?? 0) . PHP_EOL;
    }
}
