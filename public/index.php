<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Idharf\PhpMvc\App\Router;
use Idharf\PhpMvc\Controller\HomeController;
use Idharf\PhpMvc\Controller\UserController;
use Idharf\PhpMvc\Controller\ProductController;
use Idharf\PhpMvc\Middleware\AuthMiddlewareImpl;
use Idharf\PhpMvc\Middleware\MustLoginMiddleware;
use Idharf\PhpMvc\Middleware\MustNotLoginMiddleware;

Router::add('GET', '/', HomeController::class, 'index', []);
Router::add('GET', '/hallo', HomeController::class, 'hallo', [AuthMiddlewareImpl::class]);

Router::add('GET', '/users/register', UserController::class, 'register', [MustNotLoginMiddleware::class]);
Router::add('POST', '/users/register', UserController::class, 'postRegister', [MustNotLoginMiddleware::class]);
Router::add('GET', '/users/login', UserController::class, 'login', [MustNotLoginMiddleware::class]);
Router::add('POST', '/users/login', UserController::class, 'postLogin', [MustNotLoginMiddleware::class]);
Router::add('GET', '/users/profile', UserController::class, 'updateProfile', [MustLoginMiddleware::class]);
Router::add('POST', '/users/profile', UserController::class, 'postUpdateProfile', [MustLoginMiddleware::class]);
Router::add('GET', '/users/logout', UserController::class, 'logout', [MustLoginMiddleware::class]);
Router::add('GET', '/users/password', UserController::class, 'updatePassword', [MustLoginMiddleware::class]);
Router::add('POST', '/users/password', UserController::class, 'postUpdatePassword', [MustLoginMiddleware::class]);

Router::add('GET', '/hey', HomeController::class, 'hey', [AuthMiddlewareImpl::class]);
Router::add('GET', '/products/([0-9a-zA-Z]*)/categories/([0-9a-zA-Z]*)', ProductController::class, 'categories', [AuthMiddlewareImpl::class]);

Router::run();

