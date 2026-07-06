# Project made using codeigniter

## What is CodeIgniter?

CodeIgniter is a PHP full-stack web framework that is light, fast, flexible and secure.
More information can be found at the [official site](https://codeigniter.com).

## Project24
This is a website where you can find list of shows and movies with all their important informations.
Here you will find a website where you can see the things used such as CodeIgniter, Bootstrap, basic PHP functionality, CRUD features, and AJAX search suggestions.

## Local database setup (no external DB)
This project now uses a local SQLite database by default at `writable/database/project24.sqlite`.

Run the following commands:

```bash
php spark migrate
php spark db:seed DatabaseSeeder
```

Optional TMDB catalog import command:

```bash
php spark catalog:import-popular --movies 100 --shows 100
```

Ubuntu auto-sync setup (GitHub -> server):
- See `ops/AUTO_SYNC_SETUP.md`

Demo login:
- Username: `demo`
- Password: `Demo@123`
