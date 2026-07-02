<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $this->db->table('users')->ignore(true)->insert([
            'username' => 'demo',
            'email' => 'demo@example.com',
            'password_hash' => password_hash('Demo@123', PASSWORD_DEFAULT),
            'role' => 'user',
            'status' => 'active',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $mediaRows = [
            [
                'id' => 155,
                'title' => 'The Dark Knight',
                'original_language' => 'en',
                'overview' => 'Batman faces the Joker, a criminal mastermind who pushes Gotham into chaos.',
                'release_date' => '2008-07-18',
                'genre' => 'Action, Crime, Drama',
                'background_image' => 'hqkIcbrOHL86UncnHIsHVcVmzue.jpg',
                'poster_image' => 'qJ2tW6WMUDux911r6m7haRef0WH.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 603,
                'title' => 'The Matrix',
                'original_language' => 'en',
                'overview' => 'A hacker discovers the world is a simulation and joins a rebellion to free humanity.',
                'release_date' => '1999-03-31',
                'genre' => 'Action, Sci-Fi',
                'background_image' => 'fNG7i7RqMErkcqhohV2a6cV1Ehy.jpg',
                'poster_image' => 'f89U3ADr1oiB1s9GkdPOEpXUk5H.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 27205,
                'title' => 'Inception',
                'original_language' => 'en',
                'overview' => 'A thief who steals secrets through dreams is offered a chance to erase his past.',
                'release_date' => '2010-07-16',
                'genre' => 'Action, Adventure, Sci-Fi',
                'background_image' => '8ZTVqvKDQ8emSGUEMjsS4yHAwrp.jpg',
                'poster_image' => 'edv5CZvWj09upOsy2Y6IwDhK8bt.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 157336,
                'title' => 'Interstellar',
                'original_language' => 'en',
                'overview' => 'Explorers travel through a wormhole in space to ensure humanity survives.',
                'release_date' => '2014-11-07',
                'genre' => 'Adventure, Drama, Sci-Fi',
                'background_image' => 'rAiYTfKGqDCRIIqo664sY9XZIvQ.jpg',
                'poster_image' => 'gEU2QniE6E77NI6lCU6MxlNBvIx.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 680,
                'title' => 'Pulp Fiction',
                'original_language' => 'en',
                'overview' => 'The lives of hitmen, a boxer, and others intertwine in stories of crime and redemption.',
                'release_date' => '1994-10-14',
                'genre' => 'Crime, Drama',
                'background_image' => 'kXfqcdQKsToO0OUXHcrrNCHDBzO.jpg',
                'poster_image' => 'd5iIlFn5s0ImszYzBPb8JPIfbXD.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 13,
                'title' => 'Forrest Gump',
                'original_language' => 'en',
                'overview' => 'A kind-hearted man witnesses and influences key moments in modern American history.',
                'release_date' => '1994-07-06',
                'genre' => 'Drama, Romance',
                'background_image' => '7c9UVPPiTPltouxRVY6N9uugaVA.jpg',
                'poster_image' => 'arw2vcBveWOVZr6pxd9XTd1TdQa.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        $this->db->table('media')->ignore(true)->insertBatch($mediaRows);

        $showRows = [
            [
                'id' => 1399,
                'title' => 'Game of Thrones',
                'seasons' => 8,
                'episodes' => 73,
                'genre' => 'Drama, Fantasy',
                'begin_date' => '2011-04-17',
                'end_date' => '2019-05-19',
                'runtime' => 57,
                'language' => 'en',
                'overview' => 'Noble families and ancient threats collide in a brutal struggle for the Iron Throne.',
                'poster' => 'u3bZgnGQ9T01sWNhyveQz0wH0Hl.jpg',
                'background' => 'suopoADq0k8YZr4dQXcU6pToj6s.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 66732,
                'title' => 'Stranger Things',
                'seasons' => 4,
                'episodes' => 34,
                'genre' => 'Drama, Mystery, Sci-Fi',
                'begin_date' => '2016-07-15',
                'end_date' => null,
                'runtime' => 50,
                'language' => 'en',
                'overview' => 'A small town uncovers experiments, secret labs, and a supernatural parallel world.',
                'poster' => '49WJfeN0moxb9IPfGn8AIqMGskD.jpg',
                'background' => '56v2KjBlU4XaOv9rVYEQypROD7P.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 1396,
                'title' => 'Breaking Bad',
                'seasons' => 5,
                'episodes' => 62,
                'genre' => 'Crime, Drama, Thriller',
                'begin_date' => '2008-01-20',
                'end_date' => '2013-09-29',
                'runtime' => 47,
                'language' => 'en',
                'overview' => 'A chemistry teacher turns to drug production after a life-changing diagnosis.',
                'poster' => 'ztkUQFLlC19CCMYHW9o1zWhJRNq.jpg',
                'background' => 'tsRy63Mu5cu8etL1X7ZLyf7UP1M.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2316,
                'title' => 'The Office',
                'seasons' => 9,
                'episodes' => 201,
                'genre' => 'Comedy',
                'begin_date' => '2005-03-24',
                'end_date' => '2013-05-16',
                'runtime' => 22,
                'language' => 'en',
                'overview' => 'Mockumentary sitcom about office employees navigating absurd daily workplace life.',
                'poster' => 'qWnJzyZhyy74gjpSjIXWmuk0ifX.jpg',
                'background' => 'nTjBWDmK4f3hhwASi3p7Yxsxg6B.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 1398,
                'title' => 'The Sopranos',
                'seasons' => 6,
                'episodes' => 86,
                'genre' => 'Crime, Drama',
                'begin_date' => '1999-01-10',
                'end_date' => '2007-06-10',
                'runtime' => 55,
                'language' => 'en',
                'overview' => 'A New Jersey mob boss balances crime leadership, family pressure, and therapy.',
                'poster' => 'rTc7ZXdroqjkKivFPvCPX0Ru7uw.jpg',
                'background' => '4cSmu6jjkSqvvP9fhQ5QHufY4hL.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 42009,
                'title' => 'Black Mirror',
                'seasons' => 6,
                'episodes' => 27,
                'genre' => 'Drama, Sci-Fi, Thriller',
                'begin_date' => '2011-12-04',
                'end_date' => null,
                'runtime' => 60,
                'language' => 'en',
                'overview' => 'Anthology series exploring technology, society, and unsettling future scenarios.',
                'poster' => '7PRddO7z7mcPi21nZTCMGShAyy1.jpg',
                'background' => 'vq5jX9rY8rR6Z0E8xmPKzW0fvkR.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        $this->db->table('shows')->ignore(true)->insertBatch($showRows);

        $newsRows = [
            [
                'title' => 'Project24 Local Database Ready',
                'slug' => 'project24-local-database-ready',
                'body' => 'The app now ships with SQLite migrations and sample data so it runs without an external database.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'New Preview Flow Added',
                'slug' => 'new-preview-flow-added',
                'body' => 'Visitors can browse movies and shows before signing in. Account-only actions remain protected.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        $this->db->table('news')->ignore(true)->insertBatch($newsRows);
    }
}
