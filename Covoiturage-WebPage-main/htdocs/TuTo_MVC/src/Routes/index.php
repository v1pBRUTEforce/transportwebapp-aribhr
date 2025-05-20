<?php

use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Controllers\ProfileController;
use App\Controllers\AdminController;

use App\Router;

$router = new Router();

$router->get('/TuTo_MVC/public', HomeController::class, 'index');

$router->get('/', HomeController::class, 'index');
$router->get('', HomeController::class, 'index');
$router->get('/login', UserController::class, 'login');
$router->get('/logout', UserController::class, 'logout');
$router->post('/logout', UserController::class, 'logout');
$router->post('/login', UserController::class, 'login');
$router->get('/register', UserController::class, 'register');
$router->post('/register', UserController::class, 'register');
$router->post('/profile', ProfileController::class, 'index');
$router->get('/profile', ProfileController::class, 'index');
$router->post('/CPC', AdminController::class, 'index');

$router->dispatch();
