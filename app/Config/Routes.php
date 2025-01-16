<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\Home;
use App\Controllers\News;
use App\Controllers\Pages;
use App\Controllers\Movies;
use App\Controllers\Media;
use App\Controllers\Show;
use App\Controllers\Search;
/**
 * @var RouteCollection $routes
 */

$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::login');
$routes->get('/logout', 'AuthController::logout');
$routes->get('/dashboard', 'DashboardController::index', ['filter' => 'auth']);


$routes->get('/', 'Home::index');
$routes->get('home', [Home::class, 'index']);   

$routes->get('media', [Media::class, 'index']);
$routes->get('media/(:segment)', [Media::class, 'media_detail']); 

$routes->get('show', [Show::class, 'index']);
$routes->get('show/(:segment)', [Show::class, 'show_detail']); 

$routes->get('search', 'Search::index');
$routes->get('search/searchSuggestions', 'Search::searchSuggestions');

$routes->get('news', [News::class, 'index']);   
$routes->get('news/new', [News::class, 'new']); 
$routes->post('news', [News::class, 'create']);       
$routes->get('news/(:segment)', [News::class, 'show']); 
$routes->get('news/edit/(:segment)', [News::class, 'edit']);
$routes->post('news/update/(:segment)', [News::class, 'update']); 
$routes->post('news/delete/(:segment)', [News::class, 'delete']); 

$routes->get('pages', [Pages::class, 'index']);
$routes->get('(:segment)', [Pages::class, 'view']);
$routes->get('movies', 'Movies::index');

