<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\Home;
use App\Controllers\News;
use App\Controllers\Pages;
use App\Controllers\Movies;
use App\Controllers\Media;
use App\Controllers\Show;
use App\Controllers\Search;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\UserListController;
use App\Controllers\WatchProvidersController;
use App\Controllers\LikeController;
/**
 * @var RouteCollection $routes
 */

$routes->get('login', [AuthController::class, 'login']);
$routes->post('login', [AuthController::class, 'login']);
$routes->post('register', [AuthController::class, 'register']);
$routes->get('logout', [AuthController::class, 'logout']);
$routes->get('dashboard', [DashboardController::class, 'index'], ['filter' => 'auth']);
$routes->get('api/lists', [UserListController::class, 'all'], ['filter' => 'auth']);
$routes->post('api/lists/create', [UserListController::class, 'create'], ['filter' => 'auth']);
$routes->post('api/lists/(:num)/rename', [UserListController::class, 'rename/$1'], ['filter' => 'auth']);
$routes->post('api/lists/(:num)/delete', [UserListController::class, 'delete/$1'], ['filter' => 'auth']);
$routes->post('api/lists/toggle-item', [UserListController::class, 'toggleItem'], ['filter' => 'auth']);
$routes->get('api/lists/item-memberships', [UserListController::class, 'itemMemberships'], ['filter' => 'auth']);
$routes->post('api/likes/toggle', [LikeController::class, 'toggle'], ['filter' => 'auth']);
$routes->get('api/watch-providers/(:segment)/(:num)', [WatchProvidersController::class, 'byRegion/$1/$2']);


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

